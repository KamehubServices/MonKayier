<?php

use App\Http\Controllers\Api\V1\AdminResourceController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CurriculumController;
use App\Http\Controllers\Api\V1\EducationReferentialController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\LearningResourceController;
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

    // 3. Profil utilisateur connecté
    Route::middleware(['auth:sanctum', EnsureUserIsActive::class])->group(function () {
        Route::get('/me', [UserController::class, 'me']);
        Route::patch('/me', [UserController::class, 'updateProfile']);
    });

    // 4. Référentiels scolaires du Togo (Cycles, Niveaux, Séries, Matières)
    Route::prefix('education')->group(function () {
        Route::get('/cycles', [EducationReferentialController::class, 'cycles']);
        Route::get('/levels', [EducationReferentialController::class, 'levels']);
        Route::get('/tracks', [EducationReferentialController::class, 'tracks']);
        Route::get('/subjects', [EducationReferentialController::class, 'subjects']);
    });

    // 5. Programmes scolaires et arborescence (Domaines, Chapitres, Objectifs)
    Route::get('/curricula', [CurriculumController::class, 'index']);
    Route::get('/curricula/{id}/tree', [CurriculumController::class, 'tree']);

    // 6. Bibliothèque pédagogique (Considération stricte de publication)
    Route::get('/resources', [LearningResourceController::class, 'index']);
    Route::get('/resources/{id}', [LearningResourceController::class, 'show']);

    // 7. Administration éditoriale et validation des référentiels
    Route::middleware(['auth:sanctum', EnsureUserIsActive::class])->prefix('admin')->group(function () {
        Route::post('/imports', [AdminResourceController::class, 'createImport']);
        Route::post('/resources/{id}/submit-review', [AdminResourceController::class, 'submitReview']);
        Route::post('/resources/{id}/review', [AdminResourceController::class, 'review']);
        Route::post('/resources/{id}/publish', [AdminResourceController::class, 'publish']);
        Route::get('/content-versions/{id}/diff', [AdminResourceController::class, 'diff']);
    });
});
