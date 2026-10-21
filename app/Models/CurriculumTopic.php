<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurriculumTopic extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'curriculum_topics';

    protected $fillable = [
        'curriculum_id',
        'subject_id',
        'parent_topic_id',
        'code',
        'title',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CurriculumTopic::class, 'parent_topic_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CurriculumTopic::class, 'parent_topic_id')->orderBy('sort_order');
    }

    public function objectives(): HasMany
    {
        return $this->hasMany(CurriculumObjective::class, 'topic_id')->orderBy('sort_order');
    }

    public function learningResources(): BelongsToMany
    {
        return $this->belongsToMany(LearningResource::class, 'resource_topics', 'topic_id', 'resource_id')
            ->withPivot('relevance_score');
    }
}
