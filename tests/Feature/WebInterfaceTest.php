<?php

namespace Tests\Feature;

use App\Models\LearningResource;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebInterfaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test 1 : Page d'accueil vitrine.
     */
    public function test_welcome_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200)
            ->assertSee('MONKAYIER')
            ->assertSee('Togo');
    }

    /**
     * Test 2 : Tableau de bord de l'élève (Classe de 3e).
     */
    public function test_student_dashboard_displays_hero_and_subjects(): void
    {
        $response = $this->get('/student/dashboard');

        $response->assertStatus(200)
            ->assertSee('Théorème de Thalès')
            ->assertSee('Objectif BEPC')
            ->assertSee('Mathématiques')
            ->assertSee('Physique-Chimie (PCT)')
            ->assertSee('72%');
    }

    /**
     * Test 3 : Page des matières de l'élève avec coefficients officiels.
     */
    public function test_student_subjects_page_displays_curriculum_chapters(): void
    {
        $response = $this->get('/student/subjects');

        $response->assertStatus(200)
            ->assertSee('Mathématiques')
            ->assertSee('Coefficient')
            ->assertSee('Physique-Chimie et Technologie (PCT)')
            ->assertSee('Arithmétique');
    }

    /**
     * Test 4 : Bibliothèque numérique (affiche uniquement les cours publiés).
     */
    public function test_student_library_displays_published_resources_only(): void
    {
        $published = LearningResource::where('status', 'published')->first();
        $draft = LearningResource::where('status', 'draft')->first();

        $response = $this->get('/student/library');

        $response->assertStatus(200)
            ->assertSee($published->title);

        if ($draft) {
            $response->assertDontSee($draft->title);
        }
    }

    /**
     * Test 5 : Lecteur de cours conforme avec source officielle MEPST.
     */
    public function test_student_course_reader_renders_pedagogical_content(): void
    {
        $resource = LearningResource::where('status', 'published')->first();
        $this->assertNotNull($resource);

        $response = $this->get("/student/reader/{$resource->id}");

        $response->assertStatus(200)
            ->assertSee($resource->title)
            ->assertSee('Ministère des Enseignements Primaire, Secondaire et Technique')
            ->assertSee('AM / AB = AN / AC = MN / BC');
    }

    /**
     * Test 6 : Assistant Pédagogique IA interface.
     */
    public function test_student_assistant_page_renders_chat_and_citations(): void
    {
        $response = $this->get('/student/assistant');

        $response->assertStatus(200)
            ->assertSee('Assistant IA MonKayier')
            ->assertSee('RAG Actif')
            ->assertSee('Guide d\'Exécution National de Mathématiques 3e', false)
            ->assertSee('Explication complémentaire · hors de ton cours', false);
    }

    /**
     * Test 7 : Back-Office Admin — Vue d'ensemble.
     */
    public function test_admin_dashboard_renders_metrics(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200)
            ->assertSee('Supervision des Référentiels & Contenus')
            ->assertSee('Cycles Scolaires')
            ->assertSee('Mathématiques — Classe de Troisième (BEPC)');
    }

    /**
     * Test 8 : Back-Office Admin — Référentiels (Cycles, Classes, Coefficients).
     */
    public function test_admin_referentials_renders_cycles_and_levels(): void
    {
        $response = $this->get('/admin/referentials');

        $response->assertStatus(200)
            ->assertSee('Enseignement Secondaire 1 (Collège)')
            ->assertSee('Troisième (BEPC)')
            ->assertSee('Coeff 4');
    }

    /**
     * Test 9 : Back-Office Admin — Arborescence des programmes.
     */
    public function test_admin_curricula_renders_topic_tree(): void
    {
        $response = $this->get('/admin/curricula');

        $response->assertStatus(200)
            ->assertSee('Programme Officiel de Mathématiques — Classe de Troisième (BEPC)')
            ->assertSee('Domaine 1 : Activités Numériques et Calcul')
            ->assertSee('Chapitre 1 : Arithmétique et calcul numérique')
            ->assertSee('Calculer le PGCD de deux entiers naturels');
    }

    /**
     * Test 10 : Back-Office Admin — Bibliothèque et validation éditoriale.
     */
    public function test_admin_resources_workflow_page(): void
    {
        $response = $this->get('/admin/resources');

        $response->assertStatus(200)
            ->assertSee('Ressources Pédagogiques Enregistrées')
            ->assertSee('Règle de Gouvernance Souveraine')
            ->assertSee('Sécurité Active');
    }

    /**
     * Test 11 : Back-Office Admin — Imports Batch.
     */
    public function test_admin_imports_page_renders(): void
    {
        $response = $this->get('/admin/imports');

        $response->assertStatus(200)
            ->assertSee('Importer un nouveau lot de référentiels')
            ->assertSee('Mode simulation (Dry-Run)');
    }
}
