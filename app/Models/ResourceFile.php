<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceFile extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'resource_files';

    public $timestamps = false; // migration has created_at useCurrent()

    protected $fillable = [
        'resource_version_id',
        'storage_key',
        'original_name',
        'mime_type',
        'size_bytes',
        'checksum',
        'access_level',
        'antivirus_scan_status',
        'is_quarantined',
        'created_at',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'is_quarantined' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function resourceVersion(): BelongsTo
    {
        return $this->belongsTo(ResourceVersion::class, 'resource_version_id');
    }
}
