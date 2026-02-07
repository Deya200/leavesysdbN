<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Grade;
use App\Models\Department;
use App\Models\Position;
use App\Models\LeaveType;

class LeaveSysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'description' => 'Administrator with full access']);
        $supervisorRole = Role::firstOrCreate(['name' => 'Supervisor', 'description' => 'Supervisor with approval rights']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'description' => 'Regular employee']);

        // Create Grades
        $gradeA = Grade::firstOrCreate([
            'GradeName' => 'Grade A',
            'AnnualLeaveDays' => 30,
        ]);

        $gradeB = Grade::firstOrCreate([
            'GradeName' => 'Grade B',
            'AnnualLeaveDays' => 25,
        ]);

        $gradeC = Grade::firstOrCreate([
            'GradeName' => 'Grade C',
            'AnnualLeaveDays' => 21,
        ]);

        // Create Departments
        $hrDept = Department::firstOrCreate([
            'DepartmentName' => 'Human Resources',
            'Description' => 'HR Department',
        ]);

        $itDept = Department::firstOrCreate([
            'DepartmentName' => 'Information Technology',
            'Description' => 'IT Department',
        ]);

        // Create Positions
        $managerPos = Position::firstOrCreate([
            'PositionName' => 'Manager',
            'DepartmentID' => $hrDept->DepartmentID,
        ]);

        $developerPos = Position::firstOrCreate([
            'PositionName' => 'Developer',
            'DepartmentID' => $itDept->DepartmentID,
        ]);

        // Create Leave Types
        LeaveType::firstOrCreate([
            'LeaveTypeName' => 'Annual Leave',
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Both',
            'MaxLeaveDays' => 30,
            'MinServiceYears' => 0,
        ]);

        LeaveType::firstOrCreate([
            'LeaveTypeName' => 'Sick Leave',
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Both',
            'MaxLeaveDays' => 14,
            'MinServiceYears' => 0,
        ]);

        LeaveType::firstOrCreate([
            'LeaveTypeName' => 'Maternity Leave',
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Female',
            'MaxLeaveDays' => 90,
            'MinServiceYears' => 1,
        ]);

        LeaveType::firstOrCreate([
            'LeaveTypeName' => 'Paternity Leave',
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Male',
            'MaxLeaveDays' => 7,
            'MinServiceYears' => 0,
        ]);

        LeaveType::firstOrCreate([
            'LeaveTypeName' => 'Study Leave',
            'IsPaidLeave' => false,
            'GenderApplicable' => 'Both',
            'MaxLeaveDays' => 30,
            'MinServiceYears' => 2,
        ]);

        // Create User: Lucious Malizani
        $lucious = Employee::updateOrCreate(
            ['email' => 'lumalizani@gmail.com'],
            [
                'EmployeeNumber' => 'EMP001',
                'FirstName' => 'Lucious',
                'LastName' => 'Malizani',
                'DateOfBirth' => '1990-01-15',
                'Gender' => 'Male',
                'DepartmentID' => $itDept->DepartmentID,
                'GradeID' => $gradeA->GradeID,
                'PositionID' => $managerPos->PositionID,
                'SupervisorID' => null, // Top-level employee, no supervisor
                'password' => Hash::make('Airtel@2063'),
                'role_id' => $adminRole->id, // Currently Admin
                'RemainingAnnualLeaveDays' => 30,
                'email_notifications_enabled' => true,
                'system_notifications_enabled' => true,
                'carried_over_leave_days' => 0,
            ]
        );

        // Create Regular Employee: John Doe
        Employee::updateOrCreate(
            ['email' => 'johndoe@example.com'],
            [
                'EmployeeNumber' => 'EMP002',
                'FirstName' => 'John',
                'LastName' => 'Doe',
                'DateOfBirth' => '1995-05-20',
                'Gender' => 'Male',
                'DepartmentID' => $itDept->DepartmentID,
                'GradeID' => $gradeB->GradeID,
                'PositionID' => $developerPos->PositionID,
                'SupervisorID' => $lucious->EmployeeNumber, // Supervised by Lucious (Dynamically fetch ID)
                'password' => Hash::make('password123'),
                'role_id' => $employeeRole->id,
                'RemainingAnnualLeaveDays' => 25,
                'email_notifications_enabled' => true,
                'system_notifications_enabled' => true,
                'carried_over_leave_days' => 0,
            ]
        );

        $this->command->info('Database seeded successfully with user: lumalizani@gmail.com');
    }
}
