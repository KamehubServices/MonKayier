<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Log;

class NeuralTtsService
{
    /**
     * Voix neurales haute-définition Microsoft Azure (via edge-tts).
     * Voix multilingues de dernière génération avec prosodie, respiration et intonation humaines.
     */
    protected array $voices = [
        'kodjo'  => 'fr-FR-RemyMultilingualNeural',     // Voix masculine chaleureuse, dynamique et très humaine
        'afi'    => 'fr-FR-VivienneMultilingualNeural', // Voix féminine claire, expressive et naturelle
        'mawuli' => 'fr-FR-HenriNeural',                // Voix masculine posée de professeur
    ];

    /**
     * Formate une réponse brute de l'IA (Markdown + LaTeX) en HTML structuré et élégant,
     * sans aucun astérisque (**), dièse (###) ou tiret brut visible, tout en préservant le LaTeX pour KaTeX.
     */
    public function formatMarkdownToCleanHtml(string $text): string
    {
        if (empty(trim($text))) {
            return '';
        }

        // 1. Protéger les blocs mathématiques LaTeX ($$...$$, \[...\], $...$, \(...\)) avant le parsing Markdown
        $mathBlocks = [];
        $text = preg_replace_callback('/(\$\$[\s\S]*?\$\$|\\\\\[[\s\S]*?\\\\\]|\$[^\$\n]+?\$|\\\\\([\s\S]*?\\\\\))/u', function ($matches) use (&$mathBlocks) {
            $key = '@@MK_MATH_' . count($mathBlocks) . '@@';
            $mathBlocks[$key] = $matches[0];
            return $key;
        }, $text);

        // Normaliser les retours à la ligne
        $text = str_replace(["\r\n", "\r"], "\n", trim($text));

        // 2. Convertir le gras **texte** ou __texte__ et l'italique *texte*
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong class="mk-ai-bold">$1</strong>', $text);
        $text = preg_replace('/__(.+?)__/s', '<strong class="mk-ai-bold">$1</strong>', $text);
        $text = preg_replace('/(?<!\*)\*([^\*\n]+)\*(?!\*)/u', '<em>$1</em>', $text);

        // Supprimer les astérisques ou dièses orphelins résiduels en début/fin de mot
        $text = str_replace(['**', '### ', '## '], ['', '### ', '## '], $text);

        // 3. Découper ligne par ligne pour construire des titres, listes et paragraphes propres
        $lines = explode("\n", $text);
        $htmlParts = [];
        $inUl = false;
        $inOl = false;

        $closeLists = function () use (&$htmlParts, &$inUl, &$inOl) {
            if ($inUl) {
                $htmlParts[] = '</ul>';
                $inUl = false;
            }
            if ($inOl) {
                $htmlParts[] = '</ol>';
                $inOl = false;
            }
        };

        foreach ($lines as $rawLine) {
            $line = trim($rawLine);

            if ($line === '' || $line === '---' || $line === '***') {
                $closeLists();
                continue;
            }

            // Titres Markdown (#, ##, ###, ####)
            if (preg_match('/^#{1,4}\s*(.+)$/u', $line, $m)) {
                $closeLists();
                $titleText = trim($m[1], " *#:");
                $htmlParts[] = '<div class="mk-ai-heading">' . $titleText . '</div>';
                continue;
            }

            // Lignes qui sont entièrement un titre en gras (ex: "<strong>1. Définition :</strong>")
            if (preg_match('/^<strong class="mk-ai-bold">([^<]+)<\/strong>\s*:?$/u', $line, $m) && mb_strlen($m[1]) < 85) {
                $closeLists();
                $htmlParts[] = '<div class="mk-ai-heading">' . rtrim($m[1], ': ') . '</div>';
                continue;
            }

            // Listes à puces (- item, * item, • item)
            if (preg_match('/^(?:[-*•])\s+(.+)$/u', $line, $m)) {
                if ($inOl) {
                    $htmlParts[] = '</ol>';
                    $inOl = false;
                }
                if (!$inUl) {
                    $htmlParts[] = '<ul class="mk-ai-ul">';
                    $inUl = true;
                }
                $htmlParts[] = '<li class="mk-ai-li">' . trim($m[1]) . '</li>';
                continue;
            }

            // Listes numérotées (1. item, 2) item)
            if (preg_match('/^(\d+)[\.\)]\s+(.+)$/u', $line, $m)) {
                if ($inUl) {
                    $htmlParts[] = '</ul>';
                    $inUl = false;
                }
                if (!$inOl) {
                    $htmlParts[] = '<ol class="mk-ai-ol">';
                    $inOl = true;
                }
                $htmlParts[] = '<li class="mk-ai-li">' . trim($m[2]) . '</li>';
                continue;
            }

            // Paragraphe normal
            $closeLists();
            $htmlParts[] = '<p class="mk-ai-p">' . $line . '</p>';
        }

        $closeLists();

        $finalHtml = implode("\n", $htmlParts);

        // 4. Restaurer les blocs mathématiques LaTeX intacts pour KaTeX
        foreach ($mathBlocks as $key => $latex) {
            $finalHtml = str_replace($key, $latex, $finalHtml);
        }

        return $finalHtml;
    }

