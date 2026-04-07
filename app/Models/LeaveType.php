<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    /**
     * Statutory leave types required by Malawi Employment Act baseline.
     */
    public const STATUTORY_LEAVES = [
        [
            'LeaveTypeName' => 'Annual Leave',
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Both',
            'MaxLeaveDays' => 18,
            'MinServiceYears' => 0,
            'DeductsFromAnnual' => false,
        ],
        [
            'LeaveTypeName' => 'Sick Leave',
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Both',
            'MaxLeaveDays' => 60,
            'MinServiceYears' => 1,
            'DeductsFromAnnual' => false,
        ],
        [
            'LeaveTypeName' => 'Maternity Leave',
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Female',
            'MaxLeaveDays' => 56,
            'MinServiceYears' => 0,
            'DeductsFromAnnual' => false,
        ],
    ];

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
        'DeductsFromAnnual',
    ];

    // Type casting for boolean fields
    protected $casts = [
        'IsPaidLeave' => 'boolean',
        'DeductsFromAnnual' => 'boolean',
    ];

    public static function statutoryLeaveNames(): array
    {
        return array_map(fn($leave) => $leave['LeaveTypeName'], self::STATUTORY_LEAVES);
    }

    public function isStatutory(): bool
    {
        return self::isStatutoryName($this->LeaveTypeName);
    }

    public static function isStatutoryName(string $name): bool
    {
        return in_array(strtolower($name), array_map('strtolower', self::statutoryLeaveNames()), true);
    }

    // Convenience methods
    public function isAnnualLeave(): bool
    {
        return strcasecmp($this->LeaveTypeName, 'Annual Leave') === 0;
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
