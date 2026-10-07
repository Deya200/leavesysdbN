<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftAssignment extends Model
{
    use HasFactory;

    protected $table = 'shift_assignments';
    protected $primaryKey = 'ShiftAssignmentID';
    public $timestamps = true;

    protected $fillable = [
        'EmployeeNumber',
        'Shift',
        'AssignmentDate',
        'WardID',
        'Notes',
    ];

    protected $casts = [
        'AssignmentDate' => 'date',
    ];

    /**
     * Relationship: Employee
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    /**
     * Relationship: Ward
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'WardID', 'WardID');
    }
}
