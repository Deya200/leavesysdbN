<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'EmployeeNumber' => 'WF-ADMIN-001',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'remaining_leave_days' => 30,
                'profile_photo' => null,
                'gender' => 'Male',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor User',
                'EmployeeNumber' => 'WF-SUP-001',
                'email' => 'supervisor@example.com',
                'password' => bcrypt('password'),
                'remaining_leave_days' => 30,
                'profile_photo' => null,
                'gender' => 'Female',
                'role' => 'supervisor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employee User',
                'EmployeeNumber' => 'WF-EMP-001',
                'email' => 'employee@example.com',
                'password' => bcrypt('password'),
                'remaining_leave_days' => 30,
                'profile_photo' => null,
                'gender' => 'Male',
                'role' => 'employee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
