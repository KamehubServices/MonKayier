<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentStudentLink extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'parent_student_links';

    protected $fillable = [
        'parent_user_id',
        'student_user_id',
        'status',
        'relationship_type',
        'verified_at',
        'revoked_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified' && $this->revoked_at === null;
    }
}
