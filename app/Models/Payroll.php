<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $primaryKey = 'PayrollID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'EmployeeNumber',
        'PeriodStart',
        'PeriodEnd',
        'BasicPay',
        'OvertimePay',
        'HousingAllowance',
        'TransportAllowance',
        'MedicalAllowance',
        'OtherAllowance',
        'OvertimeHoursWeekdays',
        'OvertimeHoursWeekend',
        'Bonus',
        'GrossPay',
        'PAYE',
        'EmployeePension',
        'EmployerPension',
        'TevetLevy',
        'LoanAdvanceDeduction',
        'AdminFees',
        'TotalContribution',
        'OtherDeductions',
        'Deductions',
        'NetPay',
        'Status',
        'ProcessedBy',
        'ProcessedAt',
    ];

    protected $casts = [
        'PeriodStart' => 'date',
        'PeriodEnd' => 'date',
        'BasicPay' => 'decimal:2',
        'OvertimePay' => 'decimal:2',
        'HousingAllowance' => 'decimal:2',
        'TransportAllowance' => 'decimal:2',
        'MedicalAllowance' => 'decimal:2',
        'OtherAllowance' => 'decimal:2',
        'OvertimeHoursWeekdays' => 'decimal:2',
        'OvertimeHoursWeekend' => 'decimal:2',
        'Bonus' => 'decimal:2',
        'GrossPay' => 'decimal:2',
        'PAYE' => 'decimal:2',
        'EmployeePension' => 'decimal:2',
        'EmployerPension' => 'decimal:2',
        'TevetLevy' => 'decimal:2',
        'LoanAdvanceDeduction' => 'decimal:2',
        'AdminFees' => 'decimal:2',
        'TotalContribution' => 'decimal:2',
        'OtherDeductions' => 'decimal:2',
        'Deductions' => 'decimal:2',
        'NetPay' => 'decimal:2',
        'ProcessedAt' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'ProcessedBy', 'EmployeeNumber');
    }
}
