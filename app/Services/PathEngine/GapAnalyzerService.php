<?php

namespace App\Services\PathEngine;

use Illuminate\Support\Facades\DB;

class GapAnalyzerService
{
    /**
     * Coefficients officiels par série (Exemple Terminale D - Togo / UEMOA)
     */
    protected array $officialCoefficients = [
        'MATHS' => 4,
        'PCT' => 4,
        'SVT' => 4,
        'PHILO' => 2,
        'FRANCAIS' => 2,
        'ANGLAIS' => 2,
        'HIST_GEO' => 2,
        'ECM' => 1,
    ];

    /**
     * Analyse l'écart entre le niveau actuel de l'élève et les exigences officielles du BAC.
     * Calcule l'indice d'urgence : (100 - score_maitrise) * coefficient.
     */
    public function analyzeGaps(string $studentUserId): array
    {
        // 1. Récupérer les compétences réelles de l'élève
        $competencies = DB::table('student_competencies')
            ->join('curriculum_objectives', 'student_competencies.objective_id', '=', 'curriculum_objectives.id')
            ->join('curriculum_topics', 'curriculum_objectives.topic_id', '=', 'curriculum_topics.id')
            ->join('subjects', 'curriculum_topics.subject_id', '=', 'subjects.id')
            ->where('student_competencies.student_user_id', $studentUserId)
            ->select(
                'student_competencies.id as competency_id',
                'student_competencies.mastery_score',
                'curriculum_objectives.id as objective_id',
                'curriculum_objectives.code as objective_code',
                'curriculum_objectives.description as objective_desc',
                'curriculum_topics.id as topic_id',
                'curriculum_topics.title as topic_title',
                'curriculum_topics.code as topic_code',
                'subjects.id as subject_id',
                'subjects.name as subject_name',
                'subjects.code as subject_code'
            )
            ->get();

        // Si l'élève n'a pas encore de compétences enregistrées, on initialise les compétences de base
        if ($competencies->isEmpty()) {
            return $this->getDefaultGaps();
        }

        $analyzedGaps = [];

        foreach ($competencies as $comp) {
            $coef = $this->officialCoefficients[$comp->subject_code] ?? 2;
            $mastery = (int) $comp->mastery_score;
            $gap = 100 - $mastery;
            $urgencyScore = $gap * $coef;

            $analyzedGaps[] = [
                'objective_id' => $comp->objective_id,
                'objective_code' => $comp->objective_code,
                'objective_desc' => $comp->objective_desc,
                'topic_id' => $comp->topic_id,
                'topic_title' => $comp->topic_title,
                'subject_id' => $comp->subject_id,
                'subject_name' => $comp->subject_name,
                'subject_code' => $comp->subject_code,
                'mastery_score' => $mastery,
                'coefficient' => $coef,
                'urgency_score' => $urgencyScore,
                'needs_remediation' => $mastery < 60,
            ];
        }

        // Tri par ordre décroissant d'urgence : la plus grosse lacune sur le plus gros coefficient d'abord
        usort($analyzedGaps, fn($a, $b) => $b['urgency_score'] <=> $a['urgency_score']);

        return $analyzedGaps;
    }

    /**
     * Fournit un état de lacunes par défaut basé sur le programme officiel Terminale D.
     */
    protected function getDefaultGaps(): array
    {
        return [
            [
                'objective_id' => null,
                'objective_code' => 'COMP-ANALYSE',
                'objective_desc' => 'Calcul de limites, formes indéterminées et continuité',
                'topic_id' => null,
                'topic_title' => 'Limites et Continuité',
                'subject_id' => null,
                'subject_name' => 'Mathématiques',
                'subject_code' => 'MATHS',
                'mastery_score' => 48,
                'coefficient' => 4,
                'urgency_score' => 208, // (100 - 48) * 4
                'needs_remediation' => true,
            ],
            [
                'objective_id' => null,
                'objective_code' => 'COMP-PROBA',
                'objective_desc' => 'Probabilités conditionnelles et loi binomiale',
                'topic_id' => null,
                'topic_title' => 'Probabilités et Dénombrement',
                'subject_id' => null,
                'subject_name' => 'Mathématiques',
                'subject_code' => 'MATHS',
                'mastery_score' => 35,
                'coefficient' => 4,
                'urgency_score' => 260, // (100 - 35) * 4
                'needs_remediation' => true,
            ],
        ];
    }
}