    /**
     * Traduit un texte contenant du Markdown, du HTML et des formules LaTeX
     * en texte français parlé fluide et naturel pour la synthèse vocale humaine.
     */
    public function prepareTextForHumanSpeech(string $text): string
    {
        if (empty(trim($text))) {
            return '';
        }

        // 1. Remplacer les balises de saut de bloc par des pauses naturelles avant de retirer le HTML
        $speech = preg_replace('/<\/(p|div|li|h[1-6])>/i', '. ', $text);
        $speech = strip_tags($speech);
        $speech = html_entity_decode($speech, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 2. Traduction phonétique des expressions mathématiques et scientifiques LaTeX en français parlé
        $mathReplacements = [
            // Limites
            '/\\\\lim_\{?\s*x\s*\\\\to\s*\+\\\\infty\s*\}?/u' => ' la limite quand x tend vers plus l\'infini de ',
            '/\\\\lim_\{?\s*x\s*\\\\to\s*-\\\\infty\s*\}?/u'  => ' la limite quand x tend vers moins l\'infini de ',
            '/\\\\lim_\{?\s*n\s*\\\\to\s*\+\\\\infty\s*\}?/u' => ' la limite quand n tend vers plus l\'infini de ',
            '/\\\\lim_\{?\s*([a-zA-Z0-9_]+)\s*\\\\to\s*([^\}\s]+)\s*\}?/u' => ' la limite quand $1 tend vers $2 de ',

            // Fractions, racines, intégrales, sommes, vecteurs
            '/\\\\frac\{([^\}]+)\}\{([^\}]+)\}/u'  => ' $1 sur $2 ',
            '/\\\\dfrac\{([^\}]+)\}\{([^\}]+)\}/u' => ' $1 sur $2 ',
            '/\\\\sqrt\[([^\]]+)\]\{([^\}]+)\}/u'  => ' racine $1-ième de $2 ',
            '/\\\\sqrt\{([^\}]+)\}/u'              => ' racine carrée de $1 ',
            '/\\\\vec\{([^\}]+)\}/u'               => ' vecteur $1 ',
            '/\\\\overrightarrow\{([^\}]+)\}/u'    => ' vecteur $1 ',
            '/\\\\int_\{([^\}]+)\}\^\{([^\}]+)\}/u'=> ' intégrale de $1 à $2 de ',
            '/\\\\int/u'                           => ' intégrale de ',
            '/\\\\sum/u'                           => ' somme de ',

            // Ensembles et logique
            '/\\\\in\s*\\\\mathbb\{R\}/u'          => ' appartient à l\'ensemble des réels R ',
            '/\\\\in\s*\\\\mathbb\{C\}/u'          => ' appartient à l\'ensemble des nombres complexes C ',
            '/\\\\in\s*\\\\mathbb\{N\}/u'          => ' appartient à l\'ensemble des entiers naturels N ',
            '/\\\\mathbb\{R\}/u'                   => ' R ',
            '/\\\\mathbb\{C\}/u'                   => ' C ',
            '/\\\\mathbb\{N\}/u'                   => ' N ',
            '/\\\\mathbb\{Z\}/u'                   => ' Z ',
            '/\\\\in\b/u'                          => ' appartient à ',
            '/\\\\notin\b/u'                       => ' n\'appartient pas à ',
            '/\\\\forall\b/u'                      => ' pour tout ',
            '/\\\\exists\b/u'                      => ' il existe ',
            '/\\\\Longleftrightarrow|\\\\Leftrightarrow|⇔/u' => ' équivaut à ',
            '/\\\\Longrightarrow|\\\\Rightarrow|⇒/u'         => ' implique que ',
            '/\\\\to\b|→/u'                        => ' tend vers ',

            // Opérateurs et comparaisons
            '/\\\\leq|\\\\leqslant|≤/u'            => ' inférieur ou égal à ',
            '/\\\\geq|\\\\geqslant|≥/u'            => ' supérieur ou égal à ',
            '/\\\\neq|≠/u'                         => ' différent de ',
            '/\\\\approx|≈/u'                      => ' environ égal à ',
            '/\\\\pm|±/u'                          => ' plus ou moins ',
            '/\\\\times|×/u'                       => ' fois ',
            '/\\\\cdot/u'                          => ' fois ',
            '/\+\\\\infty|\+∞/u'                   => ' plus l\'infini ',
            '/-\\\\infty|-∞/u'                     => ' moins l\'infini ',
            '/\\\\infty|∞/u'                       => ' l\'infini ',

            // Fonctions et lettres grecques
            '/\\\\ln\b/u'                          => ' logarithme népérien de ',
            '/\\\\exp\b/u'                         => ' exponentielle de ',
            '/\\\\cos\b/u'                         => ' cosinus ',
            '/\\\\sin\b/u'                         => ' sinus ',
            '/\\\\tan\b/u'                         => ' tangente ',
            '/\\\\alpha\b|α/u'                     => ' alpha ',
            '/\\\\beta\b|β/u'                      => ' bêta ',
            '/\\\\gamma\b|γ/u'                     => ' gamma ',
            '/\\\\Delta\b|Δ/u'                     => ' grand Delta ',
            '/\\\\delta\b|δ/u'                     => ' delta ',
            '/\\\\theta\b|θ/u'                     => ' thêta ',
            '/\\\\lambda\b|λ/u'                    => ' lambda ',
            '/\\\\omega\b|ω/u'                     => ' oméga ',
            '/\\\\Omega\b|Ω/u'                     => ' Ohm ',
            '/\\\\pi\b|π/u'                        => ' pi ',

            // Chimie & Puissances
            '/\[H_?3O\^\+\]|\[H₃O⁺\]/u'           => ' concentration en ions hydronium H3O plus ',
            '/\[OH\^-\]|\[OH⁻\]/u'                => ' concentration en ions hydroxyde OH moins ',
            '/\^\{2\}|\^2|²/u'                     => ' au carré ',
            '/\^\{3\}|\^3|³/u'                     => ' au cube ',
            '/\^\{([^\}]+)\}/u'                    => ' puissance $1 ',
            '/\^([a-zA-Z0-9\+\-]+)/u'              => ' puissance $1 ',
            '/_\{([^\}]+)\}/u'                     => ' indice $1 ',
            '/_([0-9a-zA-Z])/u'                    => ' $1 ',
        ];

        foreach ($mathReplacements as $pattern => $replacement) {
            $speech = preg_replace($pattern, $replacement, $speech);
        }

        // Corriger le cas \sqrt{x} ci-dessus si $1
        $speech = preg_replace('/\\\\sqrt\{([^\}]+)\}/u', ' racine carrée de $1 ', $speech);

        // 3. Nettoyer les commandes LaTeX de mise en forme et les symboles Markdown restants
        $speech = preg_replace('/\\\\text\{([^\}]+)\}/u', '$1', $speech);
        $speech = preg_replace('/\\\\(left|right|Big|big|quad|qquad|displaystyle|mathrm|mathbf)\b/u', ' ', $speech);
        $speech = str_replace(['$$', '$', '\\[', '\\]', '\\(', '\\)', '**', '###', '##', '#', '`', '~', '{', '}', '\\', '•'], ' ', $speech);

        // Retirer les tirets ou astérisques de début de ligne
        $speech = preg_replace('/(^|\n)\s*[-*]\s+/u', '$1 ', $speech);

        // Nettoyer les espaces multiples et normaliser la ponctuation pour une respiration naturelle
        $speech = preg_replace('/\s*\.\s*\.\s*/u', '. ', $speech);
        $speech = preg_replace('/[ \t]+/u', ' ', $speech);
        $speech = preg_replace('/\n+/u', '. ', $speech);
        $speech = preg_replace('/\.\s*\./u', '.', $speech);

        return trim($speech);
    }

    /**
     * Génère un fichier audio MP3 avec une voix neurale humaine via edge-tts
     * et le met en cache dans public/tts_cache pour une lecture instantanée.
     */
    public function synthesizeSpeech(string $rawText, string $voiceKey = 'kodjo'): array
    {
        $spokenText = $this->prepareTextForHumanSpeech($rawText);
        if (empty($spokenText)) {
            return [
                'success' => false,
                'spoken_text' => '',
            ];
        }

        // Autoriser les réponses complètes (jusqu'à 15 000 caractères) pour que la voix lise l'intégralité du message sans jamais s'arrêter au milieu
        if (mb_strlen($spokenText) > 15000) {
            $spokenText = mb_substr($spokenText, 0, 15000) . '.';
        }

        $voiceName = $this->voices[$voiceKey] ?? $this->voices['kodjo'];
        $hash = md5($voiceName . '|+3%|' . $spokenText);

        $cacheDir = public_path('tts_cache');
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0775, true);
        }

