<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeLeaveBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employee_leave_balance')->insert([
            [
                'EmployeeNumber' => 'EMP-001',
                'AnnualLeaveBalance' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'EmployeeNumber' => 'EMP-002',
                'AnnualLeaveBalance' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'EmployeeNumber' => 'EMP-003',
                'AnnualLeaveBalance' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
