<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Country;
use App\Models\Curriculum;
use App\Models\CurriculumObjective;
use App\Models\CurriculumTopic;
use App\Models\EducationLevel;
use App\Models\LearningResource;
use App\Models\LevelSubject;
use App\Models\ResourceChunk;
use App\Models\ResourceFile;
use App\Models\ResourcePage;
use App\Models\ResourceSource;
use App\Models\ResourceVersion;
use App\Models\SchoolCycle;
use App\Models\SchoolSeries;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TogoCurriculaSeeder extends Seeder
{
    /**
     * Run the database seeds for official Togo referentials, curricula, and resources.
     */
    public function run(): void
    {
        // 1. Pays Togo
        $country = Country::firstOrCreate(
            ['code' => 'TG'],
            [
                'id' => '00000000-0000-0000-0001-000000000001',
                'name' => 'Togo',
                'default_locale' => 'fr',
                'timezone' => 'Africa/Lome',
                'is_active' => true,
            ]
        );

        // 2. Année scolaire 2026-2027
        $academicYear = AcademicYear::firstOrCreate(
            ['country_id' => $country->id, 'label' => '2026-2027'],
            [
                'id' => '00000000-0000-0000-0002-000000000001',
                'starts_on' => '2026-09-15',
                'ends_on' => '2027-06-30',
                'is_current' => true,
            ]
        );

        // 3. Cycles scolaires
        $cyclePrimaire = SchoolCycle::firstOrCreate(
            ['code' => 'primaire'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Enseignement Primaire',
                'description' => 'Cours Primaire du CP1 au CM2',
                'pedagogical_order' => 1,
                'is_active' => true,
            ]
        );

        $cycleCollege = SchoolCycle::firstOrCreate(
            ['code' => 'college'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Enseignement Secondaire 1 (Collège)',
                'description' => 'Premier cycle du secondaire : de la 6e à la 3e (BEPC)',
                'pedagogical_order' => 2,
                'is_active' => true,
            ]
        );

        $cycleLycee = SchoolCycle::firstOrCreate(
            ['code' => 'lycee'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Enseignement Secondaire 2 (Lycée Général)',
                'description' => 'Second cycle du secondaire : Seconde, Première et Terminale (BAC)',
                'pedagogical_order' => 3,
                'is_active' => true,
            ]
        );

        $cycleTechnique = SchoolCycle::firstOrCreate(
            ['code' => 'technique'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Enseignement Technique et Formation Professionnelle',
                'description' => 'Séries industrielles (F1, F2, F3, F4) et tertiaires (G1, G2, G3)',
                'pedagogical_order' => 4,
                'is_active' => true,
            ]
        );

        // 4. Niveaux scolaires (Collège & Lycée)
        $level6e = EducationLevel::firstOrCreate(
            ['country_id' => $country->id, 'code' => '6E'],
            ['name' => 'Sixième', 'cycle' => 'college', 'school_cycle_id' => $cycleCollege->id, 'sort_order' => 1, 'is_exam_level' => false]
        );
        $level5e = EducationLevel::firstOrCreate(
            ['country_id' => $country->id, 'code' => '5E'],
            ['name' => 'Cinquième', 'cycle' => 'college', 'school_cycle_id' => $cycleCollege->id, 'sort_order' => 2, 'is_exam_level' => false]
        );
        $level4e = EducationLevel::firstOrCreate(
            ['country_id' => $country->id, 'code' => '4E'],
            ['name' => 'Quatrième', 'cycle' => 'college', 'school_cycle_id' => $cycleCollege->id, 'sort_order' => 3, 'is_exam_level' => false]
        );
        $level3e = EducationLevel::firstOrCreate(
            ['country_id' => $country->id, 'code' => '3E'],
            ['name' => 'Troisième (BEPC)', 'cycle' => 'college', 'school_cycle_id' => $cycleCollege->id, 'sort_order' => 4, 'is_exam_level' => true]
        );
        $level2nde = EducationLevel::firstOrCreate(
            ['country_id' => $country->id, 'code' => '2NDE'],
            ['name' => 'Seconde', 'cycle' => 'lycee', 'school_cycle_id' => $cycleLycee->id, 'sort_order' => 5, 'is_exam_level' => false]
        );
        $level1ere = EducationLevel::firstOrCreate(
            ['country_id' => $country->id, 'code' => '1ERE'],
            ['name' => 'Première (BAC 1)', 'cycle' => 'lycee', 'school_cycle_id' => $cycleLycee->id, 'sort_order' => 6, 'is_exam_level' => true]
        );
        $levelTle = EducationLevel::firstOrCreate(
            ['country_id' => $country->id, 'code' => 'TLE'],
            ['name' => 'Terminale (BAC 2)', 'cycle' => 'lycee', 'school_cycle_id' => $cycleLycee->id, 'sort_order' => 7, 'is_exam_level' => true]
        );

        // Mettre à jour school_cycle_id si absent
        $level3e->update(['school_cycle_id' => $cycleCollege->id, 'is_exam_level' => true]);
        $level1ere->update(['school_cycle_id' => $cycleLycee->id, 'is_exam_level' => true]);
        $levelTle->update(['school_cycle_id' => $cycleLycee->id, 'is_exam_level' => true]);

        // 5. Séries du Lycée
        $seriesA4 = SchoolSeries::firstOrCreate(
            ['country_id' => $country->id, 'education_level_id' => $levelTle->id, 'code' => 'A4'],
            ['name' => 'Série Littéraire (A4)', 'description' => 'Lettres, Langues, Philosophie, Histoire-Géo']
        );
        $seriesCD = SchoolSeries::firstOrCreate(
            ['country_id' => $country->id, 'education_level_id' => $levelTle->id, 'code' => 'CD'],
            ['name' => 'Série Scientifique (C / D)', 'description' => 'Mathématiques, PCT, SVT']
        );
        $seriesF = SchoolSeries::firstOrCreate(
            ['country_id' => $country->id, 'education_level_id' => $levelTle->id, 'code' => 'F'],
            ['name' => 'Séries Techniques Industrielles', 'description' => 'Génie Mécanique, Génie Civil, Électrotechnique']
        );

        // 6. Matières
        $maths = Subject::firstOrCreate(
            ['country_id' => $country->id, 'code' => 'MATHS'],
            ['name' => 'Mathématiques', 'description' => 'Algèbre, Géométrie, Analyse', 'icon_name' => 'function', 'is_active' => true]
        );
        $pct = Subject::firstOrCreate(
            ['country_id' => $country->id, 'code' => 'PCT'],
            ['name' => 'Physique-Chimie et Technologie', 'description' => 'Mécanique, Électricité, Chimie', 'icon_name' => 'atom', 'is_active' => true]
        );
        $svt = Subject::firstOrCreate(
            ['country_id' => $country->id, 'code' => 'SVT'],
            ['name' => 'Sciences de la Vie et de la Terre', 'description' => 'Biologie, Géologie', 'icon_name' => 'dna', 'is_active' => true]
        );
        $francais = Subject::firstOrCreate(
            ['country_id' => $country->id, 'code' => 'FRANCAIS'],
            ['name' => 'Français', 'description' => 'Littérature, Grammaire, Expression', 'icon_name' => 'book-open', 'is_active' => true]
        );
        $philo = Subject::firstOrCreate(
            ['country_id' => $country->id, 'code' => 'PHILO'],
            ['name' => 'Philosophie', 'description' => 'Épistémologie, Métaphysique, Éthique', 'icon_name' => 'feather', 'is_active' => true]
        );
        $anglais = Subject::firstOrCreate(
            ['country_id' => $country->id, 'code' => 'ANGLAIS'],
            ['name' => 'Anglais', 'description' => 'Communication orale et écrite', 'icon_name' => 'globe', 'is_active' => true]
        );
        $histGeo = Subject::firstOrCreate(
            ['country_id' => $country->id, 'code' => 'HIST_GEO'],
            ['name' => 'Histoire et Géographie', 'description' => 'Histoire générale et Géographie', 'icon_name' => 'map', 'is_active' => true]
        );
        $ecm = Subject::firstOrCreate(
            ['country_id' => $country->id, 'code' => 'ECM'],
            ['name' => 'Éducation Civique et Morale', 'description' => 'Citoyenneté et Institutions', 'icon_name' => 'shield', 'is_active' => true]
        );

        // 7. Associations Niveau-Matière (level_subjects)
        // Collège 3e
        $level3eSubjects = [
            ['sub' => $maths, 'mandatory' => true, 'coeff' => 4, 'hours' => 4.0],
            ['sub' => $pct, 'mandatory' => true, 'coeff' => 3, 'hours' => 3.0],
            ['sub' => $svt, 'mandatory' => true, 'coeff' => 3, 'hours' => 3.0],
            ['sub' => $francais, 'mandatory' => true, 'coeff' => 4, 'hours' => 5.0],
            ['sub' => $anglais, 'mandatory' => true, 'coeff' => 2, 'hours' => 3.0],
            ['sub' => $histGeo, 'mandatory' => true, 'coeff' => 2, 'hours' => 3.0],
            ['sub' => $ecm, 'mandatory' => true, 'coeff' => 1, 'hours' => 1.0],
        ];
        foreach ($level3eSubjects as $item) {
            LevelSubject::firstOrCreate([
                'education_level_id' => $level3e->id,
                'series_id' => null,
                'subject_id' => $item['sub']->id,
            ], [
                'is_mandatory' => $item['mandatory'],
                'coefficient' => $item['coeff'],
                'weekly_hours' => $item['hours'],
                'is_active' => true,
            ]);
        }

        // Terminale CD (Scientifique)
        $tleCdSubjects = [
            ['sub' => $maths, 'coeff' => 6, 'hours' => 6.0],
            ['sub' => $pct, 'coeff' => 6, 'hours' => 6.0],
            ['sub' => $svt, 'coeff' => 5, 'hours' => 4.0],
            ['sub' => $philo, 'coeff' => 3, 'hours' => 3.0],
            ['sub' => $francais, 'coeff' => 3, 'hours' => 4.0],
            ['sub' => $anglais, 'coeff' => 2, 'hours' => 2.0],
        ];
        foreach ($tleCdSubjects as $item) {
            LevelSubject::firstOrCreate([
                'education_level_id' => $levelTle->id,
                'series_id' => $seriesCD->id,
                'subject_id' => $item['sub']->id,
            ], [
                'is_mandatory' => true,
                'coefficient' => $item['coeff'],
                'weekly_hours' => $item['hours'],
                'is_active' => true,
            ]);
        }

        // Terminale A4 (Littéraire)
        $tleA4Subjects = [
            ['sub' => $philo, 'coeff' => 6, 'hours' => 6.0],
            ['sub' => $francais, 'coeff' => 5, 'hours' => 5.0],
            ['sub' => $histGeo, 'coeff' => 4, 'hours' => 4.0],
            ['sub' => $anglais, 'coeff' => 4, 'hours' => 4.0],
            ['sub' => $maths, 'coeff' => 2, 'hours' => 2.0],
        ];
        foreach ($tleA4Subjects as $item) {
            LevelSubject::firstOrCreate([
                'education_level_id' => $levelTle->id,
                'series_id' => $seriesA4->id,
                'subject_id' => $item['sub']->id,
            ], [
                'is_mandatory' => true,
                'coefficient' => $item['coeff'],
                'weekly_hours' => $item['hours'],
                'is_active' => true,
            ]);
        }

        // 8. Programme officiel de Mathématiques — Classe de 3e (BEPC)
        $curriculum3eMaths = Curriculum::firstOrCreate([
            'country_id' => $country->id,
            'academic_year_id' => $academicYear->id,
            'education_level_id' => $level3e->id,
            'series_id' => null,
            'title' => 'Programme Officiel de Mathématiques — Classe de Troisième (BEPC)',
        ], [
            'source_url' => 'https://ecole.gouv.tg/college/course/index.php?categoryid=1',
            'source_title' => 'Ministère des Enseignements Primaire, Secondaire et Technique du Togo (MEPST)',
            'verified_at' => now(),
            'status' => 'published',
            'version' => 1,
        ]);

        // Arbre thématique : Domaine 1
        $domaineNumerique = CurriculumTopic::firstOrCreate([
            'curriculum_id' => $curriculum3eMaths->id,
            'subject_id' => $maths->id,
            'parent_topic_id' => null,
            'code' => 'DOM-NUM-3E',
            'title' => 'Domaine 1 : Activités Numériques et Calcul',
        ], [
            'description' => 'Arithmétique, fractions, calcul littéral et équations',
            'sort_order' => 1,
        ]);

        $chapitreArithmetique = CurriculumTopic::firstOrCreate([
            'curriculum_id' => $curriculum3eMaths->id,
            'subject_id' => $maths->id,
            'parent_topic_id' => $domaineNumerique->id,
            'code' => 'CHAP-ARITH-3E',
            'title' => 'Chapitre 1 : Arithmétique et calcul numérique',
        ], [
            'description' => 'PGCD, algorithme d’Euclide, fractions irréductibles',
            'sort_order' => 1,
        ]);

        CurriculumObjective::firstOrCreate([
            'topic_id' => $chapitreArithmetique->id,
            'code' => 'OBJ-PGCD-1',
            'description' => 'Calculer le PGCD de deux entiers naturels par l’algorithme des divisions successives (Euclide).',
            'sort_order' => 1,
        ]);
        CurriculumObjective::firstOrCreate([
            'topic_id' => $chapitreArithmetique->id,
            'code' => 'OBJ-PGCD-2',
            'description' => 'Simplifier une fraction pour la rendre irréductible en divisant par le PGCD.',
            'sort_order' => 2,
        ]);

        $chapitreLitteral = CurriculumTopic::firstOrCreate([
            'curriculum_id' => $curriculum3eMaths->id,
            'subject_id' => $maths->id,
            'parent_topic_id' => $domaineNumerique->id,
            'code' => 'CHAP-LITT-3E',
            'title' => 'Chapitre 2 : Calcul littéral et équations-produits',
        ], [
            'description' => 'Développements, factorisations et résolutions d’équations',
            'sort_order' => 2,
        ]);

        CurriculumObjective::firstOrCreate([
            'topic_id' => $chapitreLitteral->id,
            'code' => 'OBJ-LITT-1',
            'description' => 'Développer et réduire une expression en utilisant les identités remarquables du second degré.',
            'sort_order' => 1,
        ]);
        CurriculumObjective::firstOrCreate([
            'topic_id' => $chapitreLitteral->id,
            'code' => 'OBJ-LITT-2',
            'description' => 'Résoudre des équations de la forme (ax + b)(cx + d) = 0.',
            'sort_order' => 2,
        ]);

        // Domaine 2 : Géométrie
        $domaineGeometrie = CurriculumTopic::firstOrCreate([
            'curriculum_id' => $curriculum3eMaths->id,
            'subject_id' => $maths->id,
            'parent_topic_id' => null,
            'code' => 'DOM-GEOM-3E',
            'title' => 'Domaine 2 : Travaux Géométriques et Trigonométrie',
        ], [
            'description' => 'Théorème de Thalès, trigonométrie et géométrie vectorielle',
            'sort_order' => 2,
        ]);

        $chapitreThales = CurriculumTopic::firstOrCreate([
            'curriculum_id' => $curriculum3eMaths->id,
            'subject_id' => $maths->id,
            'parent_topic_id' => $domaineGeometrie->id,
            'code' => 'CHAP-THALES-3E',
            'title' => 'Chapitre 3 : Théorème de Thalès et sa réciproque',
        ], [
            'description' => 'Calcul de longueurs et démonstration du parallélisme',
            'sort_order' => 1,
        ]);

        CurriculumObjective::firstOrCreate([
            'topic_id' => $chapitreThales->id,
            'code' => 'OBJ-THALES-1',
            'description' => 'Utiliser le théorème de Thalès dans un triangle pour calculer une longueur inconnue.',
            'sort_order' => 1,
        ]);
        CurriculumObjective::firstOrCreate([
            'topic_id' => $chapitreThales->id,
            'code' => 'OBJ-THALES-2',
            'description' => 'Appliquer la réciproque du théorème de Thalès pour démontrer que deux droites sont parallèles.',
            'sort_order' => 2,
        ]);

        // 9. Ressources pédagogiques publiées & certifiées
        // Trouver ou créer un utilisateur enseignant / admin pour attribution
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@kamehub.tg'],
            [
                'phone' => '+22890000001',
                'password_hash' => bcrypt('AdminPassword2026!'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');

        $teacherUser = User::firstOrCreate(
            ['email' => 'prof.maths@kamehub.tg'],
            [
                'phone' => '+22890000002',
                'password_hash' => bcrypt('TeacherPassword2026!'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $teacherUser->assignRole('teacher');

        // Ressource 1 : Cours publié sur Thalès
        $coursThales = LearningResource::firstOrCreate([
            'title' => 'Fiche Pédagogique et Méthode : Le Théorème de Thalès et sa Réciproque',
            'subject_id' => $maths->id,
            'education_level_id' => $level3e->id,
        ], [
            'country_id' => $country->id,
            'curriculum_id' => $curriculum3eMaths->id,
            'summary' => 'Fiche complète de cours conforme au programme togolais de 3e : énoncé formel du théorème de Thalès, configurations classiques (triangle et papillon), méthode de rédaction type examen BEPC et réciproque.',
            'resource_type' => 'course',
            'origin' => 'official',
            'difficulty_level' => 'medium',
            'is_solution_restricted' => false,
            'status' => 'published',
            'created_by' => $teacherUser->id,
            'reviewed_by' => $adminUser->id,
            'published_at' => now(),
        ]);

        // Rattacher au chapitre Thalès
        if (!$coursThales->topics()->where('curriculum_topics.id', $chapitreThales->id)->exists()) {
            $coursThales->topics()->attach($chapitreThales->id, ['relevance_score' => 1.00]);
        }

        // Source officielle rattachée
        ResourceSource::firstOrCreate([
            'resource_id' => $coursThales->id,
            'source_title' => 'Guide d’Exécution du Programme de Mathématiques au Collège',
        ], [
            'publisher' => 'Ministère des Enseignements Primaire, Secondaire et Technique du Togo',
            'source_url' => 'https://ecole.gouv.tg/college/course/view.php?id=12',
            'accessed_at' => '2026-10-01',
            'verified_at' => now(),
            'verification_status' => 'verified',
            'notes' => 'Conforme aux directives nationales d’évaluation au BEPC',
            'created_at' => now(),
        ]);

        // Version 1 avec pages et chunks
        $versionThales = ResourceVersion::firstOrCreate([
            'resource_id' => $coursThales->id,
            'version_number' => 1,
        ], [
            'content_text' => 'Dans un triangle ABC, si M est un point de (AB) et N un point de (AC) tels que (MN) // (BC), alors AM/AB = AN/AC = MN/BC.',
            'checksum' => hash('sha256', 'cours-thales-3e-togo-v1'),
            'change_note' => 'Publication de la version de référence 3e.',
            'created_by' => $adminUser->id,
            'created_at' => now(),
        ]);

        // Fichier privé simulé
        ResourceFile::firstOrCreate([
            'resource_version_id' => $versionThales->id,
            'checksum' => hash('sha256', 'cours-thales-3e-togo-v1'),
        ], [
            'storage_key' => 'resources/thales_3e_cours.pdf',
            'original_name' => 'Fiche_Thales_3e_Togo_Officiel.pdf',
            'mime_type' => 'application/pdf',
            'size_bytes' => 245890,
            'access_level' => 'private',
            'antivirus_scan_status' => 'clean',
            'is_quarantined' => false,
            'created_at' => now(),
        ]);

        // Pages et chunks textuels
        $page1 = ResourcePage::firstOrCreate([
            'resource_version_id' => $versionThales->id,
            'page_number' => 1,
        ], [
            'extracted_text' => 'Théorème direct de Thalès : Soit deux droites sécantes en A. Soit B et M deux points de la première droite, C et N deux points de la deuxième droite distincts de A. Si les droites (BC) et (MN) sont parallèles, alors AM/AB = AN/AC = MN/BC.',
            'extraction_status' => 'completed',
            'created_at' => now(),
        ]);

        ResourceChunk::firstOrCreate([
            'resource_version_id' => $versionThales->id,
            'chunk_index' => 0,
        ], [
            'page_id' => $page1->id,
            'content_text' => 'Énoncé du théorème direct de Thalès dans le triangle ABC avec (MN) parallèle à (BC).',
            'token_estimate' => 35,
            'created_at' => now(),
        ]);

        // Ressource 2 (Brouillon non publié pour tester l'isolation stricte)
        LearningResource::firstOrCreate([
            'title' => 'Brouillon — Devoir d’entraînement non validé sur les PGCD',
            'subject_id' => $maths->id,
            'education_level_id' => $level3e->id,
        ], [
            'country_id' => $country->id,
            'curriculum_id' => $curriculum3eMaths->id,
            'summary' => 'Exercices bruts en cours de rédaction par un enseignant.',
            'resource_type' => 'exercise',
            'origin' => 'teacher_curated',
            'difficulty_level' => 'hard',
            'is_solution_restricted' => true,
            'status' => 'draft',
            'created_by' => $teacherUser->id,
            'published_at' => null,
        ]);
    }
}
