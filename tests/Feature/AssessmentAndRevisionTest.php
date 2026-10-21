<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\AssessmentQuestion;
use App\Models\AttemptAnswer;
use App\Models\QuestionAnswer;
use App\Models\QuestionOption;
use App\Models\RevisionPlan;
use App\Models\RevisionTask;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AssessmentAndRevisionTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected User $otherStudent;
    protected Assessment $publishedAssessment;
    protected Assessment $draftAssessment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        // Élève 1
        $this->student = User::create([
            'email' => 'koffi.student@monkayier.tg',
            'password_hash' => bcrypt('StudentPass2026!'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        UserProfile::create([
            'user_id' => $this->student->id,
            'first_name' => 'Koffi',
            'last_name' => 'Mensah',
        ]);
        $this->student->assignRole('student');

        // Élève 2 (pour tester l'isolation horizontale)
        $this->otherStudent = User::create([
            'email' => 'ablavi.student@monkayier.tg',
            'password_hash' => bcrypt('StudentPass2026!'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        UserProfile::create([
            'user_id' => $this->otherStudent->id,
            'first_name' => 'Ablavi',
            'last_name' => 'Koffi',
        ]);
        $this->otherStudent->assignRole('student');

        $this->publishedAssessment = Assessment::where('status', 'published')->firstOrFail();
        $this->draftAssessment = Assessment::where('status', 'draft')->firstOrFail();
    }

    /**
     * 1. Test : Seules les évaluations publiées sont listées pour les élèves.
     */
    public function test_student_can_list_published_assessments_only(): void
    {
        $response = $this->getJson('/api/v1/assessments');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        foreach ($data as $item) {
            $this->assertEquals('published', $item['status']);
            $this->assertNotEquals('draft', $item['status']);
        }
    }

    /**
     * 2. Test SÉCURITÉ CRITIQUE : Les corrigés et les drapeaux is_correct sont masqués aux élèves avant soumission.
     */
    public function test_assessment_questions_hide_correct_answers_before_submission(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/assessments/' . $this->publishedAssessment->id);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $questions = $response->json('data.questions');
        $this->assertNotEmpty($questions);

        foreach ($questions as $q) {
            // Règle de sécurité : aucune clé secrète ou flag is_correct
            $this->assertArrayNotHasKey('answer_key', $q);
            $this->assertArrayNotHasKey('explanation', $q);

            foreach ($q['options'] as $opt) {
                $this->assertArrayNotHasKey('is_correct', $opt, 'Security leak: is_correct must NEVER be sent before submission!');
                $this->assertArrayNotHasKey('explanation', $opt);
            }
        }
    }

    /**
     * 3. Test : Une évaluation en brouillon renvoie 404 pour un élève.
     */
    public function test_draft_assessment_is_forbidden_for_student(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/assessments/' . $this->draftAssessment->id);

        $response->assertStatus(404);
    }

    /**
     * 4. Test IDEMPOTENCE : Démarrer une tentative crée un enregistrement et réutilise le même si idempotency_key identique.
     */
    public function test_student_can_start_attempt_with_idempotency(): void
    {
        $idempotencyKey = (string) Str::uuid();

        // Premier appel
        $res1 = $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/assessments/' . $this->publishedAssessment->id . '/attempts', [
                'idempotency_key' => $idempotencyKey,
            ]);

        $res1->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'in_progress');

        $attemptId1 = $res1->json('data.attempt_id');
        $this->assertNotNull($attemptId1);

        // Deuxième appel avec la même clé d'idempotence (ex: reconnexion ou retry réseau)
        $res2 = $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/assessments/' . $this->publishedAssessment->id . '/attempts', [
                'idempotency_key' => $idempotencyKey,
            ]);

        $res2->assertStatus(201);
        $attemptId2 = $res2->json('data.attempt_id');

        $this->assertEquals($attemptId1, $attemptId2, 'Idempotency failure: new attempt created with existing key');
        $this->assertEquals(1, AssessmentAttempt::where('idempotency_key', $idempotencyKey)->count());
    }

    /**
     * 5. Test : Sauvegarde progressive des réponses au cours de l'évaluation.
     */
    public function test_student_can_save_answers_progressively(): void
    {
        $attempt = AssessmentAttempt::create([
            'assessment_id' => $this->publishedAssessment->id,
            'student_user_id' => $this->student->id,
            'status' => 'in_progress',
            'started_at' => now(),
            'max_score' => 10.0,
            'idempotency_key' => (string) Str::uuid(),
        ]);

        $question = $this->publishedAssessment->questions->first();

        $response = $this->actingAs($this->student, 'sanctum')
            ->putJson("/api/v1/attempts/{$attempt->id}/answers/{$question->id}", [
                'answer' => ['option_id' => $question->options->first()->id],
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('attempt_answers', [
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
        ]);

        $this->assertDatabaseHas('attempt_events', [
            'attempt_id' => $attempt->id,
            'event_type' => 'ANSWER_SAVED',
        ]);
    }

    /**
     * 6. Test ISOLATION HORIZONTALE : Un élève B ne peut pas modifier ni soumettre la tentative de l'élève A.
     */
    public function test_student_cannot_save_or_submit_another_students_attempt(): void
    {
        $attemptA = AssessmentAttempt::create([
            'assessment_id' => $this->publishedAssessment->id,
            'student_user_id' => $this->student->id,
            'status' => 'in_progress',
            'started_at' => now(),
            'max_score' => 10.0,
            'idempotency_key' => (string) Str::uuid(),
        ]);

        $question = $this->publishedAssessment->questions->first();

        // Élève B tente de modifier
        $saveRes = $this->actingAs($this->otherStudent, 'sanctum')
            ->putJson("/api/v1/attempts/{$attemptA->id}/answers/{$question->id}", [
                'answer' => ['option_id' => $question->options->first()->id],
            ]);

        $saveRes->assertStatus(403);

        // Élève B tente de soumettre
        $submitRes = $this->actingAs($this->otherStudent, 'sanctum')
            ->postJson("/api/v1/attempts/{$attemptA->id}/submit");

        $submitRes->assertStatus(403);
    }

    /**
     * 7. Test : Auto-correction complète avec note 100% sur réponses exactes.
     */
    public function test_auto_grading_awards_full_score_for_correct_answers(): void
    {
        $attempt = AssessmentAttempt::create([
            'assessment_id' => $this->publishedAssessment->id,
            'student_user_id' => $this->student->id,
            'status' => 'in_progress',
            'started_at' => now(),
            'max_score' => 10.0,
            'idempotency_key' => (string) Str::uuid(),
        ]);

        // Fournir les bonnes réponses pour chaque question
        foreach ($this->publishedAssessment->questions as $q) {
            $payload = [];

            if ($q->question_type === 'single_choice' || $q->question_type === 'true_false') {
                $correctOpt = $q->options()->where('is_correct', true)->first();
                $payload = ['option_id' => $correctOpt?->id];
            } elseif ($q->question_type === 'multiple_choice') {
                $correctIds = $q->options()->where('is_correct', true)->pluck('id')->toArray();
                $payload = ['option_ids' => $correctIds];
            } elseif ($q->question_type === 'numerical') {
                $payload = ['value' => '6'];
            } elseif ($q->question_type === 'short_answer') {
                $payload = ['value' => 'Thalès'];
            }

            AttemptAnswer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $q->id,
                'answer_payload' => $payload,
                'answered_at' => now(),
            ]);
        }

        // Soumettre
        $submitRes = $this->actingAs($this->student, 'sanctum')
            ->postJson("/api/v1/attempts/{$attempt->id}/submit");

        $submitRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'submitted');

        $attempt->refresh();
        $this->assertEquals(10.0, (float) $attempt->score);
        $this->assertEquals(100.0, $attempt->scorePercentage());
    }

    /**
     * 8. Test ADAPTATION PÉDAGOGIQUE : Un score < 70% génère automatiquement une tâche de révision prioritaire.
     */
    public function test_auto_grading_creates_revision_task_when_score_below_70_percent(): void
    {
        $attempt = AssessmentAttempt::create([
            'assessment_id' => $this->publishedAssessment->id,
            'student_user_id' => $this->student->id,
            'status' => 'in_progress',
            'started_at' => now(),
            'max_score' => 10.0,
            'idempotency_key' => (string) Str::uuid(),
        ]);

        // Fournir une réponse fausse pour toutes les questions
        foreach ($this->publishedAssessment->questions as $q) {
            $payload = [];
            if ($q->question_type === 'single_choice') {
                $wrongOpt = $q->options()->where('is_correct', false)->first();
                $payload = ['option_id' => $wrongOpt?->id];
            } elseif ($q->question_type === 'numerical') {
                $payload = ['value' => '999']; // Faux
            }

            AttemptAnswer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $q->id,
                'answer_payload' => $payload,
                'answered_at' => now(),
            ]);
        }

        // Soumission
        $submitRes = $this->actingAs($this->student, 'sanctum')
            ->postJson("/api/v1/attempts/{$attempt->id}/submit");

        $submitRes->assertStatus(200);

        $attempt->refresh();
        $this->assertLessThan(70.0, $attempt->scorePercentage());

        // Vérification de la création d'un plan et d'une tâche de révision
        $plan = RevisionPlan::where('student_user_id', $this->student->id)->first();
        $this->assertNotNull($plan, 'A revision plan should be created when score is below 70%');

        $task = RevisionTask::where('plan_id', $plan->id)->first();
        $this->assertNotNull($task, 'A priority revision task should be generated for low score');
        $this->assertEquals(1, $task->priority);
        $this->assertEquals('pending', $task->status);
    }

    /**
     * 9. Test : Consultation du plan de révision et validation d'une tâche terminée.
     */
    public function test_student_can_view_revision_plan_and_complete_task(): void
    {
        $plan = RevisionPlan::create([
            'student_user_id' => $this->student->id,
            'title' => 'Plan Personnel BEPC 2027',
            'status' => 'active',
        ]);

        $task = RevisionTask::create([
            'plan_id' => $plan->id,
            'resource_id' => $this->publishedAssessment->resource_id,
            'due_at' => now()->addDays(2),
            'status' => 'pending',
            'priority' => 1,
        ]);

        // Consultation du plan via API
        $planRes = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/me/revision-plan');

        $planRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $plan->id);

        // Clôture de la tâche
        $completeRes = $this->actingAs($this->student, 'sanctum')
            ->postJson("/api/v1/me/revision-plan/tasks/{$task->id}/complete");

        $completeRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'completed');

        $task->refresh();
        $this->assertEquals('completed', $task->status);
        $this->assertNotNull($task->completed_at);
    }

    /**
     * 10. Test : Les interfaces web élèves pour les exercices et révisions retournent HTTP 200.
     */
    public function test_web_routes_for_student_exercises_and_revisions(): void
    {
        $this->get('/student/exercises')->assertStatus(200);
        $this->get('/student/exercises/' . $this->publishedAssessment->id)->assertStatus(200);
        $this->get('/student/revisions')->assertStatus(200);
        $this->get('/student/progress')->assertStatus(200);
    }
}
