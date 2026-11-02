<?php

namespace App\Services\Ai;

/**
 * Routeur d'intention du Tuteur IA.
 *
 * Classe chaque question de l'élève (règles linguistiques, sans appel réseau donc instantané)
 * et fixe le profil de réponse d'un vrai professeur : réponse courte, moyenne ou argumentée.
 */
class TutorIntentRouter
{
    public const PROFILES = [
        'short'     => ['max_words' => 40,  'max_tokens' => 140],
        'brief'     => ['max_words' => 70,  'max_tokens' => 220],
        'medium'    => ['max_words' => 130, 'max_tokens' => 380],
        'hint'      => ['max_words' => 90,  'max_tokens' => 280],
        'method'    => ['max_words' => 200, 'max_tokens' => 520],
        'developed' => ['max_words' => 350, 'max_tokens' => 850],
    ];

    /**
     * @param array<int,array{role:string,content:string}> $history
     * @return array{intent:string,subtype:?string,length:string,max_words:int,max_tokens:int,chapter_number:?int,normalized:string}
     */
    public function classify(string $question, array $history = [], string $mode = 'standard'): array
    {
        $norm = self::normalize($question);
        $tokens = $norm === '' ? [] : explode(' ', $norm);
        $count = count($tokens);
        $hasHistory = !empty($history);

        // 1. Navigation dans le programme (réponse exacte depuis la carte du programme)
        $nav = $this->detectNavigation($norm, $tokens);
        if ($nav !== null) {
            return $this->route('navigation', $nav['subtype'], 'short', $mode, $norm, $nav['number'] ?? null);
        }

        // 2. Relance courte de l'élève sur la dernière réponse du tuteur
        if ($hasHistory && $count <= 5) {
            $follow = $this->detectFollowUp($norm);
            if ($follow !== null) {
                return $this->route('followup', $follow['subtype'], $follow['length'], $mode, $norm);
            }
        }

        // 3. Échange social (salutation, remerciement...)
        if ($count <= 6 && preg_match('/^(bonjour|bonsoir|salut|coucou|hello|hey|merci|ok|okay|d accord|super|cool|top|bien recu|au revoir|a plus|a bientot|ca va|comment vas tu|comment ca va|oui|non|vas y|go)\b/', $norm)) {
            return $this->route('social', null, 'short', $mode, $norm);
        }

        // 4. Méthode de rédaction / de résolution (avant l'argumentation : « comment faire une dissertation »)
        if ($this->hasAny($norm, ['methode', 'rediger', 'redaction', 'comment faire', 'comment trouver', 'comment montrer', 'comment calculer', 'comment proceder', 'comment resoudre', 'comment on fait', 'comment determiner', 'comment etudier', 'comment demontrer'])) {
            return $this->route('method', null, 'method', $mode, $norm);
        }

        // 5. Argumentation / démonstration développée
        if ($this->hasAny($norm, ['justifie', 'justifier', 'argumente', 'argumentation', 'argumenter', 'demontre', 'demontrer', 'demonstration', 'prouve', 'prouver', 'montre que', 'discute', 'discussion', 'compare', 'comparaison', 'en detail', 'developpe', 'detaille', 'dissertation', 'raisonnement complet'])) {
            return $this->route('argument', null, 'developed', $mode, $norm);
        }

        // 6. Aide sur un exercice (indices progressifs)
        if ($this->hasAny($norm, ['exercice', 'palier', 'je bloque', 'bloque', 'corrige', 'correction', 'resous', 'resoudre', 'calcule', 'aide moi sur', 'aide moi pour']) || preg_match('/\bquestion \d/', $norm)) {
            return $this->route('exercise', null, 'hint', $mode, $norm);
        }

        // 7. Utilité / applications concrètes
        if ($this->hasAny($norm, ['sert a quoi', 'a quoi ca sert', 'vraie vie', 'utilite', 'quotidien', 'application reelle', 'applications reelles'])) {
            return $this->route('explanation', 'application', 'medium', $mode, $norm);
        }

        // 8. Définition / question factuelle brève
        if ($this->hasAny($norm, ['c est quoi', 'qu est ce que', 'qu est ce qu', 'que signifie', 'que veut dire', 'definition', 'definis', 'ca veut dire', 'signifie', 'rappelle moi', 'rappel'])) {
            return $this->route('definition', null, 'brief', $mode, $norm);
        }

        // 9. Pourquoi : raisonnement causal court
        if ($this->hasAny($norm, ['pourquoi', 'comment se fait', 'quelle raison'])) {
            return $this->route('reasoning', null, 'medium', $mode, $norm);
        }

        // 10. Explication (avec variante vulgarisée)
        if ($this->hasAny($norm, ['explique', 'expliquer', 'explication', 'comprends pas', 'comprend pas', 'pas compris', 'simple', 'enfant', 'analogie', 'image', 'concret', 'exemple', 'comment ca marche'])) {
            $sub = $this->hasAny($norm, ['enfant', 'analogie', 'simple', 'comprends pas', 'comprend pas', 'pas compris', 'image', 'concret']) ? 'simplified' : null;
            return $this->route('explanation', $sub, 'medium', $mode, $norm);
        }

        // 11. Par défaut : question très brève = réponse brève, sinon moyenne
        return $this->route('general', null, $count <= 6 ? 'brief' : 'medium', $mode, $norm);
    }

