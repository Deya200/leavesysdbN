<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveExtensionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leave_extensions')->insert([
            [
                'leave_request_id' => 3, // Maternity Leave request by EMP-003
                'employee_number' => 'EMP-003',
                'original_end_date' => '2026-07-30',
                'requested_end_date' => '2026-08-30',
                'extension_days' => 30,
                'reason' => 'Extended recovery period required',
                'status' => 'Pending',
                'supervisor_approval' => true,
                'admin_approval' => false,
                'reviewer_id' => 'EMP-002',
                'rejection_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'leave_request_id' => 1, // Annual Leave request by EMP-001
                'employee_number' => 'EMP-001',
                'original_end_date' => '2026-03-10',
                'requested_end_date' => '2026-03-15',
                'extension_days' => 5,
                'reason' => 'Family trip extended',
                'status' => 'Approved',
                'supervisor_approval' => true,
                'admin_approval' => true,
                'reviewer_id' => 'EMP-002',
                'rejection_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
