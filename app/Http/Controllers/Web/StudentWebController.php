<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use App\Models\EducationLevel;
use App\Models\LearningResource;
use App\Models\LevelSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentWebController extends Controller
{
    /**
     * Dashboard de l'élève (Classe de 3e).
     */
    public function dashboard(): View
    {
        $level3e = EducationLevel::where('code', '3E')->first();

        $featuredResource = LearningResource::where('status', 'published')
            ->whereHas('educationLevel', function ($q) {
                $q->where('code', '3E');
            })->first();

        $publishedResources = LearningResource::where('status', 'published')
            ->with(['subject', 'educationLevel'])
            ->take(5)
            ->get();

        return view('student.dashboard', [
            'featuredResource' => $featuredResource,
            'publishedResources' => $publishedResources,
            'level' => $level3e,
        ]);
    }

    /**
     * Liste des matières de la classe de 3e.
     */
    public function subjects(): View
    {
        $level3e = EducationLevel::where('code', '3E')->first();
        $levelSubjects = LevelSubject::with('subject')
            ->where('education_level_id', $level3e?->id)
            ->get();

        $subjectsData = [
            [
                'code' => 'MATHS',
                'name' => 'Mathématiques',
                'coefficient' => 4,
                'hours' => 4,
                'icon' => '📐',
                'chapters' => [
                    ['title' => 'Arithmétique & PGCD', 'desc' => 'Algorithme d’Euclide, fractions irréductibles', 'status' => 'Validé'],
                    ['title' => 'Théorème de Thalès & Réciproque', 'desc' => 'Configurations triangle et papillon, démonstrations', 'status' => 'En cours'],
                    ['title' => 'Calcul Littéral & Équations', 'desc' => 'Identités remarquables, équations-produits nuls', 'status' => 'Au programme'],
                    ['title' => 'Trigonométrie', 'desc' => 'Cosinus, Sinus et Tangente dans le triangle rectangle', 'status' => 'Au programme'],
                ],
            ],
            [
                'code' => 'PCT',
                'name' => 'Physique-Chimie et Technologie (PCT)',
                'coefficient' => 3,
                'hours' => 3,
                'icon' => '⚡',
                'chapters' => [
                    ['title' => 'Solutions Aqueuses & pH', 'desc' => 'Mesure du pH, acides, bases et neutralisation', 'status' => 'Validé'],
                    ['title' => 'Loi d’Ohm & Énergie Électrique', 'desc' => 'Relation U = R x I, calcul de l’énergie consommée', 'status' => 'En cours'],
                    ['title' => 'Lentilles Minces & Optique', 'desc' => 'Foyers, vergence et construction géométrique', 'status' => 'Au programme'],
                ],
            ],
            [
                'code' => 'SVT',
                'name' => 'Sciences de la Vie et de la Terre (SVT)',
                'coefficient' => 3,
                'hours' => 3,
                'icon' => '🌱',
                'chapters' => [
                    ['title' => 'Génétique & Hérédité', 'desc' => 'Chromosomes, ADN et transmission des caractères', 'status' => 'Validé'],
                    ['title' => 'Immunologie & Défense de l’Organisme', 'desc' => 'Leucocytes, anticorps, phagocytose et vaccins', 'status' => 'En cours'],
                ],
            ],
            [
                'code' => 'FRANCAIS',
                'name' => 'Français',
                'coefficient' => 4,
                'hours' => 5,
                'icon' => '📚',
                'chapters' => [
                    ['title' => 'Grammaire & Analyse de la Phrase', 'desc' => 'Propositions subordonnées, concordance des temps', 'status' => 'Validé'],
                    ['title' => 'Expression Écrite & Argumentation', 'desc' => 'Structure de l’essai argumentatif au BEPC', 'status' => 'En cours'],
                ],
            ],
        ];

        return view('student.subjects', [
            'subjects' => $subjectsData,
            'level' => $level3e,
        ]);
    }

    /**
     * Bibliothèque numérique officielle.
     */
    public function library(Request $request): View
    {
        $query = LearningResource::where('status', 'published')
            ->with(['subject', 'educationLevel', 'sources']);

        if ($request->filled('subject')) {
            $code = $request->query('subject');
            $query->whereHas('subject', function ($q) use ($code) {
                $q->where('code', $code);
            });
        }

        $resources = $query->get();

        return view('student.library', [
            'resources' => $resources,
        ]);
    }

    /**
     * Lecteur de cours conforme.
     */
    public function reader(string $id): View
    {
        $resource = LearningResource::with([
            'subject',
            'educationLevel',
            'sources',
            'topics.objectives',
            'latestVersion.pages',
        ])->where('status', 'published')->findOrFail($id);

        return view('student.course-reader', [
            'resource' => $resource,
        ]);
    }

    /**
     * Assistant Pédagogique IA.
     */
    public function assistant(Request $request): View
    {
        return view('student.assistant');
    }

    /**
     * Catalogue des évaluations et exercices interactifs (Phase 6).
     */
    public function exercises(Request $request): View
    {
        $query = \App\Models\Assessment::with(['subject', 'resource'])
            ->where('status', 'published');

        if ($request->filled('subject')) {
            $code = $request->query('subject');
            $query->whereHas('subject', function ($q) use ($code) {
                $q->where('code', $code);
            });
        }

        $assessments = $query->get();
        $subjects = Subject::where('is_active', true)->get();

        return view('student.exercises.index', [
            'assessments' => $assessments,
            'subjects' => $subjects,
            'selectedSubject' => $request->query('subject'),
        ]);
    }

    /**
     * Lecteur et lecteur interactif de quiz/évaluation.
     */
    public function exercisePlayer(string $id): View
    {
        $assessment = \App\Models\Assessment::with(['questions.options', 'subject', 'resource'])
            ->where('status', 'published')
            ->findOrFail($id);

        return view('student.exercises.show', [
            'assessment' => $assessment,
        ]);
    }

    /**
     * Consultation des résultats et corrigés commentés.
     */
    public function exerciseResult(string $id): View
    {
        $attempt = \App\Models\AssessmentAttempt::with([
            'assessment.subject',
            'assessment.resource',
            'answers.question.options',
            'answers.question.answerKey',
        ])->findOrFail($id);

        return view('student.exercises.result', [
            'attempt' => $attempt,
        ]);
    }

    /**
     * Plan de révision personnalisé et adaptatif.
     */
    public function revisions(Request $request): View
    {
        $plan = \App\Models\RevisionPlan::with(['tasks.resource.subject', 'tasks.objective'])
            ->where('status', 'active')
            ->first();

        return view('student.revisions', [
            'plan' => $plan,
        ]);
    }

    /**
     * Tableau de progression des compétences scolaires.
     */
    public function progress(Request $request): View
    {
        $competencies = \App\Models\StudentCompetency::with('objective.topic.curriculum')
            ->take(20)
            ->get();

        return view('student.progress', [
            'competencies' => $competencies,
        ]);
    }
}
