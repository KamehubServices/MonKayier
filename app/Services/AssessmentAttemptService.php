<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\AttemptAnswer;
use App\Models\AttemptEvent;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AssessmentAttemptService
{
    public function __construct(
        protected AssessmentGradingService $gradingService
    ) {}

    /**
     * Démarrer une tentative d'évaluation (avec support d'idempotence).
     */
    public function startAttempt(Assessment $assessment, User $student, ?string $idempotencyKey = null): AssessmentAttempt
    {
        if ($assessment->status !== 'published') {
            throw ValidationException::withMessages([
                'assessment' => ["Cette évaluation n'est pas encore publiée ou accessible aux élèves."],
            ]);
        }

        $key = $idempotencyKey ?: (string) Str::uuid();

        // Si une tentative avec cette clé existe déjà pour cet élève, la renvoyer (idempotence)
        $existing = AssessmentAttempt::where('student_user_id', $student->id)
            ->where('idempotency_key', $key)
            ->first();

        if ($existing) {
            return $existing;
        }

        $totalPoints = $assessment->totalPoints();

        $attempt = AssessmentAttempt::create([
            'assessment_id' => $assessment->id,
            'student_user_id' => $student->id,
            'status' => 'in_progress',
            'started_at' => now(),
            'score' => null,
            'max_score' => $totalPoints > 0 ? $totalPoints : 10.0,
            'idempotency_key' => $key,
            'assessment_version' => 1,
        ]);

        AttemptEvent::create([
            'attempt_id' => $attempt->id,
            'event_type' => 'ATTEMPT_STARTED',
            'occurred_at' => now(),
            'metadata' => [
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
            ],
        ]);

        return $attempt;
    }

    /**
     * Enregistrer une réponse progressive (gestion de déconnexion et reprise).
     */
    public function saveAnswer(AssessmentAttempt $attempt, string $questionId, array $payload, User $student): AttemptAnswer
    {
        if ($attempt->student_user_id !== $student->id && !$student->isAdmin()) {
            throw new AuthorizationException("Accès refusé : cette tentative appartient à un autre élève.");
        }

        if ($attempt->status === 'submitted') {
            throw ValidationException::withMessages([
                'attempt' => ["Impossible de modifier une réponse sur une évaluation déjà soumise et corrigée."],
            ]);
        }

        $answer = AttemptAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
            ],
            [
                'answer_payload' => $payload,
                'answered_at' => now(),
            ]
        );

        AttemptEvent::create([
            'attempt_id' => $attempt->id,
            'event_type' => 'ANSWER_SAVED',
            'occurred_at' => now(),
            'metadata' => ['question_id' => $questionId],
        ]);

        return $answer;
    }

    /**
     * Soumettre définitivement la tentative et déclencher la correction automatique.
     */
    public function submitAttempt(AssessmentAttempt $attempt, User $student): AssessmentAttempt
    {
        if ($attempt->student_user_id !== $student->id && !$student->isAdmin()) {
            throw new AuthorizationException("Accès refusé : tentative réservée à son propriétaire.");
        }

        if ($attempt->status === 'submitted') {
            return $attempt; // Idempotence : renvoyer directement le résultat déjà corrigé
        }

        $graded = $this->gradingService->gradeAttempt($attempt);

        AttemptEvent::create([
            'attempt_id' => $attempt->id,
            'event_type' => 'ATTEMPT_SUBMITTED',
            'occurred_at' => now(),
            'metadata' => [
                'score' => $graded->score,
                'max_score' => $graded->max_score,
            ],
        ]);

        return $graded;
    }
}
