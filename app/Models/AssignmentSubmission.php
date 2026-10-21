<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssignmentSubmission extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assignment_submissions';

    public $timestamps = false;

    protected $fillable = [
        'assignment_id',
        'student_user_id',
        'attempt_id',
        'submitted_at',
        'status',
        'teacher_feedback',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(AssessmentAttempt::class, 'attempt_id');
    }

    public function grade(): HasOne
    {
        return $this->hasOne(SubmissionGrade::class, 'submission_id');
    }
}
