<?php

namespace Tests\Feature;

use App\Models\LearningResource;
use App\Models\ResourceVersion;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use App\Services\DocumentIngestionService;
use App\Services\EditorialWorkflowService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CurriculumAndResourcesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test 1 : Liste des cycles scolaires.
     */
    public function test_can_list_education_cycles(): void
    {
        $response = $this->getJson('/api/v1/education/cycles');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'code', 'name', 'description', 'pedagogical_order']
                ],
                'meta' => ['total'],
            ]);

        $codes = collect($response->json('data'))->pluck('code')->toArray();
        $this->assertContains('college', $codes);
        $this->assertContains('lycee', $codes);
    }

    /**
     * Test 2 : Filtrer les niveaux par cycle.
     */
    public function test_can_filter_levels_by_cycle(): void
    {
        $response = $this->getJson('/api/v1/education/levels?cycle=college');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $levels = collect($response->json('data'))->pluck('code')->toArray();
        $this->assertContains('6E', $levels);
        $this->assertContains('3E', $levels);
        $this->assertNotContains('TLE', $levels);
    }

    /**
     * Test 3 : Liste des séries/filières d'un niveau donné (Terminale).
     */
    public function test_can_list_tracks_for_level(): void
    {
        $response = $this->getJson('/api/v1/education/tracks?level=TLE');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $tracks = collect($response->json('data'))->pluck('code')->toArray();
        $this->assertContains('A4', $tracks);
        $this->assertContains('CD', $tracks);
    }

    /**
     * Test 4 : Matières et coefficients pour la classe de Troisième (Collège).
     */
    public function test_can_get_subjects_with_coefficients_for_troisieme(): void
    {
        $response = $this->getJson('/api/v1/education/subjects?level=3E');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $subjects = collect($response->json('data'));
        $maths = $subjects->firstWhere('code', 'MATHS');
        $francais = $subjects->firstWhere('code', 'FRANCAIS');
        $pct = $subjects->firstWhere('code', 'PCT');

        $this->assertNotNull($maths);
        $this->assertEquals(4, $maths['coefficient']);
        $this->assertEquals(4, $francais['coefficient']);
        $this->assertEquals(3, $pct['coefficient']);
    }

    /**
     * Test 5 : Matières et coefficients pour Terminale Série CD (Scientifique).
     */
    public function test_can_get_subjects_for_terminale_cd(): void
    {
        $response = $this->getJson('/api/v1/education/subjects?level=TLE&track=CD');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $subjects = collect($response->json('data'));
        $maths = $subjects->firstWhere('code', 'MATHS');
        $pct = $subjects->firstWhere('code', 'PCT');
        $svt = $subjects->firstWhere('code', 'SVT');

        $this->assertNotNull($maths);
        $this->assertEquals(6, $maths['coefficient']);
        $this->assertEquals(6, $pct['coefficient']);
        $this->assertEquals(5, $svt['coefficient']);
    }

    /**
     * Test 6 : Consultation de l'arborescence du programme de Mathématiques 3e.
     */
    public function test_can_fetch_curriculum_tree(): void
    {
        $curriculum = \App\Models\Curriculum::where('status', 'published')->first();
        $this->assertNotNull($curriculum);

        $response = $this->getJson("/api/v1/curricula/{$curriculum->id}/tree");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', $curriculum->title)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'version',
                    'academic_year',
                    'level' => ['code', 'name'],
                    'tree' => [
                        '*' => [
                            'id',
                            'code',
                            'title',
                            'objectives',
                            'children',
                        ]
                    ]
                ]
            ]);

        $tree = $response->json('data.tree');
        $this->assertNotEmpty($tree);
    }

    /**
     * Test 7 : Isolation stricte : L'API publique de ressources n'expose JAMAIS de brouillons.
     */
    public function test_public_resources_endpoint_never_exposes_drafts(): void
    {
        $response = $this->getJson('/api/v1/resources');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $items = $response->json('data');
        $this->assertNotEmpty($items);

        foreach ($items as $item) {
            $this->assertEquals('published', $item['status']);
            $this->assertNotEquals('draft', $item['status']);
            $this->assertNotEquals('rejected', $item['status']);
        }
    }

    /**
     * Test 8 : Accès direct à une ressource brouillon interdit aux élèves.
     */
    public function test_student_cannot_access_draft_resource_detail(): void
    {
        $draftResource = LearningResource::where('status', 'draft')->first();
        $this->assertNotNull($draftResource);

        $student = User::factory()->create(['status' => 'active']);
        $student->assignRole('student');

        $response = $this->actingAs($student, 'sanctum')
            ->getJson("/api/v1/resources/{$draftResource->id}");

        $response->assertStatus(403)
            ->assertJsonPath('error.code', 'FORBIDDEN');
    }

    /**
     * Test 9 : Workflow éditorial complet et règle de séparation (l'auteur ne peut pas s'auto-approuver).
     */
    public function test_editorial_workflow_prevents_author_self_approval(): void
    {
        $author = User::factory()->create(['status' => 'active']);
        $author->assignRole('teacher');

        $reviewer = User::factory()->create(['status' => 'active']);
        $reviewer->assignRole('admin');

        $subject = Subject::first();
        $level = \App\Models\EducationLevel::where('code', '3E')->first();
        $country = \App\Models\Country::where('code', 'TG')->first();

        $resource = LearningResource::create([
            'country_id' => $country->id,
            'subject_id' => $subject->id,
            'education_level_id' => $level->id,
            'title' => 'Fiche d’exercices sur les équations',
            'summary' => 'Exercices pratiques 3e',
            'resource_type' => 'exercise',
            'origin' => 'official',
            'status' => 'draft',
            'created_by' => $author->id,
        ]);

        $workflowService = app(EditorialWorkflowService::class);

        // 1. Soumission à relecture
        $workflowService->submitForReview($resource, $author);
        $this->assertEquals('review', $resource->fresh()->status);

        // 2. Tentative d'auto-approbation par l'auteur (DOIT ÉCHOUER)
        $this->expectException(AuthorizationException::class);
        $workflowService->reviewResource($resource, $author, 'approved', 'Je valide mon propre cours');
    }

    /**
     * Test 10 : Publication réussie après validation par un relecteur distinct.
     */
    public function test_editorial_workflow_approval_and_publish(): void
    {
        $author = User::factory()->create(['status' => 'active']);
        $author->assignRole('teacher');

        $reviewer = User::factory()->create(['status' => 'active']);
        $reviewer->assignRole('admin');

        $subject = Subject::first();
        $level = \App\Models\EducationLevel::where('code', '3E')->first();
        $country = \App\Models\Country::where('code', 'TG')->first();

        $resource = LearningResource::create([
            'country_id' => $country->id,
            'subject_id' => $subject->id,
            'education_level_id' => $level->id,
            'title' => 'Fiche d’exercices validée',
            'summary' => 'Exercices pratiques',
            'resource_type' => 'exercise',
            'origin' => 'official',
            'status' => 'draft',
            'created_by' => $author->id,
        ]);

        $workflowService = app(EditorialWorkflowService::class);

        // Soumission
        $workflowService->submitForReview($resource, $author);

        // Relecture par un tiers
        $workflowService->reviewResource($resource, $reviewer, 'approved', 'Conforme aux standards');
        $this->assertEquals('approved', $resource->fresh()->status);

        // Publication par un administrateur
        $published = $workflowService->publishResource($resource, $reviewer);
        $this->assertEquals('published', $published->status);
        $this->assertNotNull($published->published_at);
    }

    /**
     * Test 11 : Ingestion de fichier, détection de doublon SHA-256 et extraction de pages/chunks.
     */
    public function test_document_ingestion_and_duplicate_detection(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create(['status' => 'active']);
        $admin->assignRole('admin');

        $resource = LearningResource::first();
        $ingestionService = app(DocumentIngestionService::class);

        $fakeFile = UploadedFile::fake()->create('cours_algebrique.pdf', 150, 'application/pdf');

        // 1. Première ingestion
        $resourceFile = $ingestionService->ingestUploadedFile($resource, $fakeFile, $admin);
        $this->assertNotNull($resourceFile->checksum);
        $this->assertEquals('clean', $resourceFile->antivirus_scan_status);

        // 2. Extraction de texte, pages et chunks
        $version = $resourceFile->resourceVersion;
        $rawText = "Dans le triangle ABC, le théorème de Thalès permet d'établir les rapports proportionnels. Soit les points M et N. " .
                   "Deuxième paragraphe démontrant la réciproque avec alignement des points.";
        $extractionResult = $ingestionService->extractPagesAndChunks($version, $rawText, 10, 5);

        $this->assertGreaterThan(0, $extractionResult['pages_count']);
        $this->assertGreaterThan(0, $extractionResult['chunks_count']);

        // 3. Tentative d'ingérer exactement le même fichier (DOIT ÉCHOUER pour doublon)
        $duplicateFile = UploadedFile::fake()->create('cours_algebrique.pdf', 150, 'application/pdf');
        $this->expectException(ValidationException::class);
        $ingestionService->ingestUploadedFile($resource, $duplicateFile, $admin);
    }

    /**
     * Test 12 : Rôle admin exigé sur les routes d'importation batch.
     */
    public function test_student_forbidden_from_admin_imports(): void
    {
        $student = User::factory()->create(['status' => 'active']);
        $student->assignRole('student');

        $response = $this->actingAs($student, 'sanctum')
            ->postJson('/api/v1/admin/imports', [
                'file_name' => 'referentiel_togo.csv',
                'total_rows' => 50,
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test 13 : Création réussie d'un job d'import par l'administrateur.
     */
    public function test_admin_can_create_import_job(): void
    {
        $admin = User::factory()->create(['status' => 'active']);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/admin/imports', [
                'file_name' => 'referentiel_togo_2026.csv',
                'total_rows' => 120,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => ['job_id', 'status', 'file_name', 'created_at']
            ]);
    }
}
