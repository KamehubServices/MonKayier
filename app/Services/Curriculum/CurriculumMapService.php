<?php

namespace App\Services\Curriculum;

use App\Services\Ai\TutorIntentRouter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Carte du programme officiel : ordre, numéro et contenu des chapitres d'une matière.
 * Sert au Tuteur IA à situer l'élève dans le programme et à répondre exactement
 * aux questions de navigation (« quel chapitre ? », « le suivant parle de quoi ? »).
 */
class CurriculumMapService
{
    /**
     * Clé de déduplication des chapitres. Partagée avec la page de chat pour garantir
     * que la numérotation du tuteur est identique à celle affichée à l'élève.
     */
    public static function dedupeKey($course): string
    {
        $cleaned = preg_replace('/^(chapitre\s*\d+\s*[:\.]?|thème\s*\d+\s*[:\.]?|[·:\-\s]+)/iu', '', $course->title);
        $cleaned = str_replace(['’', "'", '-', '·', ':', ',', 'collabaoration'], [' ', ' ', ' ', ' ', ' ', ' ', 'collaboration'], mb_strtolower($cleaned));
        $cleaned = iconv('UTF-8', 'ASCII//TRANSLIT', $cleaned);
        $cleaned = preg_replace('/[^a-zA-Z0-9\s]/', '', $cleaned);
        $words = explode(' ', trim($cleaned));
        $stemmed = array_map(function ($w) {
            return rtrim($w, 's');
        }, array_filter($words));

        return implode(' ', $stemmed);
    }

    public static function cleanTitle(string $title): string
    {
        $clean = preg_replace('/^(chapitre\s*\d+\s*[:\.]?|thème\s*\d+\s*[:\.]?|[·:\-\s]+)/iu', '', $title);
        $clean = trim($clean) !== '' ? trim($clean) : $title;

        return ltrim($clean, "· \t\n\r\0\x0B:-");
    }

    /**
     * Construit la carte du programme de la matière du cours donné.
     *
     * @return array{subject_name:string,subject_code:string,total:int,current:?int,chapters:array<int,array{number:int,id:mixed,title:string,summary:string}>}|null
     */
    public function mapForCourse(?object $course): ?array
    {
        if (!$course || empty($course->subject_id) || empty($course->education_level_id)) {
            return null;
        }

        $subjectCode = strtoupper($course->subject_code ?? 'MATHS');

        /** @var Collection $courses */
        $courses = DB::table('learning_resources')
            ->where('subject_id', $course->subject_id)
            ->where('education_level_id', $course->education_level_id)
            ->where('resource_type', 'course')
            ->orderBy('title', 'asc')
            ->get()
            ->unique(fn ($c) => self::dedupeKey($c))
            ->values();

        if ($subjectCode === 'FRANCAIS' && $courses->count() <= 3) {
            foreach ([
                ['fr_meth_01', 'La Contraction de texte & Discussion au BAC', 'Méthodologie officielle de réduction de texte au quart et grille de notation MEPST.'],
                ['fr_meth_02', 'Le Commentaire composé littéraire', 'Recherche des centres d’intérêt, analyse stylistique et plan détaillé.'],
                ['fr_meth_03', 'La Dissertation littéraire', 'Problématisation du sujet, plan dialectique et argumentation étayée.'],
            ] as [$id, $title, $summary]) {
                $courses->push((object) ['id' => $id, 'title' => $title, 'summary' => $summary]);
            }
        }

        if ($courses->isEmpty()) {
            return null;
        }

        $currentKey = self::dedupeKey($course);
        $current = null;
        $chapters = [];
        foreach ($courses->values() as $i => $c) {
            $number = $i + 1;
            if ((string) $c->id === (string) ($course->id ?? '') || ($current === null && self::dedupeKey($c) === $currentKey)) {
                $current = $number;
            }
            $chapters[] = [
                'number' => $number,
                'id' => $c->id,
                'title' => self::cleanTitle($c->title),
                'summary' => trim((string) ($c->summary ?? '')),
            ];
        }

        return [
            'subject_name' => $course->subject_name ?? $subjectCode,
            'subject_code' => $subjectCode,
            'total' => count($chapters),
            'current' => $current,
            'chapters' => $chapters,
        ];
    }

    /**
     * Résumé compact de la carte pour le prompt du modèle.
     */
    public function formatForPrompt(array $map): string
    {
        $lines = [];
        foreach ($map['chapters'] as $ch) {
            $mark = ($map['current'] === $ch['number']) ? '  <= CHAPITRE EN COURS' : '';
            $lines[] = "{$ch['number']}. {$ch['title']}{$mark}";
        }
        $pos = $map['current'] ? "L'élève est au chapitre {$map['current']} sur {$map['total']}." : '';

        return "PROGRAMME OFFICIEL DE {$map['subject_name']} ({$map['total']} chapitres, dans l'ordre) :\n" . implode("\n", $lines) . "\n{$pos}";
    }

