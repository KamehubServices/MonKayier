<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RevisionPlan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'revision_plans';

    protected $fillable = [
        'student_user_id',
        'title',
        'target_date',
        'status',
        'generated_by',
    ];

    protected $casts = [
        'target_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(RevisionTask::class, 'plan_id')->orderBy('priority');
    }

    public function pendingTasks(): HasMany
    {
        return $this->hasMany(RevisionTask::class, 'plan_id')
            ->where('status', 'pending')
            ->orderBy('priority');
    }
}
