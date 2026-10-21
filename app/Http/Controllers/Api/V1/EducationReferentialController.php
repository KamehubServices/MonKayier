<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\SchoolCycle;
use App\Models\SchoolSeries;
use App\Models\Subject;
use App\Services\CurriculumReferentialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EducationReferentialController extends Controller
{
    /**
     * GET /api/v1/education/cycles
     */
    public function cycles(Request $request): JsonResponse
    {
        $cycles = SchoolCycle::where('is_active', true)
            ->orderBy('pedagogical_order')
            ->get(['id', 'code', 'name', 'description', 'pedagogical_order']);

        return response()->json([
            'success' => true,
            'data' => $cycles,
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
                'total' => $cycles->count(),
            ],
        ]);
    }

    /**
     * GET /api/v1/education/levels?cycle=...
     */
    public function levels(Request $request): JsonResponse
    {
        $query = EducationLevel::query()->with('schoolCycle:id,code,name');

        if ($request->filled('cycle')) {
            $cycle = $request->query('cycle');
            $query->where(function ($q) use ($cycle) {
                $q->where('cycle', $cycle)
                  ->orWhereHas('schoolCycle', function ($sub) use ($cycle) {
                      $sub->where('code', $cycle);
                  });
            });
        }

        $levels = $query->orderBy('sort_order')->get();

        return response()->json([
            'success' => true,
            'data' => $levels,
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
                'total' => $levels->count(),
            ],
        ]);
    }

    /**
     * GET /api/v1/education/tracks?level=...
     */
    public function tracks(Request $request): JsonResponse
    {
        $query = SchoolSeries::query()->with('educationLevel:id,code,name');

        if ($request->filled('level')) {
            $levelParam = $request->query('level');
            $query->where(function ($q) use ($levelParam) {
                $q->where('education_level_id', $levelParam)
                  ->orWhereHas('educationLevel', function ($sub) use ($levelParam) {
                      $sub->where('code', $levelParam);
                  });
            });
        }

        $tracks = $query->get();

        return response()->json([
            'success' => true,
            'data' => $tracks,
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
                'total' => $tracks->count(),
            ],
        ]);
    }

    /**
     * GET /api/v1/education/subjects?level=...&track=...
     */
    public function subjects(Request $request, CurriculumReferentialService $service): JsonResponse
    {
        $levelId = $request->query('level');
        $trackId = $request->query('track');

        if ($levelId) {
            // Find level by UUID or code
            $level = EducationLevel::where('id', $levelId)->orWhere('code', $levelId)->first();
            if ($level) {
                $series = null;
                if ($trackId) {
                    $series = SchoolSeries::where('id', $trackId)->orWhere('code', $trackId)->first();
                }

                $levelSubjects = $service->getSubjectsForLevel($level->id, $series?->id);

                return response()->json([
                    'success' => true,
                    'data' => $levelSubjects->map(function ($ls) {
                        return [
                            'id' => $ls->subject->id,
                            'code' => $ls->subject->code,
                            'name' => $ls->subject->name,
                            'icon_name' => $ls->subject->icon_name,
                            'is_mandatory' => $ls->is_mandatory,
                            'coefficient' => $ls->coefficient,
                            'weekly_hours' => $ls->weekly_hours,
                        ];
                    }),
                    'meta' => [
                        'correlation_id' => $request->attributes->get('correlation_id'),
                        'level' => $level->code,
                        'series' => $series?->code,
                    ],
                ]);
            }
        }

        // Default: list all active subjects
        $subjects = Subject::where('is_active', true)->get(['id', 'code', 'name', 'icon_name']);

        return response()->json([
            'success' => true,
            'data' => $subjects,
            'meta' => [
                'correlation_id' => $request->attributes->get('correlation_id'),
                'total' => $subjects->count(),
            ],
        ]);
    }
}
