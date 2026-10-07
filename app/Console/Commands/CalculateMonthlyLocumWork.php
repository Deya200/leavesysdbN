<?php

namespace App\Console\Commands;

use App\Models\LocumSession;
use App\Models\Employee;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CalculateMonthlyLocumWork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locum:calculate-monthly {--month= : Month in Y-m format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate monthly locum work hours for all locum employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->option('month') ?: now()->subMonth()->format('Y-m');

        $this->info("Calculating locum work for month: {$month}");

        // Get all employees who have locum sessions in the specified month
        $employeesWithSessions = LocumSession::whereYear('session_date', Carbon::parse($month)->year)
            ->whereMonth('session_date', Carbon::parse($month)->month)
            ->with('employee')
            ->get()
            ->pluck('employee')
            ->unique('EmployeeNumber');

        if ($employeesWithSessions->isEmpty()) {
            $this->warn('No employees with locum sessions found for the specified month.');
            return;
        }

        $this->info("Found {$employeesWithSessions->count()} employees with locum sessions");

        $report = [];

        foreach ($employeesWithSessions as $employee) {
            $sessions = LocumSession::where('EmployeeNumber', $employee->EmployeeNumber)
                ->whereYear('session_date', Carbon::parse($month)->year)
                ->whereMonth('session_date', Carbon::parse($month)->month)
                ->get();

            $totalHours = $sessions->sum('hours_worked');
            $totalSessions = $sessions->count();
            $totalEarnings = $sessions->sum(function ($session) {
                return $session->calculateEarnings();
            });

            if ($totalSessions > 0) {
                $report[] = [
                    'employee' => $employee->FullName,
                    'employee_number' => $employee->EmployeeNumber,
                    'employment_type' => $employee->employment_type ?? 'Unknown',
                    'is_locum' => $employee->is_locum ? 'Yes' : 'No',
                    'sessions' => $totalSessions,
                    'hours' => $totalHours,
                    'earnings' => $totalEarnings > 0 ? number_format($totalEarnings, 2) : '0.00',
                ];
            }
        }

        // Display results
        if (!empty($report)) {
            $this->table(
                ['Employee', 'Employee Number', 'Employment Type', 'Is Locum', 'Sessions', 'Total Hours', 'Total Earnings'],
                $report
            );

            // Here you could send emails, generate reports, etc.
            $this->info('Monthly locum calculation completed. Consider implementing email notifications or report generation.');
        } else {
            $this->info('No locum work found for the specified month.');
        }
    }
}
