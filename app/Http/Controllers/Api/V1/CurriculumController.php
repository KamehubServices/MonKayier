<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use App\Services\CurriculumReferentialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    /**
     * GET /api/v1/curricula
     */
    public function index(Request $request): JsonResponse
    {
        $query = Curriculum::with(['academicYear', 'educationLevel', 'series', 'country']);

        if ($request->filled('level')) {
            $level = $request->query('level');
            $query->whereHas('educationLevel', function ($q) use ($level) {
                $q->where('id', $level)->orWhere('code', $level);
            });
        }

        if ($request->filled('track')) {
            $track = $request->query('track');
            $query->whereHas('series', function ($q) use ($track) {
                $q->where('id', $track)->orWhere('code', $track);
            });
        }

        if ($request->filled('school_year')) {
            $year = $request->query('school_year');
            $query->whereHas('academicYear', function ($q) use ($year) {
                $q->where('label', $year);
            });
        }

        // Public users only see published curricula unless admin
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            $query->where('status', 'published');
        } elseif ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $curricula = $query->orderBy('version', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $curricula->map(function ($c) {
                return [
                    'id' => $c->id,
                    'title' => $c->title,
                    'version' => $c->version,
                    'status' => $c->status,
                    'academic_year' => $c->academicYear?->label,
                    'level' => $c->educationLevel?->code,
                    'series' => $c->series?->code,
                    'source_title' => $c->source_title,
                    'verified_at' => $c->verified_at?->toIso8601String(),
                ];
            }),
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
                'total' => $curricula->count(),
            ],
        ]);
    }

    /**
     * GET /api/v1/curricula/{id}/tree
     */
    public function tree(string $id, Request $request, CurriculumReferentialService $service): JsonResponse
    {
        $curriculum = Curriculum::findOrFail($id);

        $user = $request->user();
        if ((!$user || !$user->isAdmin()) && $curriculum->status !== 'published') {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'NOT_FOUND',
                    'message' => 'Programme introuvable ou non publié.',
                ],
            ], 404);
        }

        $tree = $service->getCurriculumTree($id);

        return response()->json([
            'success' => true,
            'data' => $tree,
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
            ],
        ]);
    }
}
