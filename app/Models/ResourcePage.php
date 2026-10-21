<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourcePage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'resource_pages';

    public $timestamps = false; // migration has created_at only

    protected $fillable = [
        'resource_version_id',
        'page_number',
        'extracted_text',
        'extraction_status',
        'created_at',
    ];

    protected $casts = [
        'page_number' => 'integer',
        'created_at' => 'datetime',
    ];

    public function resourceVersion(): BelongsTo
    {
        return $this->belongsTo(ResourceVersion::class, 'resource_version_id');
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(ResourceChunk::class, 'page_id');
    }
}
