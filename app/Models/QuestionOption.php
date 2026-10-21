<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'question_options';

    public $timestamps = false; // migration has created_at only

    protected $fillable = [
        'question_id',
        'option_text',
        'position',
        'is_correct',
        'explanation',
        'created_at',
    ];

    protected $casts = [
        'position' => 'integer',
        'is_correct' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(AssessmentQuestion::class, 'question_id');
    }
}
