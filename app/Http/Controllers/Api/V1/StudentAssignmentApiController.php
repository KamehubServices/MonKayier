<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AssessmentAttempt;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentAssignmentApiController extends Controller
{
    /**
     * GET /api/v1/student/assignments
     * Liste des devoirs assignés à la classe de l'élève.
     */
    public function index(Request $request): JsonResponse
    {
        $student = $request->user();

        // Trouver les classes où l'élève est inscrit
        $classIds = \App\Models\ClassMembership::where('user_id', $student->id)
            ->where('membership_role', 'student')
            ->where('status', 'active')
            ->pluck('class_id');

        $assignments = Assignment::with(['assessment.subject', 'teacher.profile'])
            ->whereIn('class_id', $classIds)
            ->where('status', 'published')
            ->orderBy('due_at')
            ->get();

        // Récupérer les soumissions existantes de l'élève
        $submissions = AssignmentSubmission::with('grade')
            ->where('student_user_id', $student->id)
            ->get()
            ->keyBy('assignment_id');

        return response()->json([
            'success' => true,
            'data' => $assignments->map(function ($a) use ($submissions) {
                $sub = $submissions->get($a->id);
                return [
                    'id' => $a->id,
                    'title' => $a->title,
                    'subject' => $a->assessment?->subject?->name,
                    'due_at' => $a->due_at?->toIso8601String(),
                    'is_past_due' => $a->isPastDue(),
                    'teacher_name' => $a->teacher?->profile ? ($a->teacher->profile->first_name . ' ' . $a->teacher->profile->last_name) : 'Enseignant',
                    'assessment_id' => $a->assessment_id,
                    'submission' => $sub ? [
                        'status' => $sub->status,
                        'submitted_at' => $sub->submitted_at?->toIso8601String(),
                        'grade' => $sub->grade ? (float) $sub->grade->final_score : null,
                        'feedback' => $sub->teacher_feedback,
                    ] : null,
                ];
            }),
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/assignments/{id}/submit
     * Lier une tentative d'évaluation réalisée comme soumission officielle du devoir.
     */
    public function submit(string $id, Request $request): JsonResponse
    {
        $student = $request->user();
        $assignment = Assignment::findOrFail($id);

        $validated = $request->validate([
            'attempt_id' => 'required|uuid|exists:assessment_attempts,id',
        ]);

        $attempt = AssessmentAttempt::findOrFail($validated['attempt_id']);

        if ($attempt->student_user_id !== $student->id) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'FORBIDDEN', 'message' => "Vous ne pouvez pas soumettre la tentative d'un autre élève."],
            ], 403);
        }

        if ($attempt->assessment_id !== $assignment->assessment_id) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'MISMATCH', 'message' => "Cette tentative ne correspond pas à l'évaluation du devoir."],
            ], 422);
        }

        $submission = AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_user_id' => $student->id,
            ],
            [
                'attempt_id' => $attempt->id,
                'submitted_at' => now(),
                'status' => 'submitted',
            ]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'submission_id' => $submission->id,
                'assignment_id' => $assignment->id,
                'status' => $submission->status,
                'submitted_at' => $submission->submitted_at->toIso8601String(),
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }
}
