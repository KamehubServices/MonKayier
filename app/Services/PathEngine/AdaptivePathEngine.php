<?php

namespace App\Services\PathEngine;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdaptivePathEngine
{
    protected GapAnalyzerService $gapAnalyzer;
    protected CurriculumCourseMatcher $courseMatcher;
    protected PathOrchestratorService $pathOrchestrator;
    protected LearningSessionGenerator $sessionGenerator;

    public function __construct(
        GapAnalyzerService $gapAnalyzer,
        CurriculumCourseMatcher $courseMatcher,
        PathOrchestratorService $pathOrchestrator,
        LearningSessionGenerator $sessionGenerator
    ) {
        $this->gapAnalyzer = $gapAnalyzer;
        $this->courseMatcher = $courseMatcher;
        $this->pathOrchestrator = $pathOrchestrator;
        $this->sessionGenerator = $sessionGenerator;
    }

    /**
     * Structure complète du parcours adaptatif de l'élève.
     */
    public function getFullPath(string $studentUserId): array
    {
        $plan = $this->pathOrchestrator->getOrCreatePlan($studentUserId);
        $tasks = DB::table('revision_tasks')
            ->where('revision_plan_id', $plan->id)
            ->orderBy('scheduled_date', 'asc')
            ->get();

        return [
            'plan' => $plan,
            'tasks' => $tasks,
            'total_tasks' => count($tasks),
            'completed_tasks' => $tasks->where('status', 'completed')->count(),
        ];
    }

    /**
     * Génère la séance personnalisée active du jour pour l'élève.
     */
    public function getDailySession(string $studentUserId): array
    {
        $gaps = $this->gapAnalyzer->analyzeGaps($studentUserId);
        $tasks = $this->pathOrchestrator->syncTasks($studentUserId, $gaps, $this->courseMatcher);

        $activeTask = null;
        foreach ($tasks as $t) {
            if ($t->status === 'pending') {
                $activeTask = $t;
                break;
            }
        }

        if (!$activeTask && !empty($tasks)) {
            $activeTask = $tasks[0];
        }

        if ($activeTask) {
            $session = $this->sessionGenerator->generateSession($activeTask);
            $session['task_id'] = $activeTask->id;
            $session['course_id'] = $activeTask->resource_id;
            $session['course_title'] = $activeTask->course_title ?? 'Limites, continuité et TVI';
            $session['course_summary'] = $activeTask->course_summary ?? 'Cours officiel de Terminale D';
            $session['priority'] = $activeTask->priority;
            $session['has_real_course'] = !empty($activeTask->resource_id);
            return $session;
        }

        return [
            'subject' => 'Mathématiques',
            'subject_code' => 'MATHS',
            'chapter' => 'Limites, continuité et théorème des valeurs intermédiaires (TVI)',
            'step' => 5,
            'duration' => '25 min',
            'type' => 'diagnostic_gap',
            'summary' => "Maîtriser les asymptotes, la continuité et le corollaire du TVI pour l'épreuve du BAC 2 Série D.",
            'badge' => 'Séance du Jour · Étape active 05',
        ];
    }

    /**
     * Valide une tâche de révision et actualise le parcours.
     */
    public function completeDailyTask(string $studentUserId, string $taskId, int $score): bool
    {
        $task = DB::table('revision_tasks')->where('id', $taskId)->first();
        if (!$task) return false;

        DB::table('revision_tasks')->where('id', $taskId)->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if ($task->objective_id) {
            $masteryGain = round(($score / 20) * 100);
            DB::table('student_competencies')->updateOrInsert(
                ['student_user_id' => $studentUserId, 'objective_id' => $task->objective_id],
                [
                    'id' => (string) Str::uuid(),
                    'mastery_score' => $masteryGain,
                    'evidence_count' => DB::raw('COALESCE(evidence_count, 0) + 1'),
                    'updated_at' => now(),
                ]
            );
        }

        return true;
    }

