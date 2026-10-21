<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SchoolClass;
use App\Services\TeacherAssignmentService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeacherApiController extends Controller
{
    public function __construct(
        protected TeacherAssignmentService $assignmentService
    ) {}

    /**
     * GET /api/v1/teacher/classes
     */
    public function classes(Request $request): JsonResponse
    {
        $teacher = $request->user();

        $classes = SchoolClass::with(['educationLevel', 'series', 'school'])
            ->withCount('students')
            ->whereHas('classSubjects', function ($q) use ($teacher) {
                $q->where('teacher_user_id', $teacher->id);
            })
            ->orWhere('created_by', $teacher->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $classes->map(function ($c) {
                return [
                    'id' => $c->id,
                    'label' => $c->label,
                    'school' => $c->school?->name,
                    'level' => $c->educationLevel?->name,
                    'series' => $c->series?->code,
                    'students_count' => $c->students_count,
                ];
            }),
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * GET /api/v1/teacher/assignments
     */
    public function assignments(Request $request): JsonResponse
    {
        $teacher = $request->user();

        $assignments = Assignment::with(['schoolClass', 'assessment.subject'])
            ->withCount('submissions')
            ->where('teacher_user_id', $teacher->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $assignments->map(function ($a) {
                return [
                    'id' => $a->id,
                    'title' => $a->title,
                    'class' => $a->schoolClass?->label,
                    'subject' => $a->assessment?->subject?->name,
                    'due_at' => $a->due_at?->toIso8601String(),
                    'status' => $a->status,
                    'submissions_count' => $a->submissions_count,
                ];
            }),
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * POST /api/v1/teacher/assignments
     */
    public function createAssignment(Request $request): JsonResponse
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'class_id' => 'required|uuid|exists:classes,id',
            'assessment_id' => 'required|uuid|exists:assessments,id',
            'title' => 'required|string|max:255',
            'due_at' => 'nullable|date',
        ]);

        $class = SchoolClass::findOrFail($validated['class_id']);
        $assessment = Assessment::findOrFail($validated['assessment_id']);
        $dueAt = !empty($validated['due_at']) ? Carbon::parse($validated['due_at']) : null;

        try {
            $assignment = $this->assignmentService->createAssignment(
                $teacher,
                $class,
                $assessment,
                $validated['title'],
                $dueAt
            );
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'FORBIDDEN', 'message' => $e->getMessage()],
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'VALIDATION_ERROR', 'message' => $e->getMessage()],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'class_id' => $assignment->class_id,
                'due_at' => $assignment->due_at?->toIso8601String(),
                'status' => $assignment->status,
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ], 201);
    }

    /**
     * GET /api/v1/teacher/assignments/{id}/submissions
     */
    public function submissions(string $id, Request $request): JsonResponse
    {
        $teacher = $request->user();
        $assignment = Assignment::findOrFail($id);

        if ($assignment->teacher_user_id !== $teacher->id && !$teacher->isAdmin()) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'FORBIDDEN', 'message' => "Accès refusé."],
            ], 403);
        }

        $submissions = AssignmentSubmission::with(['student.profile', 'grade'])
            ->where('assignment_id', $assignment->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $submissions->map(function ($s) {
                return [
                    'id' => $s->id,
                    'student_id' => $s->student_user_id,
                    'student_name' => $s->student?->profile ? ($s->student->profile->first_name . ' ' . $s->student->profile->last_name) : 'Élève',
                    'submitted_at' => $s->submitted_at?->toIso8601String(),
                    'status' => $s->status,
                    'grade' => $s->grade ? (float) $s->grade->final_score : null,
                    'feedback' => $s->teacher_feedback,
                ];
            }),
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * POST /api/v1/teacher/submissions/{id}/grade
     */
    public function gradeSubmission(string $id, Request $request): JsonResponse
    {
        $teacher = $request->user();
        $submission = AssignmentSubmission::with('assignment')->findOrFail($id);

        $validated = $request->validate([
            'final_score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        try {
            $grade = $this->assignmentService->gradeSubmission(
                $teacher,
                $submission,
                (float) $validated['final_score'],
                $validated['feedback'] ?? null
            );
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'FORBIDDEN', 'message' => $e->getMessage()],
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'submission_id' => $submission->id,
                'final_score' => (float) $grade->final_score,
                'feedback' => $submission->fresh()->teacher_feedback,
                'status' => $submission->fresh()->status,
                'graded_at' => $grade->graded_at->toIso8601String(),
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }
}
