<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            [
                'EmployeeNumber' => 'EMP-001',
                'title' => 'Prepare monthly IT report',
                'description' => 'Compile system performance and security updates for February.',
                'due_date' => '2026-02-28',
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'EmployeeNumber' => 'EMP-002',
                'title' => 'Conduct staff training',
                'description' => 'Lead cybersecurity awareness session for hospital staff.',
                'due_date' => '2026-03-05',
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'EmployeeNumber' => 'EMP-003',
                'title' => 'Update HR leave records',
                'description' => 'Ensure leave balances reflect recent approvals and cancellations.',
                'due_date' => '2026-03-10',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
