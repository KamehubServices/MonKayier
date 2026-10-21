<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    public $timestamps = false;
    protected $table = 'audit_logs';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'actor_user_id',
        'action',
        'target_type',
        'target_id',
        'metadata',
        'ip_hash',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->created_at)) {
                $model->created_at = now();
            }
        });
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    /**
     * Enregistrer une action sensible de manière normalisée et anonymisée.
     */
    public static function record(?string $actorUserId, string $action, string $targetType, ?string $targetId = null, array $metadata = []): self
    {
        $ip = request()->ip();
        $ipHash = $ip ? hash('sha256', $ip) : null;

        return self::create([
            'actor_user_id' => $actorUserId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'metadata' => $metadata,
            'ip_hash' => $ipHash,
            'created_at' => now(),
        ]);
    }
}
