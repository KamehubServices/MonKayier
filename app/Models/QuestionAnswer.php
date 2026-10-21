<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionAnswer extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'question_answers';

    public $timestamps = false; // migration has created_at only

    protected $fillable = [
        'question_id',
        'answer_key',
        'grading_rule',
        'created_at',
    ];

    protected $casts = [
        'answer_key' => 'array',
        'grading_rule' => 'array',
        'created_at' => 'datetime',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(AssessmentQuestion::class, 'question_id');
    }
}
