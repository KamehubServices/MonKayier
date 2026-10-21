<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentAttempt extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assessment_attempts';

    protected $fillable = [
        'assessment_id',
        'student_user_id',
        'status',
        'started_at',
        'submitted_at',
        'score',
        'max_score',
        'idempotency_key',
        'assessment_version',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score' => 'float',
        'max_score' => 'float',
        'assessment_version' => 'integer',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class, 'attempt_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(AttemptEvent::class, 'attempt_id');
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function scorePercentage(): float
    {
        if ($this->max_score <= 0) return 0.0;
        return round(($this->score / $this->max_score) * 100, 1);
    }
}