    /**
     * @return array{subtype:string,number?:int}|null
     */
    protected function detectNavigation(string $norm, array $tokens): ?array
    {
        // Règle d'or didactique : Si l'élève demande d'expliquer, résumer, détailler ou comprendre,
        // c'est une requête d'enseignement (PEDAGOGICAL) et JAMAIS une simple métadonnée de navigation.
        if ($this->hasAny($norm, [
            'explique', 'expliquer', 'explication', 'comprendre', 'comprends', 'aide', 'aider',
            'comment', 'pourquoi', 'resume', 'resumer', 'cours sur', 'definition', 'exemple',
            'exercice', 'detaille', 'detailler', 'fais moi', 'montre moi', 'peux tu m expliquer', 'peux tu expliquer'
        ])) {
            return null;
        }

        $chapterWord = $this->fuzzyHas($tokens, 'chapitre') || $this->fuzzyHas($tokens, 'lecon') || in_array('theme', $tokens, true);
        $courseWord = in_array('cours', $tokens, true);
        $presence = $this->hasAny($norm, ['nous sommes', 'on est', 'on en est', 'ou en', 'ou sommes', 'ou est on', 'sommes nous', 'est on'])
            || $this->fuzzyHas($tokens, 'sommes');

        // Programme complet : combien / liste
        if ($this->hasAny($norm, ['combien de chapitre', 'combien de chapitres', 'liste des chapitres', 'liste des chapitre', 'tous les chapitres', 'quels sont les chapitres', 'quels chapitres', 'sommaire', 'plan du cours', 'programme de la matiere'])) {
            return ['subtype' => $this->hasAny($norm, ['combien']) ? 'count' : 'list'];
        }

        if (!$chapterWord && !($courseWord && $presence)) {
            return null;
        }

        // Chapitre n° X : « parle de quoi »
        if (preg_match('/\bchapitre\s*(?:n|no|numero)?\s*(\d{1,2})\b/', $norm, $m) && $this->hasAny($norm, ['parle', 'traite', 'quoi', 'contenu', 'contient', 'sujet', 'concerne', 'porte'])) {
            return ['subtype' => 'about_number', 'number' => (int) $m[1]];
        }

        $askAbout = $this->hasAny($norm, ['parle', 'traite', 'de quoi', 'concerne', 'contenu', 'contient', 'porte sur', 'on y voit', 'on y etudie']);

        // Suivant / précédent (inclut « l'autre chapitre », « le prochain »)
        $nextWord = $this->fuzzyHas($tokens, 'suivant') || $this->fuzzyHas($tokens, 'prochain') || $this->hasAny($norm, ['autre chapitre', 'apres', 'd apres', 'la suite', 'a venir', 'ensuite']);
        $prevWord = $this->fuzzyHas($tokens, 'precedent') || $this->hasAny($norm, ['d avant', 'avant celui', 'chapitre avant', 'chapitre d avant', 'deja vu']);
        if ($prevWord && !$nextWord) {
            return ['subtype' => 'previous'];
        }
        if ($nextWord) {
            return ['subtype' => 'next'];
        }

        // « Le chapitre sur les limites parle de quoi ? » : chapitre désigné par son nom
        $about = $askAbout && !$this->hasAny($norm, ['ce chapitre', 'cette lecon', 'ce cours', 'ce theme', 'celui ci', 'actuel']);
        if ($about) {
            return ['subtype' => 'about_named'];
        }
        if ($askAbout) {
            return ['subtype' => 'about_current'];
        }

        // Position actuelle : quel chapitre, où est-on ?
        if ($this->hasAny($norm, ['quel', 'quelle', 'lequel', 'laquelle', 'nom du chapitre', 'titre', 'sur quel']) || $presence || $this->fuzzyHas($tokens, 'quel') || $this->fuzzyHas($tokens, 'ou')) {
            return ['subtype' => 'current'];
        }

        return null;
    }

