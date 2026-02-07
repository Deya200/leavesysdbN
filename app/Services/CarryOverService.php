<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarryOverService
{
    protected $financialYearService;

    public function __construct(FinancialYearService $financialYearService)
    {
        $this->financialYearService = $financialYearService;
    }

    /**
     * Process carry-over for rejected annual leave requests from the previous financial year.
     * This should run at the start of the new financial year (e.g., July 1st).
     */
    public function processAnnualCarryOver()
    {
        $previousFinancialYear = $this->financialYearService->getCurrentFinancialYear() - 1;
        
        Log::info("Starting annual carry-over process for FY {$previousFinancialYear}");

        // Find all rejected Annual Leave requests from the previous FY that haven't been carried over yet
        $rejectedRequests = LeaveRequest::where('RequestStatus', 'Rejected')
            ->whereHas('leaveType', function ($query) {
                $query->where('LeaveTypeName', 'Annual Leave'); // Ensure matches exact name
            })
            // ->where('financial_year', $previousFinancialYear) // Assuming column is populated
            // Since financial_year might not be populated for old records, let's filter by date
            ->whereBetween('StartDate', [
                $this->financialYearService->getFinancialYearStartDate($previousFinancialYear),
                $this->financialYearService->getFinancialYearEndDate($previousFinancialYear)
            ])
            ->where('carried_over_days', 0) // Not yet carried over
            ->get();

        foreach ($rejectedRequests as $request) {
            DB::transaction(function () use ($request) {
                $carryOverDays = $request->TotalDays;

                // Update Employee
                $employee = $request->employee;
                if ($employee) {
                    $employee->increment('carried_over_leave_days', $carryOverDays);
                    // Also update RemainingAnnualLeaveDays? 
                    // Usually carried over days are added to the available balance.
                    // The getTotalAvailableLeaveDaysAttribute handles the sum.
                    // But we might need to update RemainingAnnualLeaveDays if it's a stored counter using that logic.
                    // Let's check Employee model logic. 
                    // If we just increment `carried_over_leave_days`, we need to make sure `RemainingAnnualLeaveDays` reflects it 
                    // OR `RemainingAnnualLeaveDays` is strictly for the current year's allocation?
                    // Let's assume `RemainingAnnualLeaveDays` should include carry over.
                    
                    // However, `RemainingAnnualLeaveDays` is likely initialized at start of year.
                    // Let's increment it too to be safe/consistent with usage.
                    $employee->increment('RemainingAnnualLeaveDays', $carryOverDays);
                }

                // Update Request to mark as processed
                $request->update(['carried_over_days' => $carryOverDays]);
                
                Log::info("Carried over {$carryOverDays} days for Employee {$request->EmployeeNumber} from Request #{$request->id}");
            });
        }
        
        Log::info("Annual carry-over process completed.");
    }
}
