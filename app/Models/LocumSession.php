<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocumSession extends Model
{
    protected $fillable = [
        'EmployeeNumber',
        'DepartmentID',
        'sign_in_time',
        'sign_out_time',
        'hours_worked',
        'session_date',
        'notes',
        'hourly_rate',
        'total_earnings',
    ];

    protected $casts = [
        'sign_in_time' => 'datetime',
        'sign_out_time' => 'datetime',
        'session_date' => 'date',
        'hours_worked' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'DepartmentID', 'DepartmentID');
    }

    // Calculate hours worked if not set
    public function calculateHoursWorked()
    {
        if ($this->sign_out_time && $this->sign_in_time) {
            // Check if this is a permanent employee (not locum)
            $isPermanent = $this->employee && !$this->employee->is_locum && $this->employee->employment_type !== 'Locum';

            if ($isPermanent) {
                // Permanent employee: calculate hours within the detected shift
                $shift = $this->determineShift();
                $shiftStart = $shift['start'];
                $shiftEnd = $shift['end'];

                $effectiveStart = $this->sign_in_time->copy();
                $effectiveEnd = $this->sign_out_time->copy();

                // Adjust if times fall outside shift boundaries
                if ($effectiveStart->lessThan($shiftStart)) {
                    $effectiveStart = $shiftStart;
                }
                if ($effectiveEnd->greaterThan($shiftEnd)) {
                    $effectiveEnd = $shiftEnd;
                }

                if ($effectiveEnd->greaterThan($effectiveStart)) {
                    $minutes = $effectiveStart->diffInMinutes($effectiveEnd);
                    $hours = $minutes / 60;
                    $this->hours_worked = round($hours, 4); // Keep 4 decimals during calculation
                    // Round to 2 for storage as per decimal:2 cast
                    $this->hours_worked = round($this->hours_worked, 2);
                } else {
                    $this->hours_worked = 0;
                }
            } else {
                // Locum employee: actual hours worked (not restricted by shift)
                $minutes = $this->sign_in_time->diffInMinutes($this->sign_out_time);
                $hours = $minutes / 60;
                $this->hours_worked = round($hours, 2);
            }

            // Set default hourly rate if not already set
            if (!$this->hourly_rate) {
                $this->hourly_rate = 2000; // Default rate
            }

            // Calculate and store total earnings
            $this->total_earnings = round($this->hours_worked * $this->hourly_rate, 2);

            $this->save();
        }
    }

    // Determine shift based on sign-in time
    private function determineShift()
    {
        $signInHour = $this->sign_in_time->hour;
        $signInMinute = $this->sign_in_time->minute;

        // Night shift: 16:30 to 23:59, and 00:00 to 08:30
        if ($signInHour >= 16 || ($signInHour < 8) || ($signInHour == 8 && $signInMinute <= 30)) {
            // Night shift: 16:30 to 8:30 next day
            if ($signInHour < 8 || ($signInHour == 8 && $signInMinute <= 30)) {
                // Early morning: night shift started yesterday
                $nightStart = $this->sign_in_time->copy()->subDay()->setTime(16, 30);
                $nightEnd = $this->sign_in_time->copy()->setTime(8, 30);
            } else {
                // Evening/night: night shift starts today, ends tomorrow morning
                $nightStart = $this->sign_in_time->copy()->setTime(16, 30);
                $nightEnd = $this->sign_in_time->copy()->addDay()->setTime(8, 30);
            }
            
            return ['start' => $nightStart, 'end' => $nightEnd, 'type' => 'night'];
        }
        
        // Day shift: 07:30 to 16:30
        $dayStart = $this->sign_in_time->copy()->setTime(7, 30);
        $dayEnd = $this->sign_in_time->copy()->setTime(16, 30);
        
        return ['start' => $dayStart, 'end' => $dayEnd, 'type' => 'day'];
    }

    // Get the shift type for this session
    public function getShiftType()
    {
        if (!$this->employee || $this->employee->is_locum || $this->employee->employment_type === 'Locum') {
            return 'N/A';
        }

        $shift = $this->determineShift();
        return ucfirst($shift['type']) . ' Shift';
    }

    public function getShiftTypeValue()
    {
        if (!$this->employee || $this->employee->is_locum || $this->employee->employment_type === 'Locum') {
            return 'N/A';
        }

        return $this->determineShift()['type'];
    }

    // Get the applicable locum rate for this session
    public function getLocumRate()
    {
        if (!$this->employee || !$this->department) {
            return null;
        }

        // Determine which shift this session is for
        $shift = $this->determineShift();
        $shiftType = $shift['type']; // 'day' or 'night'

        // Get position type from employee's position
        $positionType = $this->employee->position->PositionName ?? 'General';

        // Try to find rate by position type and shift
        $rate = LocumRate::where('DepartmentID', $this->DepartmentID)
            ->where('position_type', $positionType)
            ->where('shift', $shiftType)
            ->active()
            ->first();

        // If no specific rate found, try general rate for the department and shift
        if (!$rate) {
            $rate = LocumRate::where('DepartmentID', $this->DepartmentID)
                ->where('position_type', 'General')
                ->where('shift', $shiftType)
                ->active()
                ->first();
        }

        // If still no shift-specific rate exists, fall back to any active rate for the same position
        if (!$rate) {
            $rate = LocumRate::where('DepartmentID', $this->DepartmentID)
                ->where('position_type', $positionType)
                ->active()
                ->first();
        }

        // If still none, fall back to any general active rate for the department
        if (!$rate) {
            $rate = LocumRate::where('DepartmentID', $this->DepartmentID)
                ->where('position_type', 'General')
                ->active()
                ->first();
        }

        return $rate;
    }

    // Calculate the total earnings for this session
    public function calculateEarnings()
    {
        if ($this->total_earnings !== null) {
            return round($this->total_earnings, 2);
        }

        if (!$this->hours_worked || $this->hours_worked <= 0) {
            return 0;
        }

        $rate = $this->getLocumRate();

        if ($rate && $rate->hourly_rate) {
            return round($this->hours_worked * $rate->hourly_rate, 2);
        }

        if ($rate && $rate->daily_rate) {
            $hoursPerDay = 8; // Standard working day
            $hourlyFromDaily = $rate->daily_rate / $hoursPerDay;
            return round($this->hours_worked * $hourlyFromDaily, 2);
        }

        $hourlyRate = $this->hourly_rate ?? 2000;
        return round($this->hours_worked * $hourlyRate, 2);
    }

    // Get formatted earnings with currency
    public function getFormattedEarnings()
    {
        $totalEarnings = $this->total_earnings ?? ($this->hours_worked * ($this->hourly_rate ?? 2000));
        
        if ($totalEarnings > 0) {
            return 'MWK ' . number_format($totalEarnings, 2);
        }

        return 'N/A';
    }

    // Get earnings per minute
    public function getEarningsPerMinute()
    {
        if (!$this->hours_worked || $this->hours_worked <= 0) {
            return 0;
        }
        $totalMinutes = $this->hours_worked * 60;
        $earnings = $this->calculateEarnings();
        return round($earnings / $totalMinutes, 4);
    }

    // Get earnings per hour
    public function getEarningsPerHour()
    {
        $rate = $this->getLocumRate();
        if (!$rate) {
            return 0;
        }
        if ($rate->hourly_rate) {
            return round($rate->hourly_rate, 2);
        } elseif ($rate->daily_rate) {
            return round($rate->daily_rate / 8, 2);
        }
        return 0;
    }

    // Get detailed earnings breakdown
    public function getEarningsBreakdown()
    {
        // Use the hourly_rate and total_earnings stored in the session
        $hourlyRate = $this->hourly_rate ?? 2000; // Default 2000 if not set
        $totalEarnings = $this->total_earnings ?? ($this->hours_worked * $hourlyRate);
        $currency = 'MWK'; // Always MWK

        return [
            'shift' => $this->getShiftType(),
            'hours_worked' => $this->hours_worked,
            'rate_per_hour' => $hourlyRate,
            'total_earnings' => $totalEarnings,
            'currency' => $currency,
            'formatted_total' => 'MWK ' . number_format($totalEarnings, 2),
            'formatted_per_hour' => 'MWK ' . number_format($hourlyRate, 2),
        ];
    }
}
