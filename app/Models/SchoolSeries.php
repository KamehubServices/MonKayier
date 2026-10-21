<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolSeries extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'school_series';

    protected $fillable = [
        'country_id',
        'education_level_id',
        'code',
        'name',
        'description',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function levelSubjects(): HasMany
    {
        return $this->hasMany(LevelSubject::class, 'series_id');
    }

    public function curricula(): HasMany
    {
        return $this->hasMany(Curriculum::class, 'series_id');
    }
}
