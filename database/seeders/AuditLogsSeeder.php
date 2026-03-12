<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditLogsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('audit_logs')->insert([
            [
                'EmployeeNumber' => 'EMP-001',
                'action' => 'Leave Request Approved',
                'table_name' => 'leave_requests',
                'record_id' => 1,
                'timestamp' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'EmployeeNumber' => 'EMP-002',
                'action' => 'Leave Appeal Submitted',
                'table_name' => 'leave_appeals',
                'record_id' => 1,
                'timestamp' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'EmployeeNumber' => 'EMP-003',
                'action' => 'Leave Extension Approved',
                'table_name' => 'leave_extensions',
                'record_id' => 2,
                'timestamp' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
