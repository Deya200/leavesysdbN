<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'EmployeeNumber' => 'EMP-001',
                'Message' => 'Your annual leave request has been approved.',
                'Status' => 'Unread',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'EmployeeNumber' => 'EMP-002',
                'Message' => 'Your sick leave request is pending supervisor review.',
                'Status' => 'Unread',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'EmployeeNumber' => 'EMP-003',
                'Message' => 'Your maternity leave extension request has been approved.',
                'Status' => 'Read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
