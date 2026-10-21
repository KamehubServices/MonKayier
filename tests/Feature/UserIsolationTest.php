<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class UserIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test SÉCURITÉ : Un élève A ne peut jamais modifier le profil d'un élève B (Isolation horizontale).
     */
    public function test_student_cannot_update_another_students_profile(): void
    {
        $studentA = User::create([
            'email' => 'student.a@monkayier.tg',
            'password_hash' => bcrypt('Password123!'),
            'status' => 'active',
        ]);
        UserProfile::create([
            'user_id' => $studentA->id,
            'first_name' => 'Koffi',
            'last_name' => 'A',
        ]);
        $studentA->assignRole('student');

        $studentB = User::create([
            'email' => 'student.b@monkayier.tg',
            'password_hash' => bcrypt('Password123!'),
            'status' => 'active',
        ]);
        UserProfile::create([
            'user_id' => $studentB->id,
            'first_name' => 'Ablavi',
            'last_name' => 'B',
        ]);
        $studentB->assignRole('student');

        // Test via Gate / Policy
        $this->assertFalse(Gate::forUser($studentA)->allows('update', $studentB));
        $this->assertFalse(Gate::forUser($studentA)->allows('delete', $studentB));
        $this->assertTrue(Gate::forUser($studentA)->allows('update', $studentA));
    }

    /**
     * Test SÉCURITÉ : Tentative d'injection d'élévation de privilèges via update de profil.
     */
    public function test_profile_update_cannot_escalate_role_or_status(): void
    {
        $student = User::create([
            'email' => 'hacker.student@monkayier.tg',
            'password_hash' => bcrypt('Password123!'),
            'status' => 'active',
        ]);
        UserProfile::create([
            'user_id' => $student->id,
            'first_name' => 'Innocent',
            'last_name' => 'Student',
        ]);
        $student->assignRole('student');

        $response = $this->actingAs($student, 'sanctum')->patchJson('/api/v1/me', [
            'status' => 'admin',
            'role' => 'admin',
            'is_admin' => true,
            'first_name' => 'UpdatedFirst',
        ]);

        $response->assertStatus(200);

        $student->refresh();
        $this->assertEquals('active', $student->status);
        $this->assertFalse($student->hasRole('admin'));
        $this->assertTrue($student->hasRole('student'));
        $this->assertEquals('UpdatedFirst', $student->profile->first_name);
    }
}
