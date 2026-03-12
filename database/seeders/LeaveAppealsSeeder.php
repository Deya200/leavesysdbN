<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveAppealsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leave_appeals')->insert([
            [
                'leave_request_id' => 2, // Sick Leave request by EMP-002
                'employee_number' => 'EMP-002',
                'appeal_reason' => 'Doctor provided medical certificate to support absence',
                'status' => 'Pending',
                'reviewer_id' => 'EMP-001',
                'review_reason' => null,
                'reviewed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'leave_request_id' => 3, // Maternity Leave request by EMP-003
                'employee_number' => 'EMP-003',
                'appeal_reason' => 'Extended recovery period required beyond initial approval',
                'status' => 'Approved',
                'reviewer_id' => 'EMP-002',
                'review_reason' => 'Appeal justified due to medical documentation',
                'reviewed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
