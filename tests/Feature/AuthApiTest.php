<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Initialiser les rôles système
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test : Health check minimaliste sans fuite de secrets.
     */
    public function test_health_check_endpoint_returns_pass(): void
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['status', 'service', 'version', 'timestamp'],
                'meta' => ['request_id'],
            ])
            ->assertJsonPath('data.status', 'pass');
    }

    /**
     * Test : Inscription valide d'un élève.
     */
    public function test_student_can_register_successfully(): void
    {
        $payload = [
            'first_name' => 'Koffi',
            'last_name' => 'Mensah',
            'email' => 'koffi.mensah@example.tg',
            'password' => 'MotDePasseFort123!',
            'preferred_language' => 'fr',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'email', 'status', 'first_name', 'last_name', 'roles'],
                    'token',
                ],
                'meta' => ['request_id'],
            ]);

        // Vérifier que le rôle assigné par défaut est obligatoirement "student"
        $this->assertDatabaseHas('users', ['email' => 'koffi.mensah@example.tg']);
        $user = User::where('email', 'koffi.mensah@example.tg')->first();
        $this->assertTrue($user->hasRole('student'));
        $this->assertFalse($user->hasRole('admin'));
    }

    /**
     * Test SÉCURITÉ : Tentative d'auto-attribution d'un rôle privilégié lors du register.
     */
    public function test_registration_ignores_privileged_role_injection(): void
    {
        $payload = [
            'first_name' => 'Hacker',
            'last_name' => 'Attempt',
            'email' => 'hacker@example.tg',
            'password' => 'SecretPassword123!',
            'role' => 'admin',
            'is_admin' => true,
            'roles' => ['admin', 'school_admin'],
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertStatus(201);
        $user = User::where('email', 'hacker@example.tg')->first();

        // Le rôle reste "student", l'élévation de privilège a échoué
        $this->assertTrue($user->hasRole('student'));
        $this->assertFalse($user->hasRole('admin'));
    }

    /**
     * Test : Connexion avec identifiants valides.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'email' => 'eleve@monkayier.tg',
            'password_hash' => bcrypt('Password123!'),
            'status' => 'active',
        ]);
        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ablavi',
            'last_name' => 'Dovi',
        ]);
        $user->assignRole('student');

        $response = $this->postJson('/api/v1/auth/login', [
            'login' => 'eleve@monkayier.tg',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'email', 'status', 'roles'],
                    'token',
                ],
                'meta' => ['request_id'],
            ]);
    }

    /**
     * Test SÉCURITÉ : Mot de passe incorrect renvoie une réponse générique anti-énumération.
     */
    public function test_login_with_invalid_password_returns_generic_error(): void
    {
        User::create([
            'email' => 'target@monkayier.tg',
            'password_hash' => bcrypt('ValidPassword123!'),
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login' => 'target@monkayier.tg',
            'password' => 'WrongPassword',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('error.code', 'INVALID_CREDENTIALS');
    }

    /**
     * Test SÉCURITÉ : Un compte suspendu ne peut pas se connecter.
     */
    public function test_suspended_account_cannot_login(): void
    {
        User::create([
            'email' => 'suspendu@monkayier.tg',
            'password_hash' => bcrypt('Password123!'),
            'status' => 'suspended',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login' => 'suspendu@monkayier.tg',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('error.code', 'ACCOUNT_SUSPENDED');
    }

    /**
     * Test : Accès refusé à /api/v1/me sans token (401).
     */
    public function test_unauthenticated_user_cannot_access_me_endpoint(): void
    {
        $response = $this->getJson('/api/v1/me');

        $response->assertStatus(401);
    }

    /**
     * Test : L'utilisateur connecté peut consulter son profil via /api/v1/me.
     */
    public function test_authenticated_user_can_access_me_endpoint(): void
    {
        $user = User::create([
            'email' => 'connecte@monkayier.tg',
            'password_hash' => bcrypt('Password123!'),
            'status' => 'active',
        ]);
        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Kodjo',
            'last_name' => 'Tchala',
        ]);
        $user->assignRole('student');

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/me');

        $response->assertStatus(200)
            ->assertJsonPath('data.email', 'connecte@monkayier.tg')
            ->assertJsonPath('data.profile.first_name', 'Kodjo')
            ->assertJsonPath('data.roles.0', 'student');
    }

    /**
     * Test : Mise à jour du profil par l'utilisateur connecté.
     */
    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::create([
            'email' => 'update@monkayier.tg',
            'password_hash' => bcrypt('Password123!'),
            'status' => 'active',
        ]);
        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ancien',
            'last_name' => 'Nom',
        ]);

        $response = $this->actingAs($user, 'sanctum')->patchJson('/api/v1/me', [
            'first_name' => 'Nouveau',
            'last_name' => 'Prenom',
            'bio' => 'Élève passionné de physique.',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'first_name' => 'Nouveau',
        ]);
    }

    /**
     * Test : Déconnexion et révocation de token.
     */
    public function test_user_can_logout_and_revoke_token(): void
    {
        $user = User::create([
            'email' => 'logout@monkayier.tg',
            'password_hash' => bcrypt('Password123!'),
            'status' => 'active',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJsonPath('data.message', 'Déconnexion réussie.');

        // Le token a été supprimé
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
