<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocumRate extends Model
{
    protected $fillable = [
        'DepartmentID',
        'position_type',
        'shift',
        'daily_rate',
        'hourly_rate',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'DepartmentID', 'DepartmentID');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
