<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveCancellationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leave_cancellations')->insert([
            [
                'leave_request_id' => 1, // Annual Leave request by EMP-001
                'employee_number' => 'EMP-001',
                'cancellation_reason' => 'Project deadline moved forward, cannot take leave',
                'status' => 'Approved',
                'cancelled_days' => 10,
                'refunded_days' => 10,
                'approved' => true,
                'reviewer_id' => 'EMP-002',
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'leave_request_id' => 2, // Sick Leave request by EMP-002
                'employee_number' => 'EMP-002',
                'cancellation_reason' => 'Recovered earlier than expected',
                'status' => 'Pending',
                'cancelled_days' => 2,
                'refunded_days' => 0,
                'approved' => false,
                'reviewer_id' => 'EMP-001',
                'approved_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
