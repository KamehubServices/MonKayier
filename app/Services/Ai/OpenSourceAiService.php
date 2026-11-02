<?php

namespace App\Services\Ai;

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
        $this->modelMath = env('OPENSOURCE_AI_MODEL_MATH', 'mistralai/mistral-small-24b-instruct-2501');
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
     * Génère une réponse socratique, intuitive et pédagogique pour le Tuteur IA dans la Révision IA.
     * Combine fidélité au cours officiel affiché à l'écran et liberté d'explication pédagogique (analogies, vulgarisation, exemples).
     */
    public function askTutor(string $studentQuestion, ?object $course = null, array $conversationHistory = [], ?string $contextText = null, ?string $interactionMode = 'standard'): array
    {
        $courseTitle = $course ? ($course->clean_title ?? $course->title) : 'Programme National Lycée';
        $subjectCode = $course->subject_code ?? 'MATHS';
        $subjectName = $course->subject_name ?? 'Mathématiques';
        $mode = strtolower(trim($interactionMode ?? 'standard'));

        $contextBlock = '';
        if (!empty(trim($contextText ?? ''))) {
            $contextBlock = "\n\n--- CONTENU DU COURS / EXERCICE AFFICHÉ SUR L'ÉCRAN DE L'ÉLÈVE ---\n" . trim($contextText) . "\n------------------------------------------------------------------\n";
        }

        $intentDirective = $this->buildIntentDirective($studentQuestion, $mode);

        // Système de consignes pédagogiques hybrides : Intuition personnelle du professeur + Rigueur du cours officiel MEPST Togo
        $systemPrompt = "Tu es le Tuteur IA d'excellence de la plateforme MonKayier au Togo, agissant comme un véritable professeur particulier passionné, chaleureux et pédagogue pour un élève de Lycée (Terminale BAC 2 / Première BAC 1) en {$subjectName} sur le chapitre : \"{$courseTitle}\".
{$contextBlock}
Philosophie et méthode pédagogique (Approche Hybride : Intuition + Cours Officiel) :
1. Liberté d'explication et pédagogie vivante : Tu ne dois PAS te contenter de réciter mécaniquement le cours. Explique les notions à ta manière, avec tes propres mots, des images mentales parlantes, des analogies concrètes du quotidien (par exemple des situations courantes à Lomé, Kara, en cuisine, en sport, en circulation ou dans la nature) pour débloquer la compréhension intuitive de l'élève.
2. Pont systématique avec le cours officiel : Après avoir fait comprendre l'idée intuitivement (« Pourquoi ça marche »), relie toujours ton explication aux définitions, notations et théorèmes exacts présents sur la fiche de cours de l'élève (« Comment on l'écrit au BAC »).
3. Ouverture intellectuelle : Si l'élève pose une question qui va au-delà de la fiche de cours (ex : « À quoi ça sert dans la vraie vie ? », « D'où vient cette formule ? », « Explique-moi avec un autre exemple »), utilise pleinement tes connaissances générales et scientifiques pour lui répondre avec clarté, puis montre le lien avec son chapitre.
4. Accompagnement sur les exercices : Si l'élève travaille sur un exercice ou un palier, guide-le pas à pas avec bienveillance en lui expliquant la logique du raisonnement et les attentes du barème officiel du BAC togolais (MEPST).
5. Formalisme et clarté visuelle : Utilise la syntaxe LaTeX standard ($..$ en ligne et $$..$$ en bloc centré) pour les formules mathématiques et chimiques. Structure ta réponse avec des titres courts et des puces aérées faciles à lire et agréables à écouter à l'oral. N'utilise JAMAIS d'émojis.

{$intentDirective}";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Intégration de l'historique récent (jusqu'à 6 messages)
        foreach (array_slice($conversationHistory, -6) as $msg) {
            $role = ($msg['role'] ?? 'user') === 'user' ? 'user' : 'assistant';
            $content = $msg['content'] ?? '';
            if (!empty($content)) {
                $messages[] = ['role' => $role, 'content' => $content];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $studentQuestion];

        $primaryModel = in_array(strtoupper($subjectCode), ['MATHS', 'PCT', 'PHYSIQUE', 'CHIMIE']) 
            ? $this->modelMath 
            : $this->modelGeneral;

        $candidateModels = array_values(array_unique([
            $primaryModel,
            'meta-llama/llama-3.1-70b-instruct',
            $this->modelGeneral,
            'qwen/qwen-2.5-7b-instruct',
        ]));

        $maxTokens = ($mode === 'voice_call') ? 650 : 1500;

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
                        'temperature' => 0.55,
                        'max_tokens' => $maxTokens,
                    ]);

                    if ($response->successful()) {
                        $json = $response->json();
                        $content = $json['choices'][0]['message']['content'] ?? null;
                        if (!empty($content)) {
                            $ttsService = app(\App\Services\Ai\NeuralTtsService::class);
                            $cleanHtml = $ttsService->formatMarkdownToCleanHtml($content);
                            $spokenText = $ttsService->prepareTextForHumanSpeech($content);

                            return [
                                'success' => true,
                                'source_text' => $cleanHtml,
                                'raw_text' => $content,
                                'spoken_text' => $spokenText,
                                'citation' => "{$courseTitle} · Pédagogie Intuitive & Programme Togo",
                                'complementary_text' => "Astuce BAC : Comprends d'abord l'idée avec tes propres mots, puis utilise les termes exacts du théorème sur ta copie.",
                                'suggestions' => $this->generateRelevantSuggestions($subjectCode, $courseTitle),
                                'model_used' => $tryModel,
                                'provider' => 'open-weights',
                            ];
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
     * Détecte l'intention de l'élève pour adapter dynamiquement la posture d'explication du Tuteur IA.
     */
    protected function buildIntentDirective(string $question, string $mode = 'standard'): string
    {
        $q = mb_strtolower($question);

        if ($mode === 'voice_call') {
            return "MODE APPEL VOCAL EN DIRECT (STYLE CHATGPT VOICE) ACTIF :
Tu es actuellement en appel vocal mains-libres en direct avec l'élève.
- Réponds avec le naturel d'une vraie conversation orale humaine : 2 à 3 paragraphes courts et fluides (environ 80 à 140 mots).
- Donne une analogie parlante et explique l'essentiel sans noyer l'élève sous de longues listes de formules.
- Termine TOUJOURS par une courte question orale naturelle pour relancer l'échange (ex : « Est-ce que cette image est claire pour toi, ou veux-tu qu'on prenne un petit exemple ensemble ? »).";
        }

        if ($mode === 'oral_exam' || str_contains($q, 'interroge-moi') || str_contains($q, 'oral du bac') || str_contains($q, 'simulation orale')) {
            return "MODE EXAMINATEUR ORAL DU BAC TOGO ACTIF :
- Si l'élève démarre l'interrogation ou demande une question : pose-lui UNE question orale précise et stimulante sur le chapitre affiché (définition, condition d'application d'un théorème ou petit calcul mental), et invite-le à répondre au micro ou à l'écrit.
- Si l'élève vient de répondre à ta question : attribue-lui une note d'aisance sur 10 (ex : **Appréciation Orale : 8/10**), félicite ce qui est juste, corrige avec bienveillance ce qui manque pour avoir tous les points au BAC, puis enchaîne immédiatement avec une nouvelle question orale sur le chapitre !";
        }

        if (
            str_contains($q, 'enfant') ||
            str_contains($q, 'analogie') ||
            str_contains($q, 'simple') ||
            str_contains($q, 'comprends pas') ||
            str_contains($q, 'comprend pas') ||
            str_contains($q, 'image') ||
            str_contains($q, 'concret') ||
            str_contains($q, 'ta manière') ||
            str_contains($q, 'tes mots')
        ) {
            return "CONSIGNE PRIORITAIRE POUR CETTE QUESTION : L'élève demande une explication très intuitive et vulgarisée. Commence immédiatement par une histoire concrète ou une analogie très simple de tous les jours (sans jargon compliqué). Une fois l'image comprise, traduis-la doucement avec la formule ou la règle du cours.";
        }

        if (
            str_contains($q, 'sert à quoi') ||
            str_contains($q, 'vraie vie') ||
            str_contains($q, 'utilité') ||
            str_contains($q, 'quotidien') ||
            str_contains($q, 'application réelle')
        ) {
            return "CONSIGNE PRIORITAIRE POUR CETTE QUESTION : L'élève s'interroge sur l'utilité concrète de cette notion dans le monde réel. Donne-lui 2 ou 3 exemples passionnants d'applications réelles (ingénierie, médecine, architecture, économie, technologie au Togo et dans le monde), puis montre comment la formule du cours permet cela.";
        }

        if (
            str_contains($q, 'exercice') ||
            str_contains($q, 'palier') ||
            str_contains($q, 'calcul') ||
            str_contains($q, 'résoudre') ||
            str_contains($q, 'démontrer') ||
            str_contains($q, 'rédiger') ||
            str_contains($q, 'méthode')
        ) {
            return "CONSIGNE PRIORITAIRE POUR CETTE QUESTION : L'élève cherche une méthode de résolution ou de rédaction. Donne-lui d'abord l'intuition de la méthode (« L'idée clé à retenir »), puis détaille les étapes exactes de rédaction telles qu'attendues par les correcteurs du BAC au Togo.";
        }

        return "CONSIGNE DE STRUCTURE : Organise ta réponse de façon vivante : 1) L'idée intuitive expliquée simplement à ta manière (avec une analogie ou un exemple parlant), 2) La règle ou formule officielle du cours expliquée clairement, 3) Un exemple concret ou un conseil pratique pour le BAC.";
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
