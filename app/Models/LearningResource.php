<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LearningResource extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'learning_resources';

    protected $fillable = [
        'country_id',
        'curriculum_id',
        'subject_id',
        'education_level_id',
        'title',
        'summary',
        'resource_type',
        'origin',
        'difficulty_level',
        'is_solution_restricted',
        'status',
        'created_by',
        'reviewed_by',
        'published_at',
    ];

    protected $casts = [
        'is_solution_restricted' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ResourceVersion::class, 'resource_id')->orderBy('version_number', 'desc');
    }

    public function latestVersion(): HasOne
    {
        return $this->hasOne(ResourceVersion::class, 'resource_id')->latestOfMany('version_number');
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(CurriculumTopic::class, 'resource_topics', 'resource_id', 'topic_id')
            ->withPivot('relevance_score');
    }

    public function sources(): HasMany
    {
        return $this->hasMany(ResourceSource::class, 'resource_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ResourceReview::class, 'resource_id')->orderBy('reviewed_at', 'desc');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ContentReport::class, 'resource_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeForLevel(Builder $query, string $levelId): Builder
    {
        return $query->where('education_level_id', $levelId);
    }

    public function scopeForSubject(Builder $query, string $subjectId): Builder
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeForChapter(Builder $query, string $topicId): Builder
    {
        return $query->whereHas('topics', function ($q) use ($topicId) {
            $q->where('curriculum_topics.id', $topicId);
        });
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
