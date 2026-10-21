<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswer extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'attempt_answers';

    public $timestamps = false; // migration has answered_at only (timestamp('answered_at')->useCurrent())

    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer_payload',
        'awarded_points',
        'feedback',
        'answered_at',
    ];

    protected $casts = [
        'answer_payload' => 'array',
        'awarded_points' => 'float',
        'answered_at' => 'datetime',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(AssessmentAttempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(AssessmentQuestion::class, 'question_id');
    }
}
