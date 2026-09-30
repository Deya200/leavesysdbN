<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    use HasFactory;

    protected $table = 'wards';
    protected $primaryKey = 'WardID';
    public $timestamps = true;

    protected $fillable = [
        'WardName',
        'Description',
        'DepartmentID',
    ];

    /**
     * Relationship: Department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'DepartmentID', 'DepartmentID');
    }

    /**
     * Relationship: Shift Assignments
     */
    public function shiftAssignments(): HasMany
    {
        return $this->hasMany(ShiftAssignment::class, 'WardID', 'WardID');
    }
}