    /**
     * Réponse exacte (sans IA) à une question de navigation. Null si on ne sait pas répondre.
     */
    public function answerNavigation(array $route, array $map): ?string
    {
        $subtype = $route['subtype'] ?? 'current';
        $total = $map['total'];
        $cur = $map['current'];
        $subject = $map['subject_name'];
        $byNumber = fn (int $n) => $map['chapters'][$n - 1] ?? null;

        switch ($subtype) {
            case 'count':
                $txt = "Le programme de {$subject} compte {$total} chapitres.";
                return $cur ? $txt . " Nous sommes au chapitre {$cur}." : $txt;

            case 'list':
                $items = [];
                foreach ($map['chapters'] as $ch) {
                    $items[] = "{$ch['number']}. {$ch['title']}";
                }
                $txt = "Voici les {$total} chapitres du programme de {$subject} :\n" . implode("\n", $items);
                return $cur ? $txt . "\n\nNous sommes au chapitre {$cur}." : $txt;

            case 'current':
                $ch = $cur ? $byNumber($cur) : null;
                if (!$ch) {
                    return null;
                }
                $txt = "Nous sommes au chapitre {$cur} sur {$total} du programme de {$subject} : « {$ch['title']} ».";
                $next = $byNumber($cur + 1);
                return $txt . ($next ? " Le chapitre suivant sera « {$next['title']} »." : ' C\'est le dernier chapitre du programme.');

            case 'about_current':
                $ch = $cur ? $byNumber($cur) : null;
                if (!$ch) {
                    return null;
                }
                return "Le chapitre {$cur} « {$ch['title']} » " . $this->describe($ch, $subject);

            case 'next':
                if (!$cur) {
                    return null;
                }
                $next = $byNumber($cur + 1);
                if (!$next) {
                    return "Tu es déjà au dernier chapitre du programme de {$subject} (chapitre {$cur} sur {$total}).";
                }
                return "Le chapitre suivant est le chapitre {$next['number']} : « {$next['title']} ». " . $this->describe($next, $subject, true);

            case 'previous':
                if (!$cur) {
                    return null;
                }
                $prev = $byNumber($cur - 1);
                if (!$prev) {
                    return "Tu es au tout premier chapitre du programme de {$subject}.";
                }
                return "Le chapitre précédent était le chapitre {$prev['number']} : « {$prev['title']} ». " . $this->describe($prev, $subject, true);

            case 'about_number':
                $n = (int) ($route['chapter_number'] ?? 0);
                $ch = $n > 0 ? $byNumber($n) : null;
                if (!$ch) {
                    return "Le programme de {$subject} compte {$total} chapitres : choisis un numéro entre 1 et {$total}.";
                }
                return "Le chapitre {$n} « {$ch['title']} » " . $this->describe($ch, $subject);

            case 'about_named':
                $ch = $this->matchChapterByName($route['normalized'] ?? '', $map);
                if (!$ch) {
                    return null;
                }
                return "Le chapitre {$ch['number']} « {$ch['title']} » " . $this->describe($ch, $subject);
        }

        return null;
    }

    protected function describe(array $chapter, string $subject, bool $short = false): string
    {
        $desc = $chapter['summary'];

        // Résumés génériques : on ne garde que la liste utile après « Chapitres couverts : »
        if ($desc !== '' && preg_match('/chapitres?\s+couverts?\s*:\s*(.+)$/iu', $desc, $m)) {
            $desc = trim($m[1]);
        } elseif ($desc !== '' && preg_match('/^cours officiel conforme/iu', $desc)) {
            $desc = '';
        }

        if ($desc === '') {
            try {
                $pedagogy = app(OfficialCourseService::class)->getChapterPedagogy(
                    (object) ['title' => $chapter['title'], 'clean_title' => $chapter['title'], 'summary' => ''],
                    $this->subjectCodeGuess($subject)
                );
                $objectives = $pedagogy['objectives'] ?? [];
                $desc = $objectives ? implode(' ; ', array_slice(array_map('strval', $objectives), 0, $short ? 1 : 2)) : '';
            } catch (\Throwable $e) {
                $desc = '';
            }
        }

        if ($desc === '') {
            return "fait partie du programme officiel de {$subject}.";
        }

        $desc = Str::limit(rtrim(strip_tags($desc), " .;"), 260, '...');
        $desc = mb_strtolower(mb_substr($desc, 0, 1)) . mb_substr($desc, 1);

        return $short ? "Il traite de : {$desc}." : "traite de : {$desc}.";
    }

    protected function subjectCodeGuess(string $subjectName): string
    {
        $n = TutorIntentRouter::normalize($subjectName);
        return match (true) {
            str_contains($n, 'math') => 'MATHS',
            str_contains($n, 'physique') => 'PCT',
            str_contains($n, 'vie et de la terre') || str_contains($n, 'svt') => 'SVT',
            str_contains($n, 'philo') => 'PHILO',
            str_contains($n, 'franc') => 'FRANCAIS',
            default => 'GENERIC',
        };
    }

    /**
     * Retrouve un chapitre cité par son nom dans la question (« le chapitre sur les limites »).
     */
    protected function matchChapterByName(string $normQuestion, array $map): ?array
    {
        $stop = ['chapitre', 'chapitres', 'cours', 'dans', 'sur', 'avec', 'pour', 'les', 'des', 'une', 'qui', 'que', 'parle', 'traite', 'quoi', 'contenu', 'contient', 'est', 'et', 'de', 'du', 'la', 'le', 'ce', 'cet', 'cette', 'concerne', 'porte', 'lecon', 'theme'];
        $qTokens = array_filter(explode(' ', $normQuestion), fn ($t) => strlen($t) >= 4 && !in_array($t, $stop, true));
        if (!$qTokens) {
            return null;
        }

        $best = null;
        $bestScore = 0;
        foreach ($map['chapters'] as $ch) {
            $titleTokens = array_filter(explode(' ', TutorIntentRouter::normalize($ch['title'])), fn ($t) => strlen($t) >= 4 && !in_array($t, $stop, true));
            $score = 0;
            foreach ($qTokens as $qt) {
                foreach ($titleTokens as $tt) {
                    if ($qt === $tt || (strlen($qt) >= 5 && strlen($tt) >= 5 && substr($qt, 0, 5) === substr($tt, 0, 5))) {
                        $score++;
                        break;
                    }
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $ch;
            }
        }

        return $bestScore >= 1 ? $best : null;
    }
}
