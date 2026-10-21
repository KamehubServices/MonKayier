<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCompetency extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'student_competencies';

    public $timestamps = false; // migration has updated_at only

    protected $fillable = [
        'student_user_id',
        'objective_id',
        'mastery_score',
        'evidence_count',
        'updated_at',
    ];

    protected $casts = [
        'mastery_score' => 'float',
        'evidence_count' => 'integer',
        'updated_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function objective(): BelongsTo
    {
        return $this->belongsTo(CurriculumObjective::class, 'objective_id');
    }
}
