<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarryOverLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaves:carry-over';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $signature_description = 'Carry forward remaining annual leave days to the next financial year for all employees.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Leave Carry Over process...');

        $employees = Employee::all();
        $bar = $this->output->createProgressBar(count($employees));

        $bar->start();

        foreach ($employees as $employee) {
            DB::transaction(function () use ($employee) {
                // 1. Calculate current remaining days
                // We use the attribute which considers grade, carryover, and active requests
                $remainingDays = $employee->leave_days_remaining;

                // 2. Set this as the NEW carried over amount
                $employee->carried_over_leave_days = $remainingDays;

                // 3. Update the persistent column if used by other parts of the system
                $gradeAllowance = optional($employee->grade)->AnnualLeaveDays ?? 0;
                $employee->RemainingAnnualLeaveDays = $gradeAllowance + $remainingDays;

                $employee->save();

                // 4. Archive all currently approved annual leave requests for this employee
                // This prevents them from being subtracted in the 'new' year calculation
                LeaveRequest::where('EmployeeNumber', $employee->EmployeeNumber)
                    ->where('RequestStatus', 'Approved')
                    ->where('is_archived', false)
                    ->whereHas('leaveType', function ($query) {
                        $query->where('LeaveTypeName', 'Annual Leave');
                    })
                    ->update([
                        'is_archived' => true,
                        'archived_at' => now(),
                    ]);
            });

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Leave Carry Over process completed successfully.');
        Log::info('Artisan Command: leaves:carry-over was executed successfully.');

        return 0;
    }
}
