<?php

namespace App\Services;

use Carbon\Carbon;

class FinancialYearService
{
    /**
     * Get the current financial year.
     * Assumes FY starts on July 1st.
     * If today is Jan-Jun, FY is (CurrentYear - 1).
     * If today is Jul-Dec, FY is CurrentYear.
     * Example: Feb 2026 -> FY 2025 (July 2025 - June 2026)
     *          Aug 2026 -> FY 2026 (July 2026 - June 2027)
     * 
     * @return int The start year of the financial year
     */
    public function getCurrentFinancialYear()
    {
        $now = Carbon::now();
        if ($now->month < 7) {
            return $now->year - 1;
        }
        return $now->year;
    }

    /**
     * Get the start date of a financial year.
     * 
     * @param int|null $year
     * @return Carbon
     */
    public function getFinancialYearStartDate($year = null)
    {
        $year = $year ?? $this->getCurrentFinancialYear();
        return Carbon::createFromDate($year, 7, 1)->startOfDay();
    }

    /**
     * Get the end date of a financial year.
     * 
     * @param int|null $year
     * @return Carbon
     */
    public function getFinancialYearEndDate($year = null)
    {
        $year = $year ?? $this->getCurrentFinancialYear();
        return Carbon::createFromDate($year + 1, 6, 30)->endOfDay();
    }
}
