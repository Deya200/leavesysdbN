<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            [
                'EmployeeNumber' => 'EMP-001',
                'FirstName' => 'John',
                'LastName' => 'Doe',
                'Gender' => 'Male',
                'DateOfBirth' => '1985-06-15',
                'DepartmentID' => 1, // Administration
                'GradeID' => 1,
                'PositionID' => 1, // System Administrator
                'SupervisorID' => null,
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 1, // Admin
                'created_at' => now(),
                'updated_at' => now(),
                'RemainingAnnualLeaveDays' => 20,
                'email_notifications_enabled' => true,
                'system_notifications_enabled' => true,
                'carried_over_leave_days' => 0,
            ],
            [
                'EmployeeNumber' => 'EMP-002',
                'FirstName' => 'Mary',
                'LastName' => 'Smith',
                'Gender' => 'Female',
                'DateOfBirth' => '1990-03-22',
                'DepartmentID' => 2, // HR / Customer Care
                'GradeID' => 2,
                'PositionID' => 2, // HR Manager
                'SupervisorID' => 'EMP-001',
                'email' => 'hr@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 2, // Supervisor
                'created_at' => now(),
                'updated_at' => now(),
                'RemainingAnnualLeaveDays' => 15,
                'email_notifications_enabled' => true,
                'system_notifications_enabled' => true,
                'carried_over_leave_days' => 2,
            ],
            [
                'EmployeeNumber' => 'EMP-003',
                'FirstName' => 'James',
                'LastName' => 'Brown',
                'Gender' => 'Male',
                'DateOfBirth' => '1995-11-10',
                'DepartmentID' => 9, // Admin and Finance
                'GradeID' => 3,
                'PositionID' => 3, // Financial Analyst
                'SupervisorID' => 'EMP-002',
                'email' => 'finance@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 3, // Employee
                'created_at' => now(),
                'updated_at' => now(),
                'RemainingAnnualLeaveDays' => 10,
                'email_notifications_enabled' => true,
                'system_notifications_enabled' => true,
                'carried_over_leave_days' => 1,
            ],
        ]);
    }
}
