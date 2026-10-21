<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LearningResource;
use App\Services\CurriculumReferentialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LearningResourceController extends Controller
{
    /**
     * GET /api/v1/resources
     * Strict isolation: Only published resources are accessible.
     */
    public function index(Request $request, CurriculumReferentialService $service): JsonResponse
    {
        $resources = $service->getPublicResources($request->all());

        return response()->json([
            'success' => true,
            'data' => $resources->items(),
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
                'current_page' => $resources->currentPage(),
                'last_page' => $resources->lastPage(),
                'per_page' => $resources->perPage(),
                'total' => $resources->total(),
            ],
        ]);
    }

    /**
     * GET /api/v1/resources/{id}
     */
    public function show(string $id, Request $request): JsonResponse
    {
        $resource = LearningResource::with([
            'subject',
            'educationLevel',
            'sources',
            'topics',
            'latestVersion.files',
            'latestVersion.pages',
        ])->find($id);

        if (!$resource) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'NOT_FOUND',
                    'message' => 'Ressource introuvable.',
                ],
            ], 404);
        }

        $user = $request->user();

        // Règle de confidentialité : les brouillons et rejets ne sont visibles que par l'auteur ou l'admin
        if ($resource->status !== 'published') {
            if (!$user || ($resource->created_by !== $user->id && !$user->isAdmin())) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => 'FORBIDDEN',
                        'message' => 'Cette ressource n’est pas encore publiée ou validée.',
                    ],
                ], 403);
            }
        }

        // Protection des corrigés restreints
        $canAccessSolution = true;
        if ($resource->is_solution_restricted) {
            $canAccessSolution = $user && ($user->isAdmin() || $user->isTeacher());
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $resource->id,
                'title' => $resource->title,
                'summary' => $resource->summary,
                'resource_type' => $resource->resource_type,
                'origin' => $resource->origin,
                'difficulty_level' => $resource->difficulty_level,
                'status' => $resource->status,
                'published_at' => $resource->published_at?->toIso8601String(),
                'subject' => [
                    'id' => $resource->subject?->id,
                    'code' => $resource->subject?->code,
                    'name' => $resource->subject?->name,
                ],
                'level' => [
                    'id' => $resource->educationLevel?->id,
                    'code' => $resource->educationLevel?->code,
                    'name' => $resource->educationLevel?->name,
                ],
                'sources' => $resource->sources->map(function ($s) {
                    return [
                        'title' => $s->source_title,
                        'publisher' => $s->publisher,
                        'url' => $s->source_url,
                        'status' => $s->verification_status,
                    ];
                }),
                'topics' => $resource->topics->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'code' => $t->code,
                        'title' => $t->title,
                    ];
                }),
                'files' => $canAccessSolution ? $resource->latestVersion?->files->map(function ($f) {
                    return [
                        'id' => $f->id,
                        'name' => $f->original_name,
                        'mime_type' => $f->mime_type,
                        'size_bytes' => $f->size_bytes,
                        'scan_status' => $f->antivirus_scan_status,
                    ];
                }) : [],
                'solution_restricted' => $resource->is_solution_restricted,
            ],
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }
}
