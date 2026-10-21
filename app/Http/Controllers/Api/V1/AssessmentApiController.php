<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\RevisionPlan;
use App\Models\RevisionTask;
use App\Services\AssessmentAttemptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AssessmentApiController extends Controller
{
    public function __construct(
        protected AssessmentAttemptService $attemptService
    ) {}

    /**
     * GET /api/v1/assessments
     * Seules les évaluations publiées sont listées.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Assessment::with(['subject', 'resource'])
            ->where('status', 'published');

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->query('subject_id'));
        }

        if ($request->filled('type')) {
            $query->where('assessment_type', $request->query('type'));
        }

        $assessments = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $assessments->items(),
            'meta' => [
                'total' => $assessments->total(),
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * GET /api/v1/assessments/{id}
     * Règle de sécurité : les drapeaux is_correct et les corrigés sont MASQUÉS avant soumission.
     */
    public function show(string $id, Request $request): JsonResponse
    {
        $assessment = Assessment::with(['questions.options', 'subject'])->findOrFail($id);

        if ($assessment->status !== 'published') {
            $user = $request->user();
            if (!$user || !$user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => 'NOT_FOUND',
                        'message' => 'Évaluation introuvable ou non publiée.',
                    ],
                ], 404);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'type' => $assessment->assessment_type,
                'duration_seconds' => $assessment->duration_seconds,
                'subject' => [
                    'id' => $assessment->subject?->id,
                    'code' => $assessment->subject?->code,
                    'name' => $assessment->subject?->name,
                ],
                'questions' => $assessment->questions->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'question_type' => $q->question_type,
                        'prompt' => $q->prompt,
                        'points' => $q->points,
                        'position' => $q->position,
                        // RÈGLE SÉCURITÉ : is_correct n'est JAMAIS envoyé au client avant correction
                        'options' => $q->options->map(function ($opt) {
                            return [
                                'id' => $opt->id,
                                'text' => $opt->option_text,
                                'position' => $opt->position,
                            ];
                        }),
                    ];
                }),
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * POST /api/v1/assessments/{id}/attempts
     */
    public function startAttempt(string $id, Request $request): JsonResponse
    {
        $assessment = Assessment::findOrFail($id);
        $user = $request->user();

        try {
            $idempotencyKey = $request->input('idempotency_key');
            $attempt = $this->attemptService->startAttempt($assessment, $user, $idempotencyKey);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'ATTEMPT_ERROR',
                    'message' => $e->getMessage(),
                ],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'attempt_id' => $attempt->id,
                'assessment_id' => $assessment->id,
                'status' => $attempt->status,
                'started_at' => $attempt->started_at->toIso8601String(),
                'max_score' => $attempt->max_score,
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ], 201);
    }

    /**
     * PUT /api/v1/attempts/{id}/answers/{questionId}
     */
    public function saveAnswer(string $id, string $questionId, Request $request): JsonResponse
    {
        $attempt = AssessmentAttempt::findOrFail($id);
        $user = $request->user();

        $request->validate([
            'answer' => 'required|array',
        ]);

        try {
            $answer = $this->attemptService->saveAnswer($attempt, $questionId, $request->input('answer'), $user);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => $e->getMessage(),
                ],
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SAVE_ERROR',
                    'message' => $e->getMessage(),
                ],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'question_id' => $questionId,
                'saved_at' => $answer->answered_at->toIso8601String(),
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * POST /api/v1/attempts/{id}/submit
     */
    public function submit(string $id, Request $request): JsonResponse
    {
        $attempt = AssessmentAttempt::findOrFail($id);
        $user = $request->user();

        try {
            $graded = $this->attemptService->submitAttempt($attempt, $user);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => $e->getMessage(),
                ],
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'attempt_id' => $graded->id,
                'status' => $graded->status,
                'score' => $graded->score,
                'max_score' => $graded->max_score,
                'percentage' => $graded->scorePercentage(),
                'submitted_at' => $graded->submitted_at?->toIso8601String(),
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * GET /api/v1/attempts/{id}/result
     */
    public function result(string $id, Request $request): JsonResponse
    {
        $attempt = AssessmentAttempt::with(['answers.question', 'assessment.questions.options'])
            ->findOrFail($id);

        $user = $request->user();
        if ($attempt->student_user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => "Accès refusé au résultat d'un autre élève.",
                ],
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'attempt_id' => $attempt->id,
                'score' => $attempt->score,
                'max_score' => $attempt->max_score,
                'percentage' => $attempt->scorePercentage(),
                'submitted_at' => $attempt->submitted_at?->toIso8601String(),
                'answers' => $attempt->answers->map(function ($ans) {
                    return [
                        'question_id' => $ans->question_id,
                        'prompt' => $ans->question?->prompt,
                        'awarded_points' => $ans->awarded_points,
                        'feedback' => $ans->feedback,
                    ];
                }),
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * GET /api/v1/me/revision-plan
     */
    public function revisionPlan(Request $request): JsonResponse
    {
        $user = $request->user();

        $plan = RevisionPlan::with(['pendingTasks.resource.subject'])
            ->where('student_user_id', $user->id)
            ->where('status', 'active')
            ->first();

        return response()->json([
            'success' => true,
            'data' => $plan ? [
                'id' => $plan->id,
                'title' => $plan->title,
                'target_date' => $plan->target_date?->toDateString(),
                'tasks' => $plan->pendingTasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'due_at' => $task->due_at?->toIso8601String(),
                        'priority' => $task->priority,
                        'status' => $task->status,
                        'resource' => $task->resource ? [
                            'id' => $task->resource->id,
                            'title' => $task->resource->title,
                            'subject' => $task->resource->subject?->name,
                        ] : null,
                    ];
                }),
            ] : null,
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * POST /api/v1/me/revision-plan/tasks/{id}/complete
     */
    public function completeRevisionTask(string $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $task = RevisionTask::whereHas('plan', function ($q) use ($user) {
            $q->where('student_user_id', $user->id);
        })->findOrFail($id);

        $task->markAsCompleted();

        return response()->json([
            'success' => true,
            'data' => [
                'task_id' => $task->id,
                'status' => $task->status,
                'completed_at' => $task->completed_at->toIso8601String(),
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }
}
