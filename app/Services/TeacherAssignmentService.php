<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SchoolClass;
use App\Models\SubmissionGrade;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TeacherAssignmentService
{
    /**
     * Créer et programmer un devoir pour une classe.
     */
    public function createAssignment(
        User $teacher,
        SchoolClass $class,
        Assessment $assessment,
        string $title,
        ?\DateTimeInterface $dueAt = null
    ): Assignment {
        if (!$teacher->hasRole('teacher') && !$teacher->isAdmin()) {
            throw new AuthorizationException("Seuls les enseignants autorisés peuvent assigner des devoirs.");
        }

        if (!$assessment->isPublished()) {
            throw ValidationException::withMessages([
                'assessment_id' => ["L'évaluation doit être publiée pour être assignée aux élèves."],
            ]);
        }

        return Assignment::create([
            'class_id' => $class->id,
            'teacher_user_id' => $teacher->id,
            'assessment_id' => $assessment->id,
            'title' => $title,
            'due_at' => $dueAt ?? now()->addDays(7),
            'status' => 'published',
        ]);
    }

    /**
     * Évaluer et attribuer une note officielle à la soumission d'un élève.
     */
    public function gradeSubmission(
        User $teacher,
        AssignmentSubmission $submission,
        float $finalScore,
        ?string $feedback = null
    ): SubmissionGrade {
        $assignment = $submission->assignment;

        if ($assignment->teacher_user_id !== $teacher->id && !$teacher->isAdmin()) {
            throw new AuthorizationException("Vous n'êtes pas l'enseignant titulaire de ce devoir.");
        }

        $grade = SubmissionGrade::updateOrCreate(
            ['submission_id' => $submission->id],
            [
                'graded_by' => $teacher->id,
                'final_score' => $finalScore,
                'is_official' => true,
                'graded_at' => now(),
            ]
        );

        $submission->update([
            'status' => 'graded',
            'teacher_feedback' => $feedback,
        ]);

        return $grade;
    }
}
