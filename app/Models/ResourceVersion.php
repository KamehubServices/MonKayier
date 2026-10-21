<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceVersion extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'resource_versions';

    public $timestamps = false; // migration has created_at only (timestamp('created_at')->useCurrent())

    protected $fillable = [
        'resource_id',
        'version_number',
        'content_text',
        'checksum',
        'change_note',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'version_number' => 'integer',
        'created_at' => 'datetime',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(LearningResource::class, 'resource_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ResourceFile::class, 'resource_version_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(ResourcePage::class, 'resource_version_id')->orderBy('page_number');
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(ResourceChunk::class, 'resource_version_id')->orderBy('chunk_index');
    }
}
