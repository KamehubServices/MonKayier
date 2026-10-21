<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curriculum extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'curricula';

    protected $fillable = [
        'country_id',
        'academic_year_id',
        'education_level_id',
        'series_id',
        'title',
        'source_url',
        'source_title',
        'verified_at',
        'status',
        'version',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'version' => 'integer',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(SchoolSeries::class, 'series_id');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(CurriculumTopic::class)->orderBy('sort_order');
    }

    public function rootTopics(): HasMany
    {
        return $this->hasMany(CurriculumTopic::class)
            ->whereNull('parent_topic_id')
            ->orderBy('sort_order');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
