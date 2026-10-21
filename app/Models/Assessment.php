<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assessments';

    protected $fillable = [
        'resource_id',
        'curriculum_id',
        'subject_id',
        'title',
        'assessment_type',
        'duration_seconds',
        'status',
        'created_by',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(LearningResource::class, 'resource_id');
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(AssessmentQuestion::class)->orderBy('position');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(AssessmentAttempt::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function totalPoints(): float
    {
        return (float) $this->questions()->sum('points');
    }
}
