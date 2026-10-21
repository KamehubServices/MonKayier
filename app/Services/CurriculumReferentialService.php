<?php

namespace App\Services;

use App\Models\Curriculum;
use App\Models\CurriculumTopic;
use App\Models\EducationLevel;
use App\Models\LearningResource;
use App\Models\LevelSubject;
use App\Models\Subject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CurriculumReferentialService
{
    /**
     * Get the hierarchical tree of a curriculum (Domains -> Competencies -> Chapters -> Lessons -> Objectives).
     */
    public function getCurriculumTree(string $curriculumId): array
    {
        $curriculum = Curriculum::with([
            'country',
            'academicYear',
            'educationLevel',
            'series',
            'rootTopics.children.children.objectives',
            'rootTopics.children.objectives',
            'rootTopics.objectives',
            'rootTopics.subject',
        ])->findOrFail($curriculumId);

        return [
            'id' => $curriculum->id,
            'title' => $curriculum->title,
            'version' => $curriculum->version,
            'status' => $curriculum->status,
            'academic_year' => $curriculum->academicYear?->label,
            'level' => [
                'id' => $curriculum->educationLevel?->id,
                'code' => $curriculum->educationLevel?->code,
                'name' => $curriculum->educationLevel?->name,
            ],
            'series' => $curriculum->series ? [
                'id' => $curriculum->series->id,
                'code' => $curriculum->series->code,
                'name' => $curriculum->series->name,
            ] : null,
            'source' => [
                'title' => $curriculum->source_title,
                'url' => $curriculum->source_url,
                'verified_at' => $curriculum->verified_at?->toIso8601String(),
            ],
            'tree' => $this->formatTopicsTree($curriculum->rootTopics),
        ];
    }

    /**
     * Format topic collection recursively with objectives.
     */
    private function formatTopicsTree($topics): array
    {
        $formatted = [];
        foreach ($topics as $topic) {
            $formatted[] = [
                'id' => $topic->id,
                'code' => $topic->code,
                'title' => $topic->title,
                'description' => $topic->description,
                'sort_order' => $topic->sort_order,
                'subject' => $topic->subject ? [
                    'id' => $topic->subject->id,
                    'code' => $topic->subject->code,
                    'name' => $topic->subject->name,
                ] : null,
                'objectives' => $topic->objectives->map(function ($obj) {
                    return [
                        'id' => $obj->id,
                        'code' => $obj->code,
                        'description' => $obj->description,
                        'sort_order' => $obj->sort_order,
                    ];
                })->toArray(),
                'children' => $topic->children ? $this->formatTopicsTree($topic->children) : [],
            ];
        }
        return $formatted;
    }

    /**
     * Get subjects applicable to a specific education level and optional series.
     */
    public function getSubjectsForLevel(string $levelId, ?string $seriesId = null): Collection
    {
        $query = LevelSubject::with('subject')
            ->where('education_level_id', $levelId)
            ->where('is_active', true);

        if ($seriesId) {
            $query->where(function ($q) use ($seriesId) {
                $q->where('series_id', $seriesId)
                  ->orWhereNull('series_id');
            });
        } else {
            $query->whereNull('series_id');
        }

        return $query->get();
    }

    /**
     * Get published resources for students and public users.
     * Enforces STRICT isolation: draft, review, or rejected resources are NEVER returned.
     */
    public function getPublicResources(array $filters = []): LengthAwarePaginator
    {
        $query = LearningResource::with([
            'subject',
            'educationLevel',
            'sources',
            'topics',
            'latestVersion.files',
        ])->where('status', 'published');

        if (!empty($filters['level_id'])) {
            $query->where('education_level_id', $filters['level_id']);
        }

        if (!empty($filters['subject_id'])) {
            $query->where('subject_id', $filters['subject_id']);
        }

        if (!empty($filters['chapter_id'])) {
            $query->whereHas('topics', function ($q) use ($filters) {
                $q->where('curriculum_topics.id', $filters['chapter_id']);
            });
        }

        if (!empty($filters['resource_type'])) {
            $query->where('resource_type', $filters['resource_type']);
        }

        if (!empty($filters['origin'])) {
            $query->where('origin', $filters['origin']);
        }

        $perPage = isset($filters['per_page']) ? min((int) $filters['per_page'], 50) : 15;

        return $query->orderBy('published_at', 'desc')->paginate($perPage);
    }
}
