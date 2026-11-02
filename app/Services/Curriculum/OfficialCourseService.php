<?php

namespace App\Services\Curriculum;

use Illuminate\Support\Str;

class OfficialCourseService
{
    /**
     * Retourne la pédagogie complète et officielle pour un cours donné.
     */
    public function getChapterPedagogy($course, string $subjectCode = 'MATHS'): array
    {
        $title = is_object($course) ? ($course->clean_title ?? ($course->title ?? '')) : (string) $course;
        $rawTitle = is_object($course) ? ($course->title ?? $title) : (string) $course;
        $summary = is_object($course) ? ($course->summary ?? '') : '';
        $subject = strtoupper($subjectCode);

        // IMPORTANT : On teste d'abord UNIQUEMENT sur le titre du chapitre ($titleOnly)
        // pour éviter qu'un mot du résumé (ex: "limites de suites géométriques") ne renvoie vers le mauvais chapitre !
        $titleOnly = mb_strtolower($rawTitle . ' ' . $title);
        $fullText = mb_strtolower($rawTitle . ' ' . $summary);

        $data = $this->resolveCourseDataByTitle($titleOnly, $fullText, $title, $summary, $subject);

        // Toujours conserver l'intitulé exact et le code du chapitre sélectionné par l'élève
        if (!empty($title)) {
            $data['title'] = $title;
        }
        if (is_object($course) && !empty($course->chapter_code)) {
            $data['code'] = $course->chapter_code;
        }

        return $data;
    }

    private function resolveCourseDataByTitle(string $titleOnly, string $fullText, string $title, string $summary, string $subject): array
    {
        // =========================================================================
        // 1. MATHÉMATIQUES (MATHS)
        // =========================================================================
        if ($subject === 'MATHS') {
            // Suites numériques & récurrence (Testé AVANT "limite" car le résumé mentionne "limites de suites")
            if (str_contains($titleOnly, 'suite') || str_contains($titleOnly, 'récurrence') || str_contains($titleOnly, 'convergence')) {
                return $this->getSuitesCourseData();
            }

            // Fonctions Logarithme & Exponentielle
            if (str_contains($titleOnly, 'logarithm') || str_contains($titleOnly, 'exponentiel') || str_contains($titleOnly, 'ln(') || str_contains($titleOnly, 'exp(')) {
                return $this->getLogExpCourseData();
            }

            // Nombres complexes (uniquement si "complexe" ou "affixe" est dans le titre)
            if (str_contains($titleOnly, 'complex') || str_contains($titleOnly, 'affixe')) {
                return $this->getComplexesCourseData();
            }

            // Probabilités, dénombrement & organisation des données (statistiques)
            if (str_contains($titleOnly, 'proba') || str_contains($titleOnly, 'dénombrement') || str_contains($titleOnly, 'organisation des données') || str_contains($titleOnly, 'statistiq')) {
                return $this->getProbabilitiesCourseData();
            }

            // Arithmétique
            if (str_contains($titleOnly, 'arithmétiq') || str_contains($titleOnly, 'bezout') || str_contains($titleOnly, 'congruence')) {
                return $this->getArithmetiqueCourseData();
            }

            // Calculs algébriques (Polynômes, Équations, Systèmes dans R²/R³)
            if (str_contains($titleOnly, 'algébriq') || str_contains($titleOnly, 'équation') || str_contains($titleOnly, 'système')) {
                return $this->getCalculsAlgebriquesCourseData();
            }

            // Applications du plan (Homothéties, Isométries, Similitudes)
            if (str_contains($titleOnly, 'applications du plan') || str_contains($titleOnly, 'similitude') || str_contains($titleOnly, 'homothétie') || str_contains($titleOnly, 'isométrie')) {
                return $this->getApplicationsPlanCourseData();
            }

            // Géométrie de l'espace
            if (str_contains($titleOnly, 'espace') || str_contains($titleOnly, 'orthogonalité')) {
                return $this->getGeometrieEspaceCourseData();
            }

            // Géométrie du plan (Barycentres, lignes de niveau, droites et cercles)
            if (str_contains($titleOnly, 'géométrie du plan') || str_contains($titleOnly, 'barycentre') || str_contains($titleOnly, 'ligne de niveau')) {
                return $this->getGeometriePlanCourseData();
            }

            // Trigonométrie
            if (str_contains($titleOnly, 'trigonométri') || str_contains($titleOnly, 'angles orientés')) {
                return $this->getTrigonometrieCourseData();
            }

            // Limites, continuité, asymptotes et Analyse
            if (str_contains($titleOnly, 'limit') || str_contains($titleOnly, 'contin') || str_contains($titleOnly, 'asymptot') || str_contains($titleOnly, 'analyse')) {
                return $this->getLimitsCourseData();
            }
        }

        // =========================================================================
        // 2. PHYSIQUE-CHIMIE & TECHNOLOGIE (PCT / TECHNO)
        // =========================================================================
        if (in_array($subject, ['PCT', 'TECHNO'], true)) {
            if (str_contains($titleOnly, 'newton') || str_contains($titleOnly, 'cinématiq') || str_contains($titleOnly, 'mécanique') || str_contains($titleOnly, 'mouvement')) {
                return $this->getNewtonCourseData();
            }
            if (str_contains($titleOnly, 'acide') || str_contains($titleOnly, 'base') || str_contains($titleOnly, 'dosage') || str_contains($titleOnly, 'aqueuse')) {
                return $this->getAcidoBasiqueCourseData();
            }
            if (str_contains($titleOnly, 'chimie minérale') || str_contains($titleOnly, 'oxydoréduction')) {
                return $this->getChimieMineraleCourseData();
            }
            if (str_contains($titleOnly, 'chimie organique') || str_contains($titleOnly, 'alcane') || str_contains($titleOnly, 'alcool')) {
                return $this->getChimieOrganiqueCourseData();
            }
            if (str_contains($titleOnly, 'optique') || str_contains($titleOnly, 'lentille') || str_contains($titleOnly, 'réfraction')) {
                return $this->getOptiqueCourseData();
            }
            if (str_contains($titleOnly, 'électricit') || str_contains($titleOnly, 'énergie') || str_contains($titleOnly, 'condensateur')) {
                return $this->getElectriciteCourseData();
            }
        }

        // =========================================================================
        // 3. SCIENCES DE LA VIE ET DE LA TERRE (SVT)
        // =========================================================================
        if ($subject === 'SVT') {
            if (str_contains($titleOnly, 'génétiq') || str_contains($titleOnly, 'hérédit') || str_contains($titleOnly, 'caractère')) {
                return $this->getGeneticsCourseData();
            }
            if (str_contains($titleOnly, 'immun') || str_contains($titleOnly, 'défense')) {
                return $this->getImmunologyCourseData();
            }
            if (str_contains($titleOnly, 'cellul')) {
                return $this->getSvtCelluleCourseData();
            }
            if (str_contains($titleOnly, 'géologi') || str_contains($titleOnly, 'tectoniq')) {
                return $this->getSvtGeologieCourseData();
            }
            if (str_contains($titleOnly, 'nutrition') || str_contains($titleOnly, 'matière des êtres vivants') || str_contains($titleOnly, 'respiration')) {
                return $this->getSvtNutritionCourseData();
            }
        }

        // =========================================================================
        // 4. PHILOSOPHIE (PHILO)
        // =========================================================================
        if ($subject === 'PHILO') {
            if (str_contains($titleOnly, 'conscience') || str_contains($titleOnly, 'inconscient') || str_contains($titleOnly, 'homme et le monde')) {
                return $this->getPhiloConscienceCourseData();
            }
            if (str_contains($titleOnly, 'liberté') || str_contains($titleOnly, 'pratique') || str_contains($titleOnly, 'connaissance') || str_contains($titleOnly, 'méthodologi')) {
                return $this->getPhiloLiberteCourseData();
            }
        }

        // =========================================================================
        // 5. FRANÇAIS (FRANCAIS)
        // =========================================================================
        if ($subject === 'FRANCAIS') {
            if (str_contains($titleOnly, 'contraction') || str_contains($titleOnly, 'résumé') || str_contains($titleOnly, 'discussion') || str_contains($titleOnly, 'paix')) {
                return $this->getFrancaisContractionCourseData();
            }
            if (str_contains($titleOnly, 'commentaire') || str_contains($titleOnly, 'dissertation') || str_contains($titleOnly, 'mutation') || str_contains($titleOnly, 'écriture')) {
                return $this->getFrancaisCommentaireCourseData();
            }
        }

        // =========================================================================
        // 6. HISTOIRE-GÉOGRAPHIE (HIST_GEO)
        // =========================================================================
        if ($subject === 'HIST_GEO' && (str_contains($titleOnly, 'émergence') || str_contains($titleOnly, 'urbaine') || str_contains($titleOnly, 'échanges'))) {
            return $this->getHistGeoTogoCourseData();
        }

        // Fallback dynamique enrichi construisant un vrai cours structuré à partir des sous-chapitres officiels
        return $this->getGenericOfficialCourseData($title, $summary, $subject);
    }

    /**
     * Formate la pédagogie et le cours en texte de contexte exploitable par le LLM (OpenRouter Qwen / Mistral).
     */
    public function formatPedagogyAsContext(array $pedagogy, ?object $course = null, ?string $activeTab = 'cours', ?string $currentExercise = null): string
    {
        $title = $pedagogy['title'] ?? ($course->title ?? 'Chapitre officiel');
        $subject = $pedagogy['subject'] ?? ($course->subject_name ?? 'Discipline');
        $ref = $pedagogy['ref'] ?? 'Programme Officiel National MEPST Togo';

        $out = "DISCIPLINE : {$subject}\n";
        $out .= "CHAPITRE : {$title}\n";
        $out .= "RÉFÉRENCE OFFICIELLE : {$ref}\n";
        $out .= "SECTION ACTUELLEMENT OUVERTE PAR L'ÉLÈVE : " . strtoupper($activeTab ?? 'COURS MAGISTRAL') . "\n\n";

        if (!empty($pedagogy['objectives'])) {
            $out .= "OBJECTIFS DU PROGRAMME :\n";
            foreach ($pedagogy['objectives'] as $obj) {
                $out .= "- {$obj}\n";
            }
            $out .= "\n";
        }

        if (!empty($pedagogy['definitions'])) {
            $out .= "DÉFINITIONS CLÉS AFFICHÉES À L'ÉCRAN :\n";
            foreach ($pedagogy['definitions'] as $def) {
                $term = $def['term'] ?? '';
                $text = $def['definition'] ?? '';
                $out .= "• {$term} : {$text}\n";
            }
            $out .= "\n";
        }

        if (!empty($pedagogy['theorems'])) {
            $out .= "THÉORÈMES ET PROPRIÉTÉS FORMELLES :\n";
            foreach ($pedagogy['theorems'] as $th) {
                $tTitle = $th['title'] ?? '';
                $latex = $th['latex'] ?? '';
                $cond = $th['conditions'] ?? '';
                $out .= "• [{$tTitle}] Formule : {$latex} | Conditions requises : {$cond}\n";
            }
            $out .= "\n";
        }

        if (!empty($pedagogy['demonstration_example'])) {
            $ex = $pedagogy['demonstration_example'];
            $out .= "EXEMPLE DÉMONSTRATIF RÉSOLU :\n";
            $out .= "Énoncé : " . ($ex['statement'] ?? '') . "\n";
            if (!empty($ex['steps'])) {
                foreach ($ex['steps'] as $s) {
                    $out .= "  * {$s}\n";
                }
            }
            $out .= "Conclusion : " . ($ex['conclusion'] ?? '') . "\n\n";
        }

        if (!empty($pedagogy['trap'])) {
            $out .= "PIÈGE CLASSIQUE AU BAC : {$pedagogy['trap']}\n";
        }

        if (!empty($pedagogy['redaction_tip'])) {
            $out .= "CONSEIL OFFICIEL DE RÉDACTION : {$pedagogy['redaction_tip']}\n\n";
        }

        if (!empty($currentExercise)) {
            $out .= "EXERCICE SUR LEQUEL L'ÉLÈVE EST EN TRAIN DE RÉFLÉCHIR :\n{$currentExercise}\n\n";
        } elseif ($activeTab === 'exercices' || $activeTab === 'tier1' || $activeTab === 'tier2' || $activeTab === 'tier3') {
            if (!empty($pedagogy['tier1']['questions'])) {
                $out .= "QUESTIONS DU PALIER 1 (QUIZ FLASH) :\n";
                foreach ($pedagogy['tier1']['questions'] as $idx => $q) {
                    $out .= "Q" . ($idx + 1) . ": " . ($q['prompt'] ?? '') . "\n";
                }
                $out .= "\n";
            }
            if (!empty($pedagogy['tier2']['statement'])) {
                $out .= "ÉNONCÉ DU PALIER 2 (APPLICATION MÉTHODOLOGIQUE) :\n" . strip_tags($pedagogy['tier2']['statement']) . "\n\n";
            }
            if (!empty($pedagogy['tier3']['statement'])) {
                $out .= "ÉNONCÉ DU PALIER 3 (PROBLÈME BAC) :\n" . strip_tags($pedagogy['tier3']['statement']) . "\n\n";
            }
        }

        return $out;
    }

    /* =========================================================================
       DONNÉES DIDACTIQUES COMPLÈTES PAR CHAPITRE MAJEUR (LYCÉE TOGOLAIS)
       ========================================================================= */

