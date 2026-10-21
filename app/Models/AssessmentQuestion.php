<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssessmentQuestion extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assessment_questions';

    public $timestamps = false; // migration has created_at only (timestamp('created_at')->useCurrent())

    protected $fillable = [
        'assessment_id',
        'question_type',
        'prompt',
        'explanation',
        'points',
        'position',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'points' => 'float',
        'position' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class, 'question_id')->orderBy('position');
    }

    public function answerKey(): HasOne
    {
        return $this->hasOne(QuestionAnswer::class, 'question_id');
    }

    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class, 'question_id');
    }
}
