<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — MONKAYIER
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'status' => 'healthy',
            'platform' => 'MONKAYIER',
            'timestamp' => now()->toIso8601String(),
        ]);
    });
});
