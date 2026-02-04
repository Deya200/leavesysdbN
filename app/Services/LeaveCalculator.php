<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveCalculator
{
    /**
     * Calculate deductible leave days between two dates (inclusive).
     *
     * @param  string|\DateTime  $startDate  // e.g. '2026-02-01'
     * @param  string|\DateTime  $endDate    // e.g. '2026-02-05'
     * @param  int               $leaveTypeId
     * @return int  number of days to deduct from annual leave
     */
    public function deductibleDays($startDate, $endDate, int $leaveTypeId): int
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        if ($end->lt($start)) {
            return 0;
        }

        // Check if this leave type deducts from annual leave
        $leaveType = DB::table('leave_types')
            ->where('LeaveTypeID', $leaveTypeId)
            ->select('DeductsFromAnnual')
            ->first();

        if (! $leaveType || ! (int) $leaveType->DeductsFromAnnual) {
            return 0;
        }

        // Load public holiday dates into a set for quick lookup
        $holidays = DB::table('public_holidays')
            ->select('date')
            ->pluck('date')
            ->map(function ($d) {
                return (string) $d;
            })
            ->flip(); // keys for O(1) lookup

        $daysToDeduct = 0;
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            // skip weekends (Saturday=6, Sunday=0 in Carbon when using dayOfWeek)
            $dow = $cursor->dayOfWeekIso; // 1 (Mon) .. 7 (Sun)
            $isWeekend = ($dow === 6 || $dow === 7);

            $dateString = $cursor->toDateString(); // 'YYYY-MM-DD'

            if (! $isWeekend && ! isset($holidays[$dateString])) {
                $daysToDeduct++;
            }

            $cursor->addDay();
        }

        return $daysToDeduct;
    }
}
