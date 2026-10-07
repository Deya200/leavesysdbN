<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RosterAttendance extends Model
{
    use HasFactory;

    protected $table = 'roster_attendance';
    protected $primaryKey = 'AttendanceID';
    public $timestamps = true;

    protected $fillable = [
        'EmployeeNumber',
        'AttendanceDate',
        'Shift',
        'Status',
        'MorningDevotionAttended',
        'Notes',
    ];

    protected $casts = [
        'AttendanceDate' => 'date',
        'MorningDevotionAttended' => 'boolean',
    ];

    /**
     * Relationship: Employee
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    /**
     * Get badge color for status
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->Status) {
            'present' => 'success',
            'sick' => 'danger',
            'on_holiday' => 'info',
            'absent' => 'warning',
            default => 'secondary',
        };
    }
}
