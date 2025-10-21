<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    // Primary key configuration
    protected $primaryKey = 'LeaveTypeID';
    public $incrementing = true;
    protected $keyType = 'int';

    // Mass assignable fields
    protected $fillable = [
        'LeaveTypeName',
        'IsPaidLeave',
        'GenderApplicable',
        'MaxLeaveDays',
        'MinServiceYears',
        // Removed 'DeductsFromAnnual' to enforce logic via name
    ];

    // Type casting for boolean fields
    protected $casts = [
        'IsPaidLeave' => 'boolean',
        // Removed 'DeductsFromAnnual' cast
    ];

    // Convenience methods
    public function isAnnualLeave(): bool
    {
        return $this->LeaveTypeName === 'Annual Leave';
    }

    public function isPaternityLeave(): bool
    {
        return $this->LeaveTypeName === 'Paternity Leave';
    }

    public function isSickLeave(): bool
    {
        return $this->LeaveTypeName === 'Sick Leave';
    }

    // ✅ Centralized deduction logic
    public function deductsFromAnnual(): bool
    {
        return $this->isAnnualLeave();
    }
}
