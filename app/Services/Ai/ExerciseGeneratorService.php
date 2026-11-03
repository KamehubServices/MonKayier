<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExerciseGeneratorService
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
        $this->modelGeneral = env('OPENSOURCE_AI_MODEL_GENERAL', 'qwen/qwen-2.5-7b-instruct');
        $this->timeout = max(10, min(30, (int) env('OPENSOURCE_AI_TIMEOUT', 16)));
    }

    public function isAiAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Génère une épreuve officielle personnalisée selon les filtres sélectionnés par l'élève.
     */
    public function generate(string $subjectCode, string $chapter, string $difficulty = 'medium', string $format = 'exam', array $selectedChapters = [], ?object $student = null): array
    {
        $subjectCode = strtoupper(trim($subjectCode ?: 'MATHS'));
        $difficulty = in_array($difficulty, ['easy', 'medium', 'hard'], true) ? $difficulty : 'medium';
        $format = in_array($format, ['exam', 'atelier', 'quick'], true) ? $format : 'exam';

        if (empty($chapter) && !empty($selectedChapters)) {
            $chapter = implode(' · ', $selectedChapters);
        }

        // 1. Tenter la génération par IA générative si le service est configuré
        if ($this->isAiAvailable()) {
            try {
                $aiExercise = $this->generateWithAi($subjectCode, $chapter, $difficulty, $format, $student);
                if ($aiExercise && !empty($aiExercise['sections'])) {
                    return $aiExercise;
                }
            } catch (\Throwable $e) {
                Log::warning("ExerciseGeneratorService AI error ({$subjectCode}): " . $e->getMessage());
            }
        }

        // 2. Repli immédiat et certifié sur le vivier d'épreuves authentiques adaptées aux chapitres choisis
        return $this->generateFromCurriculumFallback($subjectCode, $chapter, $difficulty, $format);
    }

    /**
     * Génération dynamique par IA (Qwen 2.5 / Mistral via OpenRouter)
     */
    protected function generateWithAi(string $subjectCode, string $chapter, string $difficulty, string $format, ?object $student = null): ?array
    {
        $subjectMeta = $this->getSubjectMeta($subjectCode);
        $diffLabel = $this->getDifficultyLabel($difficulty);
        $model = in_array($subjectCode, ['MATHS', 'PCT'], true) ? $this->modelMath : $this->modelGeneral;

        $targetDuration = match ($format) {
            'quick' => '45 minutes',
            'atelier' => '1 heure 30',
            default => $subjectMeta['default_duration'],
        };

        $totalPoints = match ($format) {
            'quick' => 6,
            'atelier' => 10,
            default => 20,
        };

        $systemPrompt = "Tu es un inspecteur pédagogique national du Ministère des Enseignements Primaire, Secondaire et Technique (MEPST) du Togo.
Tu es le concepteur officiel des épreuves du Baccalauréat 2ème Partie (BAC 2 Série D).
Tu dois concevoir une épreuve d'entraînement officielle rigoureuse, contextualisée et conforme aux annales togolaises.

Matière : {$subjectMeta['name']} ({$subjectCode})
Coefficient : {$subjectMeta['coeff_num']}
Chapitre(s) imposé(s) : \"{$chapter}\"
Niveau de difficulté : {$diffLabel}
Durée : {$targetDuration}
Total des points : {$totalPoints} points

RÈGLES PÉDAGOGIQUES ET TYPOGRAPHIQUES STRICTES :
1. Les questions doivent porter précisément sur le ou les chapitres demandés : \"{$chapter}\".
2. FORMULES MATHÉMATIQUES & SCIENTIFIQUES : Rédige TOUTES les formules avec la syntaxe LaTeX standard ($...$ pour l'inline comme \$f(x) = \\sqrt{x}\$, et $$...$$ pour les équations centrées).
3. Le total des points de toutes les questions doit être exactement de {$totalPoints} points.
4. Aucun émoji dans aucun champ.
5. Fournis une grille de barème officielle (bareme_grid) et la démonstration intégrale étape par étape (full_demonstration).
6. Fournis 3 indices méthodologiques progressifs sans donner directement la solution finale.

FORMAT DE SORTIE : Réponds UNIQUEMENT par un objet JSON valide et bien formé, sans balises ```json autour, selon la structure suivante :
{
  \"id\": \"ex_gen_" . time() . "\",
  \"subject_code\": \"{$subjectCode}\",
  \"subject_name\": \"{$subjectMeta['name']}\",
  \"chapter\": \"{$chapter}\",
  \"difficulty\": \"{$difficulty}\",
  \"difficulty_label\": \"{$diffLabel}\",
  \"format\": \"{$format}\",
  \"total_points\": {$totalPoints},
  \"title\": \"Épreuve Officielle de {$subjectMeta['name']} — BAC 2 Série D\",
  \"academic_header\": {
    \"system_title\": \"MONKAYIER · BANQUE D'ÉPREUVES D'ENTRAÎNEMENT\",
    \"exam\": \"BACCALAURÉAT DE L'ENSEIGNEMENT DU SECOND DEGRÉ (BAC 2)\",
    \"session\": \"ÉPREUVE BLANCHE D'ENTRAÎNEMENT NATIONALE\",
    \"serie\": \"SÉRIE D (SCIENTIFIQUE)\",
    \"duration\": \"{$targetDuration}\",
    \"coeff\": \"Coefficient : {$subjectMeta['coeff_num']}\",
    \"instructions\": \"La clarté des raisonnements et la précision des justifications sont primordiales.\"
  },
  \"sections\": [
    {
      \"title\": \"EXERCICE 1 · NOM DU THÈME\",
      \"points\": 6,
      \"intro\": \"Texte de mise en situation ou énoncé global...\",
      \"questions\": [
        { \"num\": \"1.a\", \"label\": \"Énoncé précis avec LaTeX si nécessaire...\", \"points\": 2.0 },
        { \"num\": \"1.b\", \"label\": \"Énoncé précis...\", \"points\": 2.0 },
        { \"num\": \"1.c\", \"label\": \"Énoncé précis...\", \"points\": 2.0 }
      ]
    }
  ],
  \"hints\": [
    \"Premier indice de méthode...\",
    \"Deuxième indice de calcul...\",
    \"Troisième indice de vérification...\"
  ],
  \"model_solution\": {
    \"bareme_grid\": {
      \"Exercice 1 - Q1.a\": \"Critère d'évaluation officiel (2.0 pts)\",
      \"Exercice 1 - Q1.b\": \"Critère d'évaluation officiel (2.0 pts)\",
      \"Exercice 1 - Q1.c\": \"Critère d'évaluation officiel (2.0 pts)\"
    },
    \"full_demonstration\": \"Démonstration intégrale détaillée...\"
  }
}";

        $userPrompt = "Génère immédiatement l'épreuve complète d'entraînement en {$subjectMeta['name']} sur le(s) chapitre(s) : {$chapter}. Difficulté : {$diffLabel}. Format : {$format}.";

        $response = Http::withoutVerifying()->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'HTTP-Referer' => 'https://monkayier.tg',
            'X-Title' => 'MonKayier Togo Education',
            'Content-Type' => 'application/json',
        ])->timeout($this->timeout)->post("{$this->baseUrl}/chat/completions", [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => 0.35,
            'max_tokens' => 2200,
        ]);

        if (!$response->successful()) {
            return null;
        }

        $rawContent = $response->json('choices.0.message.content');
        if (empty($rawContent)) {
            return null;
        }

        // Nettoyer les balises Markdown éventuelles
        $cleaned = trim($rawContent);
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/i', $cleaned, $matches)) {
            $cleaned = trim($matches[1]);
        }

        $decoded = json_decode($cleaned, true);
        if (!is_array($decoded) || empty($decoded['sections'])) {
            return null;
        }

        // S'assurer de l'identifiant unique et des métadonnées
        $decoded['id'] = 'ex_ai_' . time() . '_' . Str::random(6);
        $decoded['subject_code'] = $subjectCode;
        $decoded['subject_name'] = $subjectMeta['name'];
        $decoded['difficulty'] = $difficulty;
        $decoded['difficulty_label'] = $diffLabel;
        $decoded['generated_by'] = 'ai_qwen';

        return $decoded;
    }

    /**
     * Bibliothèque de secours de haute qualité, alignée sur les annales officielles togolaises (MEPST)
     */
    public function generateFromCurriculumFallback(string $subjectCode, string $chapter, string $difficulty, string $format): array
    {
        $subjectMeta = $this->getSubjectMeta($subjectCode);
        $diffLabel = $this->getDifficultyLabel($difficulty);
        $chapterLower = mb_strtolower($chapter);

        // Sélection thématique spécialisée
        $sections = [];
        $hints = [];
        $bareme = [];
        $demo = '';

        if ($subjectCode === 'MATHS') {
            if (str_contains($chapterLower, 'suite') || str_contains($chapterLower, 'récurrence') || str_contains($chapterLower, 'recurrence')) {
                // Chapitre : Suites numériques & Récurrence
                $sections = [
                    [
                        'title' => 'EXERCICE 1 · SUITE ARITHMÉTICO-GÉOMÉTRIQUE & CONVERGENCE',
                        'points' => 8,
                        'intro' => 'On considère la suite $(u_n)_{n \\in \\mathbb{N}}$ définie par $u_0 = 1$ et pour tout $n \\in \\mathbb{N}$, $u_{n+1} = \\frac{1}{3}u_n + 2$.',
                        'questions' => [
                            [
                                'num' => '1.a',
                                'label' => 'Démontrer par récurrence que pour tout entier naturel $n$, on a : $u_n < 3$.',
                                'points' => 2.0,
                            ],
                            [
                                'num' => '1.b',
                                'label' => 'Étudier le sens de variation de la suite $(u_n)$. En déduire qu\'elle est convergente et préciser sa limite $\\ell$.',
                                'points' => 2.5,
                            ],
                            [
                                'num' => '1.c',
                                'label' => 'On pose pour tout $n \\in \\mathbb{N}$, $v_n = u_n - 3$. Démontrer que $(v_n)$ est une suite géométrique de raison $q = \\frac{1}{3}$ dont on précisera le premier terme $v_0$.',
                                'points' => 2.0,
                            ],
                            [
                                'num' => '1.d',
                                'label' => 'Exprimer $v_n$ puis $u_n$ en fonction de $n$, et retrouver la limite de $(u_n)$. Calculer la somme $S_n = \\sum_{k=0}^n u_k$.',
                                'points' => 1.5,
                            ],
                        ],
                    ],
                    [
                        'title' => 'EXERCICE 2 · MODÉLISATION D\'ÉVOLUTION DE POPULATION',
                        'points' => 12,
                        'intro' => 'Dans une localité de la région des Savanes au Togo, une coopérative agricole compte 600 membres en 2024 ($n=0$). Chaque année, 10% des membres quittent la coopérative, mais 90 nouveaux membres y adhèrent.',
                        'questions' => [
                            [
                                'num' => '2.a',
                                'label' => 'Montrer que le nombre de membres $P_{n+1}$ l\'année $n+1$ vérifie la relation : $P_{n+1} = 0{,}9\\,P_n + 90$.',
                                'points' => 3.0,
                            ],
                            [
                                'num' => '2.b',
                                'label' => 'Déterminer la suite auxiliaire $(w_n)$ définie par $w_n = P_n - 900$. Montrer que $(w_n)$ est géométrique.',
                                'points' => 4.0,
                            ],
                            [
                                'num' => '2.c',
                                'label' => 'En déduire l\'expression explicite de $P_n$ en fonction de $n$. La coopérative peut-elle dépasser 850 membres ? Déterminer l\'année correspondante.',
                                'points' => 5.0,
                            ],
                        ],
                    ],
                ];
                $hints = [
                    'Pour la récurrence : vérifier l\'initialisation pour n=0 ($u_0 = 1 < 3$), puis utiliser l\'hypothèse de récurrence $u_n < 3$ pour majorer $\frac{1}{3}u_n + 2$.',
                    'Pour la suite auxiliaire $v_n$ : calculer le rapport $\frac{v_{n+1}}{v_n}$ ou exprimer directement $v_{n+1} = u_{n+1} - 3 = \frac{1}{3}u_n + 2 - 3$.',
                    'Pour la résolution de $P_n \ge 850$ : utiliser les propriétés du logarithme népérien $\ln(x)$ pour isoler $n$.',
                ];
                $bareme = [
                    'Exercice 1 - Q1.a' => 'Initialisation (0.5 pt), Hérédité avec encadrement rigoureux (1.0 pt), Conclusion (0.5 pt)',
                    'Exercice 1 - Q1.b' => 'Étude du signe de $u_{n+1} - u_n$ (1.5 pt), Théorème de convergence monotone (1.0 pt)',
                    'Exercice 1 - Q1.c' => 'Démonstration $v_{n+1} = \frac{1}{3}v_n$ et calcul de $v_0 = -2$ (2.0 pts)',
                    'Exercice 1 - Q1.d' => 'Expression de $u_n = 3 - 2(\frac{1}{3})^n$ et somme géométrique (1.5 pt)',
                    'Exercice 2 - Q2.a' => 'Traduction des variations : $P_{n+1} = P_n - 0{,}10P_n + 90 = 0{,}9P_n + 90$ (3.0 pts)',
                    'Exercice 2 - Q2.b' => 'Démonstration géométrique raison $q = 0{,}9$, $w_0 = -300$ (4.0 pts)',
                    'Exercice 2 - Q2.c' => 'Expression $P_n = 900 - 300(0{,}9)^n$ et résolution par les logarithmes $n \ge 18$ (5.0 pts)',
                ];
                $demo = <<<'EOD'
EXERCICE 1 : SUITES NUMÉRIQUES (8 points)
1.a Démontrons par récurrence que pour tout $n \in \mathbb{N}$, $u_n < 3$ :
- Initialisation ($n=0$) : $u_0 = 1$. Or $1 < 3$, donc la propriété est vraie au rang 0.
- Hérédité : Supposons que pour un entier $n \ge 0$, on ait $u_n < 3$.
Alors $\frac{1}{3}u_n < 1$, d'où $\frac{1}{3}u_n + 2 < 1 + 2 = 3$, c'est-à-dire $u_{n+1} < 3$.
La propriété est donc héréditaire.
- Conclusion : Par le principe de récurrence, $\forall n \in \mathbb{N},\; u_n < 3$.

1.b Étude du sens de variation :
$u_{n+1} - u_n = \frac{1}{3}u_n + 2 - u_n = -\frac{2}{3}u_n + 2 = \frac{2}{3}(3 - u_n)$.
Comme $u_n < 3$, on a $3 - u_n > 0$, donc $u_{n+1} - u_n > 0$.
La suite $(u_n)$ est donc strictement croissante.
Étant croissante et majorée par 3, d'après le théorème de convergence monotone, $(u_n)$ converge vers une limite $\ell \le 3$.
En passant à la limite dans la relation de récurrence : $\ell = \frac{1}{3}\ell + 2 \iff \frac{2}{3}\ell = 2 \iff \ell = 3$.

1.c Suite auxiliaire :
$v_{n+1} = u_{n+1} - 3 = \frac{1}{3}u_n + 2 - 3 = \frac{1}{3}u_n - 1 = \frac{1}{3}(u_n - 3) = \frac{1}{3}v_n$.
Donc $(v_n)$ est une suite géométrique de raison $q = \frac{1}{3}$ et de premier terme $v_0 = u_0 - 3 = 1 - 3 = -2$.

1.d Expression et somme :
$v_n = v_0 \cdot q^n = -2\left(\frac{1}{3}\right)^n$.
Comme $u_n = v_n + 3$, on a : $u_n = 3 - 2\left(\frac{1}{3}\right)^n$.
Puisque $\left|\frac{1}{3}\right| < 1$, $\lim_{n \to +\infty} \left(\frac{1}{3}\right)^n = 0$, donc $\lim_{n \to +\infty} u_n = 3$.
Somme : $S_n = \sum_{k=0}^n (v_k + 3) = \sum_{k=0}^n v_k + 3(n+1) = -2 \cdot \frac{1 - (1/3)^{n+1}}{1 - 1/3} + 3(n+1) = -3\left(1 - (1/3)^{n+1}\right) + 3(n+1)$.
EOD;
            } elseif (str_contains($chapterLower, 'proba') || str_contains($chapterLower, 'dénombrement') || str_contains($chapterLower, 'aléatoire')) {
                // Chapitre : Probabilités & Dénombrement
                $sections = [
                    [
                        'title' => 'EXERCICE 1 · DÉNOMBREMENT & COMBINATOIRE AU BAC',
                        'points' => 8,
                        'intro' => 'Une urne contient 12 jetons indiscernables au toucher : 5 jetons verts numérotés de 1 à 5, 4 jetons jaunes numérotés de 1 à 4, et 3 jetons rouges numérotés de 1 à 3. On tire simultanément 3 jetons au hasard.',
                        'questions' => [
                            [
                                'num' => '1.a',
                                'label' => 'Calculer le nombre total de tirages possibles $\\text{Card}(\\Omega)$.',
                                'points' => 2.0,
                            ],
                            [
                                'num' => '1.b',
                                'label' => 'Calculer la probabilité de tirer exactement 3 jetons de la même couleur.',
                                'points' => 3.0,
                            ],
                            [
                                'num' => '1.c',
                                'label' => 'Calculer la probabilité d\'obtenir au moins un jeton portant un numéro pair.',
                                'points' => 3.0,
                            ],
                        ],
                    ],
                    [
                        'title' => 'EXERCICE 2 · VARIABLE ALÉATOIRE & LOI BINOMIALE',
                        'points' => 12,
                        'intro' => 'Une épreuve consiste à interroger un candidat sur 5 questions indépendantes à choix multiples. Pour chaque question, 4 réponses sont proposées dont une seule est exacte. Un candidat non préparé répond au hasard.',
                        'questions' => [
                            [
                                'num' => '2.a',
                                'label' => 'Justifier que la variable aléatoire $X$ désignant le nombre de bonnes réponses obtenues suit une loi binomiale dont on précisera les paramètres $n$ et $p$.',
                                'points' => 3.0,
                            ],
                            [
                                'num' => '2.b',
                                'label' => 'Calculer la probabilité que le candidat obtienne exactement 3 bonnes réponses (arrondir à $10^{-3}$).',
                                'points' => 4.0,
                            ],
                            [
                                'num' => '2.c',
                                'label' => 'Déterminer l\'espérance mathématique $E(X)$ et l\'écart-type $\\sigma(X)$. Interpréter le résultat.',
                                'points' => 5.0,
                            ],
                        ],
                    ],
                ];
                $hints = [
                    'Pour le tirage simultané : utiliser les combinaisons $C_n^p = \binom{n}{p} = \frac{n!}{p!(n-p)!}$.',
                    'Pour l\'événement "au moins un numéro pair" : penser à l\'événement contraire "aucun numéro pair" (que des numéros impairs).',
                    'Pour la loi binomiale : la probabilité de $k$ succès est $P(X=k) = \binom{n}{k} p^k (1-p)^{n-k}$.',
                ];
                $bareme = [
                    'Exercice 1 - Q1.a' => 'Calcul $\binom{12}{3} = \frac{12 \times 11 \times 10}{6} = 220$ issues (2.0 pts)',
                    'Exercice 1 - Q1.b' => 'Somme $\binom{5}{3} + \binom{4}{3} + \binom{3}{3} = 10 + 4 + 1 = 15$ ; $P = \frac{15}{220} = \frac{3}{44}$ (3.0 pts)',
                    'Exercice 1 - Q1.c' => 'Événement contraire avec les 7 jetons impairs : $P = 1 - \frac{\binom{7}{3}}{220} = 1 - \frac{35}{220} = \frac{185}{220} = \frac{37}{44}$ (3.0 pts)',
                    'Exercice 2 - Q2.a' => 'Schéma de Bernoulli répété 5 fois de manière indépendante : $n=5$ et $p=0{,}25$ (3.0 pts)',
                    'Exercice 2 - Q2.b' => '$P(X=3) = \binom{5}{3}(0{,}25)^3(0{,}75)^2 = 10 \times \frac{1}{64} \times \frac{9}{16} = \frac{90}{1024} \approx 0{,}088$ (4.0 pts)',
                    'Exercice 2 - Q2.c' => '$E(X) = n \cdot p = 5 \times 0{,}25 = 1{,}25$ et $\sigma(X) = \sqrt{5 \times 0{,}25 \times 0{,}75} \approx 0{,}968$ (5.0 pts)',
                ];
                $demo = <<<'EOD'
EXERCICE 1 : DÉNOMBREMENT (8 points)
1.a Nombre d'issues possibles :
Tirage simultané de 3 jetons parmi 12 : $\text{Card}(\Omega) = \binom{12}{3} = \frac{12 \times 11 \times 10}{3 \times 2 \times 1} = 220$.

1.b Tirer 3 jetons de la même couleur :
Soit 3 verts : $\binom{5}{3} = 10$, soit 3 jaunes : $\binom{4}{3} = 4$, soit 3 rouges : $\binom{3}{3} = 1$.
Total cas favorables = $10 + 4 + 1 = 15$.
Probabilité : $P = \frac{15}{220} = \frac{3}{44} \approx 0{,}068$.

1.c Au moins un jeton pair :
Jetons pairs : Verts (2, 4 = 2), Jaunes (2, 4 = 2), Rouges (2 = 1) -> Total = 5 jetons pairs et 7 jetons impairs.
Événement contraire $\bar{E}$ : 'aucun jeton pair' (tirer 3 jetons parmi les 7 impairs).
$\text{Card}(\bar{E}) = \binom{7}{3} = 35$.
$P(E) = 1 - P(\bar{E}) = 1 - \frac{35}{220} = \frac{185}{220} = \frac{37}{44} \approx 0{,}841$.

EXERCICE 2 : LOI BINOMIALE (12 points)
2.a L'épreuve comporte 5 questions indépendantes à deux issues (Succès avec $p = 1/4 = 0{,}25$, Échec avec $q = 0{,}75$).
La variable aléatoire $X$ suit donc la loi binomiale $\mathcal{B}(5 ;\, 0{,}25)$.

2.b Probabilité d'avoir 3 bonnes réponses :
$P(X = 3) = \binom{5}{3} (0{,}25)^3 (0{,}75)^2 = 10 \times \frac{1}{64} \times \frac{9}{16} = \frac{90}{1024} \approx 0{,}088$.

2.c Espérance et écart-type :
$E(X) = n \cdot p = 5 \times 0{,}25 = 1{,}25$.
En moyenne, un candidat répondant au hasard obtient 1,25 bonne réponse sur 5.
Variance : $V(X) = n \cdot p \cdot (1 - p) = 5 \times 0{,}25 \times 0{,}75 = 0{,}9375$.
Écart-type : $\sigma(X) = \sqrt{0{,}9375} \approx 0{,}968$.
EOD;
            } else {
                // Chapitre par défaut : Analyse, Limites & TVI (Formules en LaTeX impeccable)
                $sections = [
                    [
                        'title' => 'EXERCICE 1 · NOMBRES COMPLEXES & GÉOMÉTRIE DU PLAN',
                        'points' => 6,
                        'intro' => 'Le plan complexe est rapporté à un repère orthonormé direct $(O, \\vec{u}, \\vec{v})$. On considère les points $A$, $B$ et $C$ d\'affixes respectives : $z_A = 1 + i\\sqrt{3}$, $z_B = 2$, et $z_C = z_A \\cdot z_B$.',
                        'questions' => [
                            [
                                'num' => '1.a',
                                'label' => 'Écrire $z_A$ sous forme trigonométrique et exponentielle ($z_A = r e^{i\\theta}$).',
                                'points' => 2.0,
                            ],
                            [
                                'num' => '1.b',
                                'label' => 'Calculer l\'affixe $z_C$ sous forme algébrique, puis en déduire la valeur exacte de $\\cos\\left(\\frac{\\pi}{3}\\right)$ et $\\sin\\left(\\frac{\\pi}{3}\\right)$.',
                                'points' => 2.0,
                            ],
                            [
                                'num' => '1.c',
                                'label' => 'Calculer les distances $OA$, $OB$ et $AB$. En déduire la nature géométrique exacte du triangle $OAB$.',
                                'points' => 2.0,
                            ],
                        ],
                    ],
                    [
                        'title' => 'PROBLÈME DE SYNTHÈSE · ANALYSE, ASYMPTOTES & THÉORÈME TVI',
                        'points' => 14,
                        'intro' => 'Soit la fonction numérique $f$ définie sur $\\mathbb{R} \\setminus \\{2\\}$ par : $$f(x) = \\frac{2x^2 - 3x + 1}{x - 2}$$ On note $(\\mathcal{C}_f)$ sa courbe représentative dans un repère orthonormé $(O, \\vec{i}, \\vec{j})$.',
                        'questions' => [
                            [
                                'num' => '2.a',
                                'label' => 'Déterminer l\'ensemble de définition $D_f$ et calculer les limites de $f$ aux bornes de $D_f$. En déduire l\'équation de l\'asymptote verticale.',
                                'points' => 3.5,
                            ],
                            [
                                'num' => '2.b',
                                'label' => 'Montrer que pour tout $x \\ne 2$, $f(x) = 2x + 1 + \\frac{3}{x - 2}$. En déduire que la droite $(\\mathcal{D}) : y = 2x + 1$ est asymptote oblique à $(\\mathcal{C}_f)$ en $+\\infty$ et en $-\\infty$.',
                                'points' => 3.5,
                            ],
                            [
                                'num' => '2.c',
                                'label' => 'Calculer la dérivée $f\'(x)$ et dresser le tableau de variations complet de $f$ sur son domaine.',
                                'points' => 3.5,
                            ],
                            [
                                'num' => '2.d',
                                'label' => 'Démontrer que l\'équation $f(x) = 0$ admet une solution unique $\\alpha$ sur l\'intervalle $[0 ; 1]$. Donner un encadrement à $10^{-1}$ de $\\alpha$.',
                                'points' => 3.5,
                            ],
                        ],
                    ],
                ];
                $hints = [
                    'Pour le module de $z_A$ : $|z_A| = \sqrt{1^2 + (\sqrt{3})^2} = 2$. Factoriser par le module pour identifier l\'argument.',
                    'Pour l\'asymptote oblique : calculer $\lim_{x \to \pm\infty} [f(x) - (2x + 1)]$ et vérifier que la limite vaut 0.',
                    'Pour le TVI : vérifier que $f$ est continue et strictement monotone sur $[0 ; 1]$ et que $f(0) \cdot f(1) < 0$.',
                ];
                $bareme = [
                    'Exercice 1 - Q1.a' => 'Module $|z_A| = 2$ (1.0 pt), Forme exponentielle $z_A = 2e^{i\pi/3}$ (1.0 pt)',
                    'Exercice 1 - Q1.b' => 'Forme algébrique $z_C = 2 + 2i\sqrt{3}$ et valeurs trigonométriques (2.0 pts)',
                    'Exercice 1 - Q1.c' => 'Calcul des 3 longueurs $OA = OB = AB = 2$ -> Triangle équilatéral (2.0 pts)',
                    'Problème - Q2.a' => 'Limites en $\pm\infty$ et limites à gauche et à droite en 2 (asymptote $x = 2$) (3.5 pts)',
                    'Problème - Q2.b' => 'Forme décomposée et calcul de la limite nulle de l\'écart (3.5 pts)',
                    'Problème - Q2.c' => 'Dérivée $f\'(x) = \frac{2x^2 - 8x + 5}{(x-2)^2}$ et tableau de variations complet (3.5 pts)',
                    'Problème - Q2.d' => 'Application rigoureuse du théorème de bijection (TVI) et encadrement (3.5 pts)',
                ];
                $demo = <<<'EOD'
EXERCICE 1 : COMPLEXES (6 points)
1.a $|z_A| = \sqrt{1 + 3} = 2$.
$\cos(\theta) = 1/2$ et $\sin(\theta) = \sqrt{3}/2 \implies \theta = \frac{\pi}{3} [2\pi]$.
$z_A = 2\left(\cos\frac{\pi}{3} + i\sin\frac{\pi}{3}\right) = 2e^{i\pi/3}$.

1.b $z_C = z_A \cdot z_B = 2(1 + i\sqrt{3}) = 2 + 2i\sqrt{3}$.

1.c $OA = |z_A| = 2$, $OB = |z_B| = 2$, $AB = |z_B - z_A| = |1 - i\sqrt{3}| = 2$.
Comme $OA = OB = AB = 2$, le triangle $OAB$ est équilatéral.

PROBLÈME : ANALYSE & TVI (14 points)
2.a $D_f = ]-\infty ; 2[ \cup ]2 ; +\infty[$.
$\lim_{x \to \pm\infty} f(x) = \lim_{x \to \pm\infty} \frac{2x^2}{x} = \pm\infty$.
$\lim_{x \to 2^-} f(x) = -\infty$ et $\lim_{x \to 2^+} f(x) = +\infty$. La droite $x = 2$ est asymptote verticale.

2.b Pour $x \ne 2$, $2x + 1 + \frac{3}{x - 2} = \frac{(2x+1)(x-2) + 3}{x - 2} = \frac{2x^2 - 3x + 1}{x - 2} = f(x)$.
$\lim_{x \to \pm\infty} [f(x) - (2x+1)] = \lim_{x \to \pm\infty} \frac{3}{x - 2} = 0$.
Donc la droite $(\mathcal{D}) : y = 2x + 1$ est asymptote oblique à $(\mathcal{C}_f)$ en $\pm\infty$.

2.c $f'(x) = \frac{(4x - 3)(x - 2) - (2x^2 - 3x + 1)(1)}{(x - 2)^2} = \frac{2x^2 - 8x + 5}{(x - 2)^2}$.
$\Delta = 64 - 40 = 24 > 0$. Racines : $x_1 = 2 - \frac{\sqrt{6}}{2} \approx 0{,}78$ et $x_2 = 2 + \frac{\sqrt{6}}{2} \approx 3{,}22$.
$f$ est croissante sur $]-\infty ; x_1]$, décroissante sur $[x_1 ; 2[$, décroissante sur $]2 ; x_2]$, et croissante sur $[x_2 ; +\infty[$.

2.d Sur l'intervalle $[0 ; 1]$, $f(0) = -\frac{1}{2}$ et $f(1) = 0$.
$f$ est continue et strictement croissante sur $[0 ; x_1]$. Elle s'annule en $\alpha = 0{,}5$.
EOD;
            }
        } elseif ($subjectCode === 'PCT') {
            $sections = [
                [
                    'title' => 'CHIMIE · DOSAGE ACIDO-BASIQUE & pH-MÉTRIE',
                    'points' => 6,
                    'intro' => 'On titre un volume $V_A = 20\\text{ mL}$ d\'une solution aqueuse d\'acide éthanoïque $\\text{CH}_3\\text{COOH}$ de concentration $C_A$ inconnue par une solution d\'hydroxyde de sodium $(\\text{Na}^+ + \\text{HO}^-)$ de concentration $C_B = 1{,}0 \\times 10^{-1}\\text{ mol/L}$. Le volume à l\'équivalence est $V_{BE} = 15\\text{ mL}$.',
                    'questions' => [
                        [
                            'num' => '1.a',
                            'label' => 'Écrire l\'équation-bilan de la réaction de dosage et rappeler ses caractéristiques indispensables.',
                            'points' => 1.5,
                        ],
                        [
                            'num' => '1.b',
                            'label' => 'Définir l\'équivalence acido-basique et calculer la concentration molaire $C_A$ de la solution acide.',
                            'points' => 2.0,
                        ],
                        [
                            'num' => '1.c',
                            'label' => 'À la demi-équivalence ($V_B = \\frac{V_{BE}}{2}$), le pH vaut $4{,}8$. Déterminer le $\\text{p}K_a$ du couple acide/base considéré.',
                            'points' => 2.5,
                        ],
                    ],
                ],
                [
                    'title' => 'PHYSIQUE · MÉCANIQUE NEWTONIENNE & MOUVEMENT DANS LE CHAMP DE PESANTEUR',
                    'points' => 8,
                    'intro' => 'Dans un repère orthonormé direct $(O, \\vec{i}, \\vec{j})$, un projectile assimilé à un point matériel de masse $m = 250\\text{ g}$ est lancé depuis l\'origine $O$ avec une vitesse initiale $v_0 = 18\\text{ m/s}$ faisant un angle $\\alpha = 45^\\circ$ avec l\'axe horizontal. On prend $g = 9{,}8\\text{ m/s}^2$. On néglige les frottements de l\'air.',
                    'questions' => [
                        [
                            'num' => '2.a',
                            'label' => 'Établir par application de la deuxième loi de Newton l\'expression vectorielle du vecteur accélération $\\vec{a}(t)$ du projectile.',
                            'points' => 2.0,
                        ],
                        [
                            'num' => '2.b',
                            'label' => 'En déduire les équations horaires du mouvement $x(t)$ et $y(t)$, ainsi que l\'équation cartésienne de la trajectoire $y(x)$.',
                            'points' => 3.0,
                        ],
                        [
                            'num' => '2.c',
                            'label' => 'Calculer la hauteur maximale $H$ (la flèche) et la portée horizontale $X_P$ du tir.',
                            'points' => 3.0,
                        ],
                    ],
                ],
                [
                    'title' => 'PHYSIQUE · OSCILLATIONS ÉLECTRIQUES LIBRES DANS UN CIRCUIT LC',
                    'points' => 6,
                    'intro' => 'Un condensateur de capacité $C = 5\\,\\mu\\text{F}$ préalablement chargé sous une tension $U_0 = 10\\text{ V}$ est branché aux bornes d\'une bobine purement inductive d\'inductance $L = 0{,}2\\text{ H}$.',
                    'questions' => [
                        [
                            'num' => '3.a',
                            'label' => 'Établir l\'équation différentielle régissant la charge $q(t)$ du condensateur.',
                            'points' => 2.0,
                        ],
                        [
                            'num' => '3.b',
                            'label' => 'Donner l\'expression de la période propre $T_0$ et calculer sa valeur numérique.',
                            'points' => 2.0,
                        ],
                        [
                            'num' => '3.c',
                            'label' => 'Montrer que l\'énergie électromagnétique totale du circuit est constante et calculer sa valeur en millijoules (mJ).',
                            'points' => 2.0,
                        ],
                    ],
                ],
            ];
            $hints = [
                'Pour le dosage : à l\'équivalence, $n_A = n_B \implies C_A V_A = C_B V_{BE}$.',
                'Pour le projectile : projeter $\vec{P} = m\vec{a}$ sur les axes $Ox$ horizontal et $Oy$ vertical ($a_x = 0$ et $a_y = -g$).',
                'Pour le circuit LC : l\'énergie totale $E = \frac{1}{2} C U_0^2$.',
            ];
            $bareme = [
                'Chimie - Q1.a' => 'Équation $\text{CH}_3\text{COOH} + \text{HO}^- \to \text{CH}_3\text{COO}^- + \text{H}_2\text{O}$ (totale, rapide, exothermique) (1.5 pt)',
                'Chimie - Q1.b' => 'Définition exacte et calcul $C_A = \frac{C_B V_{BE}}{V_A} = 7{,}5 \times 10^{-2}\text{ mol/L}$ (2.0 pts)',
                'Chimie - Q1.c' => 'À la demi-équivalence, $[\text{acide}] = [\text{base}]$, d\'où $\text{pH} = \text{p}K_a = 4{,}8$ (2.5 pts)',
                'Physique - Q2.a' => 'Bilan des forces, système, référentiel galiléen et $\vec{a} = \vec{g}$ (2.0 pts)',
                'Physique - Q2.b' => 'Équations horaires $x(t) = v_0 \cos(\alpha) t$, $y(t) = -\frac{1}{2}gt^2 + v_0 \sin(\alpha) t$ et trajectoire (3.0 pts)',
                'Physique - Q2.c' => 'Flèche $H = \frac{(v_0\sin\alpha)^2}{2g} = 8{,}27\text{ m}$ et portée $X_P = 33{,}06\text{ m}$ (3.0 pts)',
                'Physique - Q3.a' => 'Loi des mailles $L\frac{d^2q}{dt^2} + \frac{q}{C} = 0$ (2.0 pts)',
                'Physique - Q3.b' => 'Période propre $T_0 = 2\pi\sqrt{LC} \approx 6{,}28\text{ ms}$ (2.0 pts)',
                'Physique - Q3.c' => 'Conservation de l\'énergie $E = \frac{1}{2} C U_0^2 = 0{,}25\text{ mJ}$ (2.0 pts)',
            ];
            $demo = <<<'EOD'
CHIMIE (6 points)
1.a Équation de dosage : $\text{CH}_3\text{COOH} + \text{HO}^- \to \text{CH}_3\text{COO}^- + \text{H}_2\text{O}$. La réaction est quasi-totale, rapide et unique.

1.b À l'équivalence, les réactifs ont été mélangés dans les proportions stœchiométriques : $n_A = n_B \implies C_A V_A = C_B V_{BE} \implies C_A = \frac{C_B V_{BE}}{V_A} = \frac{0{,}10 \times 15}{20} = 0{,}075\text{ mol/L} = 7{,}5 \times 10^{-2}\text{ mol/L}$.

1.c À la demi-équivalence, la moitié de l'acide a été transformée en sa base conjuguée : $[\text{CH}_3\text{COOH}] = [\text{CH}_3\text{COO}^-]$. Par la formule de Henderson-Hasselbalch, $\text{pH} = \text{p}K_a + \log(1) = \text{p}K_a$. Donc $\text{p}K_a = 4{,}8$.

MÉCANIQUE (8 points)
2.a Système : {projectile}, Référentiel terrestre supposé galiléen. Seul le poids $\vec{P} = m\vec{g}$ s'applique. $m\vec{a} = m\vec{g} \implies \vec{a} = \vec{g}$.
Projections : $a_x = 0$ et $a_y = -g$.

2.b Vitesse : $v_x(t) = v_0\cos\alpha$ et $v_y(t) = -gt + v_0\sin\alpha$.
Position : $x(t) = (v_0\cos\alpha)t$ et $y(t) = -\frac{1}{2}gt^2 + (v_0\sin\alpha)t$.
Trajectoire : $t = \frac{x}{v_0\cos\alpha} \implies y(x) = -\frac{g}{2v_0^2\cos^2\alpha}x^2 + (\tan\alpha)x$.

2.c Flèche (sommet où $v_y = 0$) : $H = \frac{(v_0\sin\alpha)^2}{2g} = \frac{(18 \times \frac{\sqrt{2}}{2})^2}{2 \times 9{,}8} = \frac{162}{19{,}6} \approx 8{,}27\text{ m}$.
Portée ($y = 0$) : $X_P = \frac{v_0^2\sin(2\alpha)}{g} = \frac{324 \times 1}{9{,}8} \approx 33{,}06\text{ m}$.
EOD;
        } elseif ($subjectCode === 'SVT') {
            $sections = [
                [
                    'title' => 'PREMIÈRE PARTIE · RESTITUTION STRUCTURÉE DES CONNAISSANCES',
                    'points' => 8,
                    'intro' => 'La méiose et la fécondation assurent le maintien du caryotype de l\'espèce et la génération d\'une immense diversité génétique au sein des populations.',
                    'questions' => [
                        [
                            'num' => '1.a',
                            'label' => 'Définir avec rigueur scientifique : crossing-over (enjambement), brassage interchromosomique, et cellule diploïde.',
                            'points' => 3.0,
                        ],
                        [
                            'num' => '1.b',
                            'label' => 'À l\'aide de schémas clairs avec 2 paires de chromosomes homologues ($2n = 4$), expliquer les étapes de l\'anaphase I conduisant au brassage interchromosomique.',
                            'points' => 5.0,
                        ],
                    ],
                ],
                [
                    'title' => 'DEUXIÈME PARTIE · RÉSOLUTION DE PROBLÈME (GÉNÉTIQUE HUMAINE)',
                    'points' => 12,
                    'intro' => 'Dans une famille togolaise, un couple sain a donné naissance à deux enfants : une fille saine et un garçon atteint d\'une myopathie héréditaire.',
                    'questions' => [
                        [
                            'num' => '2.a',
                            'label' => 'L\'allèle morbide responsable de la maladie est-il dominant ou récessif ? Justifier formellement.',
                            'points' => 3.0,
                        ],
                        [
                            'num' => '2.b',
                            'label' => 'Discuter la localisation du gène : autosome ou chromosome sexuel X ? Établir l\'hypothèse la plus vraisemblable.',
                            'points' => 4.5,
                        ],
                        [
                            'num' => '2.c',
                            'label' => 'Écrire les génotypes des parents et calculer la probabilité pour un futur enfant d\'être atteint de la maladie.',
                            'points' => 4.5,
                        ],
                    ],
                ],
            ];
            $hints = [
                'Si deux parents sains ont un enfant malade, l\'allèle de la maladie est obligatoirement récessif.',
                'Pour la transmission liée à X : le garçon reçoit son chromosome Y de son père et son chromosome X de sa mère.',
                'Dresser l\'échiquier de croisement entre un père $X^H Y$ et une mère conductrice $X^H X^h$.',
            ];
            $bareme = [
                'Partie I - Q1.a' => 'Définitions exactes crossing-over (1.0 pt), brassage interchromosomique (1.0 pt), diploïdie (1.0 pt)',
                'Partie I - Q1.b' => 'Schémas anaphase I avec 2 dispositions équiproblables (5.0 pts)',
                'Partie II - Q2.a' => 'Allèle récessif car les parents phénotypiquement sains portent l\'allèle à l\'état masqué (3.0 pts)',
                'Partie II - Q2.b' => 'Gène porté par la portion propre du chromosome sexuel X (4.5 pts)',
                'Partie II - Q2.c' => 'Génotypes corrects et probabilité d\'atteinte égale à 1/4 (25%) (4.5 pts)',
            ];
            $demo = <<<'EOD'
PREMIÈRE PARTIE : RESTITUTION (8 points)
1.a Définitions :
- Crossing-over : Échange réciproque de segments de chromatides non-sœurs entre chromosomes homologues appariés en prophase I de méiose.
- Brassage interchromosomique : Répartition aléatoire et indépendante des chromosomes d'origine paternelle et maternelle vers les pôles cellulaires en anaphase I.
- Diploïdie : État d'une cellule possédant deux jeux complets de chromosomes homologues (noté $2n$).

1.b Schématisation :
Avec $2n=4$ (chromosomes $A/a$ et $B/b$), la métaphase I offre deux arrangements équiprobables (50% chacun) conduisant à 4 types de gamètes distincts ($AB, ab, Ab, aB$).

DEUXIÈME PARTIE : GÉNÉTIQUE HUMAINE (12 points)
2.a Deux parents sains ont un fils malade : l'allèle morbide est masqué chez les parents, il est donc récessif (noté $m$) et l'allèle normal est dominant (noté $N$).

2.b La mère saine est conductrice hétérozygote $X^N X^m$. Le père sain est $X^N Y$. Le fils atteint reçoit le chromosome $X^m$ de sa mère et le $Y$ de son père ($X^m Y$). La transmission est récessive liée au chromosome X.

2.c Échiquier de croisement : 50% de garçons sains ($X^N Y$), 50% de garçons malades ($X^m Y$). Les filles sont toutes saines ($X^N X^N$ ou $X^N X^m$). Risque global pour le futur enfant : $\frac{1}{4} = 25\%$.
EOD;
        } elseif ($subjectCode === 'FRANCAIS') {
            $sections = [
                [
                    'title' => 'PREMIÈRE PARTIE · CONTRACTION DE TEXTE (10 POINTS)',
                    'points' => 10,
                    'intro' => 'Texte officiel : Extrait d\'un essai sur « Éducation, Technologies numériques et Développement durable en Afrique de l\'Ouest » (environ 400 mots).',
                    'questions' => [
                        [
                            'num' => '1.a',
                            'label' => 'Dégager l\'idée générale du texte et structurer son plan logique en trois étapes.',
                            'points' => 3.0,
                        ],
                        [
                            'num' => '1.b',
                            'label' => 'Résumer le texte au quart de sa longueur (100 mots avec une tolérance de plus ou moins 10%). Indiquer le décompte exact des mots à la fin.',
                            'points' => 7.0,
                        ],
                    ],
                ],
                [
                    'title' => 'DEUXIÈME PARTIE · DISCUSSION LITTÉRAIRE & ARGUMENTATIVE (10 POINTS)',
                    'points' => 10,
                    'intro' => 'L\'auteur affirme : « La véritable indépendance d\'un peuple repose d\'abord sur la maîtrise souveraine de ses savoirs scientifiques et de sa culture propre. »',
                    'questions' => [
                        [
                            'num' => '2',
                            'label' => 'Dans un développement argumenté et illustré d\'exemples précis tirés de vos lectures et de l\'actualité africaine, appréciez cette réflexion.',
                            'points' => 10.0,
                        ],
                    ],
                ],
            ];
            $hints = [
                'Pour le résumé : respecter le système d\'énonciation du texte sans ajouter d\'avis personnel.',
                'Pour la discussion : adopter un plan dialectique (I. Thèse : en quoi les savoirs fondent la souveraineté ; II. Antithèse / Nuance : les autres piliers économiques et institutionnels ; III. Synthèse).',
            ];
            $bareme = [
                'Partie I - Q1.a' => 'Identification de la thèse et des 3 articulations logiques (3.0 pts)',
                'Partie I - Q1.b' => 'Respect du nombre de mots (1.5 pt), fidélité aux idées (3.5 pts), qualité de l\'expression (2.0 pts)',
                'Partie II - Q2' => 'Introduction complète (2.0 pts), Argumentation étayée (5.0 pts), Conclusion et ouverture (2.0 pts), Correction de la langue (1.0 pt)',
            ];
            $demo = <<<'EOD'
CORRIGÉ TYPE DE FRANÇAIS :
1.a Thèse : Le rôle émancipateur des savoirs technologiques et culturels dans l'essor du continent.
1.b Résumé conforme au quart (102 mots).
2. Discussion : Plan détaillé avec thèse (exemples d'inventions locales, indépendance industrielle) et antithèse (nécessité de capitaux et d'institutions démocratiques solides).
EOD;
        } elseif ($subjectCode === 'PHILO') {
            $sections = [
                [
                    'title' => 'SUJET 1 · DISSERTATION PHILOSOPHIQUE (20 POINTS)',
                    'points' => 20,
                    'intro' => 'Le candidat traitera au choix l\'un des deux sujets suivants selon les règles académiques de la dissertation ou du commentaire philosophique.',
                    'questions' => [
                        [
                            'num' => 'Sujet 1',
                            'label' => 'L\'État est-il l\'obstacle ou la condition indispensable de la liberté humaine ?',
                            'points' => 20.0,
                        ],
                        [
                            'num' => 'Sujet 2',
                            'label' => 'Commentaire de texte : « L\'obéissance à la loi qu\'on s\'est prescrite est liberté » (Rousseau, Du Contrat Social). Dégager la thèse, le problème et les étapes d\'argumentation.',
                            'points' => 20.0,
                        ],
                    ],
                ],
            ];
            $hints = [
                'Bien définir les notions clés : État (institution souveraine de contrainte légitime) et Liberté (absence d\'entrave vs autonomie morale).',
                'Mobiliser les auteurs majeurs : Thomas Hobbes (Léviathan), Jean-Jacques Rousseau (Contrat social) et Friedrich Nietzsche ou Karl Marx pour la critique de l\'appareil d\'État.',
            ];
            $bareme = [
                'Introduction' => 'Amorce, définition des termes, problème philosophique et plan (4.0 pts)',
                'Partie 1 (Obstacle)' => 'L\'État comme machine de contrainte et de surveillance (5.0 pts)',
                'Partie 2 (Condition)' => 'L\'État protecteur contre la barbarie et garant des droits civiques (5.0 pts)',
                'Partie 3 (Dépassement)' => 'L\'État de droit démocratique où le citoyen est auteur de la loi (4.0 pts)',
                'Conclusion' => 'Bilan et réponse nette au problème (2.0 pts)',
            ];
            $demo = <<<'EOD'
DISSERTATION PHILOSOPHIQUE :
Introduction : Définition de l'État et de la liberté. Problématisation : si l'État impose des lois contraignantes, ne détruit-il pas la liberté qu'il prétend garantir ?
I. L'État apparaît d'abord comme une entrave (Hobbes, anarchisme).
II. Mais sans l'État, la liberté n'est que la loi du plus fort (état de nature).
III. L'État républicain est l'accomplissement de la liberté comme autonomie (Rousseau).
EOD;
        } else {
            // Histoire-Géo, Anglais, ECM et autres
            $sections = [
                [
                    'title' => 'PREMIÈRE PARTIE · MAÎTRISE DES CONNAISSANCES DU PROGRAMME',
                    'points' => 10,
                    'intro' => 'Questions de synthèse portant sur les axes prioritaires du programme national officiel de ' . $subjectMeta['name'] . '.',
                    'questions' => [
                        [
                            'num' => '1.a',
                            'label' => 'Définir les notions fondamentales et situer leur portée dans le contexte togolais et ouest-africain.',
                            'points' => 4.0,
                        ],
                        [
                            'num' => '1.b',
                            'label' => 'Analyser les enjeux contemporains liés au thème : ' . ($chapter ?: 'Développement et Intégration') . '.',
                            'points' => 6.0,
                        ],
                    ],
                ],
                [
                    'title' => 'DEUXIÈME PARTIE · ANALYSE DE DOCUMENT OU ÉTUDE DE CAS',
                    'points' => 10,
                    'intro' => 'Étude critique de document officiel et synthèse argumentée.',
                    'questions' => [
                        [
                            'num' => '2',
                            'label' => 'À partir des éléments du cours et du document, rédiger une réponse organisée démontrant votre maîtrise méthodologique.',
                            'points' => 10.0,
                        ],
                    ],
                ],
            ];
            $hints = [
                'Bien structurer votre réponse en paragraphes thématiques.',
                'Utiliser le vocabulaire technique propre à la matière.',
            ];
            $bareme = [
                'Partie 1' => 'Exactitude des définitions et repères historiques/géographiques (10.0 pts)',
                'Partie 2' => 'Rigueur de l\'analyse critique et illustration par des données concrètes (10.0 pts)',
            ];
            $demo = "CORRIGÉ TYPE OFFICIEL DE " . strtoupper($subjectMeta['name']) . " :\nRéponse détaillée structurée en introduction, parties argumentées et conclusion conforme aux attentes des jurys d'examen du Togo.";
        }

        return [
            'id' => 'ex_' . strtolower($subjectCode) . '_' . time(),
            'subject_code' => $subjectCode,
            'subject_name' => $subjectMeta['name'],
            'chapter' => $chapter ?: 'Programme Officiel',
            'difficulty' => $difficulty,
            'difficulty_label' => $diffLabel,
            'format' => $format,
            'total_points' => 20,
            'title' => "Épreuve Officielle de {$subjectMeta['name']} — BAC 2 Série D",
            'academic_header' => [
                'system_title' => 'MONKAYIER · BANQUE D\'ÉPREUVES D\'ENTRAÎNEMENT',
                'exam' => 'BACCALAURÉAT DE L\'ENSEIGNEMENT DU SECOND DEGRÉ (BAC 2)',
                'session' => 'ÉPREUVE BLANCHE D\'ENTRAÎNEMENT NATIONALE',
                'serie' => 'SÉRIE D (SCIENTIFIQUE)',
                'duration' => $subjectMeta['default_duration'],
                'coeff' => "Coefficient : {$subjectMeta['coeff_num']}",
                'instructions' => $subjectMeta['instructions'],
            ],
            'sections' => $sections,
            'hints' => $hints,
            'model_solution' => [
                'bareme_grid' => $bareme,
                'full_demonstration' => $demo,
            ],
            'generated_by' => 'curriculum_calibrated',
        ];
    }

    protected function getSubjectMeta(string $subjectCode): array
    {
        return match ($subjectCode) {
            'PCT' => [
                'name' => 'Physique-Chimie et Technologie',
                'coeff_num' => 4,
                'default_duration' => '4 heures',
                'instructions' => 'Calculatrice scientifique non programmable autorisée. La clarté des schémas, le respect des unités et la précision des justifications seront pris en compte.',
            ],
            'SVT' => [
                'name' => 'Sciences de la Vie et de la Terre',
                'coeff_num' => 4,
                'default_duration' => '4 heures',
                'instructions' => 'La précision du vocabulaire biologique, la rigueur des déductions et le soin apporté aux schémas légendés sont déterminants.',
            ],
            'FRANCAIS' => [
                'name' => 'Français',
                'coeff_num' => 2,
                'default_duration' => '4 heures',
                'instructions' => 'L\'usage du dictionnaire est interdit. La qualité de la langue, l\'orthographe et la cohérence de l\'argumentation seront rigoureusement évaluées.',
            ],
            'PHILO' => [
                'name' => 'Philosophie',
                'coeff_num' => 2,
                'default_duration' => '4 heures',
                'instructions' => 'Le candidat traitera obligatoirement un sujet au choix. La problématisation, la rigueur conceptuelle et la culture philosophique sont requises.',
            ],
            'HIST_GEO' => [
                'name' => 'Histoire-Géographie',
                'coeff_num' => 2,
                'default_duration' => '3 heures',
                'instructions' => 'Précision des repères chronologiques et spatiaux exigée.',
            ],
            'ANGLAIS' => [
                'name' => 'Anglais',
                'coeff_num' => 2,
                'default_duration' => '2 heures',
                'instructions' => 'Answer all questions with clarity and precision.',
            ],
            default => [
                'name' => 'Mathématiques',
                'coeff_num' => 4,
                'default_duration' => '4 heures',
                'instructions' => 'La calculatrice scientifique non programmable est autorisée. La clarté des raisonnements et la précision des justifications mathématiques sont primordiales.',
            ],
        };
    }

    protected function getDifficultyLabel(string $difficulty): string
    {
        return match ($difficulty) {
            'easy' => 'Niveau 1 — Fondamental (Socle)',
            'hard' => 'Niveau 3 — Défi Majeur (Excellence BAC)',
            default => 'Niveau 2 — Approfondi (Standard BAC 2 Togo)',
        };
    }
}
