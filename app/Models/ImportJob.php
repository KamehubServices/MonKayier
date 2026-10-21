<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportJob extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'import_jobs';

    protected $fillable = [
        'file_name',
        'file_size_bytes',
        'status',
        'total_rows',
        'processed_rows',
        'error_count',
        'errors_log',
        'created_by',
    ];

    protected $casts = [
        'file_size_bytes' => 'integer',
        'total_rows' => 'integer',
        'processed_rows' => 'integer',
        'error_count' => 'integer',
        'errors_log' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
