<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevisionTask extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'revision_tasks';

    public $timestamps = false; // migration has created_at only (timestamp('created_at')->useCurrent())

    protected $fillable = [
        'plan_id',
        'objective_id',
        'resource_id',
        'due_at',
        'status',
        'priority',
        'completed_at',
        'created_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
        'priority' => 'integer',
        'created_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(RevisionPlan::class, 'plan_id');
    }

    public function objective(): BelongsTo
    {
        return $this->belongsTo(CurriculumObjective::class, 'objective_id');
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(LearningResource::class, 'resource_id');
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
