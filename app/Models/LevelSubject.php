<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelSubject extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'level_subjects';

    protected $fillable = [
        'education_level_id',
        'series_id',
        'subject_id',
        'is_mandatory',
        'coefficient',
        'weekly_hours',
        'is_active',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'coefficient' => 'integer',
        'weekly_hours' => 'float',
        'is_active' => 'boolean',
    ];

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(SchoolSeries::class, 'series_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
