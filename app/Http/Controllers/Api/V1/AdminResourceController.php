<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ImportJob;
use App\Models\LearningResource;
use App\Models\ResourceVersion;
use App\Services\EditorialWorkflowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminResourceController extends Controller
{
    /**
     * POST /api/v1/admin/imports
     */
    public function createImport(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Accès réservé aux administrateurs.',
                ],
            ], 403);
        }

        $request->validate([
            'file_name' => 'required|string|max:255',
            'file_size_bytes' => 'nullable|integer',
            'total_rows' => 'nullable|integer',
        ]);

        $job = ImportJob::create([
            'file_name' => $request->input('file_name'),
            'file_size_bytes' => $request->input('file_size_bytes', 0),
            'status' => 'pending',
            'total_rows' => $request->input('total_rows', 0),
            'processed_rows' => 0,
            'error_count' => 0,
            'errors_log' => [],
            'created_by' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'job_id' => $job->id,
                'status' => $job->status,
                'file_name' => $job->file_name,
                'created_at' => $job->created_at->toIso8601String(),
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ], 201);
    }

    /**
     * POST /api/v1/admin/resources/{id}/submit-review
     */
    public function submitReview(string $id, Request $request, EditorialWorkflowService $service): JsonResponse
    {
        $resource = LearningResource::findOrFail($id);
        $user = $request->user();

        try {
            $updated = $service->submitForReview($resource, $user);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'WORKFLOW_ERROR',
                    'message' => $e->getMessage(),
                ],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $updated->id,
                'status' => $updated->status,
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * POST /api/v1/admin/resources/{id}/review
     */
    public function review(string $id, Request $request, EditorialWorkflowService $service): JsonResponse
    {
        $request->validate([
            'decision' => 'required|in:approved,rejected,revision_requested',
            'comment' => 'nullable|string|max:1000',
        ]);

        $resource = LearningResource::findOrFail($id);
        $user = $request->user();

        try {
            $review = $service->reviewResource(
                $resource,
                $user,
                $request->input('decision'),
                $request->input('comment')
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'AUTHOR_CANNOT_SELF_APPROVE',
                    'message' => $e->getMessage(),
                ],
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => $e->getMessage(),
                ],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'review_id' => $review->id,
                'decision' => $review->status,
                'resource_status' => $resource->fresh()->status,
                'comment' => $review->comment,
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * POST /api/v1/admin/resources/{id}/publish
     */
    public function publish(string $id, Request $request, EditorialWorkflowService $service): JsonResponse
    {
        $resource = LearningResource::findOrFail($id);
        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Seul un administrateur peut publier une ressource.',
                ],
            ], 403);
        }

        try {
            $published = $service->publishResource($resource, $user);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UNAPPROVED_RESOURCE',
                    'message' => $e->getMessage(),
                ],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $published->id,
                'status' => $published->status,
                'published_at' => $published->published_at->toIso8601String(),
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }

    /**
     * GET /api/v1/admin/content-versions/{id}/diff
     */
    public function diff(string $id, Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Accès réservé aux administrateurs.',
                ],
            ], 403);
        }

        $currentVersion = ResourceVersion::findOrFail($id);
        $previousVersion = ResourceVersion::where('resource_id', $currentVersion->resource_id)
            ->where('version_number', '<', $currentVersion->version_number)
            ->orderBy('version_number', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'resource_id' => $currentVersion->resource_id,
                'current_version' => [
                    'version_number' => $currentVersion->version_number,
                    'checksum' => $currentVersion->checksum,
                    'change_note' => $currentVersion->change_note,
                    'created_at' => $currentVersion->created_at?->toIso8601String(),
                ],
                'previous_version' => $previousVersion ? [
                    'version_number' => $previousVersion->version_number,
                    'checksum' => $previousVersion->checksum,
                    'change_note' => $previousVersion->change_note,
                    'created_at' => $previousVersion->created_at?->toIso8601String(),
                ] : null,
                'has_changes' => $previousVersion ? ($currentVersion->checksum !== $previousVersion->checksum) : true,
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }
}
