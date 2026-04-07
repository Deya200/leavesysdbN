<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'LeaveTypeName' => 'Annual Leave',
                'IsPaidLeave' => true,
                'GenderApplicable' => 'Both',
                'MaxLeaveDays' => 18,
                'MinServiceYears' => 0,
                'DeductsFromAnnual' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'LeaveTypeName' => 'Sick Leave',
                'IsPaidLeave' => true,
                'GenderApplicable' => 'Both',
                'MaxLeaveDays' => 60,
                'MinServiceYears' => 1,
                'DeductsFromAnnual' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'LeaveTypeName' => 'Maternity Leave',
                'IsPaidLeave' => true,
                'GenderApplicable' => 'Female',
                'MaxLeaveDays' => 56,
                'MinServiceYears' => 0,
                'DeductsFromAnnual' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'LeaveTypeName' => 'Paternity Leave',
                'IsPaidLeave' => true,
                'GenderApplicable' => 'Male',
                'MaxLeaveDays' => 14,
                'MinServiceYears' => 1,
                'DeductsFromAnnual' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'LeaveTypeName' => 'Unpaid Leave',
                'IsPaidLeave' => false,
                'GenderApplicable' => 'Both',
                'MaxLeaveDays' => 60,
                'MinServiceYears' => 0,
                'DeductsFromAnnual' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            DB::table('leave_types')->updateOrInsert(
                ['LeaveTypeName' => $leaveType['LeaveTypeName']],
                $leaveType
            );
        }
    }
}
