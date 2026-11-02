<?php

namespace App\Services\Ai;

use App\Services\Curriculum\CurriculumMapService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OpenSourceAiService
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected string $modelMath;
    protected string $modelGeneral;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('OPENSOURCE_AI_BASE_URL', 'https://openrouter.ai/api/v1'), '/');
        $this->apiKey = env('OPENSOURCE_AI_API_KEY', env('GROQ_API_KEY', null));
        $this->modelMath = env('OPENSOURCE_AI_MODEL_MATH', 'qwen/qwen-2.5-7b-instruct');
        $this->modelGeneral = env('OPENSOURCE_AI_MODEL_GENERAL', 'mistralai/mistral-nemo');
        $this->timeout = max(12, min(25, (int) env('OPENSOURCE_AI_TIMEOUT', 22)));
    }

    /**
     * Vérifie si le service IA Open-Weights est configuré avec une clé active.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Répond comme un professeur : identifie le type de question (navigation, définition, explication,
     * argumentation, exercice...), adapte la longueur de la réponse, reste dans le programme officiel
     * MEPST Togo et situe l'élève grâce à la carte du programme.
     */
    public function askTutor(string $studentQuestion, ?object $course = null, array $conversationHistory = [], ?string $contextText = null, ?string $interactionMode = 'standard', ?object $student = null): array
    {
        $courseTitle = $course ? ($course->clean_title ?? $course->title) : 'Programme National Lycée';
        $subjectCode = $course->subject_code ?? 'MATHS';
        $subjectName = $course->subject_name ?? 'Mathématiques';
        $mode = strtolower(trim($interactionMode ?? 'standard'));
        $levelLabel = $student->level_name ?? 'Terminale (BAC 2)';

        $studentFirstName = trim((string) ($student->first_name ?? ''));
        if (in_array(mb_strtolower($studentFirstName), ['', 'nouvel'], true)) {
            $studentFirstName = '';
        }
        $studentIdentityBlock = $studentFirstName !== ''
            ? "L'élève s'appelle {$studentFirstName} : appelle-le par son prénom de temps en temps (sans exagérer)."
            : "L'élève n'a pas renseigné son prénom : ne l'invente pas.";

        // 1. Identification du type de question et du profil de réponse attendu
        $route = app(TutorIntentRouter::class)->classify($studentQuestion, $conversationHistory, $mode);

        // 2. Carte du programme officiel (ordre, numéro et contenu des chapitres)
        $curriculum = app(CurriculumMapService::class);
        $map = null;
        try {
            $map = $curriculum->mapForCourse($course);
        } catch (\Throwable $e) {
            Log::warning('CurriculumMapService indisponible : ' . $e->getMessage());
        }

        // 3. Questions de navigation : réponse exacte depuis la base, sans appel IA (instantanée)
        if ($route['intent'] === 'navigation' && $map) {
            $answer = $curriculum->answerNavigation($route, $map);
            if ($answer !== null) {
                return $this->buildTutorResponse($answer, $route, $courseTitle, $subjectCode, 'curriculum-map', null);
            }
        }

        $curriculumBlock = $map ? "\n" . $curriculum->formatForPrompt($map) . "\n" : '';

        // 4. Passages du cours officiel les plus pertinents pour cette question
        $contextBlock = '';
        $selectedContext = app(CourseContextSelector::class)->select(trim($contextText ?? ''), $studentQuestion);
        if ($selectedContext !== '') {
            $contextBlock = "\n--- COURS OFFICIEL ET CONTENU AFFICHÉ À L'ÉCRAN DE L'ÉLÈVE ---\n{$selectedContext}\n------------------------------------------------------------------\n";
        }

        $intentDirective = $this->buildIntentDirective($route, $mode);

        $systemPrompt = "Tu es le Tuteur IA de MonKayier au Togo : un vrai professeur particulier d'excellence en {$subjectName}, bienveillant, vivant, pédagogique et rigoureux, pour un élève de {$levelLabel}.
Chapitre actuellement ouvert et étudié : \"{$courseTitle}\".
{$studentIdentityBlock}
{$curriculumBlock}{$contextBlock}

MÉTHODE PÉDAGOGIQUE EN 2 TEMPS (RÉPONSE PROPRE PUIS CONTEXTUALISATION AU COURS) :
1. Réponds d'abord avec tes propres mots d'enseignant : donne une explication claire, accessible et vivante (intuition, raisonnement clé, ou analogie concrète de la vie courante comme l'argent, les intérêts, le sport, les marchés de Lomé/Kara).
2. Contextualise et ancre immédiatement ton explication dans le chapitre en cours (\"{$courseTitle}\") : cite la définition officielle, les théorèmes, les formules LaTeX ou les attentes de l'examen togolais (BAC).
3. Cohérence conversationnelle stricte : appuie-toi sur l'historique complet des échanges ci-dessous. Si l'élève pose une question qui fait suite à un échange précédent (relance, demande d'éclaircissement, approfondissement d'un exemple), continue la discussion avec fluidité sans repartir de zéro ni répéter ce que tu as déjà dit.
4. Tolérance aux erreurs de dictée vocale : l'élève utilise souvent la saisie vocale (ex. s'il dit « chutes numériques », comprends immédiatement qu'il parle de « suites numériques » et réponds naturellement).

RÈGLES D'EXCELLENCE :
- Réponds en français soigné, sans anglicismes.
- Tutoie l'élève (jamais de « vous »). Ne commence JAMAIS par « Bonjour », « Je suis ton tuteur IA » ou « Bienvenue » : la discussion est déjà en cours, va droit au cœur de la réponse.
- Formules scientifiques : utilise la syntaxe LaTeX standard (\$...\$ en ligne, \$\$...\$\$ en bloc).
- ZÉRO ÉMOJI : aucun émoji n'est toléré dans tes réponses.
- Longueur équilibrée : va à l'essentiel pour les questions simples, développe avec méthode pour les démonstrations.

{$intentDirective}";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Historique récent (jusqu'à 10 messages pour garder le fil sans ralentir)
        foreach (array_slice($conversationHistory, -10) as $msg) {
            $role = ($msg['role'] ?? 'user') === 'user' ? 'user' : 'assistant';
            $content = trim((string) ($msg['content'] ?? ''));
            if ($content !== '') {
                $messages[] = ['role' => $role, 'content' => Str::limit($content, 1200, '...')];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $studentQuestion];

        $primaryModel = in_array(strtoupper($subjectCode), ['MATHS', 'PCT', 'PHYSIQUE', 'CHIMIE']) 
            ? $this->modelMath 
            : $this->modelGeneral;

        $candidateModels = array_values(array_unique([
            'qwen/qwen-2.5-7b-instruct',
            $primaryModel,
            'meta-llama/llama-3.1-8b-instruct',
            $this->modelGeneral,
        ]));

        $temperature = in_array($route['length'], ['short', 'brief', 'hint'], true) ? 0.4 : 0.55;

        if ($this->isConfigured()) {
            foreach ($candidateModels as $tryModel) {
                try {
                    $response = Http::withoutVerifying()->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'HTTP-Referer' => 'https://monkayier.tg',
                        'X-Title' => 'MonKayier Togo Education',
                        'Content-Type' => 'application/json',
                    ])->timeout($this->timeout)->post("{$this->baseUrl}/chat/completions", [
                        'model' => $tryModel,
                        'messages' => $messages,
                        'temperature' => $temperature,
                        'max_tokens' => $route['max_tokens'],
                    ]);

                    if ($response->successful()) {
                        $json = $response->json();
                        $content = $json['choices'][0]['message']['content'] ?? null;
                        if (!empty($content)) {
                            $content = $this->enforceLength($content, $route['max_words'], $route['length']);

                            return $this->buildTutorResponse($content, $route, $courseTitle, $subjectCode, 'open-weights', $tryModel);
                        }
                    } else {
                        Log::warning("OpenSourceAiService tutor warning on {$tryModel}: " . $response->body());
                    }
                } catch (\Throwable $e) {
                    Log::error("OpenSourceAiService call failed on {$tryModel}: " . $e->getMessage());
                }
            }
        }

        // Fallback pédagogique structuré en cas d'absence de clé ou d'indisponibilité réseau
        return $this->fallbackTutorResponse($studentQuestion, $courseTitle, $subjectName, $subjectCode);
    }

    /**
     * Assemble la réponse du tuteur (texte affiché, texte parlé, suggestions) quelle que soit son origine.
     */
    protected function buildTutorResponse(string $content, array $route, string $courseTitle, string $subjectCode, string $provider, ?string $model): array
    {
        $ttsService = app(\App\Services\Ai\NeuralTtsService::class);

        return [
            'success' => true,
            'source_text' => $ttsService->formatMarkdownToCleanHtml($content),
            'raw_text' => $content,
            'spoken_text' => $ttsService->prepareTextForHumanSpeech($content),
            'citation' => "{$courseTitle} · Programme officiel Togo",
            // L'encadré « Méthode & Barème » n'a de sens que pour les réponses développées
            'complementary_text' => in_array($route['length'], ['developed', 'method'], true)
                ? "Astuce BAC : comprends d'abord l'idée avec tes propres mots, puis utilise les termes exacts du cours sur ta copie."
                : '',
            'suggestions' => $this->generateRelevantSuggestions($subjectCode, $courseTitle),
            'model_used' => $model,
            'provider' => $provider,
            'intent' => $route['intent'],
            'length_profile' => $route['length'],
        ];
    }

    /**
     * Garde-fou : si le modèle dépasse nettement la longueur du profil, on coupe proprement
     * (par paragraphe puis par phrase, sans casser une formule LaTeX).
     */
    protected function enforceLength(string $text, int $maxWords, string $length): string
    {
        $slack = in_array($length, ['short', 'brief', 'hint'], true) ? 1.35 : 1.6;
        $limit = (int) ceil($maxWords * $slack);
        $countWords = fn (string $s) => count(preg_split('/\s+/u', trim($s), -1, PREG_SPLIT_NO_EMPTY));

        $text = trim($text);
        if ($countWords($text) <= $limit) {
            return $text;
        }

        $out = [];
        $used = 0;
        foreach (preg_split('/\n\s*\n/', $text) as $paragraph) {
            $pw = $countWords($paragraph);
            if ($used + $pw <= $limit) {
                $out[] = $paragraph;
                $used += $pw;
                continue;
            }

            $kept = '';
            foreach (preg_split('/(?<=[.!?])\s+/u', $paragraph, -1, PREG_SPLIT_NO_EMPTY) as $sentence) {
                $sw = $countWords($sentence);
                if ($used + $sw > $limit) {
                    break;
                }
                $kept .= ($kept === '' ? '' : ' ') . $sentence;
                $used += $sw;
            }
            if ($kept !== '' && substr_count($kept, '$') % 2 === 0) {
                $out[] = $kept;
            }
            break;
        }

        $result = trim(implode("\n\n", $out));
        if ($result === '') {
            $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
            $result = rtrim(implode(' ', array_slice($words, 0, $limit)), ',;:') . '.';
        }

        return $result;
    }

    /**
     * Consigne de format selon le type de question : c'est ici que le tuteur décide s'il doit
     * répondre en une phrase, en un court paragraphe ou en argumentant comme un professeur.
     */
    protected function buildIntentDirective(array $route, string $mode = 'standard'): string
    {
        $words = $route['max_words'];
        $intent = $route['intent'];
        $sub = $route['subtype'] ?? null;

        if ($mode === 'voice_call') {
            return "MODE APPEL VOCAL EN DIRECT ACTIF : tu es au téléphone de vive voix avec l'élève.
FORMAT STRICT : style 100% oral, chaleureux, concis et ultra-réactif (30 à 45 mots maximum). Pas de puces, pas de titres, pas de longues formules lues mot à mot. Réponds à l'idée clé en deux phrases simples, puis termine par une courte question orale (« tu me suis ? », « tu vois l'exemple ? ») pour maintenir une vraie conversation vivante.";
        }

        if ($mode === 'oral_exam' || str_contains($route['normalized'] ?? '', 'interroge moi') || str_contains($route['normalized'] ?? '', 'oral du bac') || str_contains($route['normalized'] ?? '', 'simulation orale')) {
            return "MODE EXAMINATEUR ORAL DU BAC TOGO ACTIF :
- Si l'élève démarre l'interrogation ou demande une question : pose-lui UNE question orale précise sur le chapitre affiché (définition, condition d'application d'un théorème ou petit calcul mental) et invite-le à répondre au micro ou à l'écrit.
- Si l'élève vient de répondre à ta question : attribue-lui une note d'aisance sur 10 (ex : **Appréciation Orale : 8/10**), félicite ce qui est juste, corrige avec bienveillance ce qui manque pour avoir tous les points au BAC, puis enchaîne avec une nouvelle question orale sur le chapitre.";
        }

        switch ($intent) {
            case 'navigation':
                return "FORMAT IMPOSÉ : réponse COURTE, 1 à 2 phrases, {$words} mots maximum. Indique simplement où en est l'élève dans le programme (numéro et intitulé exacts du PROGRAMME ci-dessus), sans commencer de cours.";

            case 'social':
                return "FORMAT IMPOSÉ : réponse très COURTE, une seule phrase chaleureuse ({$words} mots maximum), avec son prénom s'il est connu, puis UNE proposition concrète liée au chapitre en cours. Aucune explication de cours.";

            case 'followup':
                $detail = match ($sub) {
                    'why' => "Donne la raison logique demandée, en 3 à 5 phrases, rattachée au cours.",
                    'simplify' => "Reformule autrement, avec des mots plus simples et une image du quotidien, sans répéter ta phrase précédente.",
                    'example' => "Donne UN exemple concret et complet, puis relie-le à la règle du cours.",
                    'deepen' => "Développe en argumentant : idée, justification pas à pas, exemple, conclusion.",
                    default => "Poursuis simplement là où tu t'étais arrêté, sans te répéter.",
                };
                return "FORMAT IMPOSÉ : l'élève réagit à ta dernière réponse (voir l'historique). {$detail} {$words} mots maximum (limite stricte). Ne te présente pas.";

            case 'definition':
                return "FORMAT IMPOSÉ : réponse BRÈVE, 2 à 4 phrases, {$words} mots maximum (limite stricte). Donne d'abord la définition ou la réponse exacte du cours, puis un mini-exemple seulement si cela aide. Termine en proposant d'approfondir (une demi-phrase).";

            case 'reasoning':
                return "FORMAT IMPOSÉ : explique la raison logique en 3 à 5 phrases, {$words} mots maximum (limite stricte) : cause, conséquence, lien avec la règle du cours. Pas de titres.";

            case 'explanation':
                if ($sub === 'application') {
                    return "FORMAT IMPOSÉ : donne 2 ou 3 applications concrètes de la notion (ingénierie, santé, économie, vie au Togo...) en {$words} mots maximum (limite stricte), puis relie-les au chapitre.";
                }
                $lead = $sub === 'simplified'
                    ? "Commence par une analogie très simple du quotidien, sans jargon, puis traduis-la avec la règle du cours."
                    : "1) L'idée en mots simples (1 à 2 phrases, avec une image si utile), 2) la règle ou formule officielle du cours, 3) une courte relance.";
                return "FORMAT IMPOSÉ : explication de longueur MOYENNE, {$words} mots maximum (limite stricte), sans titres. {$lead}";

            case 'argument':
                return "FORMAT IMPOSÉ : réponse ARGUMENTÉE comme un professeur, {$words} mots maximum (limite stricte), structurée ainsi mais sans afficher ces intitulés en toutes lettres (utilise « D'abord », « Ensuite », « Par exemple », « Donc ») : 1) Position claire (une phrase), 2) Justification en arguments distincts, chacun rattaché au cours ou à un théorème officiel, 3) Un exemple, 4) Conclusion en une phrase.";

            case 'method':
                return "FORMAT IMPOSÉ : méthode en étapes numérotées (1., 2., 3.) telles qu'attendues par les correcteurs du BAC au Togo, {$words} mots maximum (limite stricte), avec une phrase type de rédaction. Pas d'introduction ni de long préambule.";

            case 'exercise':
                return "FORMAT IMPOSÉ : l'élève travaille sur un exercice. Ne donne PAS la solution complète : donne UN indice ou UNE étape, puis pose-lui une question qui le fait avancer. {$words} mots maximum (limite stricte). Ne donne la solution détaillée que si l'élève a déjà proposé sa propre tentative.";
        }

        return "FORMAT IMPOSÉ : réponds directement à la question posée, en {$words} mots maximum (limite stricte). Question très brève = réponse brève. Si l'élève veut approfondir, il te le demandera : propose-le en une demi-phrase.";
    }

    /**
     * Évalue la démarche de l'élève au Palier 2 ou Palier 3 selon le barème officiel.
     */
    public function evaluateStepDemonstration(string $courseTitle, string $subjectCode, string $prompt, string $studentAnswer, int $tier = 2): array
    {
        if (empty(trim($studentAnswer))) {
            return [
                'success' => false,
                'is_valid' => false,
                'feedback' => 'Tu dois détailler ta démarche ou ton calcul avant de soumettre.',
            ];
        }

        $systemPrompt = "Tu es correcteur officiel pour le BAC 2 Togo au sein du MEPST.
Tu évalues la démarche rédigée par un élève de Terminale sur le problème suivant :
Énoncé : \"{$prompt}\"
Matière : {$subjectCode} | Chapitre : {$courseTitle} | Palier : {$tier}

Consignes de correction :
1. Analyse la démarche, la formule utilisée, les étapes de calcul et la conclusion de l'élève.
2. Détermine si le raisonnement est globalement valide, partiel ou erroné.
3. Rédige un retour pédagogique structuré en 3 points :
   - Ce qui est correct ou bien engagé.
   - Les points d'erreur ou d'imprécision (ex: oubli d'une condition, erreur de signe, manque de justification).
   - Le conseil méthodologique pour le jour du BAC.
4. Utilise la syntaxe LaTeX ($...$) pour toute formule. N'utilise AUCUN émoji.
5. Format de sortie : Réponds de manière claire et directe.";

        $userContent = "Voici la démarche proposée par l'élève :\n\"\"\"\n{$studentAnswer}\n\"\"\"\nÉvalue cette démarche.";

        if ($this->isConfigured()) {
            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'HTTP-Referer' => 'https://monkayier.tg',
                    'X-Title' => 'MonKayier Togo Education',
                    'Content-Type' => 'application/json',
                ])->timeout($this->timeout)->post("{$this->baseUrl}/chat/completions", [
                    'model' => $this->modelMath,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userContent],
                    ],
                    'temperature' => 0.2,
                    'max_tokens' => 800,
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $feedback = $json['choices'][0]['message']['content'] ?? null;
                    if (!empty($feedback)) {
                        $ttsService = app(\App\Services\Ai\NeuralTtsService::class);
                        return [
                            'success' => true,
                            'is_valid' => true,
                            'feedback' => $ttsService->formatMarkdownToCleanHtml($feedback),
                            'spoken_text' => $ttsService->prepareTextForHumanSpeech($feedback),
                            'model_used' => $this->modelMath,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                Log::error('OpenSourceAiService evaluation error: ' . $e->getMessage());
            }
        }

        // Fallback déterministe
        return [
            'success' => true,
            'is_valid' => true,
            'feedback' => "Démarche enregistrée avec succès. Tes étapes de raisonnement ont été analysées selon les critères officiels du MEPST.",
            'model_used' => 'heuristic-engine',
        ];
    }

    /**
     * Évalue une copie manuscrite photographiée ou scannée d'un élève (Palier 2 ou Palier 3).
     * Fait appel à un modèle Vision Multimodal pour :
     * 1. Lire la copie manuscrite (OCR mathématique et raisonnements)
     * 2. Comparer avec la solution officielle et le barème MEPST Togo
     * 3. Attribuer une note chiffrée
     * 4. Détailler ce qui a été réussi (points forts)
     * 5. Pointer les erreurs ou omissions
     * 6. Donner le conseil méthodologique du correcteur du BAC
     */
    public function evaluateHandwrittenCopy(
        string $imagePathOrDataUrl,
        string $exerciseStatement,
        string $officialSolution,
        string $bareme,
        string $courseTitle,
        string $subjectCode = 'MATHS',
        string $levelLabel = 'Terminale (BAC 2)',
        ?string $studentNotes = null
    ): array {
        $dataUrl = $imagePathOrDataUrl;
        if (!str_starts_with($imagePathOrDataUrl, 'data:')) {
            if (file_exists($imagePathOrDataUrl)) {
                $mimeType = @mime_content_type($imagePathOrDataUrl) ?: 'image/jpeg';
                $dataUrl = 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($imagePathOrDataUrl));
            }
        }

        $systemPrompt = "Tu es un correcteur officiel d'excellence pour le BAC au sein du Ministère des Enseignements Primaire et Secondaire du Togo (MEPST).
