<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\LeaveType;

class AnalyticsDataSeeder extends Seeder
{
    /**
     * Run the database seeds. Creates 12 months of realistic leave data.
     */
    public function run(): void
    {
        $employees = Employee::with(['supervisor', 'department'])->get();
        $leaveTypes = LeaveType::all();

        if ($employees->isEmpty()) {
            $this->command->warn('No employees found. Please run EmployeeSeeder first.');
            return;
        }

        if ($leaveTypes->isEmpty()) {
            $this->command->warn('No leave types found. Please run LeaveTypesSeeder first.');
            return;
        }

        $statuses = [
            'Approved'        => 55,  // 55% approved
            'Rejected'        => 15,  // 15% rejected
            'Pending Supervisor Approval' => 20, // 20% pending
            'Pending Admin Verification'  => 10, // 10% admin pending
        ];

        $currentYear = Carbon::now()->year;
        $totalCreated = 0;

        $this->command->info("Seeding analytics data for year {$currentYear}...");
        $bar = $this->command->getOutput()->createProgressBar(12);
        $bar->start();

        for ($month = 1; $month <= 12; $month++) {
            // How many requests to create per month (varies naturally)
            $requestsThisMonth = rand(4, 10);

            for ($i = 0; $i < $requestsThisMonth; $i++) {
                $employee = $employees->random();
                $leaveType = $leaveTypes->random();

                // Date within this month
                $monthStart = Carbon::create($currentYear, $month, 1);
                $monthEnd   = $monthStart->copy()->endOfMonth();

                $startDay = rand(1, max(1, $monthEnd->day - 5));
                $startDate = Carbon::create($currentYear, $month, $startDay);
                $duration  = rand(1, 7); // 1 to 7 days
                $endDate   = $startDate->copy()->addDays($duration - 1);

                // Clamp to month end
                if ($endDate->gt($monthEnd)) {
                    $endDate = $monthEnd;
                }

                // Pick a status weighted by the distribution
                $status = $this->weightedRandom($statuses);

                // Determine supervisor & admin approvals
                $supervisorApproval = false;
                $adminApproval      = false;
                $isActive = false;
                $isArchived = false;

                if ($status === 'Approved') {
                    $supervisorApproval = true;
                    $adminApproval      = true;
                    $isActive = $startDate->lte(now()) && $endDate->gte(now());
                    $isArchived = $endDate->lt(now()->startOfMonth()); // Old months archived
                } elseif ($status === 'Rejected') {
                    $supervisorApproval = rand(0, 1) ? false : true;
                    $adminApproval = $supervisorApproval === true ? false : false;
                } elseif ($status === 'Pending Admin Verification') {
                    $supervisorApproval = true;
                }

                $supervisorID = $employee->SupervisorID ?? $employee->EmployeeNumber;

                DB::table('leave_requests')->insert([
                    'EmployeeNumber'        => $employee->EmployeeNumber,
                    'SupervisorID'          => $supervisorID,
                    'LeaveTypeID'           => $leaveType->LeaveTypeID,
                    'StartDate'             => $startDate->toDateString(),
                    'EndDate'               => $endDate->toDateString(),
                    'TotalDays'             => $duration,
                    'RequestStatus'         => $status,
                    'SupervisorApproval'    => $supervisorApproval,
                    'AdminApproval'         => $adminApproval,
                    'Reason'                => $this->getRandomReason($leaveType->LeaveTypeName ?? 'Leave'),
                    'can_be_appealed'       => $status === 'Rejected',
                    'is_active'             => $isActive,
                    'is_cancelled'          => false,
                    'is_archived'           => $isArchived,
                    'created_at'            => $startDate->subDays(rand(3, 14))->toDateTimeString(),
                    'updated_at'            => now()->toDateTimeString(),
                ]);

                $totalCreated++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info("✓ Created {$totalCreated} analytics leave requests.");
    }

    /**
     * Pick a random key from an array of [value => weight] pairs.
     */
    private function weightedRandom(array $weights): string
    {
        $total = array_sum($weights);
        $rand  = rand(1, $total);
        $cumulative = 0;
        foreach ($weights as $key => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $key;
            }
        }
        return array_key_first($weights);
    }

    /**
     * Return a contextual reason string based on leave type name.
     */
    private function getRandomReason(string $leaveTypeName): string
    {
        $reasons = [
            'Annual Leave'    => ['Family vacation', 'Rest and recuperation', 'Personal travel', 'Annual holiday break'],
            'Sick Leave'      => ['Medical appointment', 'Illness recovery', 'Doctor consultation', 'Flu symptoms'],
            'Maternity Leave' => ['Childbirth preparation', 'Postnatal care', 'New baby care'],
            'Paternity Leave' => ['Newborn care', 'Supporting spouse after delivery'],
            'Study Leave'     => ['Professional certification exam', 'Short course attendance', 'Online training program'],
            'Unpaid Leave'    => ['Personal matters', 'Family emergency', 'Extended travel'],
        ];

        $defaultReasons = ['Personal reasons', 'Family commitment', 'Medical need', 'Prior engagement'];

        foreach ($reasons as $typeName => $typeReasons) {
            if (stripos($leaveTypeName, $typeName) !== false || stripos($leaveTypeName, strtolower($typeName)) !== false) {
                return $typeReasons[array_rand($typeReasons)];
            }
        }

        return $defaultReasons[array_rand($defaultReasons)];
    }
}
