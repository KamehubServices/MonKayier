<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolCycle extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'school_cycles';

    protected $fillable = [
        'code',
        'name',
        'description',
        'pedagogical_order',
        'is_active',
    ];

    protected $casts = [
        'pedagogical_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function levels(): HasMany
    {
        return $this->hasMany(EducationLevel::class, 'school_cycle_id')->orderBy('sort_order');
    }
}
