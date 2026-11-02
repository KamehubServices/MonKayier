<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes — MONKAYIER (Portail Authentification & Onboarding)
|--------------------------------------------------------------------------
*/

// Page d'accueil officielle (Vitrine statique)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentification & Récupération
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [StudentController::class, 'handleLogin'])->name('login.submit');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [StudentController::class, 'handleRegister'])->name('register.submit');

Route::get('/logout', [StudentController::class, 'handleLogout'])->name('logout');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');

// Vérification de l'e-mail (Code OTP)
Route::get('/verify-email', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::get('/otp', function () {
    return view('auth.verify-email');
})->name('otp');

// Parcours d'Onboarding Officiels
Route::get('/onboarding/student', function () {
    return view('auth.onboarding-student');
})->name('onboarding.student');
Route::post('/onboarding/student', [StudentController::class, 'handleOnboardingStudent'])->name('onboarding.student.submit');

Route::get('/onboarding/teacher', function () {
    return view('auth.onboarding-teacher');
})->name('onboarding.teacher');

Route::get('/onboarding/parent', function () {
    return view('auth.onboarding-parent');
})->name('onboarding.parent');

// Écran de confirmation & succès
Route::get('/auth/success', function () {
    return view('auth.success');
})->name('auth.success');

/*
|--------------------------------------------------------------------------
| Espace Élève — MONKAYIER (Connecté à la base de données SQLite)
|--------------------------------------------------------------------------
*/
Route::prefix('student')->group(function () {
    Route::get('/', function () {
        return redirect()->route('student.dashboard');
    });

    // 1. Accueil élève · Tableau de bord
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');

    // 2. Bibliothèque officielle unifiée (Cours & Examens nationaux)
    Route::get('/library', [StudentController::class, 'library'])->name('student.library');
    Route::get('/bibliotheque', [StudentController::class, 'library'])->name('student.bibliotheque');
    Route::get('/courses/{id}', [StudentController::class, 'showCourse'])->name('student.course.show');
    Route::get('/courses/{id}/sheet', [StudentController::class, 'getCourseSheet'])->name('student.course.sheet');
    Route::post('/library/upload', [StudentController::class, 'uploadCourse'])->name('student.library.upload');

    // 3. Révision IA (Tout-en-un Cours, Tuteur IA et Exercices Gradués)
    Route::get('/chat', [StudentController::class, 'chat'])->name('student.chat');
    Route::get('/revision', [StudentController::class, 'chat'])->name('student.revision');
    Route::post('/chat/message', [StudentController::class, 'sendChatMessage'])->name('student.chat.message');
    Route::post('/chat/clear', [StudentController::class, 'clearChatHistory'])->name('student.chat.clear');
    Route::post('/chat/tts', [StudentController::class, 'synthesizeTts'])->name('student.chat.tts');
    Route::post('/chat/feedback', [StudentController::class, 'tutorFeedback'])->name('student.chat.feedback');
    Route::post('/chat/evaluate-exercise', [StudentController::class, 'evaluateExercise'])->name('student.chat.evaluate');
    Route::post('/chat/track-time', [StudentController::class, 'trackStudyTime'])->name('student.chat.track_time');

    // 4. Redirection unifiée vers Révision IA
    Route::get('/assistant', function () { return redirect()->route('student.chat'); })->name('student.assistant');
    Route::post('/assistant/message', [StudentController::class, 'sendAssistantMessage'])->name('student.assistant.message');

    // 5. Assistant vocal
    Route::get('/vocal', [StudentController::class, 'vocal'])->name('student.vocal');

    // 6. Test de niveau & Scanner de Bulletins Scolaires IA
    Route::get('/test-niveau', [StudentController::class, 'test'])->name('student.test');
    Route::post('/test-niveau/submit', [StudentController::class, 'submitTest'])->name('student.test.submit');
    Route::post('/test-niveau/report-card/upload', [StudentController::class, 'uploadReportCard'])->name('student.test.report_card.upload');
    Route::delete('/test-niveau/report-card/{id}', [StudentController::class, 'deleteReportCard'])->name('student.test.report_card.delete');

    // 7. Exercices et corrections (Générateur & Atelier Immersif Anti-Triche)
    Route::get('/exercises', [StudentController::class, 'exercises'])->name('student.exercises');
    Route::post('/exercises/generate', [StudentController::class, 'generateExercise'])->name('student.exercises.generate');
    Route::post('/exercises/submit', [StudentController::class, 'submitExerciseCopy'])->name('student.exercises.submit');

    // 8. Préparation aux examens
    Route::get('/examens', [StudentController::class, 'examens'])->name('student.examens');
    Route::get('/examens/file/{fileId}/download', [StudentController::class, 'downloadOfficialFile'])->name('student.examens.download');
    Route::get('/examens/file/{fileId}/preview', [StudentController::class, 'previewOfficialFile'])->name('student.examens.preview');

    // 9. Parcours de révision
    Route::get('/parcours', [StudentController::class, 'parcours'])->name('student.parcours');

    // 10. Historique et progression
    Route::get('/history', [StudentController::class, 'history'])->name('student.history');

    // 11. Calendrier officiel
    Route::get('/calendar', [StudentController::class, 'calendar'])->name('student.calendar');

    // 12. Profil et paramètres élève
    Route::get('/profile', [StudentController::class, 'profile'])->name('student.profile');
    Route::post('/profile', [StudentController::class, 'updateProfile'])->name('student.profile.update');
    Route::post('/reset-data', [StudentController::class, 'resetStudentData'])->name('student.reset');

    // 13. Notifications élève
    Route::get('/notifications', [StudentController::class, 'notifications'])->name('student.notifications');
    Route::post('/notifications/mark-read', [StudentController::class, 'markAllNotificationsRead'])->name('student.notifications.markRead');

    // 14. Aide et contact élève
    Route::get('/help', [StudentController::class, 'help'])->name('student.help');
    Route::get('/aide', [StudentController::class, 'help'])->name('student.aide');

    // 15. Séance de révision interactive guidée par le cours
    Route::get('/seance/{taskId?}', [StudentController::class, 'seance'])->name('student.seance');
    Route::post('/seance/{taskId}/complete', [StudentController::class, 'completeSeance'])->name('student.seance.complete');
});

/*
|--------------------------------------------------------------------------
| Espace Enseignant / Professeur — MONKAYIER
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->group(function () {
    Route::get('/', function () {
        return redirect()->route('teacher.dashboard');
    });

    // 1. Tableau de bord enseignant
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');

    // 2. Mes classes
    Route::get('/classes', function () {
        return view('teacher.classes');
    })->name('teacher.classes');

    // 3. Mes supports
    Route::get('/supports', function () {
        return view('teacher.supports');
    })->name('teacher.supports');

    // 4. Générateur de sujets
    Route::get('/generator', function () {
        return view('teacher.generator');
    })->name('teacher.generator');

    // 5. Corrections des copies
    Route::get('/corrections', function () {
        return view('teacher.corrections');
    })->name('teacher.corrections');

    // 6. Suivi des élèves
    Route::get('/suivi', function () {
        return view('teacher.suivi');
    })->name('teacher.suivi');

    // 7. Bibliothèque partagée
    Route::get('/library', function () {
        return view('teacher.library');
    })->name('teacher.library');

    // 8. Profil et paramètres enseignant
    Route::get('/profile', function () {
        return view('teacher.profile');
    })->name('teacher.profile');

    // 9. Notifications enseignant
    Route::get('/notifications', function () {
        return view('teacher.notifications');
    })->name('teacher.notifications');

    // 10. Aide et contact enseignant
    Route::get('/help', function () {
        return view('teacher.help');
    })->name('teacher.help');

    Route::get('/aide', function () {
        return view('teacher.help');
    })->name('teacher.aide');
});

Route::redirect('/professor', '/teacher/dashboard');
Route::redirect('/prof', '/teacher/dashboard');

/*
|--------------------------------------------------------------------------
| Espace Parent — MONKAYIER
|--------------------------------------------------------------------------
*/
Route::prefix('parent')->group(function () {
    Route::get('/', function () {
        return redirect()->route('parent.dashboard');
    });

    // 1. Rapport de la semaine
    Route::get('/dashboard', function () {
        return view('parent.dashboard');
    })->name('parent.dashboard');

    Route::get('/rapport', function () {
        return view('parent.dashboard');
    })->name('parent.rapport');

    // 2. Mes enfants
    Route::get('/enfants', function () {
        return view('parent.children');
    })->name('parent.enfants');

    Route::get('/children', function () {
        return view('parent.children');
    })->name('parent.children');

    // 3. Échéances
    Route::get('/echeances', function () {
        return view('parent.deadlines');
    })->name('parent.echeances');

    Route::get('/deadlines', function () {
        return view('parent.deadlines');
    })->name('parent.deadlines');

    // 4. Notifications
    Route::get('/notifications', function () {
        return view('parent.notifications');
    })->name('parent.notifications');

    // 5. Donner mon avis
    Route::get('/avis', function () {
        return view('parent.feedback');
    })->name('parent.avis');

    Route::get('/feedback', function () {
        return view('parent.feedback');
    })->name('parent.feedback');

    // 6. Aide et contact
    Route::get('/aide', function () {
        return view('parent.help');
    })->name('parent.aide');

    Route::get('/help', function () {
        return view('parent.help');
    })->name('parent.help');

    // 7. Profil et paramètres parent
    Route::get('/profile', function () {
        return view('parent.profile');
    })->name('parent.profile');

    Route::get('/settings', function () {
        return view('parent.profile');
    })->name('parent.settings');
});




