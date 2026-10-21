<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ParentStudentLink;
use App\Services\ParentSupervisionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ParentApiController extends Controller
{
    public function __construct(
        protected ParentSupervisionService $supervisionService
    ) {}

    /**
     * GET /api/v1/parent/children
     */
    public function children(Request $request): JsonResponse
    {
        $parent = $request->user();

        $links = ParentStudentLink::with('student.profile')
            ->where('parent_user_id', $parent->id)
            ->where('status', 'verified')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $links->map(function ($link) {
                return [
                    'student_id' => $link->student_user_id,
                    'student_name' => $link->student?->profile ? ($link->student->profile->first_name . ' ' . $link->student->profile->last_name) : 'Élève',
                    'relationship_type' => $link->relationship_type,
                    'verified_at' => $link->verified_at?->toIso8601String(),
                ];
            }),
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * POST /api/v1/parent/link
     */
    public function linkChild(Request $request): JsonResponse
    {
        $parent = $request->user();

        $validated = $request->validate([
            'code' => 'required|string|min:4|max:12',
            'relationship_type' => 'nullable|string|in:parent,tutor,guardian',
        ]);

        try {
            $link = $this->supervisionService->verifyAndLinkParent(
                $parent,
                $validated['code'],
                $validated['relationship_type'] ?? 'parent'
            );
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'INVALID_CODE', 'message' => $e->getMessage()],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'link_id' => $link->id,
                'student_id' => $link->student_user_id,
                'status' => $link->status,
                'verified_at' => $link->verified_at->toIso8601String(),
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ], 201);
    }

    /**
     * GET /api/v1/parent/children/{id}/report
     */
    public function report(string $id, Request $request): JsonResponse
    {
        $parent = $request->user();

        try {
            $overview = $this->supervisionService->getStudentSupervisionOverview($parent, $id);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'FORBIDDEN', 'message' => $e->getMessage()],
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $overview,
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * POST /api/v1/student/link-code
     * Généré par l'élève pour transmettre à ses parents.
     */
    public function generateStudentCode(Request $request): JsonResponse
    {
        $student = $request->user();

        $code = $this->supervisionService->generateLinkCode($student);

        return response()->json([
            'success' => true,
            'data' => [
                'link_code' => $code,
                'expires_in_hours' => 24,
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ], 201);
    }
}