    /**
     * @return array{subtype:string,length:string}|null
     */
    protected function detectFollowUp(string $norm): ?array
    {
        if ($this->hasAny($norm, ['pourquoi'])) {
            return ['subtype' => 'why', 'length' => 'medium'];
        }
        if ($this->hasAny($norm, ['comprends pas', 'comprend pas', 'pas compris', 'reexplique', 'autrement', 'plus simple'])) {
            return ['subtype' => 'simplify', 'length' => 'medium'];
        }
        if ($this->hasAny($norm, ['exemple', 'illustre'])) {
            return ['subtype' => 'example', 'length' => 'medium'];
        }
        if ($this->hasAny($norm, ['developpe', 'detaille', 'plus de details', 'approfondis', 'explique plus', 'argumente'])) {
            return ['subtype' => 'deepen', 'length' => 'developed'];
        }
        if (preg_match('/^(et ensuite|et apres|et alors|et puis|continue|vas y|oui|ok|okay|d accord|go|suite|encore|ensuite|apres)\b/', $norm)) {
            return ['subtype' => 'continue', 'length' => 'medium'];
        }

        return null;
    }

    protected function route(string $intent, ?string $subtype, string $length, string $mode, string $norm, ?int $number = null): array
    {
        $profile = self::PROFILES[$length];
        $maxWords = $profile['max_words'];
        $maxTokens = $profile['max_tokens'];

        // En appel vocal, rythme de conversation vivante de vive voix (dialogue direct et réactif)
        if ($mode === 'voice_call') {
            $maxWords = min($maxWords, $intent === 'argument' ? 65 : 45);
            $maxTokens = min($maxTokens, 160);
        }

        return [
            'intent' => $intent,
            'subtype' => $subtype,
            'length' => $length,
            'max_words' => $maxWords,
            'max_tokens' => $maxTokens,
            'chapter_number' => $number,
            'normalized' => $norm,
        ];
    }

    /**
     * Minuscules, sans accents ni ponctuation : « Qu'est-ce que... » devient « qu est ce que... ».
     */
    public static function normalize(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = strtr($text, [
            'à' => 'a', 'â' => 'a', 'ä' => 'a', 'á' => 'a', 'ã' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'î' => 'i', 'ï' => 'i', 'í' => 'i',
            'ô' => 'o', 'ö' => 'o', 'ó' => 'o', 'õ' => 'o',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ú' => 'u',
            'ç' => 'c', 'œ' => 'oe', 'æ' => 'ae', 'ÿ' => 'y', 'ñ' => 'n',
            '’' => ' ', "'" => ' ', '-' => ' ',
        ]);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);

        return trim(preg_replace('/\s+/', ' ', $text));
    }

    protected function hasAny(string $norm, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (preg_match('/(^|\s)' . preg_quote($needle, '/') . '/', $norm)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tolère les fautes de frappe et de dictée vocale (« chapite », « somlme »...).
     */
    protected function fuzzyHas(array $tokens, string $word): bool
    {
        $len = strlen($word);
        $tolerance = $len >= 9 ? 2 : ($len >= 5 ? 1 : 0);
        foreach ($tokens as $token) {
            if ($token === $word) {
                return true;
            }
            if ($tolerance > 0 && abs(strlen($token) - $len) <= $tolerance && levenshtein($token, $word) <= $tolerance) {
                return true;
            }
        }

        return false;
    }
}
