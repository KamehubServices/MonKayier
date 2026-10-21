<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionGrade extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'submission_grades';

    public $timestamps = false;

    protected $fillable = [
        'submission_id',
        'graded_by',
        'final_score',
        'is_official',
        'graded_at',
    ];

    protected $casts = [
        'final_score' => 'decimal:2',
        'is_official' => 'boolean',
        'graded_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
