<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\Curriculum;
use App\Models\LearningResource;
use App\Models\QuestionAnswer;
use App\Models\QuestionOption;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssessmentSeeder extends Seeder
{
    /**
     * Seed official BEPC assessments for Togo (Mathématiques 3e - Thalès).
     */
    public function run(): void
    {
        $maths = Subject::where('code', 'MATHS')->first();
        $admin = User::where('email', 'admin@kamehub.tg')->first();
        $curriculum = Curriculum::where('title', 'like', '%Troisième%')->first();
        $resource = LearningResource::where('title', 'like', '%Théorème de Thalès%')->first();

        if (!$maths || !$admin) {
            return;
        }

        // 1. Évaluation 1 : Quiz Formatif BEPC — Théorème de Thalès & Réciproque (Publiée)
        $assessment1 = Assessment::firstOrCreate(
            ['title' => 'Entraînement BEPC : Théorème de Thalès et sa Réciproque'],
            [
                'id' => (string) Str::uuid(),
                'resource_id' => $resource?->id,
                'curriculum_id' => $curriculum?->id,
                'subject_id' => $maths->id,
                'assessment_type' => 'quiz',
                'duration_seconds' => 900, // 15 min
                'status' => 'published',
                'created_by' => $admin->id,
            ]
        );

        // Si l'évaluation avait déjà des questions, ne pas dupliquer
        if ($assessment1->questions()->count() === 0) {
            // Question 1 : Choix unique (2 pts)
            $q1 = AssessmentQuestion::create([
                'assessment_id' => $assessment1->id,
                'question_type' => 'single_choice',
                'prompt' => 'Dans un triangle ABC, avec M sur [AB] et N sur [AC], si la droite (MN) est strictement parallèle à la droite (BC), quelle égalité de rapports est correcte d’après le théorème de Thalès ?',
                'explanation' => 'Le théorème direct de Thalès établit le rapport : AM/AB = AN/AC = MN/BC.',
                'points' => 2.00,
                'position' => 1,
                'metadata' => ['competency_code' => 'OBJ-THALES-1'],
            ]);

            QuestionOption::create([
                'question_id' => $q1->id,
                'option_text' => 'AM / AB = AN / AC = MN / BC',
                'position' => 1,
                'is_correct' => true,
                'explanation' => 'Parfait ! C’est l’expression exacte des rapports de longueurs homologues.',
            ]);

            QuestionOption::create([
                'question_id' => $q1->id,
                'option_text' => 'AM / AN = AB / AC = BC / MN',
                'position' => 2,
                'is_correct' => false,
                'explanation' => 'Attention, on ne mélange pas les côtés d’un même triangle de cette façon.',
            ]);

            QuestionOption::create([
                'question_id' => $q1->id,
                'option_text' => 'AB / AM = AN / AC = BC / MN',
                'position' => 3,
                'is_correct' => false,
                'explanation' => 'Les quotients doivent avoir le même sens (petit côté sur grand côté ou inversement).',
            ]);

            // Question 2 : Choix multiple (2 pts)
            $q2 = AssessmentQuestion::create([
                'assessment_id' => $assessment1->id,
                'question_type' => 'multiple_choice',
                'prompt' => 'Quelles conditions sont nécessaires pour appliquer la réciproque du théorème de Thalès et conclure au parallélisme de (MN) et (BC) ? (Sélectionnez les affirmations vraies)',
                'explanation' => 'Il faut impérativement que les points soient alignés dans le même ordre sur les deux sécantes et que les rapports de distances soient égaux.',
                'points' => 2.00,
                'position' => 2,
                'metadata' => ['competency_code' => 'OBJ-THALES-2'],
            ]);

            $opt2_1 = QuestionOption::create([
                'question_id' => $q2->id,
                'option_text' => 'Les points A, M, B d’une part et A, N, C d’autre part sont alignés dans le même ordre.',
                'position' => 1,
                'is_correct' => true,
            ]);

            $opt2_2 = QuestionOption::create([
                'question_id' => $q2->id,
                'option_text' => 'Le rapport AM / AB est égal au rapport AN / AC.',
                'position' => 2,
                'is_correct' => true,
            ]);

            $opt2_3 = QuestionOption::create([
                'question_id' => $q2->id,
                'option_text' => 'Le triangle ABC doit obligatoirement avoir un angle droit en A.',
                'position' => 3,
                'is_correct' => false,
            ]);

            // Question 3 : Calcul numérique (3 pts)
            $q3 = AssessmentQuestion::create([
                'assessment_id' => $assessment1->id,
                'question_type' => 'numerical',
                'prompt' => 'Soit un triangle ABC tel que AB = 10 cm et AC = 15 cm. Soit un point M sur [AB] tel que AM = 4 cm. La droite parallèle à (BC) passant par M coupe [AC] en N. Calculez la longueur AN en cm. (Entrez uniquement le nombre).',
                'explanation' => 'Par Thalès : AN = (AM × AC) / AB = (4 × 15) / 10 = 60 / 10 = 6 cm.',
                'points' => 3.00,
                'position' => 3,
                'metadata' => ['competency_code' => 'OBJ-THALES-1'],
            ]);

            QuestionAnswer::create([
                'question_id' => $q3->id,
                'answer_key' => [
                    'accepted_values' => ['6', '6.0', '6,0', '6 cm'],
                ],
                'grading_rule' => [
                    'tolerance' => 0.01,
                    'case_sensitive' => false,
                ],
            ]);

            // Question 4 : Vrai ou Faux (1 pt)
            $q4 = AssessmentQuestion::create([
                'assessment_id' => $assessment1->id,
                'question_type' => 'true_false',
                'prompt' => 'La configuration dite « en papillon » (où le sommet commun A est situé entre les points des deux droites parallèles) empêche l’application du théorème de Thalès.',
                'explanation' => 'Faux. Le théorème de Thalès s’applique aussi bien dans la configuration triangle classique que dans la configuration papillon (ou sablier).',
                'points' => 1.00,
                'position' => 4,
                'metadata' => ['competency_code' => 'OBJ-THALES-1'],
            ]);

            QuestionOption::create([
                'question_id' => $q4->id,
                'option_text' => 'Vrai',
                'position' => 1,
                'is_correct' => false,
            ]);

            QuestionOption::create([
                'question_id' => $q4->id,
                'option_text' => 'Faux',
                'position' => 2,
                'is_correct' => true,
                'explanation' => 'Exact ! La formule des rapports reste pleinement valide dans la configuration papillon.',
            ]);

            // Question 5 : Réponse courte textuelle (2 pts)
            $q5 = AssessmentQuestion::create([
                'assessment_id' => $assessment1->id,
                'question_type' => 'short_answer',
                'prompt' => 'Quel grand savant grec de l’Antiquité (originaire de Milet) a donné son nom à ce théorème ?',
                'explanation' => 'Il s’agit de Thalès de Milet (vers 625 - 547 av. J.-C.).',
                'points' => 2.00,
                'position' => 5,
                'metadata' => ['competency_code' => 'OBJ-THALES-1'],
            ]);

            QuestionAnswer::create([
                'question_id' => $q5->id,
                'answer_key' => [
                    'accepted_values' => ['Thalès', 'Thales', 'Thalès de Milet', 'Thales de Milet'],
                ],
                'grading_rule' => [
                    'case_sensitive' => false,
                    'trim' => true,
                ],
            ]);
        }

        // 2. Évaluation 2 : Brouillon non publié (pour test de sécurité d'isolation)
        Assessment::firstOrCreate(
            ['title' => 'Devoir Sommatif Brouillon non certifié (Arithmétique & PGCD)'],
            [
                'id' => (string) Str::uuid(),
                'resource_id' => null,
                'curriculum_id' => $curriculum?->id,
                'subject_id' => $maths->id,
                'assessment_type' => 'mock_exam',
                'duration_seconds' => 3600,
                'status' => 'draft',
                'created_by' => $admin->id,
            ]
        );
    }
}
