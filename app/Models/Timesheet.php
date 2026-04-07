<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends Model
{
    use HasFactory;

    protected $primaryKey = 'TimesheetID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'EmployeeNumber',
        'WorkDate',
        'HoursWorked',
        'OvertimeHours',
        'Notes',
        'Status',
        'ApprovedBy',
        'ApprovedAt',
    ];

    protected $casts = [
        'WorkDate' => 'date',
        'HoursWorked' => 'decimal:2',
        'OvertimeHours' => 'decimal:2',
        'ApprovedAt' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'TimesheetID';
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'ApprovedBy', 'EmployeeNumber');
    }
}