    private function getLimitsCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_D_CH03',
            'title' => 'Chapitre 3 : Limites, continuité et théorèmes de bijection',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D · Manuel National',
            'coeff_badge' => 'Coeff. 4 · Incontournable BAC 2',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Déterminer les limites de fonctions rationnelles et irrationnelles par factorisation ou quantité conjuguée.",
                "Lever les 4 formes indéterminées officielles : [0/0], [∞/∞], [+∞ - ∞], [0 × ∞].",
                "Appliquer le Théorème des Valeurs Intermédiaires (TVI) et son corollaire pour justifier l'existence et l'unicité d'une solution.",
                "Étudier les asymptotes horizontales, verticales et obliques d'une courbe représentative.",
            ],
            'definitions' => [
                [
                    'term' => 'Continuité en un point',
                    'definition' => 'Une fonction $f$ est dite continue en $x_0$ appartenant à son ensemble de définition si et seulement si $\lim_{x \to x_0} f(x) = f(x_0)$.',
                ],
                [
                    'term' => 'Forme Indéterminée (FI)',
                    'definition' => 'Situation de calcul de limite où les règles usuelles d\'addition, de produit ou de quotient ne permettent pas de conclure directement. Il en existe 4 fondamentales : $\left[\frac{0}{0}\right]$, $\left[\frac{\infty}{\infty}\right]$, $[+\infty - \infty]$, et $[0 \times \infty]$.',
                ],
                [
                    'term' => 'Asymptote oblique',
                    'definition' => 'La droite d\'équation $y = ax + b$ est asymptote oblique à la courbe $\mathcal{C}_f$ en $+\infty$ (resp. $-\infty$) si et seulement si $\lim_{x \to +\infty} [f(x) - (ax + b)] = 0$.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Théorème des Valeurs Intermédiaires (TVI)',
                    'latex' => 'f \text{ continue sur } [a, b] \implies \forall k \in [f(a), f(b)], \exists c \in [a, b] \text{ tel que } f(c) = k',
                    'conditions' => '1. $f$ doit être continue sur le segment $[a, b]$. 2. $k$ doit être compris entre $f(a)$ et $f(b)$.',
                    'comment' => 'Garantit l\'existence d\'au moins une solution $c$.',
                ],
                [
                    'title' => 'Corollaire du TVI (Théorème de Bijection)',
                    'latex' => 'f \text{ continue et STRICTEMENT MONOTONE sur } [a, b] \implies \exists ! \, \alpha \in [a, b], \; f(\alpha) = k',
                    'conditions' => 'Trois conditions strictes : continuité, stricte monotonie (croissante ou décroissante), et $k \in f([a, b])$.',
                    'comment' => 'Garantit l\'EXISTENCE ET L\'UNICITÉ d\'une unique solution notée $\alpha$. Exigence absolue des jurys du BAC togolais.',
                ],
                [
                    'title' => 'Règle des plus hauts degrés en l\'infini',
                    'latex' => '\lim_{x \to \pm\infty} \frac{a_n x^n + \dots + a_0}{b_m x^m + \dots + b_0} = \lim_{x \to \pm\infty} \frac{a_n x^n}{b_m x^m}',
                    'conditions' => 'Valable UNIQUEMENT en $\pm\infty$ et UNIQUEMENT pour les fonctions rationnelles (quotient de deux polynômes). Ne JAMAIS appliquer en un réel $x_0$.',
                    'comment' => 'Permet d\'éliminer immédiatement l\'indétermination $[\infty/\infty]$.',
                ],
                [
                    'title' => 'Croissances comparées usuelles',
                    'latex' => '\lim_{x \to +\infty} \frac{e^x}{x^n} = +\infty \quad ; \quad \lim_{x \to +\infty} \frac{\ln(x)}{x^n} = 0 \quad ; \quad \lim_{x \to 0^+} x^n \ln(x) = 0 \quad (n > 0)',
                    'conditions' => 'Formules de référence du programme national togolais à citer textuellement sur la copie.',
                    'comment' => 'L\'exponentielle l\'emporte sur toute puissance de $x$, qui elle-même l\'emporte sur le logarithme népérien.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Soit la fonction $f$ définie sur $\mathbb{R}$ par $f(x) = 2x^3 - 3x^2 - 1$. Démontrer que l\'équation $f(x) = 0$ admet une unique solution $\alpha$ dans l\'intervalle $[1, 2]$.',
                'steps' => [
                    "1. Dérivabilité et continuité : \$f\$ est une fonction polynôme, elle est donc dérivable et continue sur \$\mathbb{R}\$, et en particulier sur \$[1, 2]\$.",
                    "2. Calcul de la dérivée : \$f'(x) = 6x^2 - 6x = 6x(x - 1)\$.",
                    "3. Étude du signe sur \$[1, 2]\$ : Pour tout \$x \in ]1, 2]\$, \$x > 0\$ et \$x - 1 > 0\$, donc \$f'(x) > 0\$. \$f\$ est ainsi strictement croissante sur \$[1, 2]\$.",
                    "4. Calcul des images aux bornes : \$f(1) = 2(1)^3 - 3(1)^2 - 1 = -2\$ et \$f(2) = 2(8) - 3(4) - 1 = 16 - 12 - 1 = 3\$.",
                    "5. Position de \$0\$ : \$0 \in [-2, 3]\$ car \$f(1) < 0 < f(2)\$ (soit \$f(1) \times f(2) < 0\$).",
                ],
                'conclusion' => "D'après le corollaire du théorème des valeurs intermédiaires, l'équation \$f(x) = 0\$ admet une unique solution \$\alpha \in [1, 2]\$.",
            ],
            'rules' => [
                [
                    'tag' => 'Technique 1',
                    'title' => 'Méthode de la quantité conjuguée',
                    'content' => 'Face à une différence de racines carrées $\sqrt{A(x)} - \sqrt{B(x)}$ conduisant à $[+\infty - \infty]$, multiplier et diviser par l\'expression conjuguée $\sqrt{A(x)} + \sqrt{B(x)}$.',
                ],
                [
                    'tag' => 'Technique 2',
                    'title' => 'Recherche d\'asymptote oblique $y = ax + b$',
                    'content' => '1. Calculer $a = \lim_{x \to \pm\infty} \frac{f(x)}{x}$. 2. Si $a \in \mathbb{R}^*$, calculer $b = \lim_{x \to \pm\infty} [f(x) - ax]$. 3. Si $b \in \mathbb{R}$, alors $y = ax + b$ est asymptote.',
                ],
            ],
            'trap' => 'Piège classique au BAC : énoncer le corollaire du TVI sans mentionner la stricte monotonie. Les correcteurs retirent systématiquement la moitié des points si l\'une des 3 conditions (continuité, stricte monotonie, appartenance de k à f([a, b])) est omise.',
            'redaction_tip' => 'Commencez toujours par : « f est dérivable sur I, donc elle est continue sur I ». Explicitez f([a, b]) avant de conclure à l\'existence et l\'unicité de la solution α.',
            'formula_sheet' => [
                ['name' => 'Limite rationnelle en infini', 'formula' => '\lim_{x \to \infty} \frac{P(x)}{Q(x)} = \lim_{x \to \infty} \frac{a x^p}{b x^q}'],
                ['name' => 'Asymptote verticale', 'formula' => '\lim_{x \to x_0} f(x) = \pm\infty \implies x = x_0'],
                ['name' => 'Asymptote horizontale', 'formula' => '\lim_{x \to \pm\infty} f(x) = L \implies y = L'],
                ['name' => 'Dérivée de ln(u)', 'formula' => '(\ln(u))\' = \frac{u\'}{u}'],
                ['name' => 'Dérivée de exp(u)', 'formula' => '(e^u)\' = u\' \cdot e^u'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Réflexes & Définitions',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Quelle est la limite en +∞ de la fonction f(x) = (3x² - 5x + 4) / (x² + 2) ?',
                        'options' => ['A. 3', 'B. +∞', 'C. 0', 'D. -5'],
                        'correct' => 0,
                        'expl' => 'Pour une fonction rationnelle en l\'infini, la limite est égale au rapport des termes de plus haut degré : 3x² / x² = 3.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Combien de conditions indispensables doivent être vérifiées pour appliquer le théorème de bijection (TVI) ?',
                        'options' => ['A. 1 seule', 'B. 3 conditions (continuité, stricte monotonie, k ∈ f([a, b]))', 'C. 2 conditions', 'D. Aucune condition'],
                        'correct' => 1,
                        'expl' => 'La continuité, la stricte monotonie et le fait que k appartienne à l\'intervalle image garantissent l\'existence et l\'unicité.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Que vaut la limite en 0 par valeurs supérieures de x · ln(x) ?',
                        'options' => ['A. -∞', 'B. 1', 'C. 0', 'D. N\'existe pas'],
                        'correct' => 2,
                        'expl' => 'Croissance comparée officielle du programme togolais de Terminale : lim_{x→0^+} x·ln(x) = 0.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Application Méthodologique & Calcul',
                'xp' => 50,
                'statement' => 'Soit la fonction g définie sur ]0, +∞[ par g(x) = x² - 2ln(x).<br>1. Calculez la dérivée g\'(x) et factorisez-la.<br>2. Étudiez le signe de g\'(x) sur ]0, +∞[ et donnez la valeur exacte du minimum de g.',
                'hint' => 'Dérive x² (qui donne 2x) puis -2ln(x) (qui donne -2/x). Réduis ensuite au même dénominateur.',
                'solution' => '<b>Résolution officielle détaillée :</b><br>1. Pour tout x > 0, g\'(x) = 2x - 2/x = (2x² - 2)/x = 2(x² - 1)/x = 2(x - 1)(x + 1)/x.<br>2. Comme x > 0 et x + 1 > 0, g\'(x) est du signe de (x - 1) :<br>• Sur ]0, 1[, g\'(x) < 0 donc g est strictement décroissante.<br>• Sur ]1, +∞[, g\'(x) > 0 donc g est strictement croissante.<br>3. g admet ainsi un minimum absolu en x = 1 valant g(1) = 1² - 2ln(1) = 1 - 0 = <b>1</b>.',
                'bareme' => '1 pt pour la factorisation de g\'(x) · 1 pt pour l\'étude du signe · 1 pt pour la valeur exacte du minimum g(1) = 1.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Problème de Synthèse National (Type BAC 2 Togo)',
                'xp' => 100,
                'statement' => 'Extrait conforme BAC 2 Togo (Série D) :<br>On considère la fonction f définie sur ℝ par f(x) = (2x + 1)e^(-x). On note (C) sa courbe représentative dans un repère orthonormé.<br>1. Déterminer les limites de f en -∞ et en +∞. En déduire l\'équation de l\'asymptote à (C).<br>2. Calculer f\'(x) et montrer que f\'(x) = (-2x + 1)e^(-x).<br>3. Dresser le tableau de variations complet de f sur ℝ.<br>4. Démontrer que l\'équation f(x) = 1 admet une unique solution α sur [0, 1/2].',
                'bareme' => 'Limites et asymptote : 1.5 pt · Calcul dérivée : 1.5 pt · Variations et extremum : 1.5 pt · Justification TVI : 1.5 pt (Total 6 points au BAC).',
                'solution' => '<b>Corrigé type officiel et grille de correction MEPST :</b><br>1. <b>Limites :</b><br>• En -∞ : lim (2x + 1) = -∞ et lim e^(-x) = +∞. Par produit, <b>lim_{x→-∞} f(x) = -∞</b>.<br>• En +∞ : f(x) = 2x/e^x + 1/e^x. Or lim_{x→+∞} x/e^x = 0 et lim_{x→+∞} e^(-x) = 0. Donc <b>lim_{x→+∞} f(x) = 0</b>.<br>• La droite d\'équation <b>y = 0</b> est donc asymptote horizontale à (C) en +∞.<br><br>2. <b>Dérivée :</b><br>f est de la forme u·v avec u(x) = 2x + 1 ⇒ u\'(x) = 2 et v(x) = e^(-x) ⇒ v\'(x) = -e^(-x).<br>f\'(x) = 2e^(-x) + (2x + 1)(-e^(-x)) = [2 - 2x - 1]e^(-x) = <b>(-2x + 1)e^(-x)</b>.<br><br>3. <b>Variations :</b><br>Comme e^(-x) > 0 pour tout x, f\'(x) a le signe de (-2x + 1).<br>-2x + 1 = 0 ⇔ x = 1/2. f est strictement croissante sur ]-∞, 1/2] et strictement décroissante sur [1/2, +∞[.<br>Son maximum absolu est f(1/2) = (1 + 1)e^(-1/2) = 2/√e ≈ 1.21.<br><br>4. <b>TVI :</b><br>Sur [0, 1/2], f est continue et strictement croissante. f(0) = (0 + 1)e^0 = 1. Comme f(0) = 1, la solution unique est α = 0.',
            ],
            'suggestions' => [
                'Comment rédiger le TVI sans perdre de points au BAC ?',
                'Explique-moi la croissance comparée avec une analogie simple',
                'Donne-moi une méthode pour calculer rapidement les limites avec exp(x)',
            ],
        ];
    }

    private function getComplexesCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_D_CH01',
            'title' => 'Chapitre 1 : Les Nombres Complexes et Géométrie Plane',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D · Manuel National',
            'coeff_badge' => 'Coeff. 4 · Exercice 1 Incontournable BAC',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Maîtriser les formes algébrique, trigonométrique et exponentielle d'un nombre complexe.",
                "Calculer le module \$|z|\$ et l'argument principal \$\\arg(z) \\in ]-\\pi, \\pi]\$.",
                "Résoudre des équations du second degré à coefficients réels ou complexes dans \$\\mathbb{C}\$.",
                "Caractériser géométriquement les transformations : translations, homothéties et rotations.",
            ],
            'definitions' => [
                [
                    'term' => 'Forme algébrique',
                    'definition' => 'Tout nombre complexe $z$ s\'écrit de manière unique sous la forme $z = a + ib$, où $a = \\text{Re}(z) \\in \\mathbb{R}$ est la partie réelle et $b = \\text{Im}(z) \\in \\mathbb{R}$ est la partie imaginaire, avec $i^2 = -1$.',
                ],
                [
                    'term' => 'Module d\'un complexe',
                    'definition' => 'Le module de $z = a + ib$ est le réel positif noté $|z| = \\sqrt{a^2 + b^2}$. Géométriquement, si $M$ a pour affixe $z$, alors $|z| = OM$.',
                ],
                [
                    'term' => 'Forme trigonométrique et exponentielle',
                    'definition' => 'Pour $z \\neq 0$, $z = r(\\cos \\theta + i\\sin \\theta) = r e^{i\\theta}$, où $r = |z| > 0$ et $\\theta \\equiv \\arg(z) \\pmod{2\\pi}$.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Formule d\'Euler et de Moivre',
                    'latex' => '(\\cos \\theta + i\\sin \\theta)^n = \\cos(n\\theta) + i\\sin(n\\theta) = e^{in\\theta} \\quad ; \\quad \\cos \\theta = \\frac{e^{i\\theta} + e^{-i\\theta}}{2} \\; , \\; \\sin \\theta = \\frac{e^{i\\theta} - e^{-i\\theta}}{2i}',
                    'conditions' => 'Valable pour tout réel $\\theta$ et tout entier relatif $n \\in \\mathbb{Z}$.',
                    'comment' => 'Fondamental pour la linéarisation de polynômes trigonométriques et le calcul de puissances élevées au BAC.',
                ],
                [
                    'title' => 'Caractérisation géométrique des angles et distances',
                    'latex' => 'AB = |z_B - z_A| \\quad ; \\quad (\\vec{AB}, \\vec{CD}) = \\arg\\left(\\frac{z_D - z_C}{z_B - z_A}\\right) \\pmod{2\\pi}',
                    'conditions' => '$A \\neq B$ et $C \\neq D$.',
                    'comment' => 'Permet de démontrer qu\'un triangle est rectangle, isocèle, équilatéral, ou que des points sont alignés/cocycliques.',
                ],
                [
                    'title' => 'Écriture complexe des transformations du plan',
                    'latex' => '\\text{Translation : } z\' = z + b \\quad ; \\quad \\text{Homothétie : } z\' - \\omega = k(z - \\omega) \\quad ; \\quad \\text{Rotation : } z\' - \\omega = e^{i\\theta}(z - \\omega)',
                    'conditions' => '$\\omega$ est l\'affixe du centre, $\\theta$ l\'angle de rotation, $k \\in \\mathbb{R}^*$ le rapport.',
                    'comment' => 'Question systématique au BAC 2 Togo Série D et C.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Soit les points $A, B, C$ d\'affixes respectives $z_A = 1 + i$, $z_B = 3 + 3i$, $z_C = 1 - i\\sqrt{3}$. Déterminer la nature du triangle $OAB$.',
                'steps' => [
                    "1. Calcul du rapport des affixes : \$\\frac{z_B - z_O}{z_A - z_O} = \\frac{3 + 3i}{1 + i} = \\frac{3(1 + i)}{1 + i} = 3\$.",
                    "2. Interprétation du module : \$\\left|\\frac{z_B}{z_A}\\right| = \\frac{OB}{OA} = 3 \\implies OB = 3OA\$.",
                    "3. Interprétation de l'argument : \$\\arg\\left(\\frac{z_B}{z_A}\\right) = \\arg(3) = 0 \\pmod{2\\pi}\$.",
                    "4. Conclusion géométrique : L'angle \$(\\vec{OA}, \\vec{OB}) = 0\$, donc les points \$O, A, B\$ sont alignés.",
                ],
                'conclusion' => "Les points \$O, A, B\$ sont alignés et \$B\$ est l'image de \$A\$ par l'homothétie de centre \$O\$ et de rapport \$3\$.",
            ],
            'rules' => [
                [
                    'tag' => 'Méthode 1',
                    'title' => 'Passage algébrique vers trigonométrique',
                    'content' => '1. Calculer $r = \\sqrt{a^2 + b^2}$. 2. Factoriser : $z = r\\left(\\frac{a}{r} + i\\frac{b}{r}\\right)$. 3. Trouver $\\theta$ tel que $\\cos\\theta = a/r$ et $\\sin\\theta = b/r$.',
                ],
            ],
            'trap' => 'Ne jamais confondre $|z|^2 = a^2 + b^2$ avec $(a + ib)^2 = a^2 - b^2 + 2iab$. Oublier le $i^2 = -1$ est l\'erreur la plus pénalisée.',
            'redaction_tip' => 'Pour justifier qu\'un triangle $ABC$ est rectangle isocèle en $A$, calculez le rapport $(z_C - z_A)/(z_B - z_A)$. S\'il vaut $\\pm i$, vous concluez directement : module = 1 donc $AC = AB$, et argument = $\\pm \\pi/2$ donc angle droit.',
            'formula_sheet' => [
                ['name' => 'Module d\'un produit', 'formula' => '|z \\cdot z\'| = |z| \\cdot |z\'|'],
                ['name' => 'Argument d\'un produit', 'formula' => '\\arg(z \\cdot z\') = \\arg(z) + \\arg(z\')'],
                ['name' => 'Argument d\'un quotient', 'formula' => '\\arg(z/z\') = \\arg(z) - \\arg(z\')'],
                ['name' => 'Conjugué', 'formula' => 'z \\cdot \\bar{z} = |z|^2 = a^2 + b^2'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Réflexes Complexes',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Quel est le module du nombre complexe z = 3 - 4i ?',
                        'options' => ['A. 5', 'B. 7', 'C. 1', 'D. 25'],
                        'correct' => 0,
                        'expl' => '|z| = √(3² + (-4)²) = √(9 + 16) = √25 = 5.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Quelle est la forme exponentielle de z = 1 + i ?',
                        'options' => ['A. √2 · e^(iπ/4)', 'B. 2 · e^(iπ/4)', 'C. e^(iπ/2)', 'D. √2 · e^(-iπ/4)'],
                        'correct' => 0,
                        'expl' => '|z| = √2 et cos θ = 1/√2 = √2/2, sin θ = √2/2 donc θ = π/4.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Si (zC - zA) / (zB - zA) = i, quelle est la nature du triangle ABC ?',
                        'options' => ['A. Rectangle isocèle en A', 'B. Équilatéral', 'C. Rectangle en B', 'D. Quelconque'],
                        'correct' => 0,
                        'expl' => 'Le module vaut 1 donc AB = AC, l\'argument vaut π/2 donc angle droit en A.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Résolution d\'équation dans C',
                'xp' => 50,
                'statement' => 'Résoudre dans ℂ l\'équation : z² - 2√3 z + 4 = 0.<br>Écrire les solutions sous forme exponentielle.',
                'hint' => 'Calcule le discriminant réduit Δ\' = (b\')² - ac avec b\' = -√3.',
                'solution' => 'Δ\' = (-√3)² - 1(4) = 3 - 4 = -1 = i².<br>Les solutions sont z1 = √3 - i et z2 = √3 + i.<br>Forme exponentielle : |z1| = |z2| = √(3 + 1) = 2.<br>z1 = 2e^(-iπ/6) et z2 = 2e^(iπ/6).',
                'bareme' => 'Calcul discriminant : 1 pt · Solutions algébriques : 1 pt · Formes exponentielles : 1 pt.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Problème Géométrique Type BAC 2',
                'xp' => 100,
                'statement' => 'Soit les points A(2), B(-1 + i√3), C(-1 - i√3).<br>1. Déterminer la nature exacte du triangle ABC.<br>2. Soit R la rotation de centre O et d\'angle 2π/3. Déterminer l\'image de A par R.',
                'bareme' => 'Nature du triangle ABC : 2.5 pts · Expression de la rotation : 1.5 pts · Image de A : 2 pts (Total 6 pts).',
                'solution' => '1. |zA - zB| = |3 - i√3| = √(9 + 3) = √12 = 2√3.<br>|zB - zC| = |2i√3| = 2√3.<br>|zC - zA| = |-3 - i√3| = √(9 + 3) = 2√3.<br>AB = BC = CA = 2√3, donc ABC est un triangle équilatéral.<br>2. Écriture complexe de R : z\' = e^(i 2π/3) · z.<br>z\'A = e^(i 2π/3) × 2 = 2(-1/2 + i√3/2) = -1 + i√3 = zB.<br>L\'image de A par la rotation R est le point B.',
            ],
            'suggestions' => [
                'Comment factoriser rapidement un polynôme du 3e degré dans C ?',
                'Donne-moi les astuces pour reconnaître un triangle avec les affixes',
                'Quelle est la différence entre rotation et similitude plane directe ?',
            ],
        ];
    }

    private function getProbabilitiesCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_D_CH04',
            'title' => 'Chapitre 4 : Dénombrement et Probabilités Conditionnelles',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D · Manuel National',
            'coeff_badge' => 'Coeff. 4 · Exercice 2 Incontournable BAC',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Distinguer arrangements, permutations et combinaisons selon l'ordre et la répétition.",
                "Calculer des probabilités conditionnelles avec la formule de Bayes : \$P_B(A) = \\frac{P(A \\cap B)}{P(B)}\$.",
                "Construire et exploiter un arbre de probabilité pondéré (formule des probabilités totales).",
                "Déterminer la loi de probabilité d'une variable aléatoire discrète, son espérance \$E(X)\$, sa variance \$V(X)\$ et la loi binomiale \$\\mathcal{B}(n, p)\$.",
            ],
            'definitions' => [
                [
                    'term' => 'Combinaison $\\binom{n}{k}$',
                    'definition' => 'Nombre de sous-ensembles de $k$ éléments choisis parmi $n$ éléments sans ordre et sans répétition : $\\binom{n}{k} = \\frac{n!}{k!(n - k)!}$. Utilisé pour les tirages simultanés.',
                ],
                [
                    'term' => 'Probabilité conditionnelle $P_B(A)$',
                    'definition' => 'Probabilité que l\'événement $A$ se réalise sachant que $B$ est déjà réalisé ($P(B) > 0$) : $P_B(A) = \\frac{P(A \\cap B)}{P(B)}$.',
                ],
                [
                    'term' => 'Indépendance d\'événements',
                    'definition' => 'Deux événements $A$ et $B$ sont indépendants si et seulement si $P(A \\cap B) = P(A) \\times P(B)$.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Formule des Probabilités Totales',
                    'latex' => 'P(A) = \\sum_{i=1}^n P(A \\cap B_i) = \\sum_{i=1}^n P(B_i) \\times P_{B_i}(A)',
                    'conditions' => '$(B_1, B_2, \\dots, B_n)$ forme un système complet d\'événements (partition de l\'univers $\\Omega$).',
                    'comment' => 'Correspond à la somme des branches menant à l\'événement $A$ dans un arbre pondéré.',
                ],
                [
                    'title' => 'Loi Binomiale $\\mathcal{B}(n, p)$',
                    'latex' => 'P(X = k) = \\binom{n}{k} p^k (1 - p)^{n - k} \\quad ; \\quad E(X) = np \\quad ; \\quad V(X) = np(1 - p)',
                    'conditions' => 'Répétition de $n$ épreuves de Bernoulli identiques et indépendantes à deux issues (succès de probabilité $p$, échec de probabilité $1 - p$).',
                    'comment' => 'Formule reine des sujets du BAC Togo.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Dans un centre d\'examen du BAC, 60% des candidats sont en Série D et 40% en Série A4. Le taux de réussite est de 70% en D et de 50% en A4. On choisit un candidat au hasard. 1. Quelle est la probabilité qu\'il réussisse son BAC ? 2. Sachant qu\'il a réussi, quelle est la probabilité qu\'il soit de Série D ?',
                'steps' => [
                    "1. Définition des événements : \$D\$ : 'être en Série D', \$A\$ : 'être en Série A4', \$R\$ : 'réussir le BAC'.",
                    "2. Données : \$P(D) = 0,60\$ ; \$P(A) = 0,40\$ ; \$P_D(R) = 0,70\$ ; \$P_A(R) = 0,50\$.",
                    "3. Probabilités totales : \$P(R) = P(D \\cap R) + P(A \\cap R) = P(D) \\times P_D(R) + P(A) \\times P_A(R) = (0,60 \\times 0,70) + (0,40 \\times 0,50) = 0,42 + 0,20 = 0,62\$.",
                    "4. Formule de Bayes : \$P_R(D) = \\frac{P(D \\cap R)}{P(R)} = \\frac{0,42}{0,62} = \\frac{42}{62} = \\frac{21}{31} \\approx 0,677\$ (soit 67,7%).",
                ],
                'conclusion' => "Le taux global de réussite est de 62%, et un élève admis a 67,7% de chances de provenir de la Série D.",
            ],
            'rules' => [
                [
                    'tag' => 'Règle d\'or',
                    'title' => 'Choix de l\'outil de dénombrement',
                    'content' => '• Tirage simultané (d\'un coup) : Combinaisons C(n, k). • Tirage successif sans remise : Arrangements A(n, k). • Tirage successif avec remise : Puissances n^k.',
                ],
            ],
            'trap' => 'Confondre P(A ∩ B) et P_B(A). Dans un arbre pondéré, la probabilité d\'une branche secondaire est une probabilité conditionnelle P_B(A), et non l\'intersection.',
            'redaction_tip' => 'Explicitez toujours le système complet d\'événements avant d\'écrire la formule des probabilités totales : « (D, A) forme une partition de l\'univers Omega, donc d\'après la formule des probabilités totales... »',
            'formula_sheet' => [
                ['name' => 'Combinaison', 'formula' => '\\binom{n}{k} = \\frac{n!}{k!(n-k)!}'],
                ['name' => 'Arrangement', 'formula' => 'A_n^k = \\frac{n!}{(n-k)!}'],
                ['name' => 'Événement contraire', 'formula' => 'P(\\bar{A}) = 1 - P(A)'],
                ['name' => 'Espérance E(X)', 'formula' => 'E(X) = \\sum x_i P(X = x_i)'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Probabilités',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Si P(A) = 0,4, P(B) = 0,5 et A et B sont indépendants, que vaut P(A ∩ B) ?',
                        'options' => ['A. 0,20', 'B. 0,90', 'C. 0,10', 'D. 0,45'],
                        'correct' => 0,
                        'expl' => 'Par indépendance, P(A ∩ B) = P(A) × P(B) = 0,4 × 0,5 = 0,20.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Quel outil de dénombrement utilise-t-on pour un tirage simultané de 3 boules parmi 10 ?',
                        'options' => ['A. Combinaison C(10, 3)', 'B. Arrangement A(10, 3)', 'C. 10³', 'D. 10!'],
                        'correct' => 0,
                        'expl' => 'Tirage simultané = ordre non pris en compte = Combinaisons.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Pour une variable aléatoire X suivant B(20, 0,2), quelle est son espérance E(X) ?',
                        'options' => ['A. 4', 'B. 0,2', 'C. 2', 'D. 16'],
                        'correct' => 0,
                        'expl' => 'E(X) = n × p = 20 × 0,2 = 4.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Calcul avec arbre pondéré',
                'xp' => 50,
                'statement' => 'Une urne contient 4 boules rouges et 6 boules noires. On tire successivement et sans remise 2 boules.<br>1. Calculer la probabilité d\'obtenir 2 boules rouges.<br>2. Calculer la probabilité d\'obtenir 2 boules de couleurs différentes.',
                'hint' => 'P(R1 ∩ R2) = P(R1) × P_R1(R2) = (4/10) × (3/9).',
                'solution' => '1. P(2 rouges) = (4/10) × (3/9) = 12/90 = 2/15.<br>2. P(couleurs différentes) = P(R1 ∩ N2) + P(N1 ∩ R2) = (4/10 × 6/9) + (6/10 × 4/9) = 24/90 + 24/90 = 48/90 = 8/15.',
                'bareme' => 'P(2 rouges) : 2 pts · P(différentes) : 3 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Problème de Synthèse BAC 2',
                'xp' => 100,
                'statement' => 'Un jeu consiste à lancer un dé cubique équilibré. Si le joueur obtient 6, il gagne 500 FCFA. S\'il obtient 1 ou 2, il gagne 200 FCFA. Sinon, il perd 300 FCFA.<br>Soit X la variable aléatoire associée au gain algébrique du joueur.<br>1. Déterminer la loi de probabilité de X.<br>2. Calculer l\'espérance E(X). Le jeu est-il favorable au joueur ?',
                'bareme' => 'Valeurs de X et loi : 3 pts · Calcul espérance et interprétation : 3 pts.',
                'solution' => '1. Valeurs possibles de X : {-300, 200, 500}.<br>P(X = 500) = 1/6.<br>P(X = 200) = 2/6 = 1/3.<br>P(X = -300) = 3/6 = 1/2.<br>2. Espérance : E(X) = (-300 × 3/6) + (200 × 2/6) + (500 × 1/6) = (-900 + 400 + 500)/6 = 0 FCFA.<br>Le jeu est équitable (ni favorable ni défavorable).',
            ],
            'suggestions' => [
                'Comment justifier la loi binomiale au BAC sans faute ?',
                'Explique-moi la différence entre P_B(A) et P(A ∩ B)',
                'Quelle est l\'astuce pour calculer l\'événement au moins un succès ?',
            ],
        ];
    }

    private function getSuitesCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_D_CH05',
            'title' => 'Chapitre 5 : Suites Numériques et Récurrence',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D · Manuel National',
            'coeff_badge' => 'Coeff. 4 · Incontournable BAC 2',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Maîtriser les 3 étapes du raisonnement par récurrence (Initialisation, Hérédité, Conclusion).",
                "Étudier le sens de variation d'une suite (\$u_{n+1} - u_n\$ ou quotient).",
                "Démontrer la convergence d'une suite avec le théorème de convergence monotone.",
                "Résoudre les suites arithmético-géométriques \$u_{n+1} = a u_n + b\$ par suite auxiliaire géométrique.",
            ],
            'definitions' => [
                [
                    'term' => 'Suite majorée, minorée, bornée',
                    'definition' => 'Une suite $(u_n)$ est majorée s\'il existe $M \\in \\mathbb{R}$ tel que $\\forall n, u_n \\leq M$. Elle est bornée si elle est à la fois majorée et minorée.',
                ],
                [
                    'term' => 'Théorème de convergence monotone',
                    'definition' => 'Toute suite croissante et majorée est convergente. Toute suite décroissante et minorée est convergente.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Principe du raisonnement par récurrence',
                    'latex' => '(\\mathcal{P}(n_0) \\text{ vraie et } \\forall n \\geq n_0, \\mathcal{P}(n) \\implies \\mathcal{P}(n+1)) \\implies \\forall n \\geq n_0, \\mathcal{P}(n) \\text{ est vraie}',
                    'conditions' => '1. Initialisation pour $n_0$. 2. Hérédité : supposer $\\mathcal{P}(n)$ vraie pour un entier fixé $n \\geq n_0$ et prouver $\\mathcal{P}(n+1)$. 3. Conclusion.',
                    'comment' => 'Les 3 étapes doivent être explicitement titrées sur la copie d\'examen.',
                ],
                [
                    'title' => 'Limite d\'une suite géométrique $q^n$',
                    'latex' => '\\text{Si } -1 < q < 1, \\lim_{n \\to +\\infty} q^n = 0 \\quad ; \\quad \\text{Si } q > 1, \\lim_{n \\to +\\infty} q^n = +\\infty',
                    'conditions' => '$q \\in \\mathbb{R}$. Si $q \\leq -1$, la suite n\'a pas de limite.',
                    'comment' => 'Fondamental pour déterminer la limite de $u_n = v_n + L$.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Soit la suite $(u_n)$ définie par $u_0 = 1$ et $u_{n+1} = \\frac{1}{2}u_n + 2$. Démontrer par récurrence que pour tout $n \\in \\mathbb{N}$, $u_n < 4$.',
                'steps' => [
                    "1. Initialisation : Pour \$n = 0\$, \$u_0 = 1 < 4\$. La propriété est donc vraie au rang \$0\$.",
                    "2. Hérédité : Soit \$n \\geq 0\$. Supposons que \$u_n < 4\$ (Hypothèse de récurrence). Montrons que \$u_{n+1} < 4\$.",
                    "   Comme \$u_n < 4\$, en multipliant par \$1/2 > 0\$, on a \$\\frac{1}{2}u_n < 2\$. En ajoutant \$2\$ aux deux membres : \$\\frac{1}{2}u_n + 2 < 4\$, soit \$u_{n+1} < 4\$. La propriété est héréditaire.",
                    "3. Conclusion : D'après le principe de récurrence, pour tout \$n \\in \\mathbb{N}\$, \$u_n < 4\$.",
                ],
                'conclusion' => "La suite \$(u_n)\$ est majorée par \$4\$.",
            ],
            'rules' => [
                [
                    'tag' => 'Méthode Arithmético-géométrique',
                    'title' => 'Point fixe et suite auxiliaire',
                    'content' => 'Pour $u_{n+1} = a u_n + b$ ($a \\neq 1$) : 1. Résoudre le point fixe $L = aL + b \\implies L = b/(1 - a)$. 2. Poser $v_n = u_n - L$. 3. Montrer que $(v_n)$ est géométrique de raison $a$. 4. Déduire $u_n = v_0 a^n + L$.',
                ],
            ],
            'trap' => 'Dans l\'hérédité, écrire « Supposons que pour TOUT n, P(n) est vraie » est une faute de logique sanctionnée par les jurys. Il faut écrire : « Supposons que pour UN entier fixé n ≥ n0... »',
            'redaction_tip' => 'Pour prouver qu\'une suite converge vers L vérifiant f(L) = L, n\'oubliez jamais d\'énoncer : « Comme f est continue et (u_n) converge vers L, alors f(L) = L ».',
            'formula_sheet' => [
                ['name' => 'Somme suite géométrique', 'formula' => 'S = u_0 \\frac{1 - q^{n+1}}{1 - q}'],
                ['name' => 'Somme suite arithmétique', 'formula' => 'S = \\frac{n+1}{2} (u_0 + u_n)'],
                ['name' => 'Terme général géométrique', 'formula' => 'u_n = u_0 \\cdot q^n'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Suites',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Quelle est la limite de la suite (0,75)^n quand n tend vers +∞ ?',
                        'options' => ['A. 0', 'B. 1', 'C. +∞', 'D. N\'existe pas'],
                        'correct' => 0,
                        'expl' => 'Comme -1 < 0,75 < 1, lim (0,75)^n = 0.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Une suite croissante et majorée par 5 :',
                        'options' => ['A. Converge vers une limite L ≤ 5', 'B. Diverge vers +∞', 'C. Vaut toujours 5', 'D. Est périodique'],
                        'correct' => 0,
                        'expl' => 'Théorème de convergence monotone : toute suite croissante et majorée converge vers L ≤ Majorant.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Quelle est la première étape d\'un raisonnement par récurrence ?',
                        'options' => ['A. L\'initialisation', 'B. L\'hérédité', 'C. La conclusion', 'D. Le calcul de dérivée'],
                        'correct' => 0,
                        'expl' => 'On commence toujours par vérifier la propriété au premier rang n0 (Initialisation).',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Suite arithmético-géométrique',
                'xp' => 50,
                'statement' => 'Soit u_0 = 2 et u_{n+1} = 3u_n - 4. On pose v_n = u_n - 2.<br>1. Démontrer que (v_n) est une suite géométrique dont on précisera le premier terme et la raison.<br>2. Exprimer v_n puis u_n en fonction de n.',
                'hint' => 'Calcule v_{n+1} = u_{n+1} - 2 = (3u_n - 4) - 2 = 3u_n - 6 = 3(u_n - 2).',
                'solution' => '1. v_{n+1} = 3u_n - 6 = 3(u_n - 2) = 3v_n. Donc (v_n) est géométrique de raison q = 3. Premier terme v_0 = u_0 - 2 = 2 - 2 = 0.<br>2. v_n = 0 × 3^n = 0. D\'où u_n = v_n + 2 = 2 pour tout n (suite constante).',
                'bareme' => 'Démonstration géométrique : 2.5 pts · Expression en fonction de n : 2.5 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Problème Suites & Intégrales BAC 2',
                'xp' => 100,
                'statement' => 'Soit (I_n) définie pour tout n ≥ 1 par I_n = ∫_0^1 x^n e^(-x) dx.<br>1. Démontrer que la suite (I_n) est positive et décroissante. En déduire qu\'elle converge.<br>2. À l\'aide d\'une intégration par parties, établir que I_{n+1} = (n+1)I_n - e^(-1).',
                'bareme' => 'Sens de variation et convergence : 3 pts · Intégration par parties : 3 pts.',
                'solution' => '1. Pour tout x ∈ [0, 1], x^n ≥ 0 et e^(-x) > 0 donc I_n ≥ 0. De plus x^{n+1} ≤ x^n sur [0, 1] donc I_{n+1} ≤ I_n. (I_n) est décroissante et minorée par 0, donc elle converge.<br>2. On pose u(x) = x^{n+1} ⇒ u\'(x) = (n+1)x^n et v\'(x) = e^(-x) ⇒ v(x) = -e^(-x).<br>I_{n+1} = [-x^{n+1}e^(-x)]_0^1 + (n+1)∫_0^1 x^n e^(-x) dx = -e^(-1) + (n+1)I_n.',
            ],
            'suggestions' => [
                'Comment rédiger proprement l\'hérédité d\'une récurrence ?',
                'Explique-moi le théorème de convergence monotone',
                'Comment trouver l\'expression d\'une suite auxiliaire ?',
            ],
        ];
    }

    private function getLogExpCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_D_CH02',
            'title' => 'Chapitre 2 : Fonctions Logarithme Népérien & Exponentielle',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D · Manuel National',
            'coeff_badge' => 'Coeff. 4 · Problème de synthèse BAC 2 (10 points)',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Utiliser les propriétés algébriques fondamentales de \$\\ln(x)\$ et \$\\exp(x)\$.",
                "Calculer les limites usuelles et les croissances comparées.",
                "Déterminer les fonctions dérivées \$(\\ln(u))' = \\frac{u'}{u}\$ et \$(e^u)' = u' e^u\$.",
                "Résoudre des équations et inéquations comportant des exponentielles et des logarithmes.",
            ],
            'definitions' => [
                [
                    'term' => 'Fonction Logarithme Népérien $\\ln$',
                    'definition' => 'Unique primitive sur $]0, +\\infty[$ de la fonction $x \\mapsto 1/x$ qui s\'annule en $1$. Elle est strictement croissante et réalise une bijection de $]0, +\\infty[$ sur $\\mathbb{R}$.',
                ],
                [
                    'term' => 'Fonction Exponentielle $\\exp$',
                    'definition' => 'Bijection réciproque de la fonction logarithme népérien. Pour tout $x \\in \\mathbb{R}$, $e^x > 0$ et $\\ln(e^x) = x$.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Propriétés algébriques fondamentales',
                    'latex' => '\\ln(ab) = \\ln(a) + \\ln(b) \\quad ; \\quad \\ln(a/b) = \\ln(a) - \\ln(b) \\quad ; \\quad \\ln(a^n) = n\\ln(a) \\quad ; \\quad e^{a+b} = e^a \\cdot e^b',
                    'conditions' => '$a > 0, b > 0, n \\in \\mathbb{Q}$.',
                    'comment' => 'Transforme les produits en sommes et réciproquement.',
                ],
                [
                    'title' => 'Limites aux bornes et croissances comparées',
                    'latex' => '\\lim_{x \\to 0^+} \\ln(x) = -\\infty \\; , \\; \\lim_{x \\to +\\infty} \\ln(x) = +\\infty \\; , \\; \\lim_{x \\to -\\infty} e^x = 0 \\; , \\; \\lim_{x \\to +\\infty} e^x = +\\infty',
                    'conditions' => 'Formules indispensables pour dresser les tableaux de variations au BAC.',
                    'comment' => 'Attention : $\\lim_{x \\to 0} \\frac{\\ln(1 + x)}{x} = 1$ et $\\lim_{x \\to 0} \\frac{e^x - 1}{x} = 1$ (taux d\'accroissement en 0).',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Résoudre dans $\\mathbb{R}$ l\'équation : $\\ln(2x - 1) + \\ln(x + 2) = \\ln(7)$.',
                'steps' => [
                    "1. Ensemble de validité \$D_E\$ : Il faut \$2x - 1 > 0 \\iff x > 1/2\$ et \$x + 2 > 0 \\iff x > -2\$. Donc \$D_E = ]1/2, +\\infty[\$.",
                    "2. Transformation algébrique : Pour \$x \\in D_E\$, \$\\ln((2x - 1)(x + 2)) = \\ln(7)\$.",
                    "3. Égalité des arguments : \$(2x - 1)(x + 2) = 7 \\iff 2x^2 + 4x - x - 2 = 7 \\iff 2x^2 + 3x - 9 = 0\$.",
                    "4. Résolution du second degré : \$\\Delta = 3^2 - 4(2)(-9) = 9 + 72 = 81 = 9^2\$.",
                    "   \$x_1 = \\frac{-3 - 9}{4} = -3\$ et \$x_2 = \\frac{-3 + 9}{4} = \\frac{6}{4} = \\frac{3}{2}\$.",
                    "5. Confrontation avec \$D_E\$ : \$-3 \\notin ]1/2, +\\infty[\$ (rejeté) et \$3/2 \\in ]1/2, +\\infty[\$ (accepté).",
                ],
                'conclusion' => "L'unique solution de l'équation est \$S = \\{3/2\\}\$.",
            ],
            'rules' => [
                [
                    'tag' => 'Règle d\'or',
                    'title' => 'Ensemble de validité obligatoire',
                    'content' => 'Toujours chercher l\'ensemble de validité AVANT toute manipulation algébrique sur les logarithmes. Toute solution trouvée hors du domaine doit être rejetée.',
                ],
            ],
            'trap' => 'Ne jamais écrire ln(a + b) = ln(a) + ln(b). C\'est une hérésie mathématique sévèrement sanctionnée au BAC.',
            'redaction_tip' => 'Pour justifier la dérivée de f(x) = (3x + 1)e^(-2x), écrivez explicitement : « f est dérivable sur ℝ comme produit de fonctions dérivables ». Ne commencez jamais le calcul sans cette phrase.',
            'formula_sheet' => [
                ['name' => 'Dérivée ln(u)', 'formula' => '(\\ln(u))\' = \\frac{u\'}{u}'],
                ['name' => 'Dérivée exp(u)', 'formula' => '(e^u)\' = u\' e^u'],
                ['name' => 'Taux accroissement', 'formula' => '\\lim_{x \\to 0} \\frac{e^x - 1}{x} = 1'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Log & Exp',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Quelle est la dérivée de f(x) = ln(x² + 1) ?',
                        'options' => ['A. 2x / (x² + 1)', 'B. 1 / (x² + 1)', 'C. 2x · ln(x² + 1)', 'D. 1 / x'],
                        'correct' => 0,
                        'expl' => '(ln(u))\' = u\'/u avec u(x) = x² + 1 et u\'(x) = 2x.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Que vaut e^(2 · ln(3)) ?',
                        'options' => ['A. 9', 'B. 6', 'C. 3', 'D. e^6'],
                        'correct' => 0,
                        'expl' => '2 · ln(3) = ln(3²) = ln(9), et e^(ln(9)) = 9.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Quel est le domaine de définition de f(x) = ln(3 - x) ?',
                        'options' => ['A. ]-∞, 3[', 'B. ]3, +∞[', 'C. ℝ \\ {3}', 'D. [3, +∞['],
                        'correct' => 0,
                        'expl' => 'Il faut 3 - x > 0 ⇔ x < 3, soit ]-∞, 3[.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Inéquation avec exponentielle',
                'xp' => 50,
                'statement' => 'Résoudre dans ℝ l\'inéquation : e^(2x) - 3e^x + 2 ≤ 0.<br>Indication : Poser X = e^x.',
                'hint' => 'En posant X = e^x avec X > 0, l\'inéquation devient X² - 3X + 2 ≤ 0.',
                'solution' => 'X² - 3X + 2 = (X - 1)(X - 2) ≤ 0 ⇔ 1 ≤ X ≤ 2.<br>Comme X = e^x, on a 1 ≤ e^x ≤ 2.<br>En appliquant la fonction ln strictement croissante : ln(1) ≤ x ≤ ln(2) ⇔ 0 ≤ x ≤ ln(2).<br>S = [0, ln(2)].',
                'bareme' => 'Changement de variable : 1.5 pt · Résolution en X : 1.5 pt · Encadrement final de x : 2 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Étude de fonction complète BAC 2',
                'xp' => 100,
                'statement' => 'Soit f(x) = x - 1 + ln(x)/x sur ]0, +∞[.<br>1. Déterminer les limites en 0 et +∞.<br>2. Démontrer que la droite (D) d\'équation y = x - 1 est asymptote oblique en +∞.<br>3. Préciser la position relative de (C) par rapport à (D).',
                'bareme' => 'Limites : 2 pts · Asymptote oblique : 2 pts · Position relative : 2 pts.',
                'solution' => '1. En 0 : lim (x - 1) = -1 et lim ln(x)/x = -∞/0+ = -∞. Donc lim_{x→0^+} f(x) = -∞ (asymptote verticale x = 0).<br>En +∞ : lim ln(x)/x = 0 (croissance comparée) donc lim_{x→+∞} f(x) = +∞.<br>2. f(x) - (x - 1) = ln(x)/x. Or lim_{x→+∞} ln(x)/x = 0, donc (D) : y = x - 1 est asymptote oblique.<br>3. Signe de f(x) - y = ln(x)/x : Comme x > 0, le signe dépend de ln(x).<br>• Sur ]0, 1[, ln(x) < 0 donc (C) est au-dessous de (D).<br>• Sur ]1, +∞[, ln(x) > 0 donc (C) est au-dessus de (D).<br>• En x = 1, (C) coupe (D) au point (1, 0).',
            ],
            'suggestions' => [
                'Comment factoriser e^(2x) - 4 sans faire d\'erreur ?',
                'Explique-moi la méthode pour lever l\'indétermination x - ln(x)',
                'Pourquoi la fonction exponentielle ne peut jamais être négative ?',
            ],
        ];
    }

    private function getNewtonCourseData(): array
    {
        return [
            'code' => 'PCT_TLE_D_CH01',
            'title' => 'Cinématique du point matériel & Lois de Newton',
            'subject' => 'Physique-Chimie et Technologie',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D · Guide Pédagogique National',
            'coeff_badge' => 'Coeff. 3 (Série D) / Coeff. 5 (Série C) · Épreuve Écrite',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Définir le vecteur position \$\\vec{OG}\$, le vecteur vitesse \$\\vec{v}\$ et le vecteur accélération \$\\vec{a}\$.",
                "Exprimer le vecteur accélération dans la base de Frenet : \$\\vec{a} = a_t \\vec{u}_t + a_n \\vec{u}_n = \\frac{dv}{dt} \\vec{u}_t + \\frac{v^2}{\\rho} \\vec{u}_n\$.",
                "Énoncer et appliquer la deuxième loi de Newton (Théorème du centre d'inertie) : \$\\sum \\vec{F}_{ext} = m \\vec{a}_G\$.",
                "Établir les équations horaires du mouvement d'un projectile dans un champ de pesanteur uniforme \$\\vec{g}\$.",
            ],
            'definitions' => [
                [
                    'term' => 'Référentiel galiléen',
                    'definition' => 'Référentiel dans lequel le principe d\'inertie (première loi de Newton) est vérifié. Exemples : référentiel terrestre (pour des durées courtes), référentiel géocentrique, référentiel héliocentrique.',
                ],
                [
                    'term' => 'Repère de Frenet',
                    'definition' => 'Repère local orthonormé $(M, \\vec{u}_t, \\vec{u}_n)$ lié au point mobile. $\\vec{u}_t$ est tangent à la trajectoire dans le sens du mouvement, $\\vec{u}_n$ est perpendiculaire et orienté vers le centre de courbure.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Deuxième Loi de Newton (Relation Fondamentale de la Dynamique)',
                    'latex' => '\\sum \\vec{F}_{ext} = m \\vec{a}_G \\iff \\vec{a}_G = \\frac{1}{m} \\sum \\vec{F}_{ext}',
                    'conditions' => 'Dans un référentiel galiléen, pour un système de masse $m$ constante.',
                    'comment' => 'Point de départ de toute résolution mécanique au BAC Togo.',
                ],
                [
                    'title' => 'Équations horaires du projectile dans le champ de pesanteur',
                    'latex' => 'x(t) = (v_0 \\cos\\alpha) t \\quad ; \\quad y(t) = -\\frac{1}{2} g t^2 + (v_0 \\sin\\alpha) t + y_0',
                    'conditions' => 'Système soumis uniquement à son poids $\\vec{P} = m\\vec{g}$, frottements de l\'air négligés, tir avec vitesse initiale $v_0$ faisant un angle $\\alpha$ avec l\'horizontale.',
                    'comment' => 'Trajectoire parabolique plane obtenue en éliminant le temps $t$.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Une bille de masse $m = 200\\text{ g}$ est lancée depuis l\'origine $O(0, 0)$ avec une vitesse $v_0 = 10\\text{ m/s}$ inclinée de $\\alpha = 30^\\circ$ par rapport à l\'horizontale. On prend $g = 10\\text{ m/s}^2$. Déterminer l\'équation cartésienne de la trajectoire.',
                'steps' => [
                    "1. Système : Bille de masse \$m = 0,2\\text{ kg}\$. Référentiel terrestre supposé galiléen.",
                    "2. Bilan des forces : Poids \$\\vec{P} = m\\vec{g}\$ (frottements de l'air négligés).",
                    "3. Application de la 2e loi de Newton : \$\\vec{P} = m\\vec{a} \\implies m\\vec{g} = m\\vec{a} \\implies \\vec{a} = \\vec{g}\$.",
                    "4. Projections de l'accélération : \$a_x = 0\$ et \$a_y = -g = -10\\text{ m/s}^2\$.",
                    "5. Intégrations successives (vitesse puis position avec \$x(0) = y(0) = 0\$) :",
                    "   \$v_x(t) = v_0 \\cos\\alpha\$ et \$v_y(t) = -gt + v_0 \\sin\\alpha\$.",
                    "   \$x(t) = (v_0 \\cos\\alpha)t \\implies t = \\frac{x}{v_0 \\cos\\alpha}\$.",
                    "   \$y(t) = -\\frac{1}{2}gt^2 + (v_0 \\sin\\alpha)t\$.",
                    "6. Équation de la trajectoire : \$y(x) = -\\frac{g}{2 v_0^2 \\cos^2\\alpha} x^2 + (\\tan\\alpha) x\$.",
                    "7. Application numérique : \$\\cos(30^\\circ) = \\frac{\\sqrt{3}}{2}\$, \$\\cos^2(30^\\circ) = 3/4\$, \$\\tan(30^\\circ) = 1/\\sqrt{3}\$.",
                    "   \$y(x) = -\\frac{10}{2 \\times 100 \\times (3/4)} x^2 + \\frac{\\sqrt{3}}{3} x = -\\frac{1}{15} x^2 + \\frac{\\sqrt{3}}{3} x\$.",
                ],
                'conclusion' => "La trajectoire est un arc de parabole de concavité tournée vers le bas.",
            ],
            'rules' => [
                [
                    'tag' => 'Méthodologie Officielle',
                    'title' => 'Les 5 étapes obligatoires en mécanique',
                    'content' => '1. Définir le système. 2. Préciser le référentiel d\'étude et son caractère galiléen. 3. Dresser le bilan des forces extérieures avec schéma. 4. Énoncer la 2e loi de Newton. 5. Projeter sur les axes du repère.',
                ],
            ],
            'trap' => 'Oublier d\'indiquer le référentiel galiléen ou écrire $\\vec{P} = \\vec{a}$ au lieu de $\\vec{P} = m\\vec{a}$ coûte 1 point d\'office sur le barème officiel.',
            'redaction_tip' => 'Toujours préciser les conditions initiales à t = 0 (coordonnées de position et de vitesse) avant d\'intégrer pour déterminer les constantes d\'intégration.',
            'formula_sheet' => [
                ['name' => 'Accélération dans Frenet', 'formula' => '\\vec{a} = \\frac{dv}{dt} \\vec{u}_t + \\frac{v^2}{R} \\vec{u}_n'],
                ['name' => 'Portée du tir', 'formula' => 'x_P = \\frac{v_0^2 \\sin(2\\alpha)}{g}'],
                ['name' => 'Flèche (hauteur max)', 'formula' => 'y_S = \\frac{v_0^2 \\sin^2(\\alpha)}{2g}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Lois de Newton',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Dans un mouvement circulaire uniforme de rayon R à vitesse v constante, que vaut l\'accélération tangentielle a_t ?',
                        'options' => ['A. a_t = 0', 'B. a_t = v²/R', 'C. a_t = g', 'D. a_t = R·v'],
                        'correct' => 0,
                        'expl' => 'La vitesse est constante, donc dv/dt = 0, d\'où a_t = 0. Seule l\'accélération normale a_n = v²/R est non nulle.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Quelle est l\'unité légale (SI) de la constante d\'accélération de pesanteur g ?',
                        'options' => ['A. m/s² ou N/kg', 'B. m/s', 'C. kg/m³', 'D. Joules'],
                        'correct' => 0,
                        'expl' => 'g s\'exprime en mètres par seconde carrée (m/s²) ou en Newtons par kilogramme (N/kg).',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Dans le champ de pesanteur uniforme sans frottement, de quelle grandeur dépend l\'accélération d\'un corps en chute libre ?',
                        'options' => ['A. Uniquement du champ de pesanteur g', 'B. De sa masse m', 'C. De son volume', 'D. De sa couleur'],
                        'correct' => 0,
                        'expl' => 'a = g : tous les corps tombent avec la même accélération quelle que soit leur masse.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Mouvement circulaire d\'un satellite',
                'xp' => 50,
                'statement' => 'Un satellite artificiel de masse m décrit une orbite circulaire de rayon r autour de la Terre (masse M_T).<br>1. Exprimer la force gravitationnelle exercée par la Terre sur le satellite.<br>2. En appliquant la 2e loi de Newton, montrer que la vitesse orbitale est v = √(G·M_T / r).',
                'hint' => 'L\'accélération est purement normale : a = v²/r.',
                'solution' => '1. Force de gravitation : F = G · (M_T · m) / r² orientée vers le centre de la Terre.<br>2. D\'après la 2e loi de Newton : F = m · a_n ⇔ G · (M_T · m) / r² = m · (v² / r).<br>En simplifiant par m et par r : v² = G · M_T / r ⇒ v = √(G · M_T / r).',
                'bareme' => 'Expression de la force : 2 pts · Établissement de la vitesse : 3 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Épreuve Type BAC 2 PCT Togo',
                'xp' => 100,
                'statement' => 'Au cours d\'un match au stade de Kégué à Lomé, un footballeur tire un coup franc direct. Le ballon part du sol avec une vitesse v_0 = 20 m/s sous un angle α = 30°. Un mur défensif de hauteur h = 2 m se trouve à une distance d = 9,15 m du point de tir. On prend g = 10 m/s².<br>1. Établir l\'équation cartésienne de la trajectoire du ballon.<br>2. Le ballon passe-t-il au-dessus du mur ? Justifier rigoureusement par un calcul.',
                'bareme' => 'Équation de trajectoire : 3 pts · Calcul de hauteur et conclusion : 3 pts.',
                'solution' => '1. Trajectoire : y(x) = -g/(2 v_0² cos²α) x² + (tan α) x.<br>v_0² cos²(30°) = 400 × (3/4) = 300. Donc y(x) = -10/(600) x² + (tan 30°) x = -1/60 x² + (√3/3) x.<br>2. Hauteur du ballon à x = 9,15 m :<br>y(9,15) = -1/60 × (9,15)² + (√3/3) × 9,15 = -1/60 × 83,72 + 0,577 × 9,15 = -1,395 + 5,28 = 3,88 m.<br>Comme y(9,15) = 3,88 m > h = 2 m, le ballon passe largement au-dessus du mur défensif.',
            ],
            'suggestions' => [
                'Comment calculer la portée maximale sans formule toute faite ?',
                'Pourquoi la masse n\'intervient pas dans la trajectoire du projectile ?',
                'Qu\'est-ce qu\'un satellite géostationnaire et quelle est sa période ?',
            ],
        ];
    }

    private function getAcidoBasiqueCourseData(): array
    {
        return [
            'code' => 'PCT_TLE_D_CH02',
            'title' => 'Solutions Aqueuses & Dosages Acido-Basiques',
            'subject' => 'Physique-Chimie et Technologie',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D · Guide Pédagogique National',
            'coeff_badge' => 'Coeff. 3 · Chimie Incontournable BAC 2',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Définir le pH, le produit ionique de l'eau \$K_e = 10^{-14}\$ à 25°C et les relations \$[H_3O^+] = 10^{-pH}\$.",
                "Caractériser les acides forts, bases fortes, acides faibles et bases faibles.",
                "Utiliser la constante d'acidité \$K_a\$ et la relation d'Henderson-Hasselbalch : \$pH = pK_a + \\log\\left(\\frac{[A^-]}{[AH]}\\right)\$.",
                "Exploiter une courbe de dosage pH-métrique (point d'équivalence \$E\$, méthode des tangentes parallèles, choix de l'indicateur coloré).",
            ],
            'definitions' => [
                [
                    'term' => 'Acide et Base selon Brönsted',
                    'definition' => 'Un acide est une espèce chimique capable de céder au moins un proton $H^+$. Une base est une espèce chimique capable de capter au moins un proton $H^+$.',
                ],
                [
                    'term' => 'Équivalence acido-basique',
                    'definition' => 'État du système chimique dans lequel les réactifs (acide et base) ont été mélangés dans les proportions stœchiométriques de l\'équation de réaction : $n_A \\text{ initial} = n_B \\text{ versé à l\'équivalence}$.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Relation d\'Henderson-Hasselbalch (pH d\'un couple)',
                    'latex' => 'pH = pK_a + \\log\\left(\\frac{[\\text{Base conjuguée}]}{[\\text{Acide conjugué}]}\\right)',
                    'conditions' => 'Valable pour tout couple acide/base faible en solution aqueuse diluée.',
                    'comment' => 'À la demi-équivalence d\'un acide faible par une base forte, $[AH] = [A^-]$, d\'où $pH = pK_a$.',
                ],
                [
                    'title' => 'Produit ionique de l\'eau',
                    'latex' => 'K_e = [H_3O^+][OH^-] = 10^{-14} \\text{ à } 25^\\circ\\text{C} \\implies pH + pOH = 14',
                    'conditions' => 'Dans toute solution aqueuse à 25°C.',
                    'comment' => 'Solution neutre : $[H_3O^+] = [OH^-] = 10^{-7} \\implies pH = 7$.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'On dose un volume $V_A = 20\\text{ mL}$ d\'acide éthanoïque $CH_3COOH$ de concentration inconnue $C_A$ par une solution d\'hydroxyde de sodium $(Na^+ + OH^-)$ de concentration $C_B = 0,10\\text{ mol/L}$. Le volume versé à l\'équivalence est $V_{BE} = 15\\text{ mL}$. 1. Écrire l\'équation bilan du dosage. 2. Calculer la concentration $C_A$.',
                'steps' => [
                    "1. Équation bilan : \$CH_3COOH + OH^- \\longrightarrow CH_3COO^- + H_2O\$ (réaction quasi-totale et rapide).",
                    "2. À l'équivalence : Les réactifs sont introduits dans les proportions stœchiométriques : \$n_A = n_{BE} \\implies C_A V_A = C_B V_{BE}\$.",
                    "3. Expression littérale : \$C_A = \\frac{C_B V_{BE}}{V_A}\$.",
                    "4. Application numérique : \$C_A = \\frac{0,10 \\times 15}{20} = 0,075\\text{ mol/L}\$.",
                ],
                'conclusion' => "La concentration de l'acide éthanoïque est \$C_A = 7,5 \\times 10^{-2}\\text{ mol/L}\$.",
            ],
            'rules' => [
                [
                    'tag' => 'Indicateur coloré',
                    'title' => 'Critère de choix de l\'indicateur de fin de réaction',
                    'content' => 'La zone de virage de l\'indicateur coloré doit obligatoirement englober le pH du point d\'équivalence $pH_E$. Ex : Phénolphtaléine (virage 8,2 - 10) pour le dosage d\'un acide faible par une base forte ($pH_E > 7$).',
                ],
            ],
            'trap' => 'Dire que le pH à l\'équivalence vaut toujours 7. C\'est vrai UNIQUEMENT pour acide fort + base forte. Pour acide faible + base forte, pHE > 7 à cause de la présence de la base conjuguée A-.',
            'redaction_tip' => 'Toujours écrire la relation d\'équivalence en toutes lettres : « À l\'équivalence acido-basique, les quantités de matière d\'acide et de base versées sont stœchiométriques : n_A = n_B ». Ne jamais parachuter la formule sans phrase.',
            'formula_sheet' => [
                ['name' => 'Acide fort', 'formula' => 'pH = -\\log(C_A)'],
                ['name' => 'Base forte', 'formula' => 'pH = 14 + \\log(C_B)'],
                ['name' => 'Acide faible', 'formula' => 'pH = \\frac{1}{2}(pK_a - \\log(C_A))'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Chimie Acido-Basique',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Quelle est la valeur du pH d\'une solution d\'acide chlorhydrique (HCl) à C = 10⁻² mol/L ?',
                        'options' => ['A. pH = 2', 'B. pH = 12', 'C. pH = 7', 'D. pH = 1'],
                        'correct' => 0,
                        'expl' => 'Acide fort : pH = -log(C) = -log(10⁻²) = 2.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'À la demi-équivalence du dosage d\'un acide faible par une base forte, quelle relation est exacte ?',
                        'options' => ['A. pH = pKa', 'B. pH = 7', 'C. pH = 14 - pKa', 'D. [AH] = 0'],
                        'correct' => 0,
                        'expl' => 'À la demi-équivalence, [AH] = [A⁻], donc log([A⁻]/[AH]) = log(1) = 0, d\'où pH = pKa.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Quel indicateur coloré convient pour un dosage dont le pH à l\'équivalence est pH_E = 8,8 ?',
                        'options' => ['A. Phénolphtaléine (8,2 - 10,0)', 'B. Hélianthine (3,1 - 4,4)', 'C. Bleu de bromothymol (6,0 - 7,6)', 'D. Rouge de méthyle'],
                        'correct' => 0,
                        'expl' => 'La zone de virage de la phénolphtaléine [8,2 ; 10,0] contient la valeur pH_E = 8,8.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Calcul de pH d\'un mélange tampon',
                'xp' => 50,
                'statement' => 'On mélange 50 mL d\'une solution d\'acide éthanoïque à 0,1 mol/L et 50 mL d\'une solution d\'éthanoate de sodium à 0,1 mol/L. Le pKa du couple CH3COOH/CH3COO- est 4,8.<br>1. Quel type de solution obtient-on ? Quelles sont ses propriétés remarquables ?<br>2. Calculer la valeur du pH du mélange.',
                'hint' => 'Les concentrations d\'acide et de base sont égales.',
                'solution' => '1. On obtient une solution tampon. Ses propriétés : son pH varie très peu par dilution modérée ou par addition de petites quantités d\'acide ou de base.<br>2. [CH3COO-] = [CH3COOH] car les volumes et concentrations sont égaux. D\'où pH = pKa + log(1) = 4,8 + 0 = 4,8.',
                'bareme' => 'Définition solution tampon : 2.5 pts · Calcul du pH : 2.5 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Exploitation de courbe de dosage BAC',
                'xp' => 100,
                'statement' => 'Sur une courbe de dosage d\'un acide carboxylique R-COOH par la soude, on relève :<br>• À V_B = 6 mL : pH = 4,2.<br>• À l\'équivalence V_BE = 12 mL : pH_E = 8,7.<br>1. Déterminer la valeur du pKa de ce couple acide/base.<br>2. Justifier pourquoi le pH à l\'équivalence est basique.',
                'bareme' => 'Détermination du pKa : 3 pts · Justification du pHE basique : 3 pts.',
                'solution' => '1. Le volume à la demi-équivalence est V_B = V_BE / 2 = 12 / 2 = 6 mL. À ce volume précis, pH = pKa. Donc pKa = 4,2.<br>2. À l\'équivalence, tous les acides R-COOH ont été transformés en leur base conjuguée R-COO-. La solution contient donc des ions éthanoate qui réagissent faiblement avec l\'eau selon : R-COO- + H2O ⇌ R-COOH + OH-. La présence d\'ions hydroxydes OH- confère à la solution un caractère basique (pH_E > 7).',
            ],
            'suggestions' => [
                'Pourquoi une solution tampon résiste-t-elle à la dilution ?',
                'Comment utiliser la méthode des tangentes parallèles sur un graphe ?',
                'Quelle est la différence entre équivalence et demi-équivalence ?',
            ],
        ];
    }

    private function getGeneticsCourseData(): array
    {
        return [
            'code' => 'SVT_TLE_D_CH01',
            'title' => 'Génétique formelle & Transmission des caractères héréditaires',
            'subject' => 'Sciences de la Vie et de la Terre',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D · Guide d\'Évaluation SVT',
            'coeff_badge' => 'Coeff. 3 · Épreuve Obligatoire BAC 2',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Analyser les résultats de croisements en monohybridisme et dihybridisme chez les diploïdes.",
                "Différencier dominance absolue, codominance et gènes létaux.",
                "Démontrer si deux gènes sont indépendants (sur des chromosomes différents) ou liés (sur le même chromosome : linkage).",
                "Calculer le taux de recombinaison (% de crossing-over) et établir la carte factorielle.",
            ],
            'definitions' => [
                [
                    'term' => 'Locus et Allèle',
                    'definition' => 'Un locus est l\'emplacement précis d\'un gène sur un chromosome. Un allèle est une version ou variante possible de la séquence d\'un gène.',
                ],
                [
                    'term' => 'Test-cross (Croisement test)',
                    'definition' => 'Croisement d\'un individu de phénotype dominant (mais de génotype inconnu ou hétérozygote F1) avec un individu homozygote récessif pour les caractères étudiés. Les proportions phénotypiques des descendants reflètent directement les proportions de gamètes produits par l\'individu testé.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Lois de Mendel',
                    'latex' => '1^{\\text{re}} \\text{ loi : Homogénéité de F1} \\quad ; \\quad 3^{\\text{e}} \\text{ loi : Ségrégation indépendante } [9:3:3:1]',
                    'conditions' => 'Gènes situés sur des paires chromosomiques différentes (non liés).',
                    'comment' => 'Si un test-cross donne 4 phénotypes équiprobables (25%, 25%, 25%, 25%), les deux gènes sont indépendants.',
                ],
                [
                    'title' => 'Gènes liés et Crossing-over (Taux de recombinaison)',
                    'latex' => 'p = \\frac{\\text{Nombre de recombinés}}{\\text{Nombre total de descendants}} \\times 100 \\quad (\\text{en } cM)',
                    'conditions' => 'Gènes situés sur le même chromosome. Test-cross donnant 2 phénotypes parentaux majoritaires et 2 recombinés minoritaires.',
                    'comment' => '1% de recombinaison = 1 centimorgan (cM) de distance génétique sur la carte factorielle.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'On croise deux drosophiles de lignée pure : une aux yeux rouges et ailes longues, l\'autre aux yeux bruns et ailes vestigiales. La F1 est 100% yeux rouges et ailes longues. Le croisement test d\'une femelle F1 avec un mâle récessif donne : 410 yeux rouges ailes longues, 415 yeux bruns ailes vestigiales, 90 yeux rouges ailes vestigiales, 85 yeux bruns ailes longues. Interpréter.',
                'steps' => [
                    "1. Analyse de F1 : F1 est homogène, conforme à la 1re loi de Mendel. Yeux rouges (R) domine brun (b) ; ailes longues (L) domine vestigial (v).",
                    "2. Analyse du test-cross : On obtient 4 phénotypes d'effectif total 410 + 415 + 90 + 85 = 1000 individus.",
                    "3. Types parentaux : [R, L] (410) et [b, v] (415), soit 82,5% (majoritaires).",
                    "4. Types recombinés : [R, v] (90) et [b, L] (85), soit 17,5% (minoritaires).",
                    "5. Déduction : Les pourcentages étant inégaux (Parentaux > Recombinés), les deux gènes sont LIÉS (portés par le même chromosome).",
                    "6. Distance génétique : p = (90 + 85) / 1000 × 100 = 17,5% = 17,5 cM.",
                ],
                'conclusion' => "Les gènes sont liés et distants de 17,5 cM.",
            ],
            'rules' => [
                [
                    'tag' => 'Règle de rédaction SVT',
                    'title' => 'Échiquier de croisement',
                    'content' => 'Toujours présenter : 1. Phénotypes des parents. 2. Génotypes des parents. 3. Gamètes produits avec pourcentages. 4. Tableau de rencontre des gamètes (échiquier). 5. Bilan phénotypique théorique confronté aux données réelles.',
                ],
            ],
            'trap' => 'Confondre notation de gènes indépendants (A/a ; B/b) et gènes liés (AB // ab). Utiliser la mauvaise notation retire tous les points d\'écriture génotypique.',
            'redaction_tip' => 'Toujours expliciter le mécanisme cytologique responsable des phénotypes recombinés : « Le brassage interchromosomique en anaphase I pour les gènes indépendants » ou « Le brassage intrachromosomique (crossing-over) en prophase I pour les gènes liés ».',
            'formula_sheet' => [
                ['name' => 'Gènes indépendants (F2)', 'formula' => '[9/16 ; 3/16 ; 3/16 ; 1/16]'],
                ['name' => 'Distance génétique', 'formula' => 'd = \\% \\text{ recombinés (cM)}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Génétique',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Si un test-cross de dihybridisme donne les proportions 25%, 25%, 25%, 25%, on en déduit que les gènes sont :',
                        'options' => ['A. Indépendants', 'B. Liés', 'C. Létaux', 'D. Liés au sexe'],
                        'correct' => 0,
                        'expl' => 'Quatre phénotypes équiprobables au test-cross prouvent que les gènes sont situés sur des paires différentes (indépendants).',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Quel événement cellulaire survenant en prophase I de méiose explique l\'apparition de phénotypes recombinés chez les gènes liés ?',
                        'options' => ['A. Le crossing-over', 'B. La mitose', 'C. La réplication d\'ADN', 'D. La fécondation'],
                        'correct' => 0,
                        'expl' => 'Le crossing-over (enjambement) permet l\'échange de segments de chromatides non-sœurs.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Si le taux de recombinaison entre deux gènes est de 8%, quelle est leur distance sur la carte génétique ?',
                        'options' => ['A. 8 cM', 'B. 0,08 cM', 'C. 16 cM', 'D. 92 cM'],
                        'correct' => 0,
                        'expl' => '1% de recombinaison = 1 centimorgan (cM), donc 8% = 8 cM.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Détermination de génotype',
                'xp' => 50,
                'statement' => 'Chez le maïs, le grain coloré (C) domine le grain incolore (c), et le grain plein (P) domine le grain ridé (p). On croise une plante hétérozygote pour les deux gènes avec une plante récessive. On obtient : 4000 grains colorés pleins, 4000 incolores ridés, 150 colorés ridés, 150 incolores pleins.<br>Les gènes sont-ils liés ou indépendants ? Écrire le génotype de la plante hétérozygote.',
                'hint' => 'Compare les pourcentages des classes parentales et recombinées.',
                'solution' => 'Total = 8300 grains. Classes parentales = 8000/8300 = 96,4%. Recombinées = 300/8300 = 3,6%.<br>Les classes parentales sont nettement majoritaires : les deux gènes sont liés.<br>Les allèles dominants C et P sont entrés ensemble (issus du parent coloré plein), donc la phase est en couplage : le génotype s\'écrit (CP // cp).',
                'bareme' => 'Justification gènes liés : 2.5 pts · Génotype avec barre de fraction : 2.5 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Carte factorielle complète BAC SVT',
                'xp' => 100,
                'statement' => 'Trois gènes A, B, C sont situés sur le même chromosome d\'une espèce végétale. Les fréquences de recombinaison obtenues par croisements deux à deux sont :<br>• Distance entre A et B : 12 cM.<br>• Distance entre B et C : 5 cM.<br>• Distance entre A et C : 7 cM.<br>1. Établir l\'ordre linéaire des trois gènes sur le chromosome.<br>2. Dessiner la carte factorielle à l\'échelle 1 cm pour 2 cM.',
                'bareme' => 'Ordre des gènes : 3 pts · Carte factorielle à l\'échelle : 3 pts.',
                'solution' => '1. On remarque que d(A, C) + d(C, B) = 7 + 5 = 12 cM = d(A, B). Le gène C est donc situé entre A et B.<br>L\'ordre des gènes est : A ----- C ----- B.<br>2. Carte factorielle : Sur une ligne graduée, placer A en position 0, C à 3,5 cm de A (soit 7 cM), et B à 2,5 cm de C (soit 5 cM de C et 6 cm de A correspondant à 12 cM).',
            ],
            'suggestions' => [
                'Comment savoir si un allèle est létal au vu des proportions ?',
                'Quelle est la différence entre phase de couplage et répulsion ?',
                'Comment justifier la transmission liée au chromosome sexuel X ?',
            ],
        ];
    }

    private function getImmunologyCourseData(): array
    {
        return [
            'code' => 'SVT_TLE_D_CH02',
            'title' => 'Le Système Immunitaire & Défense de l\'Organisme',
            'subject' => 'Sciences de la Vie et de la Terre',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D · Guide d\'Évaluation SVT',
            'coeff_badge' => 'Coeff. 3 · Incontournable BAC 2',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Distinguer immunité innée (non spécifique, réaction inflammatoire) et immunité adaptative (spécifique).",
                "Expliquer la coopération cellulaire entre macrophages (CPA), lymphocytes T4 (chefs d'orchestre), T8 (cytotoxiques) et B (plasmocytes sécréteurs d'anticorps).",
                "Décrire la structure d'un anticorps et le complexe immun.",
                "Expliquer le mécanisme d'infection du VIH ciblant les LT4 et l'évolution vers le stade SIDA.",
            ],
            'definitions' => [
                [
                    'term' => 'Antigène',
                    'definition' => 'Toute molécule ou particule étrangère à l\'organisme (ou altérée du soi) capable de déclencher une réaction immunitaire spécifique.',
                ],
                [
                    'term' => 'Lymphocyte T4 (CD4)',
                    'definition' => 'Cellule pivot de l\'immunité adaptative. Après reconnaissance de l\'antigène présenté par une CPA, il sécrète des interleukines stimulant la prolifération et la différenciation des LB et LT8.',
                ],
                [
                    'term' => 'Séropositivité',
                    'definition' => 'Présence dans le sérum sanguin d\'anticorps spécifiques dirigés contre un agent pathogène déterminé (ex : anticorps anti-VIH).',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Coopération cellulaire immunitaire',
                    'latex' => '\\text{CPA (Macrophage)} \\xrightarrow{\\text{IL-1}} \\text{LT4} \\xrightarrow{\\text{IL-2}} \\begin{cases} \\text{LB} \\to \\text{Plasmocytes} \\to \\text{Anticorps (Immunité Humorale)} \\\\ \\text{LT8} \\to \\text{LTc (Cytotoxiques)} \\to \\text{Perforine (Immunité Cellulaire)} \\end{cases}',
                    'conditions' => 'Reconnaissance du complexe peptide-CMH.',
                    'comment' => 'Schéma de synthèse systématique au BAC SVT.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Expliquer pourquoi la destruction des lymphocytes T4 par le VIH paralyse l\'ensemble des défenses immunitaires adaptatives.',
                'steps' => [
                    "1. Le VIH pénètre spécifiquement dans les LT4 grâce à la fixation de sa protéine de surface gp120 sur le récepteur CD4.",
                    "2. La multiplication virale entraîne la lyse et la mort progressive des LT4.",
                    "3. Les LT4 sont les uniques producteurs de cytokines (Interleukine 2), molécules indispensables pour stimuler la multiplication clonale et la différenciation des LB en plasmocytes et des LT8 en LT cytotoxiques.",
                    "4. Sans LT4, ni la réponse à médiation humorale (anticorps) ni la réponse à médiation cellulaire (cytotoxicité) ne peuvent être amplifiées.",
                ],
                'conclusion' => "L'organisme bascule dans l'immunodéficience acquise (SIDA), devenant vulnérable aux maladies opportunistes.",
            ],
            'rules' => [
                [
                    'tag' => 'Vocabulaire précis',
                    'title' => 'Différenciation cellulaire',
                    'content' => 'Les lymphocytes B ne sécrètent pas d\'anticorps directement : ils se différencient d\'abord en plasmocytes, qui sont les véritables usines de sécrétion d\'anticorps.',
                ],
            ],
            'trap' => 'Dire que les anticorps détruisent les bactéries. Les anticorps ne détruisent rien eux-mêmes : ils neutralisent l\'antigène en formant le complexe immun, qui est ensuite éliminé par phagocytose.',
            'redaction_tip' => 'Quand on vous demande un schéma fonctionnel de la réponse immunitaire, incluez impérativement les légendes complètes, le titre et le sens des flèches avec les médiateurs chimiques (interleukines).',
            'formula_sheet' => [
                ['name' => 'Structure anticorps', 'formula' => '2 \\text{ chaînes lourdes } H + 2 \\text{ chaînes légères } L'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Immunologie',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Quelle cellule sécrète les anticorps circulants dans le sang ?',
                        'options' => ['A. Le plasmocyte', 'B. Le lymphocyte T8', 'C. Le globule rouge', 'D. Le virus'],
                        'correct' => 0,
                        'expl' => 'Le lymphocyte B activé se différencie en plasmocyte riche en réticulum endoplasmique pour synthétiser les anticorps.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Quel récepteur membranaire est la cible d\'entrée principale du VIH sur les lymphocytes T4 ?',
                        'options' => ['A. CD4', 'B. CD8', 'C. Hémoglobine', 'D. Insuline'],
                        'correct' => 0,
                        'expl' => 'Le VIH se fixe sur le récepteur membranaire CD4 via sa glycoprotéine gp120.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'La formation d\'un complexe immun entre un anticorps et son antigène entraîne :',
                        'options' => ['A. La neutralisation de l\'antigène', 'B. La destruction de l\'anticorps', 'C. La réplication du virus', 'D. La baisse du pH'],
                        'correct' => 0,
                        'expl' => 'Le complexe immun neutralise l\'antigène et facilite sa phagocytose par les macrophages.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Analyse d\'électrophorèse du sérum',
                'xp' => 50,
                'statement' => 'On réalise une électrophorèse des protéines sériques chez un individu sain et chez un individu ayant contracté une infection bactérienne sévère depuis 15 jours.<br>Chez l\'individu infecté, le pic des gamma-globulines est multiplié par 3.<br>1. Que contiennent les gamma-globulines ?<br>2. Interpréter cette augmentation.',
                'hint' => 'Les anticorps sont des immunoglobulines (gamma-globulines).',
                'solution' => '1. La fraction des gamma-globulines est constituée des anticorps circulants (immunoglobulines).<br>2. L\'augmentation spectaculaire de ce pic traduit une réponse immunitaire humorale massive : les LB stimulés par l\'antigène bactérien se sont différenciés en plasmocytes qui ont sécrété une grande quantité d\'anticorps spécifiques pour neutraliser les bactéries.',
                'bareme' => 'Identification des anticorps : 2.5 pts · Interprétation biologique : 2.5 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Problème de Synthèse SIDA & Vaccin',
                'xp' => 100,
                'statement' => 'Dans le cadre de la recherche vaccinale contre le VIH, on teste chez des macaques une injection de protéine gp120 modifiée. On constate l\'apparition d\'anticorps neutralisants mais le taux de LTc reste très faible.<br>1. Quel type de réponse immunitaire est principalement stimulé par cette injection ?<br>2. Pourquoi un vaccin efficace contre le VIH doit-il impérativement stimuler à la fois l\'immunité humorale et l\'immunité cellulaire ?',
                'bareme' => 'Identification de la réponse humorale : 3 pts · Justification de la double réponse nécessaire : 3 pts.',
                'solution' => '1. L\'injection d\'une protéine soluble (gp120) stimule quasi exclusivement la réponse à médiation humorale (production d\'anticorps circulants par les plasmocytes).<br>2. Le VIH possède une double phase d\'infection :<br>• Lorsqu\'il circule librement dans les liquides biologiques (phase extracellulaire), seuls les anticorps neutralisants peuvent l\'empêcher de pénétrer dans de nouvelles cellules cibles.<br>• Dès qu\'il a intégré son ADN dans les LT4 (phase intracellulaire), les anticorps ne peuvent plus l\'atteindre. Seuls les lymphocytes T cytotoxiques (LTc) sont capables de détruire par contact et libération de perforine/granzymes les cellules réservoirs infectées.<br>Un vaccin protecteur doit donc impérativement activer les deux branches du système immunitaire.',
            ],
            'suggestions' => [
                'Quelle est la différence entre immunité innée et acquise ?',
                'Comment fonctionne le test ELISA pour détecter la séropositivité ?',
                'Quel est le rôle exact de la molécule CMH ?',
            ],
        ];
    }

    private function getPhiloConscienceCourseData(): array
    {
        return [
            'code' => 'PHILO_TLE_D_CH01',
            'title' => 'La Conscience et l\'Inconscient',
            'subject' => 'Philosophie',
            'ref' => 'MEPST Togo · Programme Officiel Terminale A, C, D · Manuel National',
            'coeff_badge' => 'Coeff. 2 (Série D) / Coeff. 4 (Série A4) · Dissertation & Commentaire',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Définir la conscience (psychologique, réflexive et morale).",
                "Comprendre le doute méthodique cartésien et l'affirmation du sujet pensant (Cogito).",
                "Analyser la critique freudienne de la souveraineté de la conscience et l'hypothèse de l'inconscient psychique.",
                "Saisir les enjeux éthiques et juridiques de l'inconscient face à la responsabilité humaine.",
            ],
            'definitions' => [
                [
                    'term' => 'Conscience (sens étymologique)',
                    'definition' => 'Du latin « cum-scientia », signifiant « avec savoir ». Connaissance immédiate ou réfléchie qu\'a l\'homme de ses états psychologiques et du monde extérieur.',
                ],
                [
                    'term' => 'Inconscient (sens psychanalytique)',
                    'definition' => 'Partie du psychisme constituée de désirs, pulsions et souvenirs refoulés hors de la conscience par la censure, mais qui continuent d\'exercer une influence déterminante sur nos actes (actes manqués, rêves, névroses).',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Le Cogito Cartésien (Descartes)',
                    'latex' => '\\text{« Cogito, ergo sum »} \\iff \\text{« Je pense, donc je suis »}',
                    'conditions' => 'Méditations Métaphysiques (1641).',
                    'comment' => 'La conscience est la première certitude inébranlable qui résiste à l\'épreuve du doute méthodique universel.',
                ],
                [
                    'title' => 'La Blessure Narcissique Freudienne',
                    'latex' => '\\text{« Le Moi n\'est pas maître dans sa propre maison »}',
                    'conditions' => 'Sigmund Freud, Une difficulté de la psychanalyse (1917).',
                    'comment' => 'Après Copernic (la Terre n\'est pas au centre du cosmos) et Darwin (l\'homme descend de l\'animal), Freud porte le troisième coup narcissique : la conscience n\'est qu\'une partie superficielle du psychisme.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Sujet type BAC Togo : « L\'inconscient est-il une excuse à nos fautes ? »',
                'steps' => [
                    "1. Problématisation : Si l'inconscient détermine nos actes à notre insu, l'homme ne peut être tenu pour moralement et juridiquement coupable (alibi). Mais si nous réduisons l'homme à ses pulsions inconscientes, nous anéantissons sa dignité d'être libre et responsable.",
                    "2. Thèse 1 (Déterminisme psychique) : L'inconscient comme fatalité. Les névroses et lapsus montrent que la volonté consciente capitule devant les pulsions refoulées (Freud).",
                    "3. Antithèse (Critique éthique) : Le refus de l'inconscient comme alibi de mauvaise foi. Pour Sartre, l'inconscient freudien est une fable commode pour fuir l'angoisse de la liberté : « L'inconscient n'est rien d'autre que la mauvaise foi ».",
                    "4. Synthèse : La psychanalyse ne vise pas à déresponsabiliser l'homme, mais à élargir son champ de conscience par la cure (« Wo Es war, soll Ich werden » : Là où était le Ça, le Moi doit advenir).",
                ],
                'conclusion' => "L'inconscient n'est pas une excuse, mais un défi éthique : l'homme a le devoir de s'efforcer de se connaître lui-même pour répondre de ses actes.",
            ],
            'rules' => [
                [
                    'tag' => 'Règle de dissertation',
                    'title' => 'La structure ternaire indispensable',
                    'content' => 'Introduction (Accroche, Définitions, Problématique, Annonce du plan) -> 3 Parties thématiques argumentées avec citations d\'auteurs -> Conclusion (Bilan critique et ouverture).',
                ],
            ],
            'trap' => 'Ne jamais faire un catalogue d\'opinions sans problématique. La philosophie n\'est pas un récit de souvenirs, mais une confrontation rigoureuse de concepts.',
            'redaction_tip' => 'Pour chaque argument, appliquez la règle A-E-I : Affirmation de la thèse, Explication philosophique du concept, Illustration par un auteur ou un exemple précis.',
            'formula_sheet' => [
                ['name' => '1re topique freudienne', 'formula' => '\\text{Conscient - Préconscient - Inconscient}'],
                ['name' => '2e topique freudienne', 'formula' => '\\text{Ça (pulsions) - Moi (réalité) - Surmoi (morale)}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Notions Philosophie',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Quel philosophe a fondé la certitude de l\'existence humaine sur la formule « Je pense, donc je suis » ?',
                        'options' => ['A. René Descartes', 'B. Karl Marx', 'C. Jean-Jacques Rousseau', 'D. Platon'],
                        'correct' => 0,
                        'expl' => 'Descartes dans le Discours de la Méthode et les Méditations Métaphysiques.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Selon Freud, quelle instance psychique représente l\'intériorisation des interdits parentaux et sociaux ?',
                        'options' => ['A. Le Surmoi', 'B. Le Ça', 'C. Le Moi', 'D. La libido'],
                        'correct' => 0,
                        'expl' => 'Le Surmoi est l\'instance morale répressive résultant de l\'intériorisation des règles et interdits.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Pour Jean-Paul Sartre, invoquer l\'inconscient pour justifier ses actes relève de :',
                        'options' => ['A. La mauvaise foi', 'B. La sagesse', 'C. La fatalité biologique', 'D. L\'inconscience pure'],
                        'correct' => 0,
                        'expl' => 'Sartre soutient que l\'homme est totalement libre et responsable : s\'abriter derrière l\'inconscient est une attitude de mauvaise foi.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Analyse de citation',
                'xp' => 50,
                'statement' => 'Expliquer la portée de cette affirmation de Freud : « Le Moi n\'est pas maître dans sa propre maison ».<br>1. Identifiez la métaphore employée.<br>2. Quels en sont les enjeux pour la conception traditionnelle de l\'homme ?',
                'hint' => 'La « maison » représente le psychisme, et le « maître » représente la conscience.',
                'solution' => '1. La maison désigne la totalité du psychisme humain. Freud affirme que la conscience (le Moi) croyait gouverner souverainement la vie mentale, alors qu\'elle est dominée par des forces obscures (le Ça et le Surmoi).<br>2. Enjeux : Cette thèse brise l\'illusion cartésienne d\'un sujet transparent à lui-même et pleinement souverain. Elle oblige l\'homme à renoncer à sa prétention à l\'omniscience intérieure.',
                'bareme' => 'Explication de la métaphore : 2.5 pts · Portée philosophique : 2.5 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Plan détaillé de dissertation BAC',
                'xp' => 100,
                'statement' => 'Sujet officiel type BAC : « La liberté consiste-t-elle à faire tout ce qui nous plaît ? »<br>Rédiger l\'introduction complète (accroche, définition, problème, plan) et les axes directeurs des trois parties.',
                'bareme' => 'Introduction conforme aux normes MEPST : 4 pts · Plan tripartite équilibré : 2 pts (Total 6 pts).',
                'solution' => 'Introduction :<br>• Accroche : L\'opinion commune assimile spontanément la liberté à l\'absence de contrainte et à la satisfaction immédiate des désirs.<br>• Définitions : « Faire tout ce qui plaît », c\'est obéir à l\'arbitraire des passions. « Liberté », c\'est le pouvoir d\'autodétermination de la volonté guidée par la raison.<br>• Problème : Céder à tous ses penchants est-il l\'expression de la liberté ou la forme la plus insidieuse de l\'esclavage ?<br>• Plan : I. La liberté comme absence d\'entraves (licence). II. La servitude des passions (Spinoza, Rousseau). III. La véritable liberté comme autonomie rationnelle et obéissance à la loi (Kant).',
            ],
            'suggestions' => [
                'Comment problématiser un sujet de dissertation au BAC ?',
                'Quelle citation utiliser pour appuyer la thèse de Sartre ?',
                'Quelle est la différence entre conscience réflexive et conscience morale ?',
            ],
        ];
    }

    private function getPhiloLiberteCourseData(): array
    {
        return [
            'code' => 'PHILO_TLE_D_CH02',
            'title' => 'Liberté et Déterminisme',
            'subject' => 'Philosophie',
            'ref' => 'MEPST Togo · Programme Officiel Terminale A, C, D · Manuel National',
            'coeff_badge' => 'Coeff. 2 · Notions Fondamentales BAC',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Distinguer la liberté naturelle, la liberté civile et l'autonomie morale.",
                "Confronter le déterminisme (naturel, social, biologique) et l'affirmation du libre arbitre.",
                "Analyser la liberté comme obéissance à la loi qu'on s'est prescrite (Rousseau, Kant).",
            ],
            'definitions' => [
                [
                    'term' => 'Libre arbitre',
                    'definition' => 'Capacité de la volonté humaine à opérer un choix sans être contrainte par aucune force extérieure ou déterminisme interne.',
                ],
                [
                    'term' => 'Autonomie',
                    'definition' => 'Du grec « autos » (soi-même) et « nomos » (la loi). Capacité d\'un sujet rationnel à se donner à lui-même ses propres lois morales et à s\'y conformer.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'L\'illusion du libre arbitre (Spinoza)',
                    'latex' => '\\text{« Les hommes sont conscients de leurs désirs mais ignorants des causes qui les déterminent »}',
                    'conditions' => 'Baruch Spinoza, Éthique (1677).',
                    'comment' => 'La croyance en la liberté n\'est qu\'une illusion issue de l\'ignorance des déterminismes naturels et psychologiques.',
                ],
                [
                    'title' => 'La liberté républicaine (Rousseau)',
                    'latex' => '\\text{« L\'obéissance à la loi qu\'on s\'est prescrite est liberté »}',
                    'conditions' => 'Du Contrat Social (1762).',
                    'comment' => 'Dans l\'État républicain, la loi votée par le souverain (le peuple) libère l\'homme de la domination arbitraire d\'autrui.',
                ],
            ],
            'demonstration_example' => [
                'statement' => '« Être libre, est-ce n\'avoir aucune règle ? »',
                'steps' => [
                    "1. Première approche : Les règles semblent être des entraves à mes désirs.",
                    "2. Renversement critique : Sans règles, c'est la loi du plus fort qui triomphe (état de nature hobbesien où la vie est solitaire, indécente et courte).",
                    "3. Dépassement : La règle rationnelle et consentie est la condition même de l'exercice effectif de la liberté.",
                ],
                'conclusion' => "La liberté authentique s'accomplit par la règle juste et non contre elle.",
            ],
            'rules' => [
                [
                    'tag' => 'Conseil de copie',
                    'title' => 'Ne confondez pas liberté et anarchie',
                    'content' => 'Faire la distinction formelle entre « licence » (faire n\'importe quoi selon ses pulsions) et « liberté » (capacité d\'agir selon sa raison).',
                ],
            ],
            'trap' => 'Prétendre que l\'homme est totalement esclave ou totalement libre de manière simpliste sans nuance.',
            'redaction_tip' => 'Utilisez la célèbre métaphore de Spinoza sur la pierre qui tombe : si la pierre pensait pendant sa chute, elle croirait tomber par son propre vouloir.',
            'formula_sheet' => [
                ['name' => 'Liberté sartrienne', 'formula' => '\\text{« L\'existence précède l\'essence »}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Liberté',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Selon Spinoza, pourquoi les hommes se croient-ils libres ?',
                        'options' => ['A. Parce qu\'ils ignorent les causes qui les font agir', 'B. Parce qu\'ils ont une âme divine', 'C. Parce que l\'État les protège', 'D. Parce qu\'ils peuvent voter'],
                        'correct' => 0,
                        'expl' => 'Pour Spinoza, le sentiment de liberté résulte de la conscience des désirs jointe à l\'ignorance des causes qui les provoquent.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Pour Jean-Jacques Rousseau, l\'obéissance à la loi qu\'on s\'est prescrite est :',
                        'options' => ['A. La liberté', 'B. L\'esclavage', 'C. La tyrannie', 'D. L\'anarchie'],
                        'correct' => 0,
                        'expl' => 'Dans Du Contrat Social, Rousseau pose que seule l\'obéissance à la loi commune librement consentie constitue la vraie liberté civile.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Quelle formule d\'Emmanuel Kant définit l\'impératif catégorique de la morale ?',
                        'options' => ['A. Agis de telle sorte que la maxime de ta volonté puisse être érigée en loi universelle', 'B. Tous les moyens sont bons', 'C. Chacun pour soi', 'D. Vivre sans entrave'],
                        'correct' => 0,
                        'expl' => 'L\'autonomie kantienne repose sur l\'universalisation de la maxime de notre action.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Dissertation thématique',
                'xp' => 50,
                'statement' => 'En quoi la liberté politique se distingue-t-elle de la liberté naturelle ?<br>Rédiger un paragraphe argumenté avec une référence philosophique.',
                'hint' => 'Compare l\'état de nature (liberté sans garantie) et l\'état civil (liberté sous la protection des lois).',
                'solution' => 'La liberté naturelle est illimitée en droit mais nulle en fait : elle est à la merci de la force physique d\'autrui (loi de la jungle). En revanche, la liberté politique ou civile, théorisée par Rousseau, consiste en l\'abandon de la force individuelle au profit de la loi commune. L\'homme perd sa liberté animale illusoire mais gagne la liberté morale et la sécurité juridique garantie par l\'État de droit.',
                'bareme' => 'Distinction conceptuelle : 2.5 pts · Référence textuelle : 2.5 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Étude critique BAC',
                'xp' => 100,
                'statement' => 'Expliquer et apprécier cette pensée de Sartre : « L\'homme est condamné à être libre ».<br>1. Pourquoi utilise-t-il le terme paradoxal de « condamné » ?<br>2. Quelles en sont les conséquences pour notre existence ?',
                'bareme' => 'Analyse du paradoxe : 3 pts · Conséquences existentielles : 3 pts.',
                'solution' => '1. Le mot « condamné » souligne qu\'on ne choisit pas d\'être libre : l\'homme n\'a pas créé sa propre existence, pourtant, dès lors qu\'il est jeté dans le monde, il est contraint de choisir ses actes et d\'en porter la responsabilité intégrale.<br>2. Conséquences : L\'absence de fatalité et de nature humaine prédéterminée (« l\'existence précède l\'essence ») place l\'homme dans l\'angoisse du choix. Aucun déterminisme extérieur (hérédité, société, inconscient) ne peut servir d\'excuse. L\'homme est l\'unique artisan de sa vie.',
            ],
            'suggestions' => [
                'Peut-on être libre et obéir aux lois de la société ?',
                'Comment concilier le déterminisme sociologique et la liberté individuelle ?',
                'Qu\'est-ce que l\'angoisse existentielle chez Sartre ?',
            ],
        ];
    }

    private function getFrancaisContractionCourseData(): array
    {
        return [
            'code' => 'FR_TLE_MOD_01',
            'title' => 'La Contraction de texte & Discussion au BAC',
            'subject' => 'Français',
            'ref' => 'MEPST Togo · Programme Officiel Terminales A, C, D · Barème National',
            'coeff_badge' => 'Coeff. 3 (Série D) / Coeff. 5 (Série A4) · Épreuve Reine',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Maîtriser la règle stricte de réduction au quart du texte initial (marge de tolérance officielle : ±10%).",
                "Identifier la thèse de l'auteur, le fil conducteur logique et les connecteurs argumentatifs.",
                "Rédiger le résumé sans paraphrase, sans copie d'expressions du texte et en respectant le système d'énonciation de l'auteur.",
                "Conduire la discussion argumentative (300 à 400 mots) en formulant une thèse personnelle nuancée avec exemples concrets.",
            ],
            'definitions' => [
                [
                    'term' => 'La règle du quart et tolérance ±10%',
                    'definition' => 'Si un texte compte $N$ mots, la contraction doit comporter entre $\\frac{N}{4} \\times 0,9$ et $\\frac{N}{4} \\times 1,1$ mots. Tout dépassement ou sous-calibrage entraîne une pénalité immédiate de 1 à 3 points au BAC.',
                ],
                [
                    'term' => 'La Discussion',
                    'definition' => 'Seconde partie de l\'épreuve invitant le candidat à confronter la thèse de l\'auteur à sa propre réflexion sur un sujet de société, d\'éthique ou de culture contemporaine.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Règle de comptage officiel des mots (MEPST Togo)',
                    'latex' => '\\text{Un mot est toute unité typographique séparée par un espace ou un tiret}',
                    'conditions' => '« c\'est-à-dire » = 4 mots ; « l\'homme » = 2 mots ; « aujourd\'hui » = 1 mot ; « socio-économique » = 2 mots.',
                    'comment' => 'Indiquer impérativement le nombre exact de mots à la fin de la copie : « Texte résumé en ... mots » sous peine de pénalité.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Texte officiel de 480 mots sur « L\'intelligence artificielle et le travail en Afrique ». Calculer la fourchette de mots autorisée et donner les 3 étapes d\'élaboration du résumé.',
                'steps' => [
                    "1. Calcul du volume cible : Longueur théorique = 480 / 4 = 120 mots.",
                    "2. Fourchette de tolérance ±10% : Entre 120 - 12 = 108 mots et 120 + 12 = 132 mots.",
                    "3. Étape 1 : Lecture analytique et repérage des idées maîtresses (éliminer les exemples purement illustratifs, les chiffres secondaires et les digressions).",
                    "4. Étape 2 : Schéma logique des connecteurs (Cependant, De plus, Par conséquent).",
                    "5. Étape 3 : Rédaction personnelle en adoptant la posture de l'auteur (interdiction formelle d'écrire « l'auteur dit que »).",
                ],
                'conclusion' => "La contraction doit compter entre 108 et 132 mots avec mention finale obligatoire du décompte.",
            ],
            'rules' => [
                [
                    'tag' => 'Interdiction absolue',
                    'title' => 'Ne jamais se substituer à l\'auteur avec des formules métalinguistiques',
                    'content' => 'Sont strictement interdites les formulations : « Selon l\'auteur », « L\'auteur démontre que », « Dans ce texte il est question de ». Vous DEVEZ épouser la voix de l\'auteur comme si vous aviez écrit le texte raccourci.',
                ],
            ],
            'trap' => 'Recopier des phrases entières du texte en pensant gagner du temps. Les correcteurs soulignent en rouge tout emprunt de plus de 4 mots consécutifs et retirent jusqu\'à 4 points pour plagiat.',
            'redaction_tip' => 'Pour la discussion, organisez votre devoir selon le plan dialectique officiel : Thèse (raisons pour lesquelles la citation de l\'auteur est fondée), Antithèse (limites, objections et contre-exemples africains et mondiaux), Synthèse (dépassement et perspective d\'avenir).',
            'formula_sheet' => [
                ['name' => 'Tolérance basse', 'formula' => 'N_{\\min} = \\frac{N}{4} \\times 0,90'],
                ['name' => 'Tolérance haute', 'formula' => 'N_{\\max} = \\frac{N}{4} \\times 1,10'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Règles de Contraction',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Pour un texte de 400 mots, quelle est la fourchette de mots autorisée au BAC ?',
                        'options' => ['A. 90 à 110 mots (100 ± 10%)', 'B. 50 à 150 mots', 'C. 100 à 200 mots', 'D. Exactement 100 mots'],
                        'correct' => 0,
                        'expl' => '400 / 4 = 100 mots. Avec la tolérance de ±10%, la fourchette est [90 ; 110] mots.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Dans la contraction de texte, a-t-on le droit d\'écrire : « Selon l\'auteur du texte... » ?',
                        'options' => ['A. Non, c\'est strictement interdit', 'B. Oui, c\'est recommandé', 'C. Uniquement dans l\'introduction', 'D. Oui si le texte est long'],
                        'correct' => 0,
                        'expl' => 'Le candidat doit adopter le système d\'énonciation de l\'auteur et ne jamais utiliser de formule de distanciation.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Combien de mots compte l\'expression « l\'Afrique de l\'ouest » selon la règle typographique ?',
                        'options' => ['A. 5 mots', 'B. 3 mots', 'C. 4 mots', 'D. 2 mots'],
                        'correct' => 0,
                        'expl' => '« l\' » (1) + « Afrique » (2) + « de » (3) + « l\' » (4) + « ouest » (5) = 5 mots.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Réduction d\'un paragraphe de 60 mots à 15 mots',
                'xp' => 50,
                'statement' => 'Réduire le paragraphe suivant (55 mots) en une seule phrase fluide de 12 à 15 mots en conservant l\'idée essentielle :<br>« Les nouvelles technologies de l\'information et de la communication bouleversent les habitudes ancestrales sur le continent africain, créant chez les jeunes des opportunités économiques inédites tout en menaçant certaines traditions orales séculaires. »',
                'hint' => 'Identifie : NTIC en Afrique -> opportunités pour la jeunesse MAIS menace sur les traditions.',
                'solution' => '« En Afrique, les technologies numériques créent des opportunités pour la jeunesse tout en fragilisant les traditions orales. » (15 mots).',
                'bareme' => 'Fidélité au sens : 2.5 pts · Respect du nombre de mots [12-15] : 2.5 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Discussion argumentée BAC',
                'xp' => 100,
                'statement' => '« La tradition est-elle un obstacle au progrès économique de l\'Afrique ? »<br>Rédiger les deux axes antithétiques du plan dialectique en fournissant pour chacun un argument solide et un exemple togolais ou africain pertinent.',
                'bareme' => 'Axe 1 (Tradition frein) : 3 pts · Axe 2 (Tradition levier) : 3 pts.',
                'solution' => 'Axe 1 : La tradition peut constituer un frein lorsqu\'elle sclérose l\'innovation ou maintient des pesanteurs sociales (ex : réticences face à la scolarisation féminine ou à la bancarisation moderne dans certaines zones rurales).<br>Axe 2 : Mais la tradition est un levier puissant d\'économie solidaire et de cohésion sociale (ex : le système traditionnel des tontines au Togo et les valeurs communautaires de solidarité qui pallient l\'absence de sécurité sociale étatique).',
            ],
            'suggestions' => [
                'Comment compter précisément les mots avec apostrophes et tirets ?',
                'Quel est le plan idéal pour réussir la discussion au BAC ?',
                'Quels exemples africains citer pour illustrer le développement ?',
            ],
        ];
    }

    private function getFrancaisCommentaireCourseData(): array
    {
        return [
            'code' => 'FR_TLE_MOD_02',
            'title' => 'Le Commentaire Composé Littéraire',
            'subject' => 'Français',
            'ref' => 'MEPST Togo · Programme Officiel Terminales A, C, D · Manuel National',
            'coeff_badge' => 'Coeff. 3 / Coeff. 5 · Épreuve Littéraire',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Dégager le sens et la portée d'un texte littéraire (poésie négro-africaine, roman, théâtre).",
                "Articuler l'analyse stylistique (figures de style, rythmes, champs lexicaux) avec l'interprétation du sens.",
                "Structurer le devoir en 2 ou 3 axes de lecture cohérents.",
            ],
            'definitions' => [
                [
                    'term' => 'Commentaire composé',
                    'definition' => 'Exercice d\'analyse littéraire où le candidat expose une interprétation personnelle et argumentée d\'un texte, en liant constamment la forme (procédés stylistiques) et le fond (le sens).',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Règle d\'or du commentaire littéraire',
                    'latex' => '\\text{Procédé stylistique (Forme)} + \\text{Citation} \\iff \\text{Effet produit / Interprétation (Fond)}',
                    'conditions' => 'Ne jamais faire de paraphrase ni de catalogue de figures sans analyse du sens.',
                    'comment' => 'Une citation sans analyse stylistique ne rapporte aucun point.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Analyser le vers de Léopold Sédar Senghor : « Femme nue, femme noire / Vêtue de ta couleur qui est vie, de ta forme qui est beauté »',
                'steps' => [
                    "1. Procédé : Anaphore de « femme », métaphore de la couleur assimilée à la vie.",
                    "2. Effet : Célébration incantatoire de la femme africaine, érigée en symbole matriciel de la négritude et de la beauté vitale.",
                ],
                'conclusion' => "Le poète sacralise l'Afrique à travers la figure féminine.",
            ],
            'rules' => [
                [
                    'tag' => 'Conseil',
                    'title' => 'Ne jamais suivre le texte chronologiquement ligne par ligne',
                    'content' => 'Le commentaire composé regroupe des remarques thématiques et transversales réparties dans les axes de lecture.',
                ],
            ],
            'trap' => 'Raconter l\'histoire du texte sans analyser les figures de style.',
            'redaction_tip' => 'Utilisez des formules d\'intégration de citations : « À travers la métaphore filée de... l\'auteur met en relief... »',
            'formula_sheet' => [
                ['name' => 'Structure d\'axe', 'formula' => '1 \\text{ axe} = 2 \\text{ ou } 3 \\text{ sous-parties (arguments + citations)}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Figures de Style',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Quelle figure de style répète un même mot au début de vers successifs ?',
                        'options' => ['A. L\'anaphore', 'B. L\'oxymore', 'C. L\'hyperbole', 'D. L\'antithèse'],
                        'correct' => 0,
                        'expl' => 'L\'anaphore consiste en la répétition d\'un même mot en tête de phrase ou de vers.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Une formule comme « cet obscur éclat » constitue :',
                        'options' => ['A. Un oxymore', 'B. Une litote', 'C. Une allitération', 'D. Un pléonasme'],
                        'correct' => 0,
                        'expl' => 'L\'oxymore unit deux termes de sens opposés dans un même groupe syntaxique.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Dans un commentaire littéraire, doit-on analyser le texte strophe par strophe ?',
                        'options' => ['A. Non, le plan doit être thématique et composé', 'B. Oui, toujours de façon linéaire', 'C. Uniquement pour le théâtre', 'D. Selon l\'humeur'],
                        'correct' => 0,
                        'expl' => 'Le commentaire est dit « composé » car il organise la réflexion par thèmes et non de façon chronologique.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Élaboration de deux centres d\'intérêt',
                'xp' => 50,
                'statement' => 'Sur un poème de David Diop célébrant l\'Afrique qui souffre mais qui renaît, proposez deux axes de lecture pertinents.',
                'hint' => 'Axe 1 : Le tableau de l\'Afrique colonisée et souffrante. Axe 2 : Le chant d\'espoir et de rédemption.',
                'solution' => 'Axe 1 : L\'évocation pathétique d\'une Afrique meurtrie par les chaînes de l\'histoire coloniale.<br>Axe 2 : Le dynamisme prophétique d\'une terre en pleine résurrection vers la liberté.',
                'bareme' => 'Pertinence des deux axes : 5 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Rédaction d\'une sous-partie rédigée',
                'xp' => 100,
                'statement' => 'Rédiger une sous-partie démontrant que la répétition du mot « Afrique » dans le poème de David Diop donne au texte une tonalité hymnique et sacrée.',
                'bareme' => 'Qualité de l\'argumentation : 3 pts · Richesse du style et vocabulaire critique : 3 pts.',
                'solution' => 'L\'apostrophe fervente « Afrique mon Afrique » qui ouvre le texte confère d\'emblée au poème l\'allure d\'un hymne liturgique. Par la répétition obsessionnelle de ce toponyme érigé en être vivant, le poète s\'adresse à la terre natale comme à une divinité tutélaire. Cette récurrence scandée rythme le texte à la manière d\'un tambour de guerre, transformant la parole poétique en acte d\'adoration et de réarmement patriotique.',
            ],
            'suggestions' => [
                'Comment trouver les deux axes de lecture d\'un texte inconnu ?',
                'Quels sont les thèmes phares de la Négritude chez Césaire et Senghor ?',
                'Comment analyser le rythme d\'un poème en vers libres ?',
            ],
        ];
    }

    private function getHistGeoTogoCourseData(): array
    {
        return [
            'code' => 'HG_TLE_D_CH01',
            'title' => 'L\'Économie Togolaise & l\'Intégration Régionale CEDEAO',
            'subject' => 'Histoire et Géographie',
            'ref' => 'MEPST Togo · Programme Officiel Terminales · Manuel National',
            'coeff_badge' => 'Coeff. 2 (Série D) / Coeff. 4 (Série A4) · Épreuve Officielle',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Identifier les atouts et vulnérabilités de l'économie togolaise (agriculture vivrière et de rente, phosphates, secteur tertiaire).",
                "Analyser le rôle géostratégique du Port Autonome de Lomé (PAL) comme hub maritime et port en eau profonde pour les pays de l'hinterland (Burkina Faso, Mali, Niger).",
                "Comprendre les dynamiques d'intégration régionale dans l'espace CEDEAO et UEMOA (libre circulation, tarif extérieur commun).",
            ],
            'definitions' => [
                [
                    'term' => 'Pays de l\'hinterland',
                    'definition' => 'Pays enclavés de l\'intérieur du continent sans littoral maritime direct (Burkina Faso, Mali, Niger) qui dépendent des corridors routiers et des ports côtiers comme Lomé pour leurs importations et exportations.',
                ],
                [
                    'term' => 'Port Autonome de Lomé (PAL)',
                    'definition' => 'Seul port naturel en eau profonde de la côte ouest-africaine (tirant d\'eau de plus de 15 mètres), capable d\'accueillir les porte-conteneurs géants de dernière génération, faisant de Lomé le premier port à conteneurs d\'Afrique de l\'Ouest.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Dynamique du Corridor Lomé-Ouagadougou',
                    'latex' => '\\text{Trafic maritime (PAL)} \\xrightarrow{\\text{Corridor RN1}} \\text{Hinterland (Burkina Faso, Mali, Niger)}',
                    'conditions' => 'Axe routier national vital traversant le Togo du sud au nord.',
                    'comment' => 'Moteur économique majeur représentant plus de 80% du commerce extérieur togolais.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Quels sont les atouts majeurs qui ont permis au Port Autonome de Lomé de s\'imposer comme le premier port de transbordement en Afrique de l\'Ouest ?',
                'steps' => [
                    "1. Atout naturel : Tirant d'eau naturel de plus de 15 mètres permettant d'accueillir des navires à fort tonnage sans dragage constant.",
                    "2. Atouts logistiques : Installation de terminaux modernes (Lomé Container Terminal - LCT et Togo Terminal).",
                    "3. Atout géographique : Position de carrefour au cœur du golfe de Guinée avec accès direct aux pays sans littoral par la Route Nationale 1.",
                    "4. Réformes administratives : Dématérialisation des formalités douanières (guichet unique) réduisant le temps de passage des marchandises.",
                ],
                'conclusion' => "Le PAL est le poumon économique du Togo et un carrefour logistique sous-régional incontournable.",
            ],
            'rules' => [
                [
                    'tag' => 'Méthodologie géo',
                    'title' => 'Toujours lier atouts physiques et aménagements humains',
                    'content' => 'En géographie du Togo, ne jamais citer un atout naturel sans montrer comment les politiques publiques d\'aménagement l\'ont valorisé.',
                ],
            ],
            'trap' => 'Oublier le secteur informel et l\'agriculture vivrière qui emploient pourtant plus de 60% de la population active togolaise.',
            'redaction_tip' => 'Structurez votre réponse selon la grille canonique : Atouts naturels -> Facteurs humains et institutionnels -> Défis et perspectives de développement.',
            'formula_sheet' => [
                ['name' => 'PIB Togo', 'formula' => '\\text{Agriculture (40\\%), Services/Commerce (45\\%), Industrie (15\\%)}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Économie Togolaise',
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => 'Quelle caractéristique physique distingue le Port Autonome de Lomé des autres ports de la sous-région ?',
                        'options' => ['A. C\'est un port naturel en eau profonde (> 15 m)', 'B. Il est situé sur un fleuve navigable', 'C. Il est le plus ancien port colonial', 'D. Il est réservé au pétrole'],
                        'correct' => 0,
                        'expl' => 'Le tirant d\'eau naturel exceptionnel du PAL permet d\'accueillir les plus grands porte-conteneurs.',
                    ],
                    [
                        'id' => 2,
                        'prompt' => 'Quels sont les principaux pays de l\'hinterland desservis par le corridor togolais ?',
                        'options' => ['A. Burkina Faso, Mali, Niger', 'B. Ghana, Bénin, Nigeria', 'C. Sénégal, Côte d\'Ivoire', 'D. Tchad, Cameroun'],
                        'correct' => 0,
                        'expl' => 'Ce sont les pays enclavés d\'Afrique de l\'Ouest sans façade maritime.',
                    ],
                    [
                        'id' => 3,
                        'prompt' => 'Quelle ressource minière historique constitue le premier produit d\'exportation minier du Togo ?',
                        'options' => ['A. Les phosphates', 'B. L\'or', 'C. Le pétrole brut', 'D. L\'uranium'],
                        'correct' => 0,
                        'expl' => 'Le Togo dispose d\'importants gisements de phosphates exploités notamment par la SNPT à Kpémé.',
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Analyse de document géographique',
                'xp' => 50,
                'statement' => 'À partir de vos connaissances, montrez en quelques lignes comment l\'agriculture togolaise, bien qu\'étant le moteur nourricier du pays, fait face à des défis climatiques et structurels majeurs.',
                'hint' => 'Mentionne : dépendance aux pluies, faible mécanisation, dégradation des sols, aléas climatiques.',
                'solution' => 'L\'agriculture togolaise assure l\'autosuffisance vivrière (maïs, manioc, igname) et génère des devises (coton, café, cacao). Cependant, elle reste vulnérable en raison d\'une forte dépendance à la pluviométrie, d\'un outillage traditionnel (houe et coupe-coupe) et d\'un accès limité aux intrants et aux crédits agricoles. Les dérèglements climatiques (sécheresses et inondations) accentuent l\'insécurité des rendements.',
                'bareme' => 'Atouts agricoles : 2.5 pts · Défis climatiques et matériels : 2.5 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Sujet de composition BAC HG',
                'xp' => 100,
                'statement' => '« Les corridors de transport et l\'intégration économique en Afrique de l\'Ouest : le cas du Togo. »<br>Dresser les grands axes de réflexion d\'une composition géographique conforme au programme de Terminale.',
                'bareme' => 'Richesse des exemples et rigueur géographique : 6 pts.',
                'solution' => 'I. La position géographique stratégique du Togo : façade maritime ouverte sur l\'Atlantique et pont naturel vers l\'hinterland sahélien.<br>II. Le rôle central des infrastructures de transport : modernisation du PAL, aménagement du corridor routier RN1, plateformes logistiques (PIA d\'Adétikopé).<br>III. Défis et retombées régionales : fluidification des postes-frontières avec le Burkina Faso, concurrence des ports voisins (Téma, Cotonou, Abidjan) et impact sur le développement économique national.',
            ],
            'suggestions' => [
                'Pourquoi le Port de Lomé est-il devenu leader du transbordement ?',
                'Quels sont les objectifs de la Plateforme Industrielle d\'Adétikopé (PIA) ?',
                'Quels sont les défis de l\'intégration monétaire dans la CEDEAO ?',
            ],
        ];
    }

    private function getArithmetiqueCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_ARITH',
            'title' => 'Arithmétique dans Z : Divisibilité, Congruences, Bézout et Gauss',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale C & D',
            'coeff_badge' => 'Coeff. 4 · Arithmétique BAC 2',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Maîtriser la division euclidienne dans Z et les propriétés des congruences modulo n.",
                "Appliquer l'algorithme d'Euclide pour déterminer le PGCD et une identité de Bézout.",
                "Résoudre dans Z² des équations diophantiennes linéaires ax + by = c à l'aide des théorèmes de Bézout et de Gauss.",
            ],
            'definitions' => [
                [
                    'term' => 'Division euclidienne dans Z',
                    'definition' => 'Pour tout entier relatif $a \in \mathbb{Z}$ et tout entier naturel non nul $b \in \mathbb{N}^*$, il existe un unique couple $(q, r) \in \mathbb{Z} \times \mathbb{N}$ tel que $a = bq + r$ avec $0 \le r < b$.',
                ],
                [
                    'term' => 'Congruence modulo n',
                    'definition' => 'Deux entiers $a$ et $b$ sont congrus modulo $n$ ($n \in \mathbb{N}^*$, noté $a \equiv b \pmod n$) si et seulement si $n$ divise $(a - b)$, c\'est-à-dire s\'ils ont le même reste dans la division euclidienne par $n$.',
                ],
                [
                    'term' => 'Nombres premiers entre eux',
                    'definition' => 'Deux entiers non nuls $a$ et $b$ sont dits premiers entre eux si leur plus grand commun diviseur est égal à 1 : $\text{PGCD}(a, b) = 1$.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Théorème de Bézout',
                    'latex' => '\text{PGCD}(a, b) = 1 \iff \exists (u, v) \in \mathbb{Z}^2 \text{ tel que } au + bv = 1',
                    'conditions' => '$a$ et $b$ sont deux entiers relatifs non nuls.',
                    'comment' => 'C\'est une équivalence fondamentale : elle caractérise les nombres premiers entre eux.',
                ],
                [
                    'title' => 'Théorème de Gauss',
                    'latex' => '\left( a \mid bc \text{ et } \text{PGCD}(a, b) = 1 \right) \implies a \mid c',
                    'conditions' => 'Il est obligatoire de vérifier et d\'écrire que $\text{PGCD}(a, b) = 1$ avant de conclure que $a$ divise $c$.',
                    'comment' => 'Indispensable pour l\'étape d\'analyse dans la résolution de $ax + by = c$.',
                ],
                [
                    'title' => 'Petit Théorème de Fermat',
                    'latex' => 'p \text{ premier et } p \nmid a \implies a^{p-1} \equiv 1 \pmod p',
                    'conditions' => '$p$ est un nombre premier et $a$ est un entier non divisible par $p$.',
                    'comment' => 'Très utile pour déterminer le reste de grandes puissances modulo un nombre premier.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Résoudre dans $\mathbb{Z}^2$ l\'équation $(E) : 7x - 5y = 1$.',
                'steps' => [
                    "1. Existence de solutions : \$\text{PGCD}(7, 5) = 1\$ car 5 et 7 sont premiers entre eux. L'équation admet donc des solutions dans \$\mathbb{Z}^2\$.",
                    "2. Solution particulière : On remarque que \$7(3) - 5(4) = 21 - 20 = 1\$. Le couple \$(x_0, y_0) = (3, 4)\$ est une solution particulière.",
                    "3. Équation homogène associée : \$7x - 5y = 7(3) - 5(4) \iff 7(x - 3) = 5(y - 4)\$.",
                    "4. Application du théorème de Gauss : \$5\$ divise \$7(x - 3)\$ et \$\text{PGCD}(5, 7) = 1\$, donc d'après le théorème de Gauss, \$5\$ divise \$(x - 3)\$. Il existe \$k \in \mathbb{Z}\$ tel que \$x - 3 = 5k\$, soit \$x = 3 + 5k\$.",
                    "5. Substitution et réciproque : En remplaçant, \$7(5k) = 5(y - 4) \implies y - 4 = 7k \implies y = 4 + 7k\$. Réciproquement, \$7(3+5k) - 5(4+7k) = 1\$.",
                ],
                'conclusion' => "L'ensemble des solutions dans \$\mathbb{Z}^2\$ est \$S = \{ (3 + 5k, \; 4 + 7k), \; k \in \mathbb{Z} \}\$.",
            ],
            'rules' => [
                ['tag' => 'Méthode BAC', 'title' => 'Tableau des restes modulo n', 'content' => 'Pour démontrer qu\'une expression est divisible par $n$, dressez le tableau des restes de $x \pmod n$ et montrez que le reste final vaut toujours $0$.'],
            ],
            'trap' => 'Oublier de préciser que PGCD(a, b) = 1 lors de l\'utilisation du théorème de Gauss, ou oublier de vérifier la réciproque dans l\'équation diophantienne.',
            'redaction_tip' => 'Précisez toujours « avec k appartenant à Z » dans l\'écriture finale de l\'ensemble des solutions.',
            'formula_sheet' => [
                ['name' => 'Identité de Bézout', 'formula' => 'au + bv = \text{PGCD}(a,b)'],
                ['name' => 'Relation PGCD-PPCM', 'formula' => '\text{PGCD}(a,b) \times \text{PPCM}(a,b) = |ab|'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Arithmétique (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Quel est le reste de la division euclidienne de -17 par 5 ?', 'options' => ['A. -2', 'B. 2', 'C. 3', 'D. -3'], 'correct' => 2, 'expl' => 'On a -17 = 5 × (-4) + 3 avec 0 ≤ 3 < 5. Le reste est toujours positif ou nul !'],
                    ['id' => 2, 'prompt' => 'L\'équation 6x + 9y = 2 admet-elle des solutions dans Z² ?', 'options' => ['A. Oui, une infinité', 'B. Non, aucune', 'C. Une unique solution', 'D. Seulement dans N²'], 'correct' => 1, 'expl' => 'PGCD(6, 9) = 3, or 3 ne divise pas 2. L\'équation n\'admet donc aucune solution entière.'],
                    ['id' => 3, 'prompt' => 'Que vaut 2^6 modulo 7 d\'après le petit théorème de Fermat ?', 'options' => ['A. 0', 'B. 1', 'C. 2', 'D. 6'], 'correct' => 1, 'expl' => '7 est premier et ne divise pas 2, donc 2^(7-1) = 2^6 ≡ 1 (mod 7).'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Équation diophantienne',
                'xp' => 50,
                'statement' => 'Déterminer le PGCD de 15 et 8 puis trouver une solution particulière entière de $15x - 8y = 1$.',
                'hint' => 'Compare $15 \times 1$ et $8 \times 2$.',
                'solution' => '$\text{PGCD}(15, 8) = 1$. Comme $15 \times (-1) - 8 \times (-2) = -15 + 16 = 1$, le couple $(-1, -2)$ (ou $(7, 13)$) est solution particulière.',
                'bareme' => 'PGCD : 1 pt | Couple solution vérifié : 2 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Problème d\'Arithmétique Type BAC',
                'xp' => 100,
                'statement' => 'Démontrer que pour tout entier naturel $n$, l\'entier $N = n(n^2 - 1)$ est divisible par $6$.',
                'bareme' => 'Factorisation : 2 pts | Étude de la parité et modulo 3 : 4 pts.',
                'solution' => '$N = (n-1)n(n+1)$ est le produit de trois entiers consécutifs : il contient au moins un multiple de 2 et un multiple de 3. Comme $\text{PGCD}(2, 3) = 1$, d\'après le corollaire du théorème de Gauss, $N$ est divisible par $2 \times 3 = 6$.',
            ],
            'suggestions' => [
                'Comment remonter l\'algorithme d\'Euclide sans erreur de signe ?',
                'Explique-moi le théorème de Gauss avec un exemple simple',
                'Comment utiliser un tableau de congruences au BAC ?',
            ],
        ];
    }

    private function getCalculsAlgebriquesCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_ALG',
            'title' => 'Calculs Algébriques : Polynômes, Équations et Systèmes Linéaires',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D',
            'coeff_badge' => 'Coeff. 4 · Base Fondamentale BAC 2',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Factoriser un polynôme de degré 3 connaissant une racine évidente par division euclidienne ou méthode de Horner.",
                "Résoudre les équations et inéquations du second degré et bicarrées dans R.",
                "Résoudre les systèmes d'équations linéaires dans R² et R³ par la méthode du pivot de Gauss.",
            ],
            'definitions' => [
                [
                    'term' => 'Racine d\'un polynôme P(x)',
                    'definition' => 'Un réel $\alpha$ est dit racine (ou zéro) du polynôme $P$ si et seulement si $P(\alpha) = 0$. Dans ce cas, $P(x)$ est factorisable par $(x - \alpha)$.',
                ],
                [
                    'term' => 'Discriminant du trinôme ax² + bx + c',
                    'definition' => 'Le réel $\Delta = b^2 - 4ac$ détermine l\'existence des racines réelles et le signe du trinôme sur $\mathbb{R}$.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Factorisation d\'un polynôme de degré n',
                    'latex' => 'P(\alpha) = 0 \iff \exists Q(x) \text{ de degré } (n-1) \text{ tel que } P(x) = (x - \alpha) Q(x)',
                    'conditions' => '$P$ est un polynôme de degré $n \ge 1$.',
                    'comment' => 'Permet de ramener une équation de degré 3 à une équation du second degré.',
                ],
                [
                    'title' => 'Somme et Produit des racines d\'un trinôme',
                    'latex' => 'S = x_1 + x_2 = -\frac{b}{a} \quad ; \quad P = x_1 x_2 = \frac{c}{a}',
                    'conditions' => 'Lorsque $\Delta \ge 0$ et $a \neq 0$.',
                    'comment' => 'Permet de trouver immédiatement la seconde racine lorsqu\'une racine évidente est connue.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Soit $P(x) = 2x^3 - 3x^2 - 11x + 6$. Vérifier que $2$ est racine de $P$, puis résoudre $P(x) = 0$ dans $\mathbb{R}$.',
                'steps' => [
                    "1. Vérification : \$P(2) = 2(8) - 3(4) - 11(2) + 6 = 16 - 12 - 22 + 6 = -12 \neq 0\$ ; testons \$x = -2\$ : \$P(-2) = 2(-8) - 3(4) - 11(-2) + 6 = -16 - 12 + 22 + 6 = 0\$. Donc \$-2\$ est racine.",
                    "2. Factorisation : \$P(x) = (x + 2)(2x^2 - 7x + 3)\$.",
                    "3. Résolution de \$2x^2 - 7x + 3 = 0\$ : \$\Delta = (-7)^2 - 4(2)(3) = 49 - 24 = 25 = 5^2\$. Les racines sont \$x_1 = \frac{7 - 5}{4} = \frac{1}{2}\$ et \$x_2 = \frac{7 + 5}{4} = 3\$.",
                ],
                'conclusion' => "L'ensemble des solutions dans \$\mathbb{R}\$ est \$S = \left\{ -2 ; \; \frac{1}{2} ; \; 3 \right\}\$.",
            ],
            'rules' => [
                ['tag' => 'Pivot de Gauss', 'title' => 'Systèmes 3×3 dans R³', 'content' => 'Conservez la première ligne comme pivot pour éliminer $x$ dans $L_2$ et $L_3$, puis éliminez $y$ dans $L_3$ pour obtenir un système triangulaire.'],
            ],
            'trap' => 'Se tromper de signe dans le tableau de signes d\'un produit $(x - \alpha)(ax^2 + bx + c)$.',
            'redaction_tip' => 'Écrivez toujours clairement la phrase : « Le trinôme est du signe de a à l\'extérieur des racines ».',
            'formula_sheet' => [
                ['name' => 'Discriminant', 'formula' => '\Delta = b^2 - 4ac'],
                ['name' => 'Racines réelles', 'formula' => 'x_{1,2} = \frac{-b \pm \sqrt{\Delta}}{2a}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Calculs Algébriques (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Si x₁ et x₂ sont les racines de x² - 5x + 6 = 0, que vaut x₁ + x₂ ?', 'options' => ['A. -5', 'B. 5', 'C. 6', 'D. -6'], 'correct' => 1, 'expl' => 'La somme des racines vaut S = -b/a = -(-5)/1 = 5.'],
                    ['id' => 2, 'prompt' => 'Quel est le signe de -2x² + x - 5 sur R ?', 'options' => ['A. Toujours positif', 'B. Toujours strictement négatif', 'C. S\'annule deux fois', 'D. Positif entre les racines'], 'correct' => 1, 'expl' => 'Δ = 1 - 4(-2)(-5) = -39 < 0, donc le trinôme est partout du signe de a = -2 < 0.'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Factorisation d\'un polynôme de degré 3',
                'xp' => 50,
                'statement' => 'Soit $P(x) = x^3 - 7x + 6$.Calculer $P(1)$ puis factoriser complètement $P(x)$.',
                'hint' => 'Pose $P(x) = (x - 1)(x^2 + ax + b)$.',
                'solution' => '$P(1) = 1 - 7 + 6 = 0$. Par identification, $P(x) = (x - 1)(x^2 + x - 6) = (x - 1)(x - 2)(x + 3)$.',
                'bareme' => 'Calcul de P(1) : 1 pt | Factorisation complète : 2 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Système linéaire dans R³',
                'xp' => 100,
                'statement' => 'Résoudre dans $\mathbb{R}^3$ le système : $x + y + z = 6$, $2x - y + z = 3$, $x + 2y - z = 2$.',
                'bareme' => 'Triangularisation de Gauss : 3 pts | Triplet solution : 3 pts.',
                'solution' => 'Par combinaisons linéaires, on obtient l\'unique triplet solution $(x, y, z) = (1, 2, 3)$.',
            ],
            'suggestions' => [
                'Montre-moi la méthode d\'identification des coefficients',
                'Comment résoudre un système 3x3 avec le pivot de Gauss ?',
                'Comment dresser le tableau de signes d\'une fraction rationnelle ?',
            ],
        ];
    }

    private function getApplicationsPlanCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_SIM',
            'title' => 'Applications du Plan : Homothéties, Isométries et Similitudes Directes',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale C & D',
            'coeff_badge' => 'Coeff. 4 · Transformations Planes',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Identifier l'écriture complexe \$z' = az + b\$ d'une translation, d'une rotation, d'une homothétie et d'une similitude directe.",
                "Déterminer les éléments caractéristiques d'une similitude plane directe : centre \$\Omega\$, rapport \$k = |a|\$ et angle \$\theta = \arg(a)\$.",
                "Étudier l'image de figures géométriques (droites, cercles) par une transformation du plan.",
            ],
            'definitions' => [
                [
                    'term' => 'Similitude plane directe',
                    'definition' => 'Toute transformation du plan qui multiplie les distances par un réel $k > 0$ et conserve les angles orientés. Son écriture complexe est de la forme $z\' = az + b$ avec $a \in \mathbb{C}^*$.',
                ],
                [
                    'term' => 'Isométrie du plan',
                    'definition' => 'Transformation du plan qui conserve les distances ($M\'N\' = MN$). Les translations, rotations et symétries orthogonales sont des isométries.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Éléments caractéristiques de z\' = az + b (a ≠ 1, a ≠ 0)',
                    'latex' => '\omega = \frac{b}{1 - a} \quad ; \quad k = |a| \quad ; \quad \theta \equiv \arg(a) \pmod{2\pi}',
                    'conditions' => '$a \in \mathbb{C} \setminus \{0, 1\}$. Si $a = 1$, c\'est une translation de vecteur d\'affixe $b$.',
                    'comment' => 'L\'écriture complexe se met alors sous la forme canonique $z\' - \omega = k e^{i\theta}(z - \omega)$.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Déterminer la nature et les éléments caractéristiques de la transformation $S$ d\'écriture complexe $z\' = (1 + i\sqrt{3})z + 2 - i\sqrt{3}$.',
                'steps' => [
                    "1. Identification de \$a\$ : \$a = 1 + i\sqrt{3} \neq 1\$. \$S\$ est donc une similitude plane directe.",
                    "2. Rapport \$k\$ : \$k = |a| = \sqrt{1^2 + (\sqrt{3})^2} = \sqrt{4} = 2\$.",
                    "3. Angle \$\theta\$ : \$a = 2\left(\frac{1}{2} + i\frac{\sqrt{3}}{2}\right) = 2e^{i\pi/3}\$, donc \$\theta = \frac{\pi}{3} \pmod{2\pi}\$.",
                    "4. Centre \$\Omega(\omega)\$ : On résout \$\omega = (1 + i\sqrt{3})\omega + 2 - i\sqrt{3} \iff -i\sqrt{3}\omega = -2 + i\sqrt{3} \iff \omega = 1 + \frac{2i}{\sqrt{3}}\$.",
                ],
                'conclusion' => "\$S\$ est la similitude directe de rapport \$k = 2\$, d'angle \$\theta = \frac{\pi}{3}\$ et de centre \$\Omega\$.",
            ],
            'rules' => [
                ['tag' => 'Cas particuliers', 'title' => 'Reconnaître une rotation ou une homothétie', 'content' => 'Si $a \in \mathbb{R}^* \setminus \{1\}$, c\'est une homothétie de rapport $a$. Si $|a| = 1$ ($a \neq 1$), c\'est une rotation d\'angle $\arg(a)$.'],
            ],
            'trap' => 'Confondre le rapport d\'une homothétie (qui peut être négatif) avec le rapport d\'une similitude directe $k = |a|$ qui est toujours strictement positif.',
            'redaction_tip' => 'Vérifiez toujours que le point fixe trouvé vérifie bien l\'équation $z\' = z$.',
            'formula_sheet' => [
                ['name' => 'Affixe du centre', 'formula' => '\omega = \frac{b}{1-a}'],
                ['name' => 'Forme réduite', 'formula' => 'z\' - \omega = a(z - \omega)'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Transformations du Plan (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Quelle est la nature de z\' = -2z + 3i ?', 'options' => ['A. Une translation', 'B. Une homothétie de rapport -2', 'C. Une rotation d\'angle π/2', 'D. Une symétrie axiale'], 'correct' => 1, 'expl' => 'Comme a = -2 est un réel différent de 0 et 1, c\'est une homothétie de rapport -2 (ou une similitude directe de rapport 2 et d\'angle π).'],
                    ['id' => 2, 'prompt' => 'Quel est le rapport de la similitude z\' = (3 - 3i)z + 1 ?', 'options' => ['A. 3', 'B. 3√2', 'C. 6', 'D. √6'], 'correct' => 1, 'expl' => 'k = |3 - 3i| = √(9 + 9) = √18 = 3√2.'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Éléments caractéristiques d\'une similitude',
                'xp' => 50,
                'statement' => 'Déterminer le centre, le rapport et l\'angle de la similitude directe d\'écriture complexe $z\' = 2iz + 1 + 2i$.',
                'hint' => 'Calcule $|2i|$, $\arg(2i)$ et résous $z = 2iz + 1 + 2i$.',
                'solution' => 'Rapport $k = |2i| = 2$, angle $\theta = \frac{\pi}{2} \pmod{2\pi}$, et centre $\Omega$ d\'affixe $\omega = \frac{1+2i}{1-2i} = \frac{(1+2i)^2}{5} = -\frac{3}{5} + \frac{4}{5}i$.',
                'bareme' => 'Rapport et angle : 1.5 pt | Affixe du centre : 1.5 pt.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Similitude envoyant deux points sur deux autres',
                'xp' => 100,
                'statement' => 'Soient $A(1)$, $B(i)$, $A\'(2i)$ et $B\'(-2)$. Déterminer l\'écriture complexe de la similitude directe $S$ telle que $S(A) = A\'$ et $S(B) = B\'$.',
                'bareme' => 'Système en a et b : 3 pts | Éléments caractéristiques : 3 pts.',
                'solution' => 'On a $a = \frac{z_{B\'} - z_{A\'}}{z_B - z_A} = \frac{-2 - 2i}{i - 1} = 2i$, puis $b = z_{A\'} - a z_A = 2i - 2i(1) = 0$. D\'où $z\' = 2iz$.',
            ],
            'suggestions' => [
                'Comment distinguer une rotation d\'une similitude directe ?',
                'Comment trouver l\'image d\'un cercle par une similitude ?',
                'Explique-moi la forme canonique d\'une similitude plane',
            ],
        ];
    }

    private function getGeometrieEspaceCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_ESP',
            'title' => 'Géométrie dans l\'Espace : Orthogonalité, Plans et Produit Vectoriel',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale C & D',
            'coeff_badge' => 'Coeff. 4 · Géométrie Analytique 3D',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Calculer le produit scalaire et le produit vectoriel de deux vecteurs dans un repère orthonormé direct de l'espace.",
                "Déterminer une équation cartésienne d'un plan et une représentation paramétrique d'une droite.",
                "Calculer la distance d'un point à un plan, l'aire d'un triangle et le volume d'un tétraèdre.",
            ],
            'definitions' => [
                [
                    'term' => 'Vecteur normal à un plan',
                    'definition' => 'Un vecteur non nul $\vec{n}(a, b, c)$ est normal à un plan $\mathcal{P}$ s\'il est orthogonal à tout vecteur directeur de ce plan. Le plan $\mathcal{P}$ admet alors une équation cartésienne de la forme $ax + by + cz + d = 0$.',
                ],
                [
                    'term' => 'Produit vectoriel u ∧ v',
                    'definition' => 'Pour deux vecteurs non colinéaires $\vec{u}$ et $\vec{v}$, $\vec{w} = \vec{u} \wedge \vec{v}$ est l\'unique vecteur orthogonal à la fois à $\vec{u}$ et à $\vec{v}$ tel que $(\vec{u}, \vec{v}, \vec{w})$ soit une base directe et $\|\vec{w}\| = \|\vec{u}\| \cdot \|\vec{v}\| \cdot |\sin(\vec{u}, \vec{v})|$.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Distance d\'un point M₀(x₀, y₀, z₀) à un plan (P) : ax + by + cz + d = 0',
                    'latex' => 'd(M_0, \mathcal{P}) = \frac{|a x_0 + b y_0 + c z_0 + d|}{\sqrt{a^2 + b^2 + c^2}}',
                    'conditions' => 'Le repère $(O; \vec{i}, \vec{j}, \vec{k})$ doit être orthonormé.',
                    'comment' => 'Permet de calculer immédiatement la hauteur d\'un tétraèdre ou le rayon d\'une sphère tangente à un plan.',
                ],
                [
                    'title' => 'Aire d\'un triangle ABC et Volume d\'un tétraèdre ABCD',
                    'latex' => '\mathcal{A}_{ABC} = \frac{1}{2} \|\overrightarrow{AB} \wedge \overrightarrow{AC}\| \quad ; \quad \mathcal{V}_{ABCD} = \frac{1}{6} \left| (\overrightarrow{AB} \wedge \overrightarrow{AC}) \cdot \overrightarrow{AD} \right|',
                    'conditions' => 'En unités d\'aire (u.a.) et unités de volume (u.v.) du repère orthonormé.',
                    'comment' => 'Formule ultra-efficace aux épreuves du BAC togolais.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Dans l\'espace muni d\'un repère orthonormé direct, on donne $A(1, 0, 2)$, $B(2, 1, 1)$ et $C(0, 2, 1)$. Démontrer que $A, B, C$ définissent un plan $(ABC)$ et déterminer son équation cartésienne.',
                'steps' => [
                    "1. Coordonnées des vecteurs : \$\overrightarrow{AB}(1, 1, -1)\$ et \$\overrightarrow{AC}(-1, 2, -1)\$.",
                    "2. Calcul du produit vectoriel \$\vec{n} = \overrightarrow{AB} \wedge \overrightarrow{AC}\$ : \$x_n = (1)(-1) - (-1)(2) = 1\$, \$y_n = (-1)(-1) - (1)(-1) = 2\$, \$z_n = (1)(2) - (1)(-1) = 3\$. Donc \$\vec{n}(1, 2, 3) \neq \vec{0}\$. Les points \$A, B, C\$ ne sont pas alignés.",
                    "3. Équation du plan \$(ABC)\$ : \$\vec{n}(1, 2, 3)\$ est normal au plan, donc \$(ABC) : x + 2y + 3z + d = 0\$.",
                    "4. Détermination de \$d\$ avec \$A(1, 0, 2) \in (ABC)\$ : \$1 + 0 + 3(2) + d = 0 \implies d = -7\$.",
                ],
                'conclusion' => "Une équation cartésienne du plan \$(ABC)\$ est \$x + 2y + 3z - 7 = 0\$.",
            ],
            'rules' => [
                ['tag' => 'Orthogonalité', 'title' => 'Droite orthogonale à un plan', 'content' => 'Pour montrer qu\'une droite $(D)$ est orthogonale à un plan $(P)$, il suffit de montrer qu\'elle est orthogonale à deux droites sécantes de ce plan.'],
            ],
            'trap' => 'Confondre l\'équation d\'une droite dans le plan ($ax+by+c=0$) avec l\'espace où $ax+by+cz+d=0$ représente un PLAN et non une droite !',
            'redaction_tip' => 'Vérifiez toujours votre produit vectoriel en calculant $\vec{n} \cdot \overrightarrow{AB} = 0$ et $\vec{n} \cdot \overrightarrow{AC} = 0$ au brouillon.',
            'formula_sheet' => [
                ['name' => 'Distance point-plan', 'formula' => 'd(A,P) = \frac{|ax_A+by_A+cz_A+d|}{\sqrt{a^2+b^2+c^2}}'],
                ['name' => 'Aire triangle', 'formula' => '\mathcal{A} = \frac{1}{2}\|\overrightarrow{AB} \wedge \overrightarrow{AC}\|'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Géométrie de l\'Espace (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Quel est un vecteur normal au plan d\'équation 2x - y + 4z - 5 = 0 ?', 'options' => ['A. (2, 1, 4)', 'B. (2, -1, 4)', 'C. (-1, 4, -5)', 'D. (2, -1, -5)'], 'correct' => 1, 'expl' => 'Les coefficients de x, y, z donnent directement les coordonnées du vecteur normal n(2, -1, 4).'],
                    ['id' => 2, 'prompt' => 'Si u ∧ v = 0 (vecteur nul), alors les vecteurs u et v sont :', 'options' => ['A. Orthogonaux', 'B. Colinéaires', 'C. De même norme', 'D. Unitaires'], 'correct' => 1, 'expl' => 'Le produit vectoriel de deux vecteurs est nul si et seulement s\'ils sont colinéaires.'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Distance d\'un point à un plan',
                'xp' => 50,
                'statement' => 'Calculer la distance du point $I(1, -1, 2)$ au plan $(P)$ d\'équation $2x + 2y - z + 3 = 0$.',
                'hint' => 'Applique la formule $\frac{|2x_I + 2y_I - z_I + 3|}{\sqrt{2^2 + 2^2 + (-1)^2}}$.',
                'solution' => '$d(I, P) = \frac{|2(1) + 2(-1) - 2 + 3|}{\sqrt{4 + 4 + 1}} = \frac{|1|}{\sqrt{9}} = \frac{1}{3}$.',
                'bareme' => 'Formule : 1 pt | Calcul exact : 2 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Volume d\'un tétraèdre au BAC',
                'xp' => 100,
                'statement' => 'On reprend le plan $(ABC) : x + 2y + 3z - 7 = 0$ avec $\overrightarrow{AB} \wedge \overrightarrow{AC} = (1, 2, 3)$, et on donne $D(2, 1, 4)$. Calculer l\'aire du triangle $ABC$ et le volume du tétraèdre $ABCD$.',
                'bareme' => 'Aire de ABC : 3 pts | Volume du tétraèdre : 3 pts.',
                'solution' => '$\mathcal{A}_{ABC} = \frac{1}{2}\sqrt{1^2+2^2+3^2} = \frac{\sqrt{14}}{2}\text{ u.a.}$ et $\mathcal{V}_{ABCD} = \frac{1}{6}|1(1) + 2(1) + 3(2)| = \frac{9}{6} = \frac{3}{2}\text{ u.v.}$',
            ],
            'suggestions' => [
                'Comment calculer rapidement un produit vectoriel sans erreur ?',
                'Comment trouver l\'intersection d\'une droite et d\'un plan ?',
                'Quelle est l\'équation cartésienne d\'une sphère ?',
            ],
        ];
    }

    private function getGeometriePlanCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_BARY',
            'title' => 'Géométrie du Plan : Barycentres, Lignes de Niveau, Droites et Cercles',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale C & D',
            'coeff_badge' => 'Coeff. 4 · Barycentres & Lieux Géométriques',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Déterminer et construire le barycentre de n points pondérés à l'aide de la relation vectorielle fondamentale et de l'associativité.",
                "Réduire une somme vectorielle \$\sum \alpha_i \overrightarrow{MA_i}\$ pour déterminer des ensembles de points (lignes de niveau).",
                "Étudier les lignes de niveau de la forme \$MA^2 + MB^2 = k\$ ou \$\overrightarrow{MA} \cdot \overrightarrow{MB} = k\$.",
            ],
            'definitions' => [
                [
                    'term' => 'Barycentre de n points pondérés',
                    'definition' => 'Si $\sum_{i=1}^n \alpha_i \neq 0$, il existe un unique point $G$ tel que $\sum_{i=1}^n \alpha_i \overrightarrow{GA_i} = \vec{0}$. Pour tout point $M$ du plan, $\sum_{i=1}^n \alpha_i \overrightarrow{MA_i} = \left(\sum_{i=1}^n \alpha_i\right) \overrightarrow{MG}$.',
                ],
                [
                    'term' => 'Ligne de niveau k d\'une application f',
                    'definition' => 'Ensemble des points $M$ du plan tels que $f(M) = k$, où $k$ est une constante réelle donnée.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Théorème de la médiane (Apollonius)',
                    'latex' => 'MA^2 + MB^2 = 2 MI^2 + \frac{AB^2}{2} \quad ; \quad \overrightarrow{MA} \cdot \overrightarrow{MB} = MI^2 - \frac{AB^2}{4}',
                    'conditions' => '$I$ est le milieu du segment $[AB]$.',
                    'comment' => 'Ramène immédiatement l\'étude de $M$ à la distance $MI$ par rapport au point fixe $I$.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Soient $A$ et $B$ deux points tels que $AB = 4$. Déterminer l\'ensemble $(E)$ des points $M$ du plan tels que $\|2\overrightarrow{MA} + \overrightarrow{MB}\| = 6$.',
                'steps' => [
                    "1. Somme des coefficients : \$2 + 1 = 3 \neq 0\$. Soit \$G = \text{bar}\{(A, 2), (B, 1)\}\$.",
                    "2. Réduction vectorielle : Pour tout point \$M\$, \$2\overrightarrow{MA} + \overrightarrow{MB} = 3\overrightarrow{MG}\$.",
                    "3. Équation en distance : \$\|3\overrightarrow{MG}\| = 6 \iff 3 MG = 6 \iff MG = 2\$.",
                ],
                'conclusion' => "L'ensemble \$(E)\$ est le cercle de centre \$G\$ et de rayon \$R = 2\$.",
            ],
            'rules' => [
                ['tag' => 'Cas Σ α_i = 0', 'title' => 'Quand la somme des coefficients est nulle', 'content' => 'Si $\sum \alpha_i = 0$, le vecteur $\sum \alpha_i \overrightarrow{MA_i}$ est un vecteur constant indépendant de $M$ (on le calcule en remplaçant $M$ par $A_1$).'],
            ],
            'trap' => 'Introduire un barycentre $G$ alors que la somme des coefficients $\sum \alpha_i$ est égale à $0$ !',
            'redaction_tip' => 'Vérifiez toujours que $\sum \alpha_i \neq 0$ avant d\'écrire « Soit G le barycentre... ».',
            'formula_sheet' => [
                ['name' => 'Réduction de Leibniz', 'formula' => '\sum \alpha_i \overrightarrow{MA_i} = (\sum \alpha_i)\overrightarrow{MG}'],
                ['name' => 'Position de G (2 points)', 'formula' => '\overrightarrow{AG} = \frac{\beta}{\alpha+\beta}\overrightarrow{AB}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Barycentres (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Pour tout point M du plan, le vecteur 2MA - 2MB est égal à :', 'options' => ['A. 4MG', 'B. 2BA (vecteur constant)', 'C. 2AB', 'D. Le vecteur nul'], 'correct' => 1, 'expl' => 'Par la relation de Chasles, 2MA - 2MB = 2(MA + BM) = 2BA, indépendant de M.'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Ensemble de points par le barycentre',
                'xp' => 50,
                'statement' => 'Déterminer la nature de l\'ensemble des points $M$ tels que $\| \overrightarrow{MA} + 2\overrightarrow{MB} \| = \| \overrightarrow{MA} - \overrightarrow{MB} \|$ avec $AB = 6$.',
                'hint' => 'Introduis $G = \text{bar}\{(A,1),(B,2)\}$ à gauche et simplifie $\overrightarrow{MA} - \overrightarrow{MB} = \overrightarrow{BA}$ à droite.',
                'solution' => 'On obtient $\|3\overrightarrow{MG}\| = \|\overrightarrow{BA}\| \iff 3MG = AB = 6 \iff MG = 2$. C\'est le cercle de centre $G$ et de rayon $2$.',
                'bareme' => 'Réduction des deux membres : 2 pts | Conclusion géométrique : 1 pt.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Ligne de niveau de Leibniz',
                'xp' => 100,
                'statement' => 'Soit $ABC$ un triangle équilatéral de côté $a$. Déterminer l\'ensemble des points $M$ tels que $\overrightarrow{MA} \cdot \overrightarrow{MB} = 0$.',
                'bareme' => 'Application du théorème de la médiane : 6 pts.',
                'solution' => 'C\'est le cercle de diamètre $[AB]$ (de centre $I$ milieu de $[AB]$ et de rayon $a/2$).',
            ],
            'suggestions' => [
                'Comment utiliser le théorème des barycentres partiels ?',
                'Que faire quand la somme des coefficients vaut zéro ?',
                'Comment déterminer une ligne de niveau avec MA² + MB² = k ?',
            ],
        ];
    }

    private function getTrigonometrieCourseData(): array
    {
        return [
            'code' => 'MATHS_TLE_TRIGO',
            'title' => 'Trigonométrie : Angles Orientés, Formules et Équations Trigonométriques',
            'subject' => 'Mathématiques',
            'ref' => 'MEPST Togo · Programme Officiel Terminale C & D',
            'coeff_badge' => 'Coeff. 4 · Trigonométrie & Analyse',
            'duration' => '35 min d\'assimilation',
            'objectives' => [
                "Maîtriser les formules d'addition, de duplication et de linéarisation en trigonométrie.",
                "Résoudre dans R et dans ]-π, π] les équations de type cos(x) = cos(a), sin(x) = sin(a) et a cos(x) + b sin(x) = c.",
            ],
            'definitions' => [
                [
                    'term' => 'Transformation de a cos(x) + b sin(x)',
                    'definition' => 'Pour tous réels $a, b$ non tous deux nuls, en posant $r = \sqrt{a^2 + b^2}$, il existe $\varphi \in ]-\pi, \pi]$ tel que $a\cos x + b\sin x = r\cos(x - \varphi)$ avec $\cos\varphi = \frac{a}{r}$ et $\sin\varphi = \frac{b}{r}$.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Équations trigonométriques fondamentales',
                    'latex' => '\cos x = \cos \alpha \iff x \equiv \alpha \;[2\pi] \text{ ou } x \equiv -\alpha \;[2\pi] \quad ; \quad \sin x = \sin \alpha \iff x \equiv \alpha \;[2\pi] \text{ ou } x \equiv \pi - \alpha \;[2\pi]',
                    'conditions' => '$x, \alpha \in \mathbb{R}$.',
                    'comment' => 'Toujours représenter les solutions sur le cercle trigonométrique.',
                ],
                [
                    'title' => 'Formules de duplication et de linéarisation',
                    'latex' => '\cos(2x) = 2\cos^2 x - 1 = 1 - 2\sin^2 x \quad ; \quad \sin(2x) = 2\sin x \cos x',
                    'conditions' => 'Valables pour tout réel $x$.',
                    'comment' => 'Indispensables pour calculer des primitives et intégrales trigonométriques.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Résoudre dans $\mathbb{R}$ l\'équation $\sqrt{3}\cos x + \sin x = \sqrt{2}$.',
                'steps' => [
                    "1. Calcul du module \$r\$ : \$r = \sqrt{(\sqrt{3})^2 + 1^2} = \sqrt{4} = 2\$.",
                    "2. Factorisation par \$2\$ : \$2\left(\frac{\sqrt{3}}{2}\cos x + \frac{1}{2}\sin x\right) = \sqrt{2} \iff \cos\left(\frac{\pi}{6}\right)\cos x + \sin\left(\frac{\pi}{6}\right)\sin x = \frac{\sqrt{2}}{2}\$.",
                    "3. Formule d'addition : \$\cos\left(x - \frac{\pi}{6}\right) = \cos\left(\frac{\pi}{4}\right)\$.",
                    "4. Résolution : \$x - \frac{\pi}{6} = \frac{\pi}{4} + 2k\pi\$ ou \$x - \frac{\pi}{6} = -\frac{\pi}{4} + 2k\pi\$ (\$k \in \mathbb{Z}\$).",
                ],
                'conclusion' => "\$S = \left\{ \frac{5\pi}{12} + 2k\pi \; ; \; -\frac{\pi}{12} + 2k\pi, \; k \in \mathbb{Z} \right\}\$.",
            ],
            'rules' => [
                ['tag' => 'Cercle trigo', 'title' => 'Solutions dans ]-π, π]', 'content' => 'Donnez aux entiers $k$ les valeurs $-1, 0, 1$ pour extraire uniquement les solutions appartenant à l\'intervalle demandé.'],
            ],
            'trap' => 'Oublier la deuxième famille de solutions ($\pi - \alpha$ pour le sinus, $-\alpha$ pour le cosinus).',
            'redaction_tip' => 'Précisez systématiquement « avec k appartenant à Z » à chaque ligne d\'équivalence.',
            'formula_sheet' => [
                ['name' => 'Relation fondamentale', 'formula' => '\cos^2 x + \sin^2 x = 1'],
                ['name' => 'Linéarisation cos²', 'formula' => '\cos^2 x = \frac{1+\cos(2x)}{2}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Trigonométrie (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Que vaut cos(π/3) ?', 'options' => ['A. √3 / 2', 'B. 1 / 2', 'C. √2 / 2', 'D. 1'], 'correct' => 1, 'expl' => 'cos(π/3) = 1/2 et sin(π/3) = √3/2.'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Équation trigonométrique',
                'xp' => 50,
                'statement' => 'Résoudre dans $]-\pi, \pi]$ l\'équation $2\sin x - 1 = 0$.',
                'hint' => '$\sin x = \frac{1}{2} = \sin(\pi/6)$.',
                'solution' => 'Dans $]-\pi, \pi]$, les deux solutions sont $x = \frac{\pi}{6}$ et $x = \pi - \frac{\pi}{6} = \frac{5\pi}{6}$.',
                'bareme' => '3 points.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Linéarisation et primitive',
                'xp' => 100,
                'statement' => 'Linéariser $\sin^2(x)$ puis en déduire une primitive de $f(x) = \sin^2(x)$ sur $\mathbb{R}$.',
                'bareme' => 'Linéarisation : 3 pts | Primitive : 3 pts.',
                'solution' => '$\sin^2(x) = \frac{1 - \cos(2x)}{2}$, d\'où une primitive est $F(x) = \frac{1}{2}x - \frac{1}{4}\sin(2x)$.',
            ],
            'suggestions' => [
                'Comment résoudre a cos(x) + b sin(x) = c ?',
                'Rappelle-moi les valeurs remarquables sur le cercle trigonométrique',
                'Comment linéariser cos²(x) et sin²(x) ?',
            ],
        ];
    }

    private function getChimieMineraleCourseData(): array
    {
        return [
            'code' => 'PCT_TLE_REDOX',
            'title' => 'Chimie Minérale : Oxydoréduction, Piles Électrochimiques et Électrolyse',
            'subject' => 'Physique-Chimie',
            'ref' => 'MEPST Togo · Programme Officiel Terminale C & D',
            'coeff_badge' => 'Coeff. 4 · Chimie Générale & Minérale',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Équilibrer les demi-équations électroniques et l'équation-bilan d'une réaction d'oxydoréduction en milieu acide.",
                "Utiliser les potentiels standard d'oxydoréduction \$E^\circ\$ (règle du gamma \$\gamma\$) pour prévoir le sens spontané d'une réaction.",
                "Étudier le fonctionnement d'une pile électrochimique (pile Daniell), sa f.é.m. et le bilan quantitatif d'une électrolyse.",
            ],
            'definitions' => [
                [
                    'term' => 'Oxydant et Réducteur',
                    'definition' => 'Un **oxydant** est une espèce chimique capable de capter un ou plusieurs électrons ($\text{Ox} + n e^- \rightleftarrows \text{Red}$). Un **réducteur** est capable de céder un ou plusieurs électrons. Une **oxydation** est une perte d\'électrons ; une **réduction** est un gain d\'électrons.',
                ],
                [
                    'term' => 'Pile électrochimique',
                    'definition' => 'Générateur convertissant de l\'énergie chimique en énergie électrique grâce à un transfert spontané mais indirect d\'électrons entre le réducteur le plus fort (à l\'anode, pôle $-$) et l\'oxydant le plus fort (à la cathode, pôle $+$).',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Force électromotrice (f.é.m.) et Quantité d\'électricité',
                    'latex' => 'E = E_{\text{cathode (+)}} - E_{\text{anode (-)}} > 0 \quad ; \quad Q = I \cdot \Delta t = n(e^-) \times \mathcal{F}',
                    'conditions' => 'Avec $\mathcal{F} \approx 96\,500\text{ C}\cdot\text{mol}^{-1}$ (constante de Faraday).',
                    'comment' => 'Relie directement l\'intensité du courant $I$ à la quantité de matière de métal déposé ou consommé.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'On réalise une pile à l\'aide des couples $\text{Cu}^{2+}/\text{Cu}$ ($E^\circ_1 = 0{,}34\text{ V}$) et $\text{Zn}^{2+}/\text{Zn}$ ($E^\circ_2 = -0{,}76\text{ V}$). Déterminer les pôles de la pile, sa f.é.m. standard et l\'équation-bilan de fonctionnement.',
                'steps' => [
                    "1. Comparaison des potentiels : \$E^\circ(\text{Cu}^{2+}/\text{Cu}) > E^\circ(\text{Zn}^{2+}/\text{Zn})\$. L'électrode de cuivre est donc le pôle positif (cathode, siège d'une réduction) et l'électrode de zinc est le pôle négatif (anode, siège d'une oxydation).",
                    "2. Demi-équations aux électrodes : À l'anode \$(-)\$ : \$\text{Zn} \to \text{Zn}^{2+} + 2e^-\$. À la cathode \$(+)\$ : \$\text{Cu}^{2+} + 2e^- \to \text{Cu}\$.",
                    "3. Équation-bilan : \$\text{Zn} + \text{Cu}^{2+} \to \text{Zn}^{2+} + \text{Cu}\$.",
                    "4. Calcul de la f.é.m. : \$E^\circ = E^\circ_1 - E^\circ_2 = 0{,}34 - (-0{,}76) = 1{,}10\text{ V}\$.",
                ],
                'conclusion' => "La pile Daniell a pour f.é.m. \$E^\circ = 1{,}10\text{ V}\$, le courant circule du cuivre \$(+)\$ vers le zinc \$(-)\$ à l'extérieur de la pile.",
            ],
            'rules' => [
                ['tag' => 'Règle du Gamma', 'title' => 'Sens spontané d\'une réaction redox', 'content' => 'Placez les couples sur une échelle verticale de potentiel $E^\circ$ croissant : l\'oxydant le plus fort (en haut à gauche) réagit spontanément avec le réducteur le plus fort (en bas à droite).'],
            ],
            'trap' => 'Inverser le sens des électrons et du courant conventionnel : à l\'extérieur de la pile, les électrons vont du pôle $-$ vers le pôle $+$, tandis que le courant $I$ va du $+$ vers le $-$.',
            'redaction_tip' => 'Vérifiez toujours à la fois la conservation des éléments chimiques ET la conservation de la charge électrique globale dans votre équation-bilan.',
            'formula_sheet' => [
                ['name' => 'Loi de Faraday', 'formula' => 'Q = I \Delta t = n(e^-)\mathcal{F}'],
                ['name' => 'F.é.m. d\'une pile', 'formula' => 'E = E_+ - E_-'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Oxydoréduction (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Dans une pile électrochimique, quelle réaction a toujours lieu à l\'anode ?', 'options' => ['A. Une réduction', 'B. Une oxydation', 'C. Une neutralisation acido-basique', 'D. Une estérification'], 'correct' => 1, 'expl' => 'Moyen mnémotechnique : Anode = Oxydation (deux voyelles), Cathode = Réduction (deux consonnes).'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 :Demi-équation électronique en milieu acide',
                'xp' => 50,
                'statement' => 'Écrire la demi-équation électronique du couple ion permanganate / ion manganèse II : $\text{MnO}_4^- / \text{Mn}^{2+}$ en milieu acide.',
                'hint' => 'Équilibre les atomes d\'oxygène avec $\text{H}_2\text{O}$, puis les hydrogènes avec $\text{H}^+$ (ou $\text{H}_3\text{O}^+$), puis les charges avec $e^-$.',
                'solution' => '$\text{MnO}_4^- + 8\text{H}^+ + 5e^- \rightleftarrows \text{Mn}^{2+} + 4\text{H}_2\text{O}$ (ou avec $8\text{H}_3\text{O}^+$ et $12\text{H}_2\text{O}$).',
                'bareme' => 'Conservation de la matière et des charges : 3 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Bilan quantitatif d\'une pile',
                'xp' => 100,
                'statement' => 'Une pile Daniell débite un courant constant $I = 0{,}20\text{ A}$ pendant $\Delta t = 2\text{ heures}$. Calculer la variation de masse de l\'électrode de zinc ($M(\text{Zn}) = 65{,}4\text{ g/mol}$, $\mathcal{F} = 96\,500\text{ C/mol}$).',
                'bareme' => 'Quantité d\'électricité : 2 pts | Masse de zinc consommée : 4 pts.',
                'solution' => '$Q = I \Delta t = 0{,}20 \times 7200 = 1440\text{ C}$. Or $n(\text{Zn}) = \frac{n(e^-)}{2} = \frac{Q}{2\mathcal{F}} = 7{,}46 \times 10^{-3}\text{ mol}$, soit $m(\text{Zn}) = n \times M \approx 0{,}49\text{ g}$ consommés.',
            ],
            'suggestions' => [
                'Comment équilibrer une demi-équation redox avec H3O+ ?',
                'Explique-moi le rôle du pont salin dans une pile',
                'Comment calculer la masse déposée lors d\'une électrolyse ?',
            ],
        ];
    }

    private function getChimieOrganiqueCourseData(): array
    {
        return [
            'code' => 'PCT_TLE_ORGA',
            'title' => 'Chimie Organique : Hydrocarbures, Alcools, Composés Carbonylés et Estérification',
            'subject' => 'Physique-Chimie',
            'ref' => 'MEPST Togo · Programme Officiel Terminale C & D',
            'coeff_badge' => 'Coeff. 4 · Chimie Organique BAC 2',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Nommer les alcanes, alcènes, alcynes, alcools, aldéhydes, cétones, acides carboxyliques et esters selon les règles de l'UICPA.",
                "Distinguer les trois classes d'alcools (primaire, secondaire, tertiaire) par oxydation ménagée et tests d'identification (2,4-DNPH, liqueur de Fehling, réactif de Schiff).",
                "Étudier les caractéristiques de la réaction d'estérification (lente, limitée, athermique) et de la saponification.",
            ],
            'definitions' => [
                [
                    'term' => 'Les trois classes d\'alcools R-OH',
                    'definition' => 'Un alcool est **primaire** si le carbone fonctionnel (portant $-\text{OH}$) est lié à au plus un groupe alkyle ($R-\text{CH}_2-\text{OH}$), **secondaire** s\'il est lié à deux groupes alkyles, et **tertiaire** s\'il est lié à trois groupes alkyles.',
                ],
                [
                    'term' => 'Estérification directe',
                    'definition' => 'Réaction entre un acide carboxylique $R-\text{COOH}$ et un alcool $R\'-\text{OH}$ donnant un ester $R-\text{COO}-R\'$ et de l\'eau $\text{H}_2\text{O}$. C\'est une réaction **lente, limitée (réversible) et athermique**.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Oxydation ménagée des alcools',
                    'latex' => '\text{Alcool I} \xrightarrow{[\text{O}]} \text{Aldéhyde} \xrightarrow{[\text{O}]} \text{Acide carboxylique} \quad ; \quad \text{Alcool II} \xrightarrow{[\text{O}]} \text{Cétone} \quad ; \quad \text{Alcool III} \xrightarrow{[\text{O}]} \text{Rien}',
                    'conditions' => 'Oxydant en défaut ou en excès (ex: $\text{MnO}_4^-$ ou $\text{Cr}_2\text{O}_7^{2-}$ en milieu acide).',
                    'comment' => 'Les aldéhydes et cétones donnent un précipité jaune-orangé avec la 2,4-DNPH ; seuls les aldéhydes rosissent le réactif de Schiff et donnent un précipité rouge brique avec la liqueur de Fehling.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Un monoalcool saturé $A$ a pour masse molaire $M = 74\text{ g}\cdot\text{mol}^{-1}$. Déterminer sa formule brute et identifier l\'isomère qui donne une cétone par oxydation ménagée.',
                'steps' => [
                    "1. Formule générale d'un monoalcool saturé : \$\text{C}_n\text{H}_{2n+2}\text{O}\$ de masse molaire \$M = 14n + 18\$.",
                    "2. Calcul de \$n\$ : \$14n + 18 = 74 \implies 14n = 56 \implies n = 4\$. La formule brute est \$\text{C}_4\text{H}_{10}\text{O}\$.",
                    "3. Identification de la classe : Comme l'oxydation ménagée de \$A\$ donne une cétone, \$A\$ est un alcool secondaire.",
                    "4. Formule semi-développée et nom : Le seul alcool secondaire en \$\text{C}_4\$ est \$\text{CH}_3-\text{CH}(\text{OH})-\text{CH}_2-\text{CH}_3\$, qui est le **butan-2-ol**.",
                ],
                'conclusion' => "\$A\$ est le butan-2-ol et son oxydation ménagée conduit à la butanone.",
            ],
            'rules' => [
                ['tag' => 'Rendement', 'title' => 'Limite d\'estérification pour un mélange équimolaire', 'content' => 'Avec un acide carboxylique et un alcool en proportions équimolaires : le rendement à l\'équilibre est de $67\%$ pour un alcool primaire, $60\%$ pour un alcool secondaire et $5\%$ pour un alcool tertiaire.'],
            ],
            'trap' => 'Croire qu\'un catalyseur ($\text{H}_2\text{SO}_4$) ou le chauffage déplace l\'équilibre d\'une estérification : ils augmentent uniquement la vitesse de réaction sans changer la limite !',
            'redaction_tip' => 'Entourez et nommez toujours le groupe fonctionnel caractéristique sur votre copie.',
            'formula_sheet' => [
                ['name' => 'Monoalcool saturé', 'formula' => '\text{C}_n\text{H}_{2n+2}\text{O} \;(14n+18)'],
                ['name' => 'Rendement', 'formula' => 'r = \frac{n_{\text{exp}}}{n_{\text{max}}}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Chimie Organique (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Quelles sont les trois caractéristiques d\'une réaction d\'estérification directe ?', 'options' => ['A. Rapide, totale et exothermique', 'B. Lente, limitée et athermique', 'C. Lente, totale et endothermique', 'D. Instantanée et réversible'], 'correct' => 1, 'expl' => 'L\'estérification entre un acide carboxylique et un alcool est lente, limitée (réversible) et athermique.'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Nomenclature et tests d\'identification',
                'xp' => 50,
                'statement' => 'Donner le nom UICPA de l\'ester obtenu par réaction entre l\'acide éthanoïque et le propan-1-ol.',
                'hint' => 'Le nom d\'un ester est « alcanoate d\'alkyle ».',
                'solution' => 'C\'est l\'**éthanoate de propyle**, de formule semi-développée $\text{CH}_3-\text{COO}-\text{CH}_2-\text{CH}_2-\text{CH}_3$.',
                'bareme' => '3 points.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Synthèse organique au BAC',
                'xp' => 100,
                'statement' => 'Comment rendre la synthèse d\'un ester rapide et totale en laboratoire ? Donner l\'équation de la réaction correspondante.',
                'bareme' => 'Choix du dérivé d\'acide : 3 pts | Équation-bilan : 3 pts.',
                'solution' => 'En remplaçant l\'acide carboxylique par un **chlorure d\'acyle** ($R-\text{COCl}$) ou un **anhydride d\'acide** : $R-\text{COCl} + R\'-\text{OH} \to R-\text{COO}-R\' + \text{HCl}$.',
            ],
            'suggestions' => [
                'Comment distinguer un aldéhyde d\'une cétone avec les tests colorés ?',
                'Quelle est la différence entre estérification et saponification ?',
                'Explique-moi l\'hydratation d\'un alcène dissymétrique (règle de Markovnikov)',
            ],
        ];
    }

    private function getOptiqueCourseData(): array
    {
        return [
            'code' => 'PCT_TLE_OPT',
            'title' => 'Optique Géométrique et Ondulatoire : Réfraction, Lentilles Minces et Diffraction',
            'subject' => 'Physique-Chimie',
            'ref' => 'MEPST Togo · Programme Officiel Terminale C & D',
            'coeff_badge' => 'Coeff. 4 · Optique & Ondes',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Appliquer les lois de Snell-Descartes pour la réflexion et la réfraction de la lumière.",
                "Utiliser les relations de conjugaison et de grandissement de Descartes pour les lentilles minces convergentes et divergentes.",
                "Comprendre les phénomènes de diffraction et d'interférences lumineuses.",
            ],
            'definitions' => [
                [
                    'term' => 'Vergence C d\'une lentille mince',
                    'definition' => 'Inverse de la mesure algébrique de la distance focale image $\overline{OF\'} = f\'$ : $C = \frac{1}{\overline{OF\'}}$ exprimée en **dioptries** ($\delta$, avec $\overline{OF\'}$ en mètres). $C > 0$ pour une lentille convergente, $C < 0$ pour une lentille divergente.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Relations de conjugaison et de grandissement (Descartes)',
                    'latex' => '\frac{1}{\overline{OA\'}} - \frac{1}{\overline{OA}} = \frac{1}{\overline{OF\'}} = C \quad ; \quad \gamma = \frac{\overline{A\'B\'}}{\overline{AB}} = \frac{\overline{OA\'}}{\overline{OA}}',
                    'conditions' => 'Dans les conditions de Gauss, avec l\'axe optique orienté dans le sens de propagation de la lumière.',
                    'comment' => 'Attention aux signes algébriques : pour un objet réel placé avant la lentille, $\overline{OA} < 0$.',
                ],
                [
                    'title' => 'Deuxième loi de Snell-Descartes (Réfraction)',
                    'latex' => 'n_1 \sin(i_1) = n_2 \sin(i_2)',
                    'conditions' => '$n_1, n_2$ sont les indices de réfraction des deux milieux transparents.',
                    'comment' => 'Si $n_1 > n_2$, il y a réflexion totale lorsque $i_1 > \lambda$ avec $\sin(\lambda) = \frac{n_2}{n_1}$.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Un objet réel $AB$ de hauteur $2\text{ cm}$ est placé à $30\text{ cm}$ devant une lentille convergente de distance focale $f\' = +20\text{ cm}$. Déterminer la position, la nature et la taille de l\'image $A\'B\'$.',
                'steps' => [
                    "1. Données algébriques : \$\overline{OA} = -30\text{ cm}\$ et \$\overline{OF'} = +20\text{ cm}\$.",
                    "2. Relation de conjugaison : \$\frac{1}{\overline{OA'}} = \frac{1}{\overline{OF'}} + \frac{1}{\overline{OA}} = \frac{1}{20} - \frac{1}{30} = \frac{3 - 2}{60} = \frac{1}{60}\$. D'où \$\overline{OA'} = +60\text{ cm}\$.",
                    "3. Grandissement : \$\gamma = \frac{\overline{OA'}}{\overline{OA}} = \frac{60}{-30} = -2\$. Donc \$\overline{A'B'} = \gamma \cdot \overline{AB} = -4\text{ cm}\$.",
                ],
                'conclusion' => "L'image \$A'B'\$ est réelle (\$\overline{OA'} > 0\$), renversée (\$\gamma < 0\$), de hauteur \$4\text{ cm}\$, située à \$60\text{ cm}\$ après la lentille.",
            ],
            'rules' => [
                ['tag' => 'Théorème des vergences', 'title' => 'Lentilles accolées', 'content' => 'Deux lentilles minces accolées de vergences $C_1$ et $C_2$ sont équivalentes à une lentille unique de vergence $C = C_1 + C_2$.'],
            ],
            'trap' => 'Oublier le signe négatif de $\overline{OA}$ pour un objet réel placé à gauche du centre optique $O$.',
            'redaction_tip' => 'Exprimez toutes les distances dans la même unité (cm pour la conjugaison, mètres pour le calcul en dioptries).',
            'formula_sheet' => [
                ['name' => 'Conjugaison', 'formula' => '\frac{1}{\overline{OA\'}} - \frac{1}{\overline{OA}} = \frac{1}{f\'}'],
                ['name' => 'Grandissement', 'formula' => '\gamma = \frac{\overline{OA\'}}{\overline{OA}}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Optique (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Quelle est la vergence d\'une lentille convergente de distance focale f\' = 25 cm ?', 'options' => ['A. +0,04 δ', 'B. +4 δ', 'C. -4 δ', 'D. +25 δ'], 'correct' => 1, 'expl' => 'f\' = 0,25 m donc C = 1 / 0,25 = +4 dioptries (δ).'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Position de l\'image',
                'xp' => 50,
                'statement' => 'Un objet réel est situé à $40\text{ cm}$ devant une lentille convergente de distance focale $20\text{ cm}$ ($\overline{OA} = -2f\'$). Où se forme l\'image et quel est le grandissement ?',
                'hint' => 'Calcule $\frac{1}{20} - \frac{1}{40}$.',
                'solution' => '$\overline{OA\'} = +40\text{ cm}$ et $\gamma = -1$ (montage dit « 2f - 2f » : image réelle symétrique de même taille).',
                'bareme' => '3 points.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Angle limite de réfraction',
                'xp' => 100,
                'statement' => 'Calculer l\'angle limite de réfraction $\lambda$ lors du passage d\'un rayon lumineux de l\'eau ($n_1 = 1{,}33$) vers l\'air ($n_2 = 1{,}00$).',
                'bareme' => 'Loi de Descartes : 3 pts | Application numérique : 3 pts.',
                'solution' => '$\sin(\lambda) = \frac{n_2}{n_1} = \frac{1}{1{,}33} \approx 0{,}752 \implies \lambda \approx 48{,}8^\circ$.',
            ],
            'suggestions' => [
                'Comment tracer l\'image d\'un objet par une lentille convergente ?',
                'Quand observe-t-on une image virtuelle (effet loupe) ?',
                'Explique-moi le phénomène de réflexion totale',
            ],
        ];
    }

    private function getElectriciteCourseData(): array
    {
        return [
            'code' => 'PCT_TLE_ELEC',
            'title' => 'Électricité et Énergie : Champ Électrostatique, Condensateurs et Dipôle RC',
            'subject' => 'Physique-Chimie',
            'ref' => 'MEPST Togo · Programme Officiel Terminale C & D',
            'coeff_badge' => 'Coeff. 4 · Électricité & Électromagnétisme',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Étudier le mouvement d'une particule chargée dans un champ électrostatique uniforme \$\vec{E}\$.",
                "Établir l'équation différentielle de la charge et de la décharge d'un condensateur dans un dipôle RC.",
                "Calculer la constante de temps \$\tau = RC\$ et l'énergie électrostatique emmagasinée \$E_e = \frac{1}{2} C u_C^2\$.",
            ],
            'definitions' => [
                [
                    'term' => 'Relation charge-tension d\'un condensateur',
                    'definition' => 'En convention récepteur, la charge $q$ de l\'armature recevant le courant $i$ est proportionnelle à la tension $u_C$ : $q = C \cdot u_C$, et l\'intensité vaut $i = \frac{dq}{dt} = C \frac{du_C}{dt}$.',
                ],
                [
                    'term' => 'Constante de temps τ d\'un dipôle RC',
                    'definition' => 'Grandeur homogène à un temps définie par $\tau = R \cdot C$ (en secondes). À $t = \tau$, le condensateur est chargé à $63\%$ de sa tension finale ; à $t = 5\tau$, la charge est considérée comme terminée ($99\%$).',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Équation différentielle de charge d\'un dipôle RC (échelon E)',
                    'latex' => 'RC \frac{du_C}{dt} + u_C = E \implies u_C(t) = E\left(1 - e^{-t/RC}\right)',
                    'conditions' => 'Pour un condensateur initialement déchargé ($u_C(0) = 0$).',
                    'comment' => 'Obtenue par application de la loi d\'additivité des tensions : $u_R + u_C = E$ avec $u_R = R i = RC \frac{du_C}{dt}$.',
                ],
                [
                    'title' => 'Énergie emmagasinée dans un condensateur',
                    'latex' => 'E_e = \frac{1}{2} C u_C^2 = \frac{1}{2} \frac{q^2}{C}',
                    'conditions' => '$C$ en Farads ($\text{F}$), $u_C$ en Volts ($\text{V}$) et $E_e$ en Joules ($\text{J}$).',
                    'comment' => 'La continuité de l\'énergie impose que la tension $u_C(t)$ aux bornes d\'un condensateur ne subit jamais de discontinuité.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Un condensateur de capacité $C = 10\,\mu\text{F}$ est chargé sous une tension $U = 100\text{ V}$ à travers une résistance $R = 1\text{ k}\Omega$. Calculer la constante de temps $\tau$, la charge finale $Q_{\max}$ et l\'énergie emmagasinée.',
                'steps' => [
                    "1. Conversion en unités SI : \$C = 10 \times 10^{-6}\text{ F} = 10^{-5}\text{ F}\$ et \$R = 10^3\,\Omega\$.",
                    "2. Constante de temps : \$\tau = R \times C = 10^3 \times 10^{-5} = 10^{-2}\text{ s} = 10\text{ ms}\$.",
                    "3. Charge maximale : \$Q_{\max} = C \times U = 10^{-5} \times 100 = 10^{-3}\text{ C} = 1\text{ mC}\$.",
                    "4. Énergie stockée : \$E_e = \frac{1}{2} C U^2 = 0{,}5 \times 10^{-5} \times 100^2 = 0{,}05\text{ J} = 50\text{ mJ}\$.",
                ],
                'conclusion' => "\$\tau = 10\text{ ms}\$, \$Q_{\max} = 1\text{ mC}\$ et \$E_e = 50\text{ mJ}\$.",
            ],
            'rules' => [
                ['tag' => 'Association', 'title' => 'Condensateurs en parallèle et en série', 'content' => 'En parallèle, les capacités s\'additionnent : $C_{\text{éq}} = C_1 + C_2$. En série, $\frac{1}{C_{\text{éq}}} = \frac{1}{C_1} + \frac{1}{C_2}$.'],
            ],
            'trap' => 'Confondre les formules d\'association des condensateurs avec celles des résistances (elles sont inversées !).',
            'redaction_tip' => 'Convertissez toujours les microfarads ($\mu\text{F} = 10^{-6}\text{ F}$) et nanofarads ($\text{nF} = 10^{-9}\text{ F}$) en Farads avant l\'application numérique.',
            'formula_sheet' => [
                ['name' => 'Force électrostatique', 'formula' => '\vec{F} = q\vec{E}'],
                ['name' => 'Énergie condensateur', 'formula' => 'E_e = \frac{1}{2}Cu_C^2'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Électricité (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'En régime permanent continu (une fois le condensateur totalement chargé), un condensateur se comporte comme :', 'options' => ['A. Un fil conducteur parfait', 'B. Un interrupteur ouvert (i = 0)', 'C. Une résistance R', 'D. Un générateur de courant'], 'correct' => 1, 'expl' => 'Quand u_C est constante, i = C du_C/dt = 0 : le courant ne passe plus dans la branche du condensateur.'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Accélération d\'un électron dans un champ E',
                'xp' => 50,
                'statement' => 'Exprimer le vecteur accélération $\vec{a}$ d\'un électron (masse $m$, charge $q = -e$) placé dans un champ électrostatique uniforme $\vec{E}$ en négligeant son poids.',
                'hint' => 'Applique le théorème du centre d\'inertie : $\sum \vec{F} = m\vec{a}$.',
                'solution' => 'La seule force est $\vec{F} = -e\vec{E}$, donc $m\vec{a} = -e\vec{E} \implies \vec{a} = -\frac{e}{m}\vec{E}$ (colinéaire et de sens opposé à $\vec{E}$).',
                'bareme' => '3 points.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Déflexion électrostatique au BAC',
                'xp' => 100,
                'statement' => 'Un faisceau d\'électrons pénètre avec une vitesse horizontale $\vec{v}_0$ entre deux plaques parallèles de longueur $\ell$ soumises à une tension $U$ (distance $d$). Quelle est la nature de la trajectoire entre les plaques ?',
                'bareme' => 'Équations horaires : 3 pts | Équation cartésienne de la parabole : 3 pts.',
                'solution' => 'On a $x(t) = v_0 t$ et $y(t) = \frac{1}{2}\frac{eU}{md}t^2$, d\'où $y(x) = \frac{eU}{2md v_0^2}x^2$ : l\'arc de trajectoire entre les plaques est **parabolique**.',
            ],
            'suggestions' => [
                'Comment établir l\'équation différentielle de décharge d\'un dipôle RC ?',
                'Comment déterminer graphiquement la constante de temps τ ?',
                'Explique-moi le mouvement d\'une particule chargée entre deux plaques',
            ],
        ];
    }

    private function getSvtCelluleCourseData(): array
    {
        return [
            'code' => 'SVT_TLE_CELL',
            'title' => 'La Cellule : Organisation, Échanges Cellulaires, Synthèse des Protéines et Divisions',
            'subject' => 'SVT',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D',
            'coeff_badge' => 'Coeff. 4 · Biologie Cellulaire & Moléculaire',
            'duration' => '45 min d\'assimilation',
            'objectives' => [
                "Décrire l'ultrastructure de la cellule eucaryote (animale et végétale) au microscope électronique.",
                "Expliquer les mécanismes des échanges d'eau (osmose, turgescence, plasmolyse) et de substances dissoutes à travers la membrane plasmique.",
                "Maîtriser les étapes de la biosynthèse des protéines (transcription de l'ADN en ARNm et traduction) ainsi que le cycle cellulaire (mitose et méiose).",
            ],
            'definitions' => [
                [
                    'term' => 'Osmose, Turgescence et Plasmolyse',
                    'definition' => 'L\'**osmose** est le passage d\'eau du milieu le moins concentré (hypotonique) vers le milieu le plus concentré (hypertonique) à travers une membrane semi-perméable. En milieu hypotonique, la cellule végétale absorbe de l\'eau et devient **turgescente** ; en milieu hypertonique, elle perd de l\'eau et devient **plasmolysée**.',
                ],
                [
                    'term' => 'Transcription et Traduction',
                    'definition' => 'La **transcription** (dans le noyau) est la copie du brin transcrit d\'ADN en ARN messager (ARNm) par l\'ARN polymérase. La **traduction** (dans le cytoplasme au niveau des ribosomes) est le décodage des codons de l\'ARNm en une séquence d\'acides aminés grâce aux ARNt.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Pression osmotique π (Loi de Van\'t Hoff)',
                    'latex' => '\pi = i \cdot C \cdot R \cdot T = \frac{n}{V} R T',
                    'conditions' => '$C$ est la concentration molaire ($\text{mol/L}$), $T$ la température absolue en Kelvins ($T = t^\circ\text{C} + 273$), $i$ le nombre d\'ions dissociés par molécule.',
                    'comment' => 'L\'eau se déplace toujours du compartiment de plus faible pression osmotique vers celui de plus forte pression osmotique.',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Soit le fragment de brin non transcrit (brin codant) d\'un gène : `5\' - ATG CCA GTA TGA - 3\'`. Donner la séquence du brin transcrit, de l\'ARNm correspondant et de la chaîne polypeptidique synthétisée.',
                'steps' => [
                    "1. Brin transcrit d'ADN (complémentaire A↔T, C↔G) : `3' - TAC GGT CAT ACT - 5'`.",
                    "2. Séquence de l'ARNm (complémentaire du brin transcrit avec U à la place de T, soit identique au brin non transcrit en remplaçant T par U) : `5' - AUG CCA GUA UGA - 3'`.",
                    "3. Traduction à l'aide du code génétique : `AUG` = Méthionine (codon initiateur), `CCA` = Proline, `GUA` = Valine, `UGA` = codon STOP (arrêt de la synthèse).",
                ],
                'conclusion' => "Le peptide synthétisé est : Méthionine – Proline – Valine (ou Pro – Val après excision de la méthionine initiale).",
            ],
            'rules' => [
                ['tag' => 'Divisions', 'title' => 'Mitose vs Méiose', 'content' => 'La mitose est une reproduction conforme conservant le nombre de chromosomes ($2n \to 2n$), tandis que la méiose comprend deux divisions successives (réductionnelle puis équationnelle) réduisant de moitié le nombre de chromosomes ($2n \to n$) pour former les gamètes.'],
            ],
            'trap' => 'Oublier de remplacer la Thymine (T) par l\'Uracile (U) lors de l\'écriture de la séquence d\'ARNm, ou traduire le codon STOP en acide aminé.',
            'redaction_tip' => 'Schématisez toujours avec soin les 4 phases de la mitose : Prophase, Métaphase, Anaphase et Télophase (moyen mnémotechnique : PMAT).',
            'formula_sheet' => [
                ['name' => 'Pression osmotique', 'formula' => '\pi = iCRT'],
                ['name' => 'Quantité d\'ADN', 'formula' => 'Q \xrightarrow{\text{Phase S}} 2Q \xrightarrow{\text{Mitose}} Q'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Biologie Cellulaire (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Quel organite cellulaire est le siège de la respiration cellulaire et de la production d\'ATP ?', 'options' => ['A. Le ribosome', 'B. La mitochondrie', 'C. L\'appareil de Golgi', 'D. Le centrosome'], 'correct' => 1, 'expl' => 'La mitochondrie est la centrale énergétique de la cellule eucaryote.'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Calcul de pression osmotique',
                'xp' => 50,
                'statement' => 'Calculer la pression osmotique d\'une solution de chlorure de sodium ($\text{NaCl} \to \text{Na}^+ + \text{Cl}^-$, $i = 2$) de concentration $C = 0{,}1\text{ mol/L}$ à $27^\circ\text{C}$ ($R = 0{,}082$).',
                'hint' => 'Convertis $27^\circ\text{C}$ en Kelvins ($T = 300\text{ K}$) et applique $\pi = iCRT$.',
                'solution' => '$\pi = 2 \times 0{,}1 \times 0{,}082 \times 300 = 4{,}92\text{ atm}$.',
                'bareme' => '3 points.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Synthèse des protéines et mutation',
                'xp' => 100,
                'statement' => 'Expliquer pourquoi certaines mutations par substitution d\'un nucléotide dans l\'ADN ne modifient pas la protéine synthétisée.',
                'bareme' => 'Redondance du code génétique : 6 pts.',
                'solution' => 'Parce que le code génétique est **redondant (ou dégénéré)** : plusieurs codons différents (61 codons sens pour 20 acides aminés) codent pour le même acide aminé. On parle alors de **mutation silencieuse**.',
            ],
            'suggestions' => [
                'Quelle est la différence entre transport passif et transport actif (pompe Na+/K+) ?',
                'Explique-moi l\'évolution de la quantité d\'ADN au cours du cycle cellulaire',
                'Quelles sont les différences entre cellule animale et cellule végétale ?',
            ],
        ];
    }

    private function getSvtGeologieCourseData(): array
    {
        return [
            'code' => 'SVT_TLE_GEOL',
            'title' => 'Géologie : Tectonique Globale et Grandes Formations Géologiques du Togo',
            'subject' => 'SVT',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D',
            'coeff_badge' => 'Coeff. 4 · Géologie du Togo & Tectonique',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Expliquer les arguments de la dérive des continents et les mécanismes de la tectonique des plaques (divergence aux dorsales, convergence aux zones de subduction et de collision).",
                "Identifier et localiser les quatre grands ensembles géologiques du Togo du Nord-Ouest au Sud-Est.",
                "Connaître les principales ressources minières du Togo associées à chaque formation géologique.",
            ],
            'definitions' => [
                [
                    'term' => 'Les 4 grandes formations géologiques du Togo',
                    'definition' => '1. **Le socle birrimien (Craton Ouest-Africain)** à l\'extrême Nord-Ouest (région de Dapaong). 2. **Le bassin des Volta** (Dapaong – Mango – Barkoissi). 3. **La chaîne panafricaine des Dahomeyides** (de Kantè/Kara jusqu\'à Tsévié, incluant les monts Togo). 4. **Le bassin sédimentaire côtier** au Sud (de Tsévié à l\'océan Atlantique).',
                ],
                [
                    'term' => 'Ressources minières du sous-sol togolais',
                    'definition' => 'Les **phosphates** (Hahotoé, Kpogamé) et le **calcaire** (Tabligbo, clinker) se trouvent dans le bassin sédimentaire côtier ; le **fer** (Bassar/Bandjeli) et la **chromite** (mont Ahito) se situent dans la chaîne des Dahomeyides.',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Bilan dynamique de la lithosphère',
                    'latex' => '\text{Accrétion océanique (Dorsale)} \iff \text{Subduction / Obduction / Collision (Fosses & Chaînes)}',
                    'conditions' => 'Moteur thermique : courants de convection dans le manteau asthénosphérique.',
                    'comment' => 'La chaîne des Dahomeyides au Togo résulte d\'une ancienne collision continentale panafricaine (vers 600 Ma).',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Expliquer l\'origine géologique de la chaîne de l\'Atakora (monts Togo) au sein des Dahomeyides.',
                'steps' => [
                    "1. Au Protérozoïque supérieur, un océan séparait le craton Ouest-Africain à l'ouest et le bouclier bénino-nigérian à l'est.",
                    "2. La fermeture de cet océan par subduction vers l'est a entraîné la collision continentale lors de l'orogenèse panafricaine (environ 600 millions d'années).",
                    "3. Ce charriage et ce métamorphisme ont donné naissance à l'unité structurale de l'Atakora (quartzites et micaschistes) et à la suture basique/ultrabasique (axe Kabye – Sotouboua – Agou).",
                ],
                'conclusion' => "La chaîne des Dahomeyides qui traverse le Togo du Nord-Est au Sud-Ouest est une chaîne de collision panafricaine.",
            ],
            'rules' => [
                ['tag' => 'Carte du Togo', 'title' => 'Coupe géologique NO - SE du Togo', 'content' => 'Du Nord-Ouest vers le Sud-Est, on rencontre successivement : Socle Birrimien → Bassin des Volta → Chaîne des Dahomeyides → Bassin sédimentaire côtier.'],
            ],
            'trap' => 'Confondre le bassin sédimentaire des Volta (au Nord, d\'âge protérozoïque/paléozoïque) avec le bassin sédimentaire côtier (au Sud, d\'âge secondaire à quaternaire, riche en phosphates et calcaire).',
            'redaction_tip' => 'Citez toujours des localités togolaises précises (Dapaong, Bassar, Kara, Agou, Tabligbo, Hahotoé) pour illustrer votre copie de géologie.',
            'formula_sheet' => [
                ['name' => 'Orogenèse des Dahomeyides', 'formula' => '\text{Panafricain } (\approx 600\text{ Ma})'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Géologie du Togo (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Dans quel ensemble géologique du Togo exploite-t-on les phosphates (Hahotoé, Kpogamé) et le calcaire (Tabligbo) ?', 'options' => ['A. Le socle birrimien de Dapaong', 'B. Le bassin sédimentaire côtier', 'C. Le bassin des Volta', 'D. L\'unité de l\'Atakora'], 'correct' => 1, 'expl' => 'Les phosphates et le calcaire sont des roches sédimentaires exploitées dans le bassin sédimentaire côtier au sud du Togo.'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Les ensembles géologiques du Togo',
                'xp' => 50,
                'statement' => 'Citer dans l\'ordre du Nord-Ouest au Sud-Est les quatre grandes formations géologiques du Togo.',
                'hint' => 'Commence à Dapaong (extrême nord-ouest) et termine à Lomé (océan Atlantique).',
                'solution' => '1. Le socle birrimien (Dapaong) ; 2. Le bassin sédimentaire des Volta ; 3. La chaîne panafricaine des Dahomeyides ; 4. Le bassin sédimentaire côtier.',
                'bareme' => '3 points.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Synthèse sur la chaîne des Dahomeyides',
                'xp' => 100,
                'statement' => 'Présenter les grandes unités structurales de la chaîne des Dahomeyides au Togo et les minerais qui y sont associés.',
                'bareme' => 'Unités structurales (Buem, Atakora, zone de suture, plaine bénino-togolaise) : 4 pts | Minerais (fer de Bassar, chromite) : 2 pts.',
                'solution' => 'D\'ouest en est : l\'unité du Buem (hématite/fer de Bassar), l\'unité de l\'Atakora (quartzites et micaschistes des monts Togo), la zone de suture à massifs basiques/ultrabasiques (monts Kabye, Agou, chromite d\'Ahito) et la plaine granito-gneissique bénino-togolaise.',
            ],
            'suggestions' => [
                'Explique-moi les 4 grandes formations géologiques du Togo',
                'Quelle est la différence entre subduction et collision ?',
                'Quelles sont les ressources minières du bassin sédimentaire côtier togolais ?',
            ],
        ];
    }

    private function getSvtNutritionCourseData(): array
    {
        return [
            'code' => 'SVT_TLE_METAB',
            'title' => 'Matière Vivante et Nutrition : Constituants Chimiques, Respiration et Fermentations',
            'subject' => 'SVT',
            'ref' => 'MEPST Togo · Programme Officiel Terminale D',
            'coeff_badge' => 'Coeff. 4 · Bioénergétique & Métabolisme',
            'duration' => '40 min d\'assimilation',
            'objectives' => [
                "Identifier les constituants minéraux et organiques (glucides, lipides, protides) de la matière vivante et leurs réactifs caractéristiques.",
                "Comparer le bilan énergétique de la respiration cellulaire aérobie et des fermentations (alcoolique, lactique) dans la régénération de l'ATP.",
            ],
            'definitions' => [
                [
                    'term' => 'Respiration cellulaire vs Fermentation',
                    'definition' => 'La **respiration** est une dégradation complète du glucose en présence de dioxygène ($\text{O}_2$) dans la mitochondrie à fort rendement énergétique ($36\text{ à }38\text{ ATP}$). La **fermentation** est une dégradation incomplète du glucose en anaérobiose dans le hyaloplasme, laissant un résidu organique (éthanol ou acide lactique) avec un faible rendement ($2\text{ ATP}$).',
                ],
            ],
            'theorems' => [
                [
                    'title' => 'Équations-bilans de la respiration et de la fermentation alcoolique',
                    'latex' => '\text{C}_6\text{H}_{12}\text{O}_6 + 6\text{O}_2 \to 6\text{CO}_2 + 6\text{H}_2\text{O} + 38\text{ ATP} \quad ; \quad \text{C}_6\text{H}_{12}\text{O}_6 \to 2\text{C}_2\text{H}_5\text{OH} + 2\text{CO}_2 + 2\text{ ATP}',
                    'conditions' => 'La glycolyse (dans le hyaloplasme) est l\'étape commune initiale qui transforme 1 molécule de glucose en 2 molécules d\'acide pyruvique.',
                    'comment' => 'Le quotient respiratoire vaut $QR = \frac{V_{\text{CO}_2}}{V_{\text{O}_2}} = 1$ pour les glucides ($0{,}7$ pour les lipides et $0{,}8$ pour les protides).',
                ],
            ],
            'demonstration_example' => [
                'statement' => 'Lors de l\'oxydation complète d\'un nutriment, un animal consomme $2{,}0\text{ L}$ de $\text{O}_2$ et rejette $1{,}4\text{ L}$ de $\text{CO}_2$. Calculer le quotient respiratoire $QR$ et identifier la nature du nutriment oxydé.',
                'steps' => [
                    "1. Formule du quotient respiratoire : \$QR = \frac{V(\text{CO}_2)}{V(\text{O}_2)}\$.",
                    "2. Application numérique : \$QR = \frac{1{,}4}{2{,}0} = 0{,}70\$.",
                ],
                'conclusion' => "Comme \$QR = 0{,}7\$, le métabolite énergétique oxydé est un **lipide**.",
            ],
            'rules' => [
                ['tag' => 'Réactifs colorés', 'title' => 'Tests d\'identification des molécules organiques', 'content' => 'Amidon + Eau iodée → coloration bleue-noire | Glucose + Liqueur de Fehling à chaud → précipité rouge brique | Protéines + Réactif du Biuret ($\text{CuSO}_4 + \text{NaOH}$) → coloration violette.'],
            ],
            'trap' => 'Confondre l\'hydrolyse du saccharose (qui est un diholoside non réducteur) avec le glucose (ose réducteur qui réagit directement avec la liqueur de Fehling).',
            'redaction_tip' => 'Précisez toujours la localisation cellulaire de chaque étape : glycolyse dans le hyaloplasme, cycle de Krebs dans la matrice mitochondriale, chaîne respiratoire dans la membrane interne des crêtes mitochondriales.',
            'formula_sheet' => [
                ['name' => 'Quotient respiratoire', 'formula' => 'QR = \frac{V(\text{CO}_2)}{V(\text{O}_2)}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash Métabolisme (60s)',
                'duration' => 60,
                'xp' => 25,
                'questions' => [
                    ['id' => 1, 'prompt' => 'Quelle est l\'étape commune à la respiration cellulaire et aux fermentations ?', 'options' => ['A. Le cycle de Krebs', 'B. La glycolyse dans le hyaloplasme', 'C. La phosphorylation oxydative', 'D. Le cycle de Calvin'], 'correct' => 1, 'expl' => 'La glycolyse (dégradation du glucose en 2 pyruvates + 2 ATP dans le hyaloplasme) ne nécessite pas de dioxygène et constitue le tronc commun.'],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Réaction du Biuret et Xanthoprotéique',
                'xp' => 50,
                'statement' => 'Quelles sont les deux réactions colorées permettant de mettre en évidence une substance protéique en travaux pratiques ?',
                'hint' => 'Pense au sulfate de cuivre en milieu basique et à l\'acide nitrique.',
                'solution' => '1. La **réaction du Biuret** ($\text{CuSO}_4 + \text{NaOH}$) qui donne une coloration violette en présence de liaisons peptidiques. 2. La **réaction xanthoprotéique** ($\text{HNO}_3$ à chaud puis $\text{NH}_4\text{OH}$) qui donne une coloration jaune puis orangée.',
                'bareme' => '3 points.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Effet Pasteur chez la levure de bière',
                'xp' => 100,
                'statement' => 'Expliquer pourquoi les levures placées en milieu aérobie se multiplient beaucoup plus rapidement qu\'en milieu anaérobie pour une même masse de glucose consommée.',
                'bareme' => 'Comparaison des bilans énergétiques en ATP : 6 pts.',
                'solution' => 'En aérobiose, la levure réalise la respiration cellulaire qui produit **38 ATP par mole de glucose**, contre seulement **2 ATP par mole de glucose** par fermentation alcoolique en anaérobiose. L\'énergie disponible pour les synthèses et la division cellulaire est donc 19 fois supérieure.',
            ],
            'suggestions' => [
                'Quelles sont les 3 étapes de la respiration cellulaire ?',
                'Rappelle-moi les tests d\'identification des glucides, lipides et protides',
                'Comment calculer le quotient respiratoire et l\'intensité respiratoire ?',
            ],
        ];
    }

    private function getGenericOfficialCourseData(string $title, string $summary, string $subject): array
    {
        // Extraire les sous-chapitres officiels s'ils figurent dans le résumé ("Chapitres couverts : A, B, C.")
        $subChapters = [];
        if (preg_match('/Chapitres couverts\s*:\s*(.+)$/iu', $summary, $matches)) {
            $rawList = rtrim(trim($matches[1]), '.');
            $parts = preg_split('/\s*,\s*/u', $rawList);
            foreach ($parts as $p) {
                $p = trim($p);
                if ($p !== '') {
                    $subChapters[] = $p;
                }
            }
        }

        $definitions = [];
        if (!empty($subChapters)) {
            foreach ($subChapters as $idx => $sub) {
                $definitions[] = [
                    'term' => 'Axe ' . ($idx + 1) . ' : ' . $sub,
                    'definition' => "Étude approfondie de la notion officielle « {$sub} » dans le cadre du thème « {$title} » au programme national de Terminale (MEPST Togo) : maîtrise des concepts clés, vocabulaire de référence et argumentation structurée.",
                ];
            }
        } else {
            $definitions[] = [
                'term' => 'Concepts fondamentaux : ' . $title,
                'definition' => !empty($summary) ? $summary : "Ensemble des notions et principes directeurs régissant l'étude approfondie du chapitre « {$title} » selon les normes pédagogiques du Ministère des Enseignements Primaire, Secondaire et Technique (MEPST).",
            ];
        }

        $objectives = [
            "Maîtriser les définitions fondamentales et le vocabulaire canonique du chapitre « {$title} ».",
            "Structurer une analyse rigoureuse sur : " . (!empty($subChapters) ? implode(' ; ', $subChapters) : $title) . ".",
            "Développer la méthodologie d'argumentation et de rédaction exigée aux épreuves nationales du BAC 2.",
        ];

        return [
            'code' => strtoupper(substr($subject, 0, 4)) . '_TLE_MOD',
            'title' => $title,
            'subject' => $subject,
            'ref' => 'MEPST Togo · Programme Officiel National Lycée',
            'coeff_badge' => 'Programme Officiel Terminale',
            'duration' => '35 min d\'assimilation',
            'objectives' => $objectives,
            'definitions' => $definitions,
            'theorems' => [
                [
                    'title' => 'Axes directeurs du programme officiel : ' . $title,
                    'latex' => '\\text{' . str_replace(['\\', '{', '}'], '', !empty($subChapters) ? $subChapters[0] : $title) . '}',
                    'conditions' => 'Exigence méthodologique du Baccalauréat Togolais (MEPST).',
                    'comment' => !empty($summary) ? $summary : 'Toute réponse doit être argumentée, illustrée d\'exemples précis et reliée aux notions officielles du chapitre.',
                ],
            ],
            'demonstration_example' => [
                'statement' => "Sujet d'application méthodologique sur le thème « {$title} ».",
                'steps' => [
                    "1. Introduction et problématisation : Définir les termes clés du sujet (« {$title} ») et dégager l'enjeu principal.",
                    "2. Développement structuré : Mobiliser les notions du cours (" . (!empty($subChapters) ? implode(', ', $subChapters) : 'arguments et exemples précis') . ").",
                    "3. Synthèse et conclusion : Répondre clairement à la problématique posée en soignant l'expression écrite.",
                ],
                'conclusion' => "Une copie de BAC réussie sur « {$title} » articule définitions exactes, exemples pertinents et transitions logiques.",
            ],
            'rules' => [
                [
                    'tag' => 'Méthodologie BAC',
                    'title' => 'Exigence des jurys MEPST',
                    'content' => 'L\'Approche Par les Compétences (APC) valorise la pertinence de l\'analyse, la cohérence de l\'argumentation et la correction de la langue.',
                ],
            ],
            'trap' => 'Réciter le cours sans répondre précisément à la consigne ou sans illustrer ses idées par des exemples précis.',
            'redaction_tip' => 'Prenez le temps de définir les notions clés dès l\'introduction et de structurer votre réponse en paragraphes distincts.',
            'formula_sheet' => [
                ['name' => 'Thème officiel', 'formula' => '\\text{Programme MEPST Togo}'],
            ],
            'tier1' => [
                'title' => 'Palier 1 : Quiz Flash de Vérification — ' . $title,
                'duration' => 60,
                'xp' => 20,
                'questions' => [
                    [
                        'id' => 1,
                        'prompt' => "Quelle est l'exigence principale des jurys du BAC sur le chapitre « {$title} » ?",
                        'options' => [
                            'A. Définir les concepts clés, structurer son argumentation et illustrer par des exemples précis',
                            'B. Répondre par une seule phrase sans justification',
                            'C. Faire du hors-sujet',
                            'D. Omettre l\'introduction et la conclusion'
                        ],
                        'correct' => 0,
                        'expl' => "La grille officielle du MEPST évalue la maîtrise des concepts du chapitre « {$title} », la cohérence du plan et la précision des exemples.",
                    ],
                ],
            ],
            'tier2' => [
                'title' => 'Palier 2 : Exercice d\'application — ' . $title,
                'xp' => 50,
                'statement' => "Présenter et expliquer les notions essentielles couvertes par le chapitre « {$title} » (" . (!empty($subChapters) ? implode(', ', $subChapters) : $summary) . ").",
                'hint' => 'Appuie-toi sur les axes définis dans la fiche de cours.',
                'solution' => "1. Définition précise du thème « {$title} ». 2. Développement des axes du programme (" . (!empty($subChapters) ? implode(' ; ', $subChapters) : 'concepts fondamentaux') . ") avec exemples à l'appui.",
                'bareme' => 'Définitions : 2 pts | Pertinence des exemples : 3 pts.',
            ],
            'tier3' => [
                'title' => 'Palier 3 : Sujet de Synthèse Type BAC — ' . $title,
                'xp' => 100,
                'statement' => "Rédiger un paragraphe argumenté de synthèse répondant aux objectifs officiels du thème « {$title} ».",
                'bareme' => 'Évaluation globale sur 6 points selon les critères APC du MEPST.',
                'solution' => "Introduction définissant le sujet, développement structuré autour des axes officiels (" . (!empty($subChapters) ? implode(', ', $subChapters) : $title) . ") et conclusion bilan.",
            ],
            'suggestions' => [
                "Explique-moi en détail le cours sur « {$title} »",
                "Quelles sont les questions fréquentes au BAC sur « {$title} » ?",
                "Donne-moi un résumé structuré à retenir pour l'examen",
            ],
        ];
    }
}