    /**
     * Matières et coefficients officiels par niveau et série du Lycée.
     */
    public static function getOfficialCoeffs(string $className): array
    {
        $classNameUpper = strtoupper($className);

        // 1. Terminale D
        if (str_contains($classNameUpper, 'TERMINALE') && str_contains($classNameUpper, 'D')) {
            return [
                'MATHS' => ['name' => 'Mathématiques', 'coeff' => 4, 'subject_id' => '01a161a5-8782-7314-b217-a13b54a3c124', 'color' => '#0284C7'],
                'PCT' => ['name' => 'Physique-Chimie', 'coeff' => 4, 'subject_id' => '01a161a5-878f-7087-a5ba-92866a8f6413', 'color' => '#D97706'],
                'SVT' => ['name' => 'SVT', 'coeff' => 4, 'subject_id' => '01a161a5-879c-734a-bc06-149d55c042a7', 'color' => '#059669'],
                'FRANCAIS' => ['name' => 'Français', 'coeff' => 2, 'subject_id' => '01a161a5-87a3-7042-b3fc-0b750ed8ff04', 'color' => '#DC2626'],
                'PHILO' => ['name' => 'Philosophie', 'coeff' => 2, 'subject_id' => '01a161a5-87ae-73d5-85f1-8163bf117be9', 'color' => '#7C3AED'],
                'HIST_GEO' => ['name' => 'Histoire-Géo', 'coeff' => 2, 'subject_id' => '01a161a5-87bb-734b-9505-98ff14bdb53e', 'color' => '#EA580C'],
                'ANGLAIS' => ['name' => 'Anglais', 'coeff' => 2, 'subject_id' => '01a161a5-87b4-73c2-8267-b1895c8b6b34', 'color' => '#2563EB'],
            ];
        }

        // 2. Terminale C
        if (str_contains($classNameUpper, 'TERMINALE') && str_contains($classNameUpper, 'C')) {
            return [
                'MATHS' => ['name' => 'Mathématiques', 'coeff' => 6, 'subject_id' => '01a161a5-8782-7314-b217-a13b54a3c124', 'color' => '#0284C7'],
                'PCT' => ['name' => 'Physique-Chimie', 'coeff' => 5, 'subject_id' => '01a161a5-878f-7087-a5ba-92866a8f6413', 'color' => '#D97706'],
                'SVT' => ['name' => 'SVT', 'coeff' => 2, 'subject_id' => '01a161a5-879c-734a-bc06-149d55c042a7', 'color' => '#059669'],
                'FRANCAIS' => ['name' => 'Français', 'coeff' => 2, 'subject_id' => '01a161a5-87a3-7042-b3fc-0b750ed8ff04', 'color' => '#DC2626'],
                'PHILO' => ['name' => 'Philosophie', 'coeff' => 2, 'subject_id' => '01a161a5-87ae-73d5-85f1-8163bf117be9', 'color' => '#7C3AED'],
            ];
        }

        // 3. Terminale A4
        if (str_contains($classNameUpper, 'TERMINALE') && str_contains($classNameUpper, 'A')) {
            return [
                'PHILO' => ['name' => 'Philosophie', 'coeff' => 6, 'subject_id' => '01a161a5-87ae-73d5-85f1-8163bf117be9', 'color' => '#7C3AED'],
                'FRANCAIS' => ['name' => 'Français', 'coeff' => 5, 'subject_id' => '01a161a5-87a3-7042-b3fc-0b750ed8ff04', 'color' => '#DC2626'],
                'HIST_GEO' => ['name' => 'Histoire-Géo', 'coeff' => 4, 'subject_id' => '01a161a5-87bb-734b-9505-98ff14bdb53e', 'color' => '#EA580C'],
                'ANGLAIS' => ['name' => 'Anglais', 'coeff' => 4, 'subject_id' => '01a161a5-87b4-73c2-8267-b1895c8b6b34', 'color' => '#2563EB'],
                'MATHS' => ['name' => 'Mathématiques', 'coeff' => 2, 'subject_id' => '01a161a5-8782-7314-b217-a13b54a3c124', 'color' => '#0284C7'],
            ];
        }

        // 4. Première C / D
        if (str_contains($classNameUpper, 'PREMIERE') || str_contains($classNameUpper, '1ERE')) {
            return [
                'MATHS' => ['name' => 'Mathématiques', 'coeff' => 5, 'subject_id' => '01a161a5-8782-7314-b217-a13b54a3c124', 'color' => '#0284C7'],
                'PCT' => ['name' => 'Physique-Chimie', 'coeff' => 5, 'subject_id' => '01a161a5-878f-7087-a5ba-92866a8f6413', 'color' => '#D97706'],
                'SVT' => ['name' => 'SVT', 'coeff' => 4, 'subject_id' => '01a161a5-879c-734a-bc06-149d55c042a7', 'color' => '#059669'],
                'FRANCAIS' => ['name' => 'Français', 'coeff' => 3, 'subject_id' => '01a161a5-87a3-7042-b3fc-0b750ed8ff04', 'color' => '#DC2626'],
                'HIST_GEO' => ['name' => 'Histoire-Géo', 'coeff' => 2, 'subject_id' => '01a161a5-87bb-734b-9505-98ff14bdb53e', 'color' => '#EA580C'],
            ];
        }

        // Par défaut (Terminale D standard)
        return self::getOfficialCoeffs('Terminale D');
    }

