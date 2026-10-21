<?php

use App\Http\Controllers\Web\AdminWebController;
use App\Http\Controllers\Web\StudentWebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — MONKAYIER
|--------------------------------------------------------------------------
*/

// 1. Vitrine publique & Inscription
Route::get('/', function () {
    return view('welcome');
});

// 2. Interface Élève (Web & Mobile PWA)
Route::prefix('student')->group(function () {
    Route::get('/dashboard', [StudentWebController::class, 'dashboard']);
    Route::get('/subjects', [StudentWebController::class, 'subjects']);
    Route::get('/library', [StudentWebController::class, 'library']);
    Route::get('/reader/{id}', [StudentWebController::class, 'reader']);
    Route::get('/assistant', [StudentWebController::class, 'assistant']);
    Route::get('/exercises', [StudentWebController::class, 'exercises']);
    Route::get('/exercises/{id}', [StudentWebController::class, 'exercisePlayer']);
    Route::get('/exercises/{id}/result', [StudentWebController::class, 'exerciseResult']);
    Route::get('/revisions', [StudentWebController::class, 'revisions']);
    Route::get('/progress', [StudentWebController::class, 'progress']);
});

// 3. Back-Office d'Administration des Référentiels & Bibliothèque
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminWebController::class, 'dashboard']);
    Route::get('/referentials', [AdminWebController::class, 'referentials']);
    Route::get('/curricula', [AdminWebController::class, 'curricula']);
    Route::get('/resources', [AdminWebController::class, 'resources']);
    Route::post('/resources/{id}/submit', [AdminWebController::class, 'submitResource']);
    Route::post('/resources/{id}/approve', [AdminWebController::class, 'approveResource']);
    Route::post('/resources/{id}/publish', [AdminWebController::class, 'publishResource']);
    Route::get('/imports', [AdminWebController::class, 'imports']);
    Route::post('/imports/upload', [AdminWebController::class, 'uploadImport']);
    Route::get('/audit', [AdminWebController::class, 'dashboard']);
});
