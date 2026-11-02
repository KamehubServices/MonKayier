<?php

namespace App\Services\PathEngine;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CurriculumCourseMatcher
{
    /**
     * Trouve le support de cours réel associé à une notion officielle ou lacune.
     * Priorité : 1. Cours élève > 2. Cours de son professeur > 3. Cours officiel national
     */
    public function matchCourseForGap(string $studentUserId, array $gap): ?object
    {
        $subjectId = $gap['subject_id'] ?? null;
        $subjectCode = $gap['subject_code'] ?? null;
        $topicTitle = $gap['topic_title'] ?? '';

        // Si subject_id n'est pas fourni, on le résout via subject_code
        if (!$subjectId && $subjectCode) {
            $subj = DB::table('subjects')->where('code', $subjectCode)->first();
            $subjectId = $subj->id ?? null;
        }

        // Mots-clés spécifiques à l'objectif pédagogique (sans bruit général)
        $searchContext = ($gap['objective_desc'] ?? '') . ' ' . str_replace('COMP-', '', ($gap['objective_code'] ?? ''));
        $keywords = $this->extractKeywords($searchContext);

        // 1. Recherche ciblée par mots-clés spécifiques sur la matière
        if (!empty($keywords)) {
            $courses = DB::table('learning_resources')
                ->leftJoin('subjects', 'learning_resources.subject_id', '=', 'subjects.id')
                ->where('learning_resources.subject_id', $subjectId)
                ->where('learning_resources.resource_type', 'course')
                ->select('learning_resources.*', 'subjects.name as subject_name', 'subjects.code as subject_code')
                ->get();

            $bestCourse = null;
            $bestScore = 0;

            foreach ($courses as $c) {
                $score = 0;
                $haystack = mb_strtolower($c->title . ' ' . ($c->summary ?? ''));
                foreach ($keywords as $kw) {
                    if (str_contains($haystack, $kw)) {
                        $score += 10;
                    }
                }
                // Bonus si cours importé par l'élève lui-même
                if ($score > 0 && $c->created_by === $studentUserId) {
                    $score += 5;
                }

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestCourse = $c;
                }
            }

            if ($bestCourse) {
                return $bestCourse;
            }
        }

        // 2. Si aucun cours spécifique n'est trouvé, prendre le cours le plus récent de l'élève dans cette matière
        $studentSubjectCourse = DB::table('learning_resources')
            ->leftJoin('subjects', 'learning_resources.subject_id', '=', 'subjects.id')
            ->where('learning_resources.subject_id', $subjectId)
            ->where('learning_resources.created_by', $studentUserId)
            ->where('learning_resources.resource_type', 'course')
            ->select('learning_resources.*', 'subjects.name as subject_name', 'subjects.code as subject_code')
            ->orderBy('learning_resources.created_at', 'desc')
            ->first();

        if ($studentSubjectCourse) {
            return $studentSubjectCourse;
        }

        // 3. Sinon cours officiel général de la matière
        if ($subjectId) {
            $officialCourse = DB::table('learning_resources')
                ->leftJoin('subjects', 'learning_resources.subject_id', '=', 'subjects.id')
                ->where('learning_resources.subject_id', $subjectId)
                ->where('learning_resources.resource_type', 'course')
                ->select('learning_resources.*', 'subjects.name as subject_name', 'subjects.code as subject_code')
                ->orderBy('learning_resources.created_at', 'desc')
                ->first();

            if ($officialCourse) {
                return $officialCourse;
            }
        }

        // 4. Fallback ultime : premier cours de la bibliothèque
        return DB::table('learning_resources')
            ->leftJoin('subjects', 'learning_resources.subject_id', '=', 'subjects.id')
            ->select('learning_resources.*', 'subjects.name as subject_name', 'subjects.code as subject_code')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Extrait les racines de mots significatives pour matcher le document.
     */
    protected function extractKeywords(string $title): array
    {
        $words = preg_split('/[\s,\.\-\'’]+/u', mb_strtolower($title));
        $stopWords = [
            'et', 'de', 'la', 'le', 'les', 'des', 'du', 'en', 'dans', 'sur', 'pour', 'avec', 'par',
            'cours', 'chapitre', 'classe', 'terminale', 'mathématiques', 'sciences', 'physique',
            'chimie', 'notion', 'programme', 'officiel', 'lycée', 'fiche'
        ];

        $cleaned = array_filter($words, function ($w) use ($stopWords) {
            return mb_strlen($w) >= 3 && !in_array($w, $stopWords);
        });

        return array_values($cleaned);
    }
}
