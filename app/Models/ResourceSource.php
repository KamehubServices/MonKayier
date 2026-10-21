<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceSource extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'resource_sources';

    public $timestamps = false; // migration has created_at only

    protected $fillable = [
        'resource_id',
        'source_title',
        'publisher',
        'source_url',
        'accessed_at',
        'verified_at',
        'verification_status',
        'notes',
        'created_at',
    ];

    protected $casts = [
        'accessed_at' => 'date',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(LearningResource::class, 'resource_id');
    }
}
