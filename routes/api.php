<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Middleware\AssignCorrelationId;
use App\Http\Middleware\EnsureUserIsActive;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — MONKAYIER API v1
|--------------------------------------------------------------------------
*/

Route::middleware([AssignCorrelationId::class])->prefix('v1')->group(function () {

    // 1. Contrôle technique
    Route::get('/health', [HealthController::class, 'check']);

    // 2. Authentification et réinitialisation (avec rate-limiting)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1');

        Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    });

    // 3. Profil utilisateur connecté et ressources protégées
    Route::middleware(['auth:sanctum', EnsureUserIsActive::class])->group(function () {
        Route::get('/me', [UserController::class, 'me']);
        Route::patch('/me', [UserController::class, 'updateProfile']);
    });
});
