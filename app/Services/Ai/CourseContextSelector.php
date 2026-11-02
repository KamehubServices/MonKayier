<?php

namespace App\Services\Ai;

/**
 * Sélectionne dans le cours officiel les passages les plus proches de la question de l'élève,
 * au lieu d'envoyer au modèle les premiers caractères de l'écran.
 */
class CourseContextSelector
{
    protected const STOP = ['les', 'des', 'une', 'qui', 'que', 'quoi', 'est', 'sont', 'pour', 'dans', 'avec', 'sur', 'par', 'pas', 'plus', 'comme', 'cette', 'ce', 'cet', 'ces', 'mon', 'ton', 'son', 'nous', 'vous', 'elle', 'ils', 'peux', 'peut', 'fait', 'faire', 'expliquer', 'explique', 'moi', 'tu', 'te', 'me', 'se', 'sa', 'ses', 'aux', 'ont', 'etre', 'avoir', 'donc', 'mais', 'ainsi', 'alors', 'tout', 'tous', 'comment', 'pourquoi', 'quel', 'quelle', 'quels'];

    /**
     * Conserve l'en-tête (matière, chapitre, référence) puis les blocs les mieux classés,
     * en respectant l'ordre d'origine du cours.
     */
    public function select(string $fullContext, string $question, int $maxChars = 2800): string
    {
        $fullContext = trim($fullContext);
        if ($fullContext === '' || mb_strlen($fullContext) <= $maxChars) {
            return $fullContext;
        }

        $blocks = preg_split('/\n\s*\n/', $fullContext) ?: [$fullContext];
        $header = array_shift($blocks);
        $qTokens = $this->tokens($question);

        $scored = [];
        foreach ($blocks as $i => $block) {
            $bTokens = $this->tokens($block);
            $score = 0;
            foreach ($qTokens as $qt) {
                foreach ($bTokens as $bt) {
                    if ($qt === $bt || (strlen($qt) >= 5 && strlen($bt) >= 5 && substr($qt, 0, 5) === substr($bt, 0, 5))) {
                        $score++;
                        break;
                    }
                }
            }
            // Légère priorité aux objectifs et définitions (début du cours) à égalité de score
            $scored[] = ['i' => $i, 'score' => $score - ($i * 0.001), 'text' => trim($block)];
        }

        usort($scored, fn ($a, $b) => $b['score'] <=> $a['score']);

        $budget = $maxChars - mb_strlen($header);
        $keep = [];
        foreach ($scored as $s) {
            $len = mb_strlen($s['text']) + 2;
            if ($len > $budget) {
                continue;
            }
            $keep[$s['i']] = $s['text'];
            $budget -= $len;
            if ($budget < 80) {
                break;
            }
        }

        ksort($keep);

        return trim($header . "\n\n" . implode("\n\n", $keep));
    }

    protected function tokens(string $text): array
    {
        $norm = TutorIntentRouter::normalize($text);

        return array_values(array_unique(array_filter(
            explode(' ', $norm),
            fn ($t) => strlen($t) >= 3 && !in_array($t, self::STOP, true)
        )));
    }
}

