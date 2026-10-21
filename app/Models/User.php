<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * Primary key configuration (UUID).
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     * Note: 'status' and roles are explicitly excluded from mass-assignment for security.
     */
    protected $fillable = [
        'email',
        'phone',
        'password_hash',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the name of the password attribute for authentication.
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Auto-generate UUIDv4 on creation.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /* -------------------------------------------------------------------------
     * Relations
     * ------------------------------------------------------------------------- */

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
            ->withPivot('assigned_by', 'created_at');
    }

    public function preferences(): HasOne
    {
        return $this->hasOne(UserPreference::class, 'user_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class, 'user_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'actor_user_id');
    }

    /* -------------------------------------------------------------------------
     * Helpers de rôles et sécurité
     * ------------------------------------------------------------------------- */

    public function hasRole(string $roleCode): bool
    {
        return $this->roles->contains('code', $roleCode);
    }

    public function assignRole(string $roleCode, ?string $assignedBy = null): void
    {
        $role = Role::where('code', $roleCode)->first();
        if ($role && !$this->roles()->where('role_id', $role->id)->exists()) {
            $this->roles()->attach($role->id, [
                'assigned_by' => $assignedBy,
                'created_at' => now(),
            ]);
            $this->unsetRelation('roles');
        }
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }
}
