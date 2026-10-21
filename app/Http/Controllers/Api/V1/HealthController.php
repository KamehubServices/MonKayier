<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HealthController extends Controller
{
    /**
     * Contrôle technique minimal sans exposition de secrets ni de configuration interne.
     */
    public function check(Request $request): JsonResponse
    {
        $requestId = $request->header('X-Correlation-ID', (string) Str::uuid());

        return response()->json([
            'data' => [
                'status' => 'pass',
                'service' => 'MONKAYIER API',
                'version' => 'v1',
                'timestamp' => now()->toIso8601String(),
            ],
            'meta' => [
                'request_id' => $requestId,
            ],
        ]);
    }
}