    /**
     * Calcule la note calibrée combinée : 40% Test QCM + 60% Bulletins de classe.
     */
    public static function computeCalibratedScore(string $studentId, ?float $testScore = null): array
    {
        // 1. Récupération des notes de bulletins enregistrés
        $reports = DB::table('student_report_cards')
            ->where('student_user_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();

        $reportAvg = null;
        $hasReports = false;
        if ($reports->isNotEmpty()) {
            $sum = 0;
            $cnt = 0;
            foreach ($reports as $r) {
                if ($r->overall_average !== null && $r->overall_average > 0) {
                    $sum += (float) $r->overall_average;
                    $cnt++;
                }
            }
            if ($cnt > 0) {
                $reportAvg = round($sum / $cnt, 2);
                $hasReports = true;
            }
        }

        // 2. Récupération du score QCM
        if ($testScore === null) {
            $lastAttempt = DB::table('assessment_attempts')
                ->where('student_user_id', $studentId)
                ->where('status', 'submitted')
                ->orderBy('submitted_at', 'desc')
                ->first();
            $testScore = $lastAttempt ? (float) $lastAttempt->score : 13.5;
        }

        // 3. Combinaison pondérée
        if ($hasReports && $reportAvg !== null) {
            $combinedScore = round(($testScore * 0.40) + ($reportAvg * 0.60), 1);
            $reliability = 95;
            $reliabilityLabel = 'Fiabilité 95% (Test QCM + Bulletins de classe)';
        } else {
            $combinedScore = round($testScore, 1);
            $reliability = 82;
            $reliabilityLabel = 'Fiabilité 82% (Test QCM seul)';
        }

        // 4. Mention officielle
        $mention = 'Passable';
        if ($combinedScore >= 16) $mention = 'Mention Très Bien';
        elseif ($combinedScore >= 14) $mention = 'Mention Bien';
        elseif ($combinedScore >= 12) $mention = 'Mention Assez Bien';
        elseif ($combinedScore >= 10) $mention = 'Passable (Admis)';
        else $mention = 'Renforcement impératif';

        return [
            'combined_score' => $combinedScore,
            'test_score' => $testScore,
            'report_avg' => $reportAvg,
            'has_reports' => $hasReports,
            'reliability' => $reliability,
            'reliability_label' => $reliabilityLabel,
            'mention' => $mention,
        ];
    }

    /**
     * Génère la structure complète des 16 Jalons officiels ordonnancés pour la série de l'élève.
     */
    public static function buildCurriculumJalons(string $studentId, string $className = 'Terminale D'): array
    {
        $calib = self::computeCalibratedScore($studentId);
        $score = $calib['combined_score'];

        // Définition des 16 chapitres officiels majeurs du BAC 2 Série D (avec coordonnées pour le tracé du dragon)
        $curriculumMap = [
            1 => [
                'title' => 'Analyse & Primitives',
                'subject' => 'Maths',
                'subject_code' => 'MATHS',
                'subject_id' => '01a161a5-8782-7314-b217-a13b54a3c124',
                'resource_id' => '6db75430-87bc-4e40-b0f4-807fc0aece0c',
                'coeff' => 4,
                'xp' => 50,
                'duration' => '30 min',
                'x' => 200, 'y' => 220,
            ],
            2 => [
                'title' => 'Cinématique & Lois de Newton',
                'subject' => 'Physique-Chimie',
                'subject_code' => 'PCT',
                'subject_id' => '01a161a5-878f-7087-a5ba-92866a8f6413',
                'resource_id' => 'cc1bed02-6404-4d62-92e6-8b953727dae0',
                'coeff' => 4,
                'xp' => 50,
                'duration' => '35 min',
                'x' => 400, 'y' => 140,
            ],
            3 => [
                'title' => 'Génétique formelle & Hérédité',
                'subject' => 'SVT',
                'subject_code' => 'SVT',
                'subject_id' => '01a161a5-879c-734a-bc06-149d55c042a7',
                'resource_id' => 'a0ab6520-11dd-4c16-85a8-31b936250e68',
                'coeff' => 4,
                'xp' => 45,
                'duration' => '25 min',
                'x' => 600, 'y' => 300,
            ],
            4 => [
                'title' => 'Contraction de texte & Discussion au BAC',
                'subject' => 'Français',
                'subject_code' => 'FRANCAIS',
                'subject_id' => '01a161a5-87a3-7042-b3fc-0b750ed8ff04',
                'resource_id' => 'fr_meth_01',
                'coeff' => 2,
                'xp' => 40,
                'duration' => '25 min',
                'x' => 800, 'y' => 150,
            ],
            5 => [
                'title' => 'Limites, Continuité & Théorème TVI',
                'subject' => 'Maths',
                'subject_code' => 'MATHS',
                'subject_id' => '01a161a5-8782-7314-b217-a13b54a3c124',
                'resource_id' => '387f1b9e-f14a-461d-ba6b-733ee19894d6',
                'coeff' => 4,
                'xp' => 70,
                'duration' => '25 min',
                'x' => 1000, 'y' => 290,
            ],
            6 => [
                'title' => 'Dosages acido-basiques & pH-métrie',
                'subject' => 'Physique-Chimie',
                'subject_code' => 'PCT',
                'subject_id' => '01a161a5-878f-7087-a5ba-92866a8f6413',
                'resource_id' => '643149a1-567d-4c37-a92c-b702f943e9ed',
                'coeff' => 4,
                'xp' => 60,
                'duration' => '30 min',
                'x' => 1200, 'y' => 150,
            ],
            7 => [
                'title' => 'Immunologie & Système de Défense',
                'subject' => 'SVT',
                'subject_code' => 'SVT',
                'subject_id' => '01a161a5-879c-734a-bc06-149d55c042a7',
                'resource_id' => '44458e55-5535-4bef-8c75-a48be3875e6a',
                'coeff' => 4,
                'xp' => 60,
                'duration' => '30 min',
                'x' => 1400, 'y' => 300,
            ],
            8 => [
                'title' => 'Le Commentaire composé littéraire',
                'subject' => 'Français',
                'subject_code' => 'FRANCAIS',
                'subject_id' => '01a161a5-87a3-7042-b3fc-0b750ed8ff04',
                'resource_id' => 'fr_meth_02',
                'coeff' => 2,
                'xp' => 60,
                'duration' => '25 min',
                'x' => 1600, 'y' => 150,
            ],
            9 => [
                'title' => 'Nombres complexes & Géométrie',
                'subject' => 'Maths',
                'subject_code' => 'MATHS',
                'subject_id' => '01a161a5-8782-7314-b217-a13b54a3c124',
                'resource_id' => '6db75430-87bc-4e40-b0f4-807fc0aece0c',
                'coeff' => 4,
                'xp' => 70,
                'duration' => '35 min',
                'x' => 1800, 'y' => 290,
            ],
            10 => [
                'title' => 'Électricité & Lois d\'Induction',
                'subject' => 'Physique-Chimie',
                'subject_code' => 'PCT',
                'subject_id' => '01a161a5-878f-7087-a5ba-92866a8f6413',
                'resource_id' => 'cd075534-bb63-456b-bee1-dde16ff8f4e9',
                'coeff' => 4,
                'xp' => 65,
                'duration' => '30 min',
                'x' => 2000, 'y' => 150,
            ],
            11 => [
                'title' => 'Nutrition, Reproduction & Régulation',
                'subject' => 'SVT',
                'subject_code' => 'SVT',
                'subject_id' => '01a161a5-879c-734a-bc06-149d55c042a7',
                'resource_id' => '974597c2-98a6-430a-aa61-b980f0f5c110',
                'coeff' => 4,
                'xp' => 65,
                'duration' => '30 min',
                'x' => 2200, 'y' => 300,
            ],
            12 => [
                'title' => 'Philosophie : L’homme et le monde',
                'subject' => 'Philosophie',
                'subject_code' => 'PHILO',
                'subject_id' => '01a161a5-87ae-73d5-85f1-8163bf117be9',
                'resource_id' => '4588b854-dec2-45dc-bbc2-39ed83a2188f',
                'coeff' => 2,
                'xp' => 60,
                'duration' => '25 min',
                'x' => 2400, 'y' => 150,
            ],
            13 => [
                'title' => 'Suites numériques & Raisonnement par récurrence',
                'subject' => 'Maths',
                'subject_code' => 'MATHS',
                'subject_id' => '01a161a5-8782-7314-b217-a13b54a3c124',
                'resource_id' => '2dac7ba1-c7af-448c-bd43-51ae1d2d2e3a',
                'coeff' => 4,
                'xp' => 75,
                'duration' => '35 min',
                'x' => 2600, 'y' => 290,
            ],
            14 => [
                'title' => 'Chimie organique & Estérification',
                'subject' => 'Physique-Chimie',
                'subject_code' => 'PCT',
                'subject_id' => '01a161a5-878f-7087-a5ba-92866a8f6413',
                'resource_id' => 'd04c358d-3066-4d2a-910e-6904c4bb3972',
                'coeff' => 4,
                'xp' => 70,
                'duration' => '30 min',
                'x' => 2800, 'y' => 150,
            ],
            15 => [
                'title' => 'Probabilités conditionnelles & Dénombrement',
                'subject' => 'Maths',
                'subject_code' => 'MATHS',
                'subject_id' => '01a161a5-8782-7314-b217-a13b54a3c124',
                'resource_id' => 'fd20dbe8-addf-415e-a595-68b5a4ebfc5b',
                'coeff' => 4,
                'xp' => 80,
                'duration' => '35 min',
                'x' => 3000, 'y' => 290,
            ],
            16 => [
                'title' => 'Grand Examen Blanc National BAC 2 (Épreuve Intégrale)',
                'subject' => 'Examen Blanc',
                'subject_code' => 'BAC',
                'subject_id' => '01a161a5-8782-7314-b217-a13b54a3c124',
                'resource_id' => '387f1b9e-f14a-461d-ba6b-733ee19894d6',
                'coeff' => 16,
                'xp' => 250,
                'duration' => '4h 00',
                'x' => 3240, 'y' => 200,
            ],
        ];

        // 1. Échelon de départ calibré par la note globale QCM + Bulletins
        $baseCompleted = 0;
        if ($score >= 16) {
            $baseCompleted = 6;
        } elseif ($score >= 14) {
            $baseCompleted = 4;
        } elseif ($score >= 11) {
            $baseCompleted = 2;
        } else {
            $baseCompleted = 0;
        }

        // 2. Récupération des cours validés dans la Révision IA (tâches de révision complétées)
        $completedResourceIds = DB::table('revision_tasks')
            ->join('revision_plans', 'revision_tasks.plan_id', '=', 'revision_plans.id')
            ->where('revision_plans.student_user_id', $studentId)
            ->where('revision_tasks.status', 'completed')
            ->pluck('revision_tasks.resource_id')
            ->toArray();

        // 3. Récupération du temps d'étude effectif par ressource dans study_sessions
        $studySessions = DB::table('study_sessions')
            ->where('student_user_id', $studentId)
            ->get();
        $studyDurationByResource = [];
        foreach ($studySessions as $ss) {
            if (str_starts_with($ss->source ?? '', 'revision_ia:')) {
                $resId = substr($ss->source, strlen('revision_ia:'));
                $studyDurationByResource[$resId] = ($studyDurationByResource[$resId] ?? 0) + (int) $ss->duration_seconds;
            }
        }

        // 4. Évaluation dynamique de chaque jalon selon le parcours d'apprentissage réel de l'élève
        $activeStep = 1;
        $foundActive = false;

        $nodes = [];
        foreach ($curriculumMap as $step => $data) {
            $isBoss = ($step === 16);
            $resId = $data['resource_id'];
            $timeSpentSec = $studyDurationByResource[$resId] ?? 0;
            $hasTaskCompleted = in_array($resId, $completedResourceIds);

            // Le jalon est considéré comme validé si :
            // a) Il faisait partie du socle initial validé par la note diagnostique
            // b) OU l'élève a complété les exercices de ce cours dans la Révision IA
            $isCompleted = ($step <= $baseCompleted) || $hasTaskCompleted;

            if ($isCompleted) {
                $status = 'completed';
                $nodeScore = min(100, round(75 + ($step * 1.5) + ($hasTaskCompleted ? 10 : 0)));
                $stars = 3;
            } elseif (!$foundActive) {
                // Le premier jalon non-validé devient le jalon actif
                $status = 'active';
                $nodeScore = null;
                $stars = 0;
                $activeStep = $step;
                $foundActive = true;
            } else {
                $status = 'locked';
                $nodeScore = null;
                $stars = 0;
            }

            $type = $isBoss ? 'boss' : ($status === 'active' ? 'current' : 'normal');

            $nodes[] = array_merge($data, [
                'step' => $step,
                'status' => $status,
                'score' => $nodeScore,
                'stars' => $stars,
                'type' => $type,
                'time_spent_seconds' => $timeSpentSec,
                'time_spent_minutes' => round($timeSpentSec / 60),
                'tag' => ($status === 'active') ? 'Étape active du jour' : null,
            ]);
        }

        if (!$foundActive) {
            // Tous les jalons sont complétés, le boss final reste actif
            $activeStep = 16;
        }

        return [
            'nodes' => $nodes,
            'active_step' => $activeStep,
            'calibration' => $calib,
        ];
    }
}
