<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherWebController extends Controller
{
    /**
     * Dashboard de l'enseignant.
     */
    public function dashboard(): View
    {
        $classes = SchoolClass::with(['educationLevel', 'school'])
            ->withCount('students')
            ->take(5)
            ->get();

        $assignments = Assignment::with(['schoolClass', 'assessment.subject'])
            ->withCount('submissions')
            ->take(5)
            ->get();

        $recentSubmissions = AssignmentSubmission::with(['assignment.assessment.subject', 'student.profile'])
            ->latest('submitted_at')
            ->take(5)
            ->get();

        return view('teacher.dashboard', [
            'classes' => $classes,
            'assignments' => $assignments,
            'recentSubmissions' => $recentSubmissions,
        ]);
    }

    /**
     * Gestion des classes attribuées.
     */
    public function classes(): View
    {
        $classes = SchoolClass::with(['educationLevel', 'school', 'series', 'students.profile'])
            ->withCount('students')
            ->get();

        return view('teacher.classes', [
            'classes' => $classes,
        ]);
    }

    /**
     * Gestion et création des devoirs.
     */
    public function assignments(): View
    {
        $assignments = Assignment::with(['schoolClass.educationLevel', 'assessment.subject'])
            ->withCount('submissions')
            ->orderByDesc('created_at')
            ->get();

        $classes = SchoolClass::with('educationLevel')->get();
        $assessments = Assessment::where('status', 'published')->with('subject')->get();

        return view('teacher.assignments', [
            'assignments' => $assignments,
            'classes' => $classes,
            'assessments' => $assessments,
        ]);
    }

    /**
     * Consultation des devoirs remis et notation.
     */
    public function submissions(string $id): View
    {
        $assignment = Assignment::with([
            'schoolClass.educationLevel',
            'assessment.questions',
            'submissions.student.profile',
            'submissions.grade',
        ])->findOrFail($id);

        return view('teacher.submissions', [
            'assignment' => $assignment,
        ]);
    }

    /**
     * Enregistrement d'une note et appréciation enseignant.
     */
    public function gradeSubmission(string $id, Request $request): RedirectResponse
    {
        $submission = AssignmentSubmission::findOrFail($id);

        $request->validate([
            'final_score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        \App\Models\SubmissionGrade::updateOrCreate(
            ['submission_id' => $submission->id],
            [
                'graded_by' => $submission->assignment->teacher_user_id,
                'final_score' => $request->input('final_score'),
                'is_official' => true,
                'graded_at' => now(),
            ]
        );

        $submission->update([
            'status' => 'graded',
            'teacher_feedback' => $request->input('feedback'),
        ]);

        return back()->with('success', 'Note et appréciation enregistrées.');
    }
}
