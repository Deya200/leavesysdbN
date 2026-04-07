<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $statutoryLeaves = [
            [
                'LeaveTypeName' => 'Annual Leave',
                'IsPaidLeave' => true,
                'GenderApplicable' => 'Both',
                'MaxLeaveDays' => 18,
                'MinServiceYears' => 0,
                'DeductsFromAnnual' => false,
            ],
            [
                'LeaveTypeName' => 'Sick Leave',
                'IsPaidLeave' => true,
                'GenderApplicable' => 'Both',
                'MaxLeaveDays' => 60,
                'MinServiceYears' => 1,
                'DeductsFromAnnual' => false,
            ],
            [
                'LeaveTypeName' => 'Maternity Leave',
                'IsPaidLeave' => true,
                'GenderApplicable' => 'Female',
                'MaxLeaveDays' => 56,
                'MinServiceYears' => 0,
                'DeductsFromAnnual' => false,
            ],
        ];

        foreach ($statutoryLeaves as $leave) {
            DB::table('leave_types')->updateOrInsert(
                ['LeaveTypeName' => $leave['LeaveTypeName']],
                array_merge($leave, [
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep statutory leave records intact on rollback to avoid removing legal baselines.
    }
};
