<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDeadline extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'student_deadlines';

    public $timestamps = false;

    protected $fillable = [
        'student_user_id',
        'title',
        'due_at',
        'source_type',
        'source_id',
        'is_completed',
        'created_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'is_completed' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }
}
