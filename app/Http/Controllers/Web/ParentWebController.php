<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ParentStudentLink;
use App\Models\User;
use App\Services\ParentSupervisionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ParentWebController extends Controller
{
    public function __construct(
        protected ParentSupervisionService $supervisionService
    ) {}

    /**
     * Dashboard de supervision parentale.
     */
    public function dashboard(): View
    {
        // En mode démo/développement Web, récupérer le premier élève (Koffi)
        $studentKoffi = User::where('email', 'koffi.student@monkayier.tg')->first();

        $examDate = Carbon::parse('2027-06-08');
        $daysUntilExam = (int) now()->diffInDays($examDate, false);

        $demoReport = [
            'student_name' => $studentKoffi?->profile ? ($studentKoffi->profile->first_name . ' ' . $studentKoffi->profile->last_name) : 'Koffi Mensah',
            'class' => 'Troisième 3e A · BEPC 2027',
            'school' => 'Lycée de Tokoin (Lomé)',
            'global_average' => 16.5,
            'completed_assessments' => 8,
            'attendance' => 'Assidu (100%)',
            'days_remaining' => max(0, $daysUntilExam),
            'grades' => [
                ['subject' => 'Mathématiques', 'title' => 'Devoir surveillé n°1 : Théorème de Thalès', 'score' => 18.5, 'date' => '18/10/2026', 'feedback' => 'Très bonne rédaction et rigueur géométrique.'],
                ['subject' => 'Physique-Chimie', 'title' => 'Interrogation écrite : Solutions aqueuses & pH', 'score' => 16.0, 'date' => '12/10/2026', 'feedback' => 'Notions bien assimilées.'],
                ['subject' => 'Français', 'title' => 'Devoir type examen : Argumentation & Essai', 'score' => 15.0, 'date' => '05/10/2026', 'feedback' => 'Bonne syntaxe, enrichir le vocabulaire.'],
            ],
            'upcoming_deadlines' => [
                ['title' => 'Devoir maison de Trigonométrie (Maths)', 'due' => 'Dans 4 jours'],
                ['title' => 'Fiche de lecture : L’Enfant Noir (Français)', 'due' => 'Dans 8 jours'],
            ],
        ];

        return view('parent.dashboard', [
            'report' => $demoReport,
        ]);
    }

    /**
     * Lier un enfant via le code à 6 caractères.
     */
    public function linkChild(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        return back()->with('success', 'Élève relié avec succès ! Votre espace a été actualisé.');
    }
}
