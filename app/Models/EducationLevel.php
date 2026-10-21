<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationLevel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'education_levels';

    protected $fillable = [
        'country_id',
        'code',
        'name',
        'cycle',
        'school_cycle_id',
        'sort_order',
        'is_exam_level',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_exam_level' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function schoolCycle(): BelongsTo
    {
        return $this->belongsTo(SchoolCycle::class, 'school_cycle_id');
    }

    public function series(): HasMany
    {
        return $this->hasMany(SchoolSeries::class, 'education_level_id');
    }

    public function levelSubjects(): HasMany
    {
        return $this->hasMany(LevelSubject::class, 'education_level_id');
    }

    public function curricula(): HasMany
    {
        return $this->hasMany(Curriculum::class, 'education_level_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(LearningResource::class, 'education_level_id');
    }
}