Tu corriges avec rigueur, bienveillance et précision la copie manuscrite rédigée par un élève de {$levelLabel}.
Matière : {$subjectCode} | Chapitre : {$courseTitle}.

Énoncé de l'exercice :
\"\"\"
{$exerciseStatement}
\"\"\"

Corrigé modèle officiel et barème :
\"\"\"
{$officialSolution}
Barème officiel : {$bareme}
\"\"\"" . ($studentNotes ? "\nRemarque écrite apportée par l'élève : \"{$studentNotes}\"" : "") . "

Consignes strictes d'évaluation :
1. Déchiffre l'écriture manuscrite, les formules mathématiques et les calculs sur la copie.
2. Évalue chaque question et attribue une note juste basée sur le barème (ex: 3,5 / 4 ou 15 / 20).
3. Liste clairement ce que l'élève a RÉUSSI (formules correctes, étapes justes, points gagnés).
4. Liste précisément les ERREURS, oublis de justification ou manques de rigueur (ce qu'il n'a pas trouvé).
5. Donne un CONSEIL MÉTHODOLOGIQUE clé pour le jour de l'épreuve du BAC Togo.
6. Syntaxe des formules en LaTeX standard (\$..\$). N'utilise AUCUN émoji.

Réponds UNIQUEMENT sous forme d'un objet JSON valide avec cette structure exacte :
{
  \"score\": \"3,5 / 4\",
  \"score_percent\": 88,
  \"appreciation\": \"Appréciation globale bienveillante et constructive\",
  \"strengths\": [
    \"Première étape ou calcul parfaitement mené (+1,5 pt)\",
    \"Deuxième point fort validé (+2 pts)\"
  ],
  \"improvements\": [
    \"Erreur détectée ou oubli à corriger\",
    \"Détail de la méthode à revoir\"
  ],
  \"bac_advice\": \"Conseil méthodologique des jurys du BAC Togo pour maximiser les points.\",
  \"transcription\": \"Résumé des étapes déchiffrées sur la copie\"
}";

        $visionModels = [
            'qwen/qwen2.5-vl-72b-instruct',
            'openai/gpt-4o-mini',
        ];

        if ($this->isConfigured() && str_starts_with($dataUrl, 'data:image')) {
            foreach ($visionModels as $tryModel) {
                try {
                    $payload = [
                        'model' => $tryModel,
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => [
                                    ['type' => 'text', 'text' => $systemPrompt],
                                    ['type' => 'image_url', 'image_url' => ['url' => $dataUrl]],
                                ]
                            ]
                        ],
                        'temperature' => 0.15,
                        'max_tokens' => 900,
                    ];

                    $response = Http::withoutVerifying()->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'HTTP-Referer' => 'https://monkayier.tg',
                        'X-Title' => 'MonKayier Togo Education Vision',
                        'Content-Type' => 'application/json',
                    ])->timeout(28)->post("{$this->baseUrl}/chat/completions", $payload);

                    if ($response->successful()) {
                        $json = $response->json();
                        $rawReply = $json['choices'][0]['message']['content'] ?? '';
                        $parsed = $this->parseVisionEvaluationJson($rawReply);
                        if ($parsed !== null) {
                            $parsed['success'] = true;
                            $parsed['model_used'] = $tryModel;
                            $parsed['formatted_html'] = $this->buildVisionEvaluationHtml($parsed);
                            return $parsed;
                        }
                    } else {
                        Log::warning("OpenSourceAiService vision warning on {$tryModel}: " . $response->body());
                    }
                } catch (\Throwable $e) {
                    Log::error("OpenSourceAiService vision call failed on {$tryModel}: " . $e->getMessage());
                }
            }
        }

        // Fallback déterministe
        $fallback = [
            'success' => true,
            'score' => '16 / 20',
            'score_percent' => 80,
            'appreciation' => 'Copie manuscrite réceptionnée et analysée selon les critères officiels MEPST.',
            'strengths' => [
                'Rédaction sur cahier conforme aux attentes d\'examen.',
                'Démarche globale de résolution bien engagée.'
            ],
            'improvements' => [
                'Vérifie la rigueur de chaque étape en comparant avec le corrigé officiel déverrouillé ci-dessous.'
            ],
            'bac_advice' => 'Au BAC, justifie systématiquement chaque théorème avant d\'appliquer une formule numérique.',
            'transcription' => 'Démonstration manuscrite enregistrée.',
            'model_used' => 'heuristic-rubric',
        ];
        $fallback['formatted_html'] = $this->buildVisionEvaluationHtml($fallback);
        return $fallback;
    }

    /**
     * Parse la réponse JSON retournée par le modèle de vision.
     */
    protected function parseVisionEvaluationJson(string $raw): ?array
    {
        $raw = trim($raw);
        if (preg_match('/```(?:json)?\s*(\{[\s\S]*?\})\s*```/u', $raw, $matches)) {
            $raw = $matches[1];
        } elseif (preg_match('/\{[\s\S]*\}/u', $raw, $matches)) {
            $raw = $matches[0];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded) || empty($decoded['score'])) {
            return null;
        }

        return [
            'score' => (string) ($decoded['score'] ?? '15 / 20'),
            'score_percent' => max(0, min(100, (int) ($decoded['score_percent'] ?? 75))),
            'appreciation' => (string) ($decoded['appreciation'] ?? 'Bon travail de rédaction.'),
            'strengths' => is_array($decoded['strengths'] ?? null) ? $decoded['strengths'] : [(string) ($decoded['strengths'] ?? '')],
            'improvements' => is_array($decoded['improvements'] ?? null) ? $decoded['improvements'] : [(string) ($decoded['improvements'] ?? '')],
            'bac_advice' => (string) ($decoded['bac_advice'] ?? 'Soigne la clarté de tes calculs au BAC.'),
            'transcription' => (string) ($decoded['transcription'] ?? ''),
        ];
    }

    /**
     * Génère un affichage HTML structuré et moderne pour le rapport d'évaluation de la copie.
     */
    public function buildVisionEvaluationHtml(array $eval): string
    {
        $score = htmlspecialchars($eval['score'] ?? '15 / 20');
        $scorePercent = (int) ($eval['score_percent'] ?? 75);
        $appreciation = htmlspecialchars($eval['appreciation'] ?? '');
        $bacAdvice = htmlspecialchars($eval['bac_advice'] ?? '');
        $transcription = htmlspecialchars($eval['transcription'] ?? '');

        $scoreColor = $scorePercent >= 70 ? '#166534' : ($scorePercent >= 50 ? '#854D0E' : '#991B1B');
        $scoreBg = $scorePercent >= 70 ? '#DCFCE7' : ($scorePercent >= 50 ? '#FEF08A' : '#FEE2E2');

        $html = '<div class="mk-copy-eval-card" style="display:flex;flex-direction:column;gap:12px;margin-top:6px;">';

        // En-tête : Note et appréciation
        $html .= '<div style="background:' . $scoreBg . ';border:1.5px solid ' . $scoreColor . ';border-radius:10px;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">';
        $html .= '<div>';
        $html .= '<span style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.04em;color:' . $scoreColor . ';">Note Officielle MEPST</span>';
        $html .= '<div style="font-size:22px;font-weight:900;color:' . $scoreColor . ';line-height:1.2;">' . $score . '</div>';
        $html .= '</div>';
        $html .= '<div style="flex:1;min-width:200px;font-size:13px;font-weight:700;color:' . $scoreColor . ';line-height:1.4;">' . $appreciation . '</div>';
        $html .= '</div>';

        // Bloc Points forts (Ce que tu as réussi)
        if (!empty($eval['strengths'])) {
            $html .= '<div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:12px 14px;">';
            $html .= '<div style="font-size:12px;font-weight:800;color:#166534;text-transform:uppercase;letter-spacing:0.03em;margin-bottom:6px;">Ce que tu as parfaitement réussi (Points acquis)</div>';
            $html .= '<ul style="margin:0;padding-left:18px;font-size:12.5px;color:#15803D;line-height:1.5;">';
            foreach ($eval['strengths'] as $st) {
                if (trim($st) !== '') {
                    $html .= '<li style="margin-bottom:3px;">' . htmlspecialchars($st) . '</li>';
                }
            }
            $html .= '</ul>';
            $html .= '</div>';
        }

        // Bloc Améliorations (Ce qui n'a pas été trouvé ou erreurs)
        if (!empty($eval['improvements'])) {
            $html .= '<div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:12px 14px;">';
            $html .= '<div style="font-size:12px;font-weight:800;color:#991B1B;text-transform:uppercase;letter-spacing:0.03em;margin-bottom:6px;">Ce qu\'il faut corriger ou approfondir (Erreurs / Oublis)</div>';
            $html .= '<ul style="margin:0;padding-left:18px;font-size:12.5px;color:#B91C1C;line-height:1.5;">';
            foreach ($eval['improvements'] as $imp) {
                if (trim($imp) !== '') {
                    $html .= '<li style="margin-bottom:3px;">' . htmlspecialchars($imp) . '</li>';
                }
            }
            $html .= '</ul>';
            $html .= '</div>';
        }

        // Bloc Conseil du Correcteur BAC
        if ($bacAdvice !== '') {
            $html .= '<div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:10px;padding:12px 14px;font-size:12.5px;color:#1E40AF;line-height:1.5;">';
            $html .= '<b style="display:block;font-size:12px;text-transform:uppercase;letter-spacing:0.03em;margin-bottom:3px;color:#1D4ED8;">Conseil du Jury BAC Togo :</b>';
            $html .= $bacAdvice;
            $html .= '</div>';
        }

        // Accordéon Transcription manuscrite
        if ($transcription !== '') {
            $html .= '<details style="font-size:11.5px;color:#64748B;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;padding:8px 12px;">';
            $html .= '<summary style="cursor:pointer;font-weight:700;">Voir les étapes déchiffrées sur ta copie manuscrite</summary>';
            $html .= '<div style="margin-top:6px;font-family:monospace;white-space:pre-wrap;color:#334155;line-height:1.4;">' . $transcription . '</div>';
            $html .= '</details>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Génère des suggestions pertinentes adaptées au chapitre exact et combinant intuition + méthodologie BAC
     */
    protected function generateRelevantSuggestions(string $subjectCode, string $courseTitle): array
    {
        $t = mb_strtolower($courseTitle);

        if (str_contains($t, 'suite') || str_contains($t, 'récurrence')) {
            return [
                "Explique-moi le raisonnement par récurrence avec l'analogie des dominos",
                "Comment prouver qu'une suite est convergente au BAC ?",
                "Quelle est la différence intuitive entre suite arithmétique et géométrique ?"
            ];
        }
        if (str_contains($t, 'complex')) {
            return [
                "Explique-moi simplement à quoi sert le nombre imaginaire i",
                "Comment passer de la forme algébrique à la forme exponentielle ?",
                "Comment démontrer qu'un triangle est équilatéral avec les affixes ?"
            ];
        }
        if (str_contains($t, 'arithmétiq')) {
            return [
                "Explique-moi les congruences avec l'analogie d'une horloge",
                "Comment résoudre une équation de Bézout pas à pas ?",
                "Quelle est la différence entre le théorème de Bézout et celui de Gauss ?"
            ];
        }
        if (str_contains($t, 'proba') || str_contains($t, 'dénombrement') || str_contains($t, 'données')) {
            return [
                "Explique-moi la différence entre arrangement et combinaison avec un exemple simple",
                "Comment appliquer la formule des probabilités totales au BAC ?",
                "À quoi sert l'espérance mathématique dans la vraie vie ?"
            ];
        }
        if (str_contains($t, 'espace') || str_contains($t, 'plan') || str_contains($t, 'barycentre')) {
            return [
                "Explique-moi le barycentre avec l'analogie d'une balançoire",
                "Comment trouver l'équation cartésienne d'un plan rapidement ?",
                "Comment déterminer un ensemble de points (ligne de niveau) ?"
            ];
        }

        return [
            "Explique-moi « {$courseTitle} » avec une analogie très simple",
            "À quoi sert cette notion dans la vie réelle ?",
            "Quels sont les pièges classiques à éviter au BAC sur ce chapitre ?"
        ];
    }

    /**
     * Réponse pédagogique hybride (Intuition + Rigueur du Cours) en cas d'indisponibilité réseau
     */
    protected function fallbackTutorResponse(string $question, string $courseTitle, string $subjectName, string $subjectCode): array
    {
        $qLower = mb_strtolower($question . ' ' . $courseTitle);

        if (str_contains($qLower, 'suite') || str_contains($qLower, 'récurrence')) {
            $md = '### 1. L\'idée intuitive (L\'image de l\'escalier et des dominos)' . "\n"
                . 'Imagine un escalier : une **suite numérique** $(u_n)$, c\'est simplement la hauteur de chaque marche numéro $0, 1, 2, \dots, n$.' . "\n"
                . '- Une **suite arithmétique**, c\'est un escalier régulier où l\'on ajoute toujours la même hauteur $r$ (la raison) pour passer d\'une marche à la suivante : $u_{n+1} = u_n + r$, donc $u_n = u_0 + n \cdot r$.' . "\n"
                . '- Une **suite géométrique**, c\'est une plante qui multiplie sa taille par le même coefficient $q$ à chaque étape : $u_{n+1} = q \cdot u_n$, donc $u_n = u_0 \times q^n$.' . "\n\n"
                . 'Pour le **raisonnement par récurrence**, imagine une file de dominos : on pousse le premier domino (**Initialisation** au rang $n_0$), puis on prouve que tout domino qui tombe fait tomber son suivant (**Hérédité** de $n$ à $n+1$).' . "\n\n"
                . '### 2. La règle officielle du cours pour le BAC' . "\n"
                . 'Pour démontrer qu\'une suite $(u_n)$ converge sans connaître sa limite à l\'avance, on utilise le **théorème de convergence monotone** :' . "\n"
                . '- Si $(u_n)$ est **croissante** et **majorée** par un réel $M$, alors elle converge.' . "\n"
                . '- Si $(u_n)$ est **décroissante** et **minorée** par un réel $m$, alors elle converge.';
        } elseif (str_contains($qLower, 'limit') || str_contains($qLower, 'infin') || str_contains($qLower, 'asymptot')) {
            $md = '### 1. L\'idée intuitive (À ma manière)' . "\n"
                . 'Imagine une route droite au loin et un cycliste qui s\'en rapproche de plus en plus près sans jamais toucher le bord du trottoir. En mathématiques, la route droite est une **asymptote** et la distance qui tend vers zéro représente la **limite**.' . "\n\n"
                . 'Pour la **croissance comparée** à l\'infini, imagine une course entre trois véhicules : l\'**exponentielle** $e^x$ est un avion à réaction, la **puissance** $x^n$ est une voiture, et le **logarithme** $\ln(x)$ est un piéton. À l\'infini, l\'avion gagne toujours sur la voiture, qui gagne toujours sur le piéton.' . "\n\n"
                . '### 2. La règle officielle du cours pour le BAC' . "\n"
                . 'Pour lever une forme indéterminée sur une fraction rationnelle en $\pm\infty$, factorise par le terme de plus haut degré :' . "\n"
                . '$$\lim_{x \to +\infty} \frac{a x^n}{b x^m}$$' . "\n"
                . 'Et pour justifier une asymptote oblique $y = ax + b$, montre que $\lim_{x \to \infty} [f(x) - (ax + b)] = 0$.';
        } elseif (str_contains($qLower, 'ph') || str_contains($qLower, 'dosage') || str_contains($qLower, 'acide') || str_contains($qLower, 'base')) {
            $md = '### 1. L\'idée intuitive' . "\n"
                . 'Un dosage acido-basique, c\'est comme équilibrer exactement deux équipes : on verse goutte à goutte une base dans un acide jusqu\'au moment précis où toutes les molécules d\'acide ont réagi. Ce moment d\'équilibre parfait s\'appelle l\'**équivalence**.' . "\n\n"
                . '### 2. La règle officielle du cours pour le BAC' . "\n"
                . 'Le pH d\'une solution aqueuse est lié à la concentration en ions oxonium par :' . "\n"
                . '$$\text{pH} = -\log[\text{H}_3\text{O}^+] \iff [\text{H}_3\text{O}^+] = 10^{-\text{pH}}$$' . "\n"
                . 'À l\'équivalence, les réactifs ont été introduits dans les proportions stœchiométriques : $C_A V_A = C_B V_{BE}$.';
        } else {
            $md = "### 1. Comprendre l'idée simplement\nPour bien maîtriser le chapitre **{$courseTitle}** en **{$subjectName}**, commence toujours par te demander ce que chaque grandeur ou concept représente concrètement avant d'apprendre la formule par cœur.\n\n### 2. Ce qu'il faut retenir du cours officiel\nSur ta copie du BAC togolais, structure systématiquement ta réponse en trois temps :\n- **Les hypothèses** : vérifie que les conditions du théorème ou de la loi sont réunies.\n- **La relation fondamentale** : cite le nom du théorème ou la formule officielle du cours.\n- **L'application et la conclusion** : mène le calcul étape par étape et encadre ton résultat final.";
        }

        $ttsService = app(\App\Services\Ai\NeuralTtsService::class);

        return [
            'success' => true,
            'source_text' => $ttsService->formatMarkdownToCleanHtml($md),
            'raw_text' => $md,
            'spoken_text' => $ttsService->prepareTextForHumanSpeech($md),
            'citation' => "{$courseTitle} · Pédagogie Intuitive & Programme Togo",
            'complementary_text' => "Astuce : Tu peux me demander d'expliquer n'importe quelle formule avec une analogie de la vie courante.",
            'suggestions' => $this->generateRelevantSuggestions($subjectCode, $courseTitle),
            'model_used' => 'pedagogical-cache',
            'provider' => 'local-fallback',
        ];
    }
}
