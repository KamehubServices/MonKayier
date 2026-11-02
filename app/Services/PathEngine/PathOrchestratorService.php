<?php

namespace App\Services\PathEngine;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PathOrchestratorService
{
    /**
     * Récupère ou initialise le plan de révision officiel de l'élève.
     */
    public function getOrCreatePlan(string $studentUserId): object
    {
        $plan = DB::table('revision_plans')
            ->where('student_user_id', $studentUserId)
            ->where('status', 'active')
            ->first();

        if (!$plan) {
            $planId = (string) Str::uuid();
            DB::table('revision_plans')->insert([
                'id' => $planId,
                'student_user_id' => $studentUserId,
                'title' => 'Parcours Personnalisé BAC 2 — Terminale D',
                'target_date' => now()->addMonths(8)->format('Y-m-d'),
                'status' => 'active',
                'generated_by' => 'ai_orchestrator',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $plan = DB::table('revision_plans')->where('id', $planId)->first();
        }

        return $plan;
    }

    /**
     * Planifie les prochaines tâches de révision dans revision_tasks d'après les lacunes et les cours réels.
     */
    public function syncTasks(string $studentUserId, array $gaps, CurriculumCourseMatcher $matcher): array
    {
        $plan = $this->getOrCreatePlan($studentUserId);

        // 1. Récupérer les tâches existantes
        $existingTasks = DB::table('revision_tasks')
            ->where('plan_id', $plan->id)
            ->get();

        $pendingTasks = $existingTasks->where('status', 'pending');

        // Si moins de 3 tâches en attente, on en génère de nouvelles à partir des lacunes
        if ($pendingTasks->count() < 3) {
            $dayOffset = 0;
            foreach (array_slice($gaps, 0, 5) as $gap) {
                $objectiveId = $gap['objective_id'] ?? null;

                // Vérifier si une tâche pour cet objectif existe déjà en attente
                if ($objectiveId) {
                    $alreadyScheduled = $pendingTasks->firstWhere('objective_id', $objectiveId);
                    if ($alreadyScheduled) continue;
                }

                // Trouver le cours réel correspondant à ce besoin
                $matchedCourse = $matcher->matchCourseForGap($studentUserId, $gap);

                $dueAt = now()->addDays($dayOffset)->setHour(18)->setMinute(0)->setSecond(0);

                DB::table('revision_tasks')->insert([
                    'id' => (string) Str::uuid(),
                    'plan_id' => $plan->id,
                    'objective_id' => $objectiveId,
                    'resource_id' => $matchedCourse ? $matchedCourse->id : null,
                    'due_at' => $dueAt,
                    'status' => 'pending',
                    'priority' => (int) ($gap['urgency_score'] ?? 100),
                    'created_at' => now(),
                ]);

                $dayOffset++;
            }
        }

        // Retourne la liste ordonnée des tâches avec leurs cours et objectifs
        return DB::table('revision_tasks')
            ->leftJoin('learning_resources', 'revision_tasks.resource_id', '=', 'learning_resources.id')
            ->leftJoin('subjects', 'learning_resources.subject_id', '=', 'subjects.id')
            ->leftJoin('curriculum_objectives', 'revision_tasks.objective_id', '=', 'curriculum_objectives.id')
            ->where('revision_tasks.plan_id', $plan->id)
            ->select(
                'revision_tasks.*',
                'learning_resources.title as course_title',
                'learning_resources.summary as course_summary',
                'learning_resources.resource_type as course_type',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'curriculum_objectives.description as objective_desc'
            )
            ->orderBy('revision_tasks.status', 'desc') // pending d'abord
            ->orderBy('revision_tasks.priority', 'desc')
            ->orderBy('revision_tasks.due_at', 'asc')
            ->get()
            ->toArray();
    }
}
