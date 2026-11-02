<?php

namespace App\Services\PathEngine;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LearningSessionGenerator
{
    /**
     * Génère une séance didactique interactive de 25 minutes adossée au cours réel.
     */
    public function generateSession(object $task): array
    {
        $courseTitle = $task->course_title ?? 'Cours de révision';
        $courseSummary = $task->course_summary ?? 'Rappels fondamentaux du programme officiel';
        $subjectName = $task->subject_name ?? 'Mathématiques';

        // Détection thématique pour une adaptation didactique fidèle
        $titleLower = mb_strtolower($courseTitle . ' ' . $courseSummary . ' ' . ($task->objective_desc ?? ''));

        // 1. Probabilités conditionnelles & Dénombrement (Priorité majeure)
        if (str_contains($titleLower, 'proba') || str_contains($titleLower, 'dénombrement') || str_contains($titleLower, 'aléatoire')) {
            return [
                'session_title' => "Séance du jour · {$courseTitle}",
                'course_title' => $courseTitle,
                'course_summary' => $courseSummary,
                'duration' => '25 min',
                'subject' => $subjectName,
                'recap' => [
                    "Formule de Bayes et probabilités conditionnelles : P_B(A) = P(A ∩ B) / P(B) pour P(B) > 0.",
                    "Formule des probabilités totales : si (B1, B2, ..., Bn) forme une partition de l'univers, alors P(A) = ∑ P(A ∩ Bi).",
                    "Deux événements A et B sont dits indépendants si et seulement si P(A ∩ B) = P(A) × P(B).",
                    "Loi binomiale B(n, p) : P(X = k) = (n choisit k) × p^k × (1 - p)^(n - k).",
                ],
                'example' => [
                    'question' => "Dans un lycée, 60% des élèves sont en Terminale D et 40% en Terminale C. 80% des élèves de D et 90% des élèves de C réussissent leur épreuve de Maths. On choisit un élève au hasard. Calculer la probabilité qu'il réussisse son épreuve.",
                    'steps' => [
                        "1. Partition de l'univers : Soit D l'événement 'être en Terminale D' et C 'être en Terminale C'. Soit R 'réussir l'épreuve'.",
                        "2. Données : P(D) = 0,60 ; P(C) = 0,40 ; P_D(R) = 0,80 ; P_C(R) = 0,90.",
                        "3. Application de la formule des probabilités totales : P(R) = P(D ∩ R) + P(C ∩ R) = P(D) × P_D(R) + P(C) × P_C(R).",
                        "4. Calcul numérique : P(R) = (0,60 × 0,80) + (0,40 × 0,90) = 0,48 + 0,36 = 0,84 (soit 84%).",
                    ],
                ],
                'quiz' => [
                    [
                        'id' => 1,
                        'prompt' => "Si P(A) = 0,4, P(B) = 0,5 et A et B sont indépendants, quelle est la valeur de P(A ∩ B) ?",
                        'options' => ['0,20', '0,90', '0,10', '0,80'],
                        'correct' => 0,
                        'explanation' => "Par indépendance, P(A ∩ B) = P(A) × P(B) = 0,4 × 0,5 = 0,20.",
                    ],
                    [
                        'id' => 2,
                        'prompt' => "La somme des probabilités de toutes les branches issues d'un même nœud d'un arbre pondéré est toujours égale à :",
                        'options' => ['1', '0', '0,5', 'n'],
                        'correct' => 0,
                        'explanation' => "Dans un arbre pondéré, la somme des probabilités issues d'un même nœud vaut toujours 1 (système complet d'événements).",
                    ],
                    [
                        'id' => 3,
                        'prompt' => "Pour une variable aléatoire X suivant la loi binomiale B(10; 0,3), quelle est l'espérance mathématique E(X) ?",
                        'options' => ['3', '0,3', '10', '2,1'],
                        'correct' => 0,
                        'explanation' => "L'espérance d'une loi binomiale B(n, p) est donnée par E(X) = n × p = 10 × 0,3 = 3.",
                    ],
                ],
            ];
        }

        // 2. Limites, continuité & dérivation (Analyse)
        if (str_contains($titleLower, 'limit') || str_contains($titleLower, 'continuit') || str_contains($titleLower, 'asymptot')) {
            return [
                'session_title' => "Séance du jour · {$courseTitle}",
                'course_title' => $courseTitle,
                'course_summary' => $courseSummary,
                'duration' => '25 min',
                'subject' => $subjectName,
                'recap' => [
                    "Une forme indéterminée courante en ±∞ est du type ∞/∞.",
                    "Méthode officielle : factoriser par le monôme de plus haut degré au numérateur et au dénominateur.",
                    "Théorème des gendarmes : si g(x) ≤ f(x) ≤ h(x) et lim g = lim h = L, alors lim f = L.",
                    "Continuité : une fonction f est continue en a si lim_{x→a} f(x) = f(a).",
                ],
                'example' => [
                    'question' => "Calculer lim (4x² - 3x + 1) / (2x² + 5) quand x tend vers +∞.",
                    'steps' => [
                        "1. On factorise par x² : (x²(4 - 3/x + 1/x²)) / (x²(2 + 5/x²))",
                        "2. On simplifie par x² pour x ≠ 0 : (4 - 3/x + 1/x²) / (2 + 5/x²)",
                        "3. Quand x → +∞, les termes 3/x, 1/x² et 5/x² tendent vers 0.",
                        "Conclusion : la limite est 4 / 2 = 2.",
                    ],
                ],
                'quiz' => [
                    [
                        'id' => 1,
                        'prompt' => "Quelle est la limite de (5x³ - 2) / (x³ + 4) quand x tend vers +∞ ?",
                        'options' => ['5', '0', '+∞', '−2'],
                        'correct' => 0,
                        'explanation' => "Le quotient des monômes de plus haut degré est 5x³/x³ = 5.",
                    ],
                    [
                        'id' => 2,
                        'prompt' => "Parmi ces cas, lequel est une forme indéterminée classique ?",
                        'options' => ['+∞ + (+∞)', '0 / +∞', '+∞ − (+∞)', '0 / 1'],
                        'correct' => 2,
                        'explanation' => "+∞ − (+∞) est l'une des 4 formes indéterminées classiques avec 0/0, ∞/∞ et 0 × ∞.",
                    ],
                    [
                        'id' => 3,
                        'prompt' => "Si f(x) tend vers 3 quand x tend vers a, et f(a) = 3, que peut-on affirmer ?",
                        'options' => ['f est continue en a', 'f est dérivable en a', 'f est strictement croissante', 'f s\'annule en a'],
                        'correct' => 0,
                        'explanation' => "Une fonction f est continue en a si et seulement si lim_{x→a} f(x) = f(a).",
                    ],
                ],
            ];
        }

        // 3. Nombres Complexes & Algèbre
        if (str_contains($titleLower, 'complexe') || str_contains($titleLower, 'algébr') || str_contains($titleLower, 'polynôm')) {
            return [
                'session_title' => "Séance du jour · {$courseTitle}",
                'course_title' => $courseTitle,
                'course_summary' => $courseSummary,
                'duration' => '25 min',
                'subject' => $subjectName,
                'recap' => [
                    "Forme algébrique : z = a + ib où a = Re(z) et b = Im(z). Le conjugué est z̄ = a - ib.",
                    "Module : |z| = √(a² + b²) avec |z|² = z · z̄.",
                    "Forme trigonométrique et exponentielle : z = r(cos θ + i sin θ) = r e^(iθ).",
                    "Formule de Moivre : (cos θ + i sin θ)^n = cos(nθ) + i sin(nθ).",
                ],
                'example' => [
                    'question' => "Mettre sous forme trigonométrique le complexe z = 1 + i√3.",
                    'steps' => [
                        "1. Calcul du module : |z| = √(1² + (√3)²) = √(1 + 3) = √4 = 2.",
                        "2. Recherche de l'argument θ : cos θ = 1/2 et sin θ = √3/2.",
                        "3. On en déduit θ = π/3 [2π].",
                        "Conclusion : z = 2 (cos(π/3) + i sin(π/3)) = 2 e^(iπ/3).",
                    ],
                ],
                'quiz' => [
                    [
                        'id' => 1,
                        'prompt' => "Quel est le module de z = 3 - 4i ?",
                        'options' => ['5', '7', '1', '25'],
                        'correct' => 0,
                        'explanation' => "|z| = √(3² + (-4)²) = √(9 + 16) = √25 = 5.",
                    ],
                    [
                        'id' => 2,
                        'prompt' => "Quelle est la valeur de i² selon la définition du corps des complexes ?",
                        'options' => ['-1', '1', '0', '-i'],
                        'correct' => 0,
                        'explanation' => "Par convention fondamentale dans C, i² = -1.",
                    ],
                    [
                        'id' => 3,
                        'prompt' => "Si arg(z) = π/4 et arg(z') = π/2, quel est l'argument du produit z × z' ?",
                        'options' => ['3π/4', 'π/8', '2π/3', 'π/4'],
                        'correct' => 0,
                        'explanation' => "L'argument d'un produit est la somme des arguments : arg(z z') = π/4 + π/2 = 3π/4 [2π].",
                    ],
                ],
            ];
        }

        // 4. Géométrie dans l'espace
        if (str_contains($titleLower, 'espace') || str_contains($titleLower, 'scalaire') || str_contains($titleLower, 'géométri')) {
            return [
                'session_title' => "Séance du jour · {$courseTitle}",
                'course_title' => $courseTitle,
                'course_summary' => $courseSummary,
                'duration' => '25 min',
                'subject' => $subjectName,
                'recap' => [
                    "Expression analytique du produit scalaire dans un repère orthonormal : u · v = xx' + yy' + zz'.",
                    "Condition d'orthogonalité : u et v sont orthogonaux si et seulement si u · v = 0.",
                    "Équation cartésienne d'un plan orthogonal au vecteur normal n(a, b, c) : ax + by + cz + d = 0.",
                    "Distance d'un point M0(x0, y0, z0) au plan (P) : d = |ax0 + by0 + cz0 + d| / √(a² + b² + c²).",
                ],
                'example' => [
                    'question' => "Déterminer une équation cartésienne du plan (P) passant par A(1, 2, -1) et de vecteur normal n(2, -3, 1).",
                    'steps' => [
                        "1. Comme n(2, -3, 1) est normal, (P) a une équation de la forme 2x - 3y + z + d = 0.",
                        "2. A(1, 2, -1) appartient à (P), donc ses coordonnées vérifient l'équation : 2(1) - 3(2) + (-1) + d = 0.",
                        "3. Calcul : 2 - 6 - 1 + d = 0 <=> -5 + d = 0 <=> d = 5.",
                        "Conclusion : L'équation du plan est 2x - 3y + z + 5 = 0.",
                    ],
                ],
                'quiz' => [
                    [
                        'id' => 1,
                        'prompt' => "Deux vecteurs u(1, 2, -1) et v(4, -1, 2) sont-ils orthogonaux ?",
                        'options' => ['Oui car u · v = 0', 'Non car u · v = 4', 'Non car u · v = 2', 'Ils sont colinéaires'],
                        'correct' => 0,
                        'explanation' => "u · v = 1(4) + 2(-1) + (-1)(2) = 4 - 2 - 2 = 0. Le produit scalaire est nul, donc ils sont orthogonaux.",
                    ],
                    [
                        'id' => 2,
                        'prompt' => "Quel est un vecteur normal au plan d'équation 3x - y + 4z - 7 = 0 ?",
                        'options' => ['n(3, -1, 4)', 'n(3, 1, 4)', 'n(-7, 3, -1)', 'n(1, -1, 1)'],
                        'correct' => 0,
                        'explanation' => "Les coefficients de x, y, z dans l'équation cartésienne ax + by + cz + d = 0 forment directement les coordonnées d'un vecteur normal.",
                    ],
                    [
                        'id' => 3,
                        'prompt' => "Quelle est la norme du vecteur u(1, 2, 2) ?",
                        'options' => ['3', '5', '9', '√5'],
                        'correct' => 0,
                        'explanation' => "||u|| = √(1² + 2² + 2²) = √(1 + 4 + 4) = √9 = 3.",
                    ],
                ],
            ];
        }

        // 5. Suites numériques et récurrence (Cours uploadé de l'élève)
        if (str_contains($titleLower, 'suite') || str_contains($titleLower, 'récurrence')) {
            return [
                'session_title' => "Séance du jour · {$courseTitle}",
                'course_title' => $courseTitle,
                'course_summary' => $courseSummary,
                'duration' => '25 min',
                'subject' => $subjectName,
                'recap' => [
                    "Raisonnement par récurrence en 3 étapes : Initialisation (P(n0) vraie), Hérédité (si P(k) vraie alors P(k+1) vraie), Conclusion.",
                    "Suite géométrique de raison q : un = u0 × q^n. Si |q| < 1, alors lim un = 0.",
                    "Théorème de convergence monotone : toute suite croissante et majorée est convergente.",
                ],
                'example' => [
                    'question' => "Soit la suite (un) définie par u0 = 2 et un+1 = 3un - 1. Démontrer par récurrence que un > 0 pour tout n.",
                    'steps' => [
                        "1. Initialisation : Pour n = 0, u0 = 2 > 0. La proposition est vraie au rang 0.",
                        "2. Hérédité : Supposons que pour un entier k fixé, uk > 0. Alors 3uk > 0, mais est-ce suffisant ?",
                        "Montrons plus précisément que uk ≥ 1/2. Comme u0 = 2, 3(2) - 1 = 5 > 2.",
                        "3. Conclusion : Par récurrence, un > 0 pour tout n ∈ N.",
                    ],
                ],
                'quiz' => [
                    [
                        'id' => 1,
                        'prompt' => "Si une suite est décroissante et minorée par 0, que peut-on affirmer ?",
                        'options' => ['Elle converge', 'Elle diverge vers -∞', 'Elle s\'annule forcément', 'Elle est géométrique'],
                        'correct' => 0,
                        'explanation' => "Par le théorème de convergence monotone, toute suite décroissante et minorée est convergente.",
                    ],
                    [
                        'id' => 2,
                        'prompt' => "Quelle est la première étape obligatoire d'un raisonnement par récurrence ?",
                        'options' => ['L\'initialisation', 'L\'hérédité', 'La factorisation', 'La dérivation'],
                        'correct' => 0,
                        'explanation' => "L'initialisation vérifie que la propriété est vraie au rang de départ n0.",
                    ],
                    [
                        'id' => 3,
                        'prompt' => "Quelle est la limite de la suite un = (0,8)^n quand n tend vers +∞ ?",
                        'options' => ['0', '1', '+∞', '0,8'],
                        'correct' => 0,
                        'explanation' => "Pour tout réel q tel que -1 < q < 1, lim q^n = 0. Ici q = 0,8 donc la limite est 0.",
                    ],
                ],
            ];
        }

        // Modèle didactique enrichi par défaut
        return [
            'session_title' => "Séance du jour · {$courseTitle}",
            'course_title' => $courseTitle,
            'course_summary' => $courseSummary,
            'duration' => '25 min',
            'subject' => $subjectName,
            'recap' => [
                "Revoir avec précision les définitions et théorèmes du support officiel {$courseTitle}.",
                "Identifier les formules clés et leurs domaines de validité au BAC.",
                "Soigner la rédaction : nommer les théorèmes utilisés avant d'effectuer les calculs.",
            ],
            'example' => [
                'question' => "Application type BAC relative aux notions fondamentales de {$courseTitle}.",
                'steps' => [
                    "1. Énoncer les hypothèses requises par le programme officiel.",
                    "2. Poser la formule mathématique ou physique appropriée.",
                    "3. Rédiger la conclusion avec rigueur.",
                ],
            ],
            'quiz' => [
                [
                    'id' => 1,
                    'prompt' => "Quelle est la condition indispensable pour appliquer un théorème lors d'une épreuve officielle ?",
                    'options' => ['Toutes les hypothèses doivent être justifiées', 'Connaître le résultat à l\'avance', 'Utiliser une calculatrice programmable', 'Écrire en rouge'],
                    'correct' => 0,
                    'explanation' => "Les barèmes du BAC sanctionnent systématiquement l'application d'un théorème sans vérification de ses hypothèses.",
                ],
                [
                    'id' => 2,
                    'prompt' => "Comment maximiser ses points lors de la rédaction d'un exercice d'examen ?",
                    'options' => ['Expliciter chaque étape de calcul avec rigueur', 'Donner directement la réponse finale', 'Rédiger uniquement au brouillon', 'Sauter les justifications'],
                    'correct' => 0,
                    'explanation' => "La démarche et la clarté des justifications représentent la majeure partie des points du barème officiel.",
                ],
            ],
        ];
    }
}
