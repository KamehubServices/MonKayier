<?php

namespace Tests\Feature;

use App\Models\ParentLinkCode;
use App\Models\ParentReportAccessLog;
use App\Models\ParentStudentLink;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentSupervisionTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected User $parent;
    protected User $otherParent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->student = User::where('email', 'koffi.student@monkayier.tg')->firstOrFail();

        // Parent 1 (M. Mensah)
        $this->parent = User::create([
            'email' => 'parent.mensah@gmail.com',
            'password_hash' => bcrypt('ParentPass2026!'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        UserProfile::create([
            'user_id' => $this->parent->id,
            'first_name' => 'Kokou',
            'last_name' => 'Mensah',
        ]);
        $this->parent->assignRole('parent');

        // Parent 2 (tiers non relié)
        $this->otherParent = User::create([
            'email' => 'other.parent@gmail.com',
            'password_hash' => bcrypt('ParentPass2026!'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        UserProfile::create([
            'user_id' => $this->otherParent->id,
            'first_name' => 'Afi',
            'last_name' => 'Gbede',
        ]);
        $this->otherParent->assignRole('parent');
    }

    /**
     * 1. Test : L'élève peut générer un code à 6 caractères pour son parent.
     */
    public function test_student_can_generate_parent_link_code(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/student/link-code');

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $code = $response->json('data.link_code');
        $this->assertNotEmpty($code);
        $this->assertEquals(6, strlen($code));

        $this->assertDatabaseHas('parent_link_codes', [
            'student_user_id' => $this->student->id,
            'code_hash' => hash('sha256', strtoupper($code)),
        ]);
    }

    /**
     * 2. Test : Le parent saisit le code et le lien est activé (status = verified).
     */
    public function test_parent_can_verify_and_link_child_using_code(): void
    {
        // Générer le code
        $codeRes = $this->actingAs($this->student, 'sanctum')->postJson('/api/v1/student/link-code');
        $rawCode = $codeRes->json('data.link_code');

        // Le parent soumet le code
        $linkRes = $this->actingAs($this->parent, 'sanctum')
            ->postJson('/api/v1/parent/link', [
                'code' => $rawCode,
                'relationship_type' => 'parent',
            ]);

        $linkRes->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'verified');

        $this->assertDatabaseHas('parent_student_links', [
            'parent_user_id' => $this->parent->id,
            'student_user_id' => $this->student->id,
            'status' => 'verified',
        ]);
    }

    /**
     * 3. Test SÉCURITÉ : Un code erroné ou expiré échoue avec 422.
     */
    public function test_invalid_or_expired_code_fails_linking(): void
    {
        $response = $this->actingAs($this->parent, 'sanctum')
            ->postJson('/api/v1/parent/link', [
                'code' => 'INVALID',
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    /**
     * 4. Test : Le parent peut lister ses enfants reliés.
     */
    public function test_parent_can_view_linked_children(): void
    {
        ParentStudentLink::create([
            'parent_user_id' => $this->parent->id,
            'student_user_id' => $this->student->id,
            'status' => 'verified',
            'relationship_type' => 'parent',
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($this->parent, 'sanctum')
            ->getJson('/api/v1/parent/children');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals($this->student->id, $data[0]['student_id']);
    }

    /**
     * 5. Test : Le parent relié peut consulter le rapport de son enfant (notes, compte à rebours BEPC).
     */
    public function test_parent_can_view_child_supervision_report(): void
    {
        ParentStudentLink::create([
            'parent_user_id' => $this->parent->id,
            'student_user_id' => $this->student->id,
            'status' => 'verified',
            'relationship_type' => 'parent',
            'verified_at' => now(),
        ]);

        $response = $this->actingAs($this->parent, 'sanctum')
            ->getJson("/api/v1/parent/children/{$this->student->id}/report");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.student.id', $this->student->id);

        $data = $response->json('data');
        $this->assertArrayHasKey('academic_summary', $data);
        $this->assertArrayHasKey('national_exam_countdown', $data);
        $this->assertEquals('Brevet d’Études du Premier Cycle (BEPC 2027)', $data['national_exam_countdown']['exam_label']);
    }

    /**
     * 6. Test ISOLATION HORIZONTALE CRITIQUE : Un parent non relié ne peut pas accéder aux notes de l'élève (403 Forbidden).
     */
    public function test_unlinked_parent_cannot_view_child_report_security(): void
    {
        $response = $this->actingAs($this->otherParent, 'sanctum')
            ->getJson("/api/v1/parent/children/{$this->student->id}/report");

        $response->assertStatus(403);
    }

    /**
     * 7. Test AUDITABILITÉ : L'accès d'un parent au rapport est tracé dans la table d'audit parent_report_access_logs.
     */
    public function test_parent_report_access_is_logged_in_audit_table(): void
    {
        ParentStudentLink::create([
            'parent_user_id' => $this->parent->id,
            'student_user_id' => $this->student->id,
            'status' => 'verified',
            'relationship_type' => 'parent',
            'verified_at' => now(),
        ]);

        $this->actingAs($this->parent, 'sanctum')
            ->getJson("/api/v1/parent/children/{$this->student->id}/report");

        $this->assertDatabaseHas('parent_report_access_logs', [
            'parent_user_id' => $this->parent->id,
            'student_user_id' => $this->student->id,
        ]);
    }

    /**
     * 8. Test : L'interface web de l'espace parents retourne HTTP 200.
     */
    public function test_parent_web_dashboard_loads_with_http_200(): void
    {
        $this->get('/parent/dashboard')->assertStatus(200);
    }
}
