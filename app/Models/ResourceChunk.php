<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceChunk extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'resource_chunks';

    public $timestamps = false; // migration has created_at only

    protected $fillable = [
        'resource_version_id',
        'page_id',
        'chunk_index',
        'content_text',
        'token_estimate',
        'created_at',
    ];

    protected $casts = [
        'chunk_index' => 'integer',
        'token_estimate' => 'integer',
        'created_at' => 'datetime',
    ];

    public function resourceVersion(): BelongsTo
    {
        return $this->belongsTo(ResourceVersion::class, 'resource_version_id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(ResourcePage::class, 'page_id');
    }
}
