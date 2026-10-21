<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TeacherAndAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacher;
    protected User $otherTeacher;
    protected User $student;
    protected SchoolClass $class;
    protected Assessment $assessment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->teacher = User::where('email', 'prof.maths@kamehub.tg')->firstOrFail();
        $this->student = User::where('email', 'koffi.student@monkayier.tg')->firstOrFail();
        $this->class = SchoolClass::where('label', 'Classe de 3e A')->firstOrFail();
        $this->assessment = Assessment::where('status', 'published')->firstOrFail();

        // Autre enseignant
        $this->otherTeacher = User::create([
            'email' => 'other.prof@kamehub.tg',
            'password_hash' => bcrypt('TeacherPass2026!'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        UserProfile::create([
            'user_id' => $this->otherTeacher->id,
            'first_name' => 'Kokou',
            'last_name' => 'Abalo',
        ]);
        $this->otherTeacher->assignRole('teacher');
    }

    /**
     * 1. Test : L'enseignant liste ses classes en charge.
     */
    public function test_teacher_can_list_assigned_classes(): void
    {
        $response = $this->actingAs($this->teacher, 'sanctum')
            ->getJson('/api/v1/teacher/classes');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals('Classe de 3e A', $data[0]['label']);
    }

    /**
     * 2. Test : L'enseignant peut programmer un nouveau devoir pour sa classe.
     */
    public function test_teacher_can_create_homework_assignment_for_class(): void
    {
        $response = $this->actingAs($this->teacher, 'sanctum')
            ->postJson('/api/v1/teacher/assignments', [
                'class_id' => $this->class->id,
                'assessment_id' => $this->assessment->id,
                'title' => 'Devoir surveillé de Mathématiques N°2',
                'due_at' => now()->addDays(5)->toIso8601String(),
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Devoir surveillé de Mathématiques N°2');

        $this->assertDatabaseHas('assignments', [
            'class_id' => $this->class->id,
            'teacher_user_id' => $this->teacher->id,
            'title' => 'Devoir surveillé de Mathématiques N°2',
        ]);
    }

    /**
     * 3. Test SÉCURITÉ : Un élève ne peut JAMAIS créer un devoir (403 Forbidden).
     */
    public function test_student_cannot_create_assignment_security_check(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/teacher/assignments', [
                'class_id' => $this->class->id,
                'assessment_id' => $this->assessment->id,
                'title' => 'Devoir Frauduleux',
            ]);

        $response->assertStatus(403);
    }

    /**
     * 4. Test : L'élève peut lister les devoirs assignés à sa classe.
     */
    public function test_student_can_list_homework_for_their_enrolled_class(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/student/assignments');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertStringContainsString('Thalès', $data[0]['title']);
    }

    /**
     * 5. Test : L'élève peut soumettre une tentative réalisée en tant que devoir officiel.
     */
    public function test_student_can_submit_attempt_for_homework(): void
    {
        $assignment = Assignment::where('class_id', $this->class->id)->firstOrFail();

        // Créer une tentative réalisée par l'élève
        $attempt = AssessmentAttempt::create([
            'assessment_id' => $assignment->assessment_id,
            'student_user_id' => $this->student->id,
            'status' => 'submitted',
            'started_at' => now()->subMinutes(15),
            'submitted_at' => now(),
            'score' => 9.0,
            'max_score' => 10.0,
            'idempotency_key' => (string) Str::uuid(),
        ]);

        $response = $this->actingAs($this->student, 'sanctum')
            ->postJson("/api/v1/student/assignments/{$assignment->id}/submit", [
                'attempt_id' => $attempt->id,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'submitted');

        $this->assertDatabaseHas('assignment_submissions', [
            'assignment_id' => $assignment->id,
            'student_user_id' => $this->student->id,
            'attempt_id' => $attempt->id,
        ]);
    }

    /**
     * 6. Test SÉCURITÉ : Un élève ne peut pas lier la tentative d'un autre élève à son devoir.
     */
    public function test_student_cannot_submit_homework_with_another_students_attempt(): void
    {
        $assignment = Assignment::where('class_id', $this->class->id)->firstOrFail();

        // Tentative appartenant à un autre utilisateur
        $fraudulentAttempt = AssessmentAttempt::create([
            'assessment_id' => $assignment->assessment_id,
            'student_user_id' => $this->otherTeacher->id,
            'status' => 'submitted',
            'started_at' => now(),
            'max_score' => 10.0,
            'idempotency_key' => (string) Str::uuid(),
        ]);

        $response = $this->actingAs($this->student, 'sanctum')
            ->postJson("/api/v1/student/assignments/{$assignment->id}/submit", [
                'attempt_id' => $fraudulentAttempt->id,
            ]);

        $response->assertStatus(403);
    }

    /**
     * 7. Test : L'enseignant note officiellement la copie et fournit une appréciation.
     */
    public function test_teacher_can_grade_submission_with_official_score_and_feedback(): void
    {
        $assignment = Assignment::where('class_id', $this->class->id)->firstOrFail();

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_user_id' => $this->student->id,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($this->teacher, 'sanctum')
            ->postJson("/api/v1/teacher/submissions/{$submission->id}/grade", [
                'final_score' => 18.5,
                'feedback' => 'Excellente maîtrise des propriétés géométriques et démonstration rigoureuse.',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.final_score', 18.5)
            ->assertJsonPath('data.status', 'graded');

        $this->assertDatabaseHas('submission_grades', [
            'submission_id' => $submission->id,
            'final_score' => 18.50,
            'is_official' => true,
        ]);
    }

    /**
     * 8. Test SÉCURITÉ : Un enseignant tiers ne peut pas modifier les notes du devoir d'un collègue sans être admin.
     */
    public function test_teacher_cannot_grade_another_teachers_assignment(): void
    {
        $assignment = Assignment::where('class_id', $this->class->id)->firstOrFail();

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_user_id' => $this->student->id,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($this->otherTeacher, 'sanctum')
            ->postJson("/api/v1/teacher/submissions/{$submission->id}/grade", [
                'final_score' => 12.0,
                'feedback' => 'Modification non autorisée',
            ]);

        $response->assertStatus(403);
    }

    /**
     * 9. Test : Les interfaces web de l'espace enseignant retournent HTTP 200.
     */
    public function test_teacher_web_views_load_successfully(): void
    {
        $assignment = Assignment::where('class_id', $this->class->id)->firstOrFail();

        $this->get('/teacher/dashboard')->assertStatus(200);
        $this->get('/teacher/classes')->assertStatus(200);
        $this->get('/teacher/assignments')->assertStatus(200);
        $this->get("/teacher/assignments/{$assignment->id}/submissions")->assertStatus(200);
    }
}
