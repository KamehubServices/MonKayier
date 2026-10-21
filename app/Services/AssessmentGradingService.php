<?php

namespace App\Services;

use App\Models\AssessmentAttempt;
use App\Models\AssessmentQuestion;
use App\Models\AttemptAnswer;
use App\Models\QuestionOption;
use App\Models\RevisionPlan;
use App\Models\RevisionTask;
use App\Models\StudentCompetency;
use Illuminate\Support\Str;

class AssessmentGradingService
{
    /**
     * Corriger une question individuellement selon son type.
     */
    public function gradeQuestion(AssessmentQuestion $question, array $studentAnswer): array
    {
        $maxPoints = (float) $question->points;
        $awardedPoints = 0.0;
        $isCorrect = false;
        $feedback = '';

        switch ($question->question_type) {
            case 'single_choice':
            case 'true_false':
                $selectedOptionId = $studentAnswer['option_id'] ?? null;
                if ($selectedOptionId) {
                    $option = QuestionOption::where('id', $selectedOptionId)
                        ->where('question_id', $question->id)
                        ->first();

                    if ($option && $option->is_correct) {
                        $awardedPoints = $maxPoints;
                        $isCorrect = true;
                        $feedback = $option->explanation ?: "Excellente réponse ! Notion bien maîtrisée.";
                    } else {
                        $correctOption = QuestionOption::where('question_id', $question->id)
                            ->where('is_correct', true)
                            ->first();
                        $feedback = "Réponse incorrecte. " . ($question->explanation ?: ($correctOption ? "La bonne réponse était : {$correctOption->option_text}." : ""));
                    }
                } else {
                    $feedback = "Aucune réponse fournie.";
                }
                break;

            case 'multiple_choice':
                $selectedOptionIds = (array) ($studentAnswer['option_ids'] ?? []);
                $correctOptionIds = QuestionOption::where('question_id', $question->id)
                    ->where('is_correct', true)
                    ->pluck('id')
                    ->toArray();

                sort($selectedOptionIds);
                sort($correctOptionIds);

                if (!empty($selectedOptionIds) && $selectedOptionIds === $correctOptionIds) {
                    $awardedPoints = $maxPoints;
                    $isCorrect = true;
                    $feedback = "Parfait ! Toutes les bonnes propositions ont été sélectionnées.";
                } else {
                    $feedback = "Sélection incomplète ou erronée. " . ($question->explanation ?: "");
                }
                break;

            case 'numerical':
            case 'short_answer':
                $userValue = trim((string) ($studentAnswer['value'] ?? ''));
                $userValueNormalized = strtolower(str_replace([' ', ','], ['', '.'], $userValue));

                $answerKey = $question->answerKey;
                $acceptedAnswers = [];
                if ($answerKey && !empty($answerKey->answer_key['accepted_values'])) {
                    $acceptedAnswers = (array) $answerKey->answer_key['accepted_values'];
                }

                // Vérifier si l'une des valeurs acceptées correspond
                $matched = false;
                foreach ($acceptedAnswers as $acc) {
                    $accNormalized = strtolower(str_replace([' ', ','], ['', '.'], (string) $acc));
                    if ($userValueNormalized === $accNormalized) {
                        $matched = true;
                        break;
                    }
                }

                if ($matched) {
                    $awardedPoints = $maxPoints;
                    $isCorrect = true;
                    $feedback = "Calcul exact ! Très bonne démarche.";
                } else {
                    $expected = implode(' ou ', $acceptedAnswers);
                    $feedback = "Résultat incorrect. Valeur attendue : {$expected}. " . ($question->explanation ?: "");
                }
                break;

            case 'essay':
            default:
                // Pour les questions rédigées, pas de note fictive automatique sans correcteur
                $awardedPoints = $maxPoints * 0.7; // Note formative indicative
                $isCorrect = true;
                $feedback = "Réponse enregistrée. Évaluation formative indicative — validation définitive par l'enseignant.";
                break;
        }

        return [
            'score' => $awardedPoints,
            'max_points' => $maxPoints,
            'is_correct' => $isCorrect,
            'feedback' => $feedback,
        ];
    }

    /**
     * Corriger l'intégralité d'une tentative et mettre à jour le plan de révision de l'élève.
     */
    public function gradeAttempt(AssessmentAttempt $attempt): AssessmentAttempt
    {
        $assessment = $attempt->assessment()->with('questions.options', 'questions.answerKey')->first();
        $totalScore = 0.0;
        $maxScore = 0.0;

        foreach ($assessment->questions as $question) {
            $maxScore += (float) $question->points;

            $attemptAnswer = AttemptAnswer::where('attempt_id', $attempt->id)
                ->where('question_id', $question->id)
                ->first();

            $studentAnswer = $attemptAnswer ? (array) $attemptAnswer->answer_payload : [];
            $gradeResult = $this->gradeQuestion($question, $studentAnswer);

            $totalScore += $gradeResult['score'];

            if ($attemptAnswer) {
                $attemptAnswer->update([
                    'awarded_points' => $gradeResult['score'],
                    'feedback' => $gradeResult['feedback'],
                ]);
            } else {
                AttemptAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'answer_payload' => [],
                    'awarded_points' => 0.0,
                    'feedback' => 'Non répondu.',
                    'answered_at' => now(),
                ]);
            }
        }

        $attempt->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'score' => $totalScore,
            'max_score' => $maxScore,
        ]);

        // Mettre à jour les recommandations de révision personnalisées
        $this->updateRevisionRecommendations($attempt);

        return $attempt->fresh();
    }

    /**
     * Recommander automatiquement des activités de révision explicables si score < 70%.
     */
    private function updateRevisionRecommendations(AssessmentAttempt $attempt): void
    {
        $percentage = $attempt->scorePercentage();
        $studentId = $attempt->student_user_id;

        // Récupérer ou créer le plan de révision actif de l'élève
        $plan = RevisionPlan::firstOrCreate(
            ['student_user_id' => $studentId, 'status' => 'active'],
            [
                'title' => 'Plan de Révision Personnel · BEPC 2027',
                'target_date' => '2027-06-08',
                'generated_by' => 'system_adaptive',
            ]
        );

        // Si l'élève a obtenu moins de 70%, créer une tâche de révision prioritaire
        if ($percentage < 70.0) {
            $resource = $attempt->assessment->resource;

            RevisionTask::create([
                'plan_id' => $plan->id,
                'resource_id' => $resource?->id,
                'due_at' => now()->addDays(2),
                'status' => 'pending',
                'priority' => 1,
                'created_at' => now(),
            ]);
        }
    }
}