        $mp3Filename = $hash . '.mp3';
        $mp3Path = $cacheDir . DIRECTORY_SEPARATOR . $mp3Filename;

        // Si déjà en cache et valide, retourner immédiatement
        if (file_exists($mp3Path) && filesize($mp3Path) > 512) {
            return [
                'success' => true,
                'audio_url' => '/tts_cache/' . $mp3Filename,
                'spoken_text' => $spokenText,
                'voice' => $voiceName,
                'cached' => true,
            ];
        }

        // Écriture dans un fichier texte UTF-8 temporaire pour éviter tout problème d'encodage des accents sous Windows
        $tmpTxtPath = $cacheDir . DIRECTORY_SEPARATOR . $hash . '.txt';
        file_put_contents($tmpTxtPath, $spokenText);

        $cmd = sprintf(
            'python -m edge_tts --voice %s --rate="+3%%" --file %s --write-media %s 2>&1',
            escapeshellarg($voiceName),
            escapeshellarg($tmpTxtPath),
            escapeshellarg($mp3Path)
        );

        $output = [];
        $returnVar = 0;
        @exec($cmd, $output, $returnVar);
        @unlink($tmpTxtPath);

        if ($returnVar === 0 && file_exists($mp3Path) && filesize($mp3Path) > 512) {
            return [
                'success' => true,
                'audio_url' => '/tts_cache/' . $mp3Filename,
                'spoken_text' => $spokenText,
                'voice' => $voiceName,
                'cached' => false,
            ];
        }

        Log::warning('NeuralTtsService edge-tts fallback: ' . implode(' ', $output));

        return [
            'success' => false,
            'audio_url' => null,
            'spoken_text' => $spokenText,
            'voice' => $voiceName,
        ];
    }
}

