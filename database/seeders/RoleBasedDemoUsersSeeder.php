<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\Position;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleBasedDemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrator with full access']
        );

        $supervisorRole = Role::firstOrCreate(
            ['name' => 'Supervisor'],
            ['description' => 'Supervisor with approval rights']
        );

        $employeeRole = Role::firstOrCreate(
            ['name' => 'Employee'],
            ['description' => 'Regular employee']
        );

        $department = Department::first() ?? Department::create([
            'DepartmentID' => 1,
            'DepartmentName' => 'Administration',
        ]);

        $grade = Grade::first() ?? Grade::create([
            'GradeID' => 1,
            'GradeName' => 'Grade I',
            'AnnualLeaveDays' => 30,
        ]);

        $position = Position::first() ?? Position::create([
            'PositionID' => 1,
            'PositionName' => 'Staff',
            'GradeID' => $grade->GradeID,
            'DepartmentID' => $department->DepartmentID,
        ]);

        $password = Hash::make('password123');

        $admin = Employee::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'EmployeeNumber' => 'ADM-001',
                'FirstName' => 'Admin',
                'LastName' => 'User',
                'Gender' => 'Male',
                'DateOfBirth' => '1980-01-01',
                'DepartmentID' => $department->DepartmentID,
                'GradeID' => $grade->GradeID,
                'PositionID' => $position->PositionID,
                'SupervisorID' => null,
                'password' => $password,
                'role_id' => $adminRole->id,
                'RemainingAnnualLeaveDays' => 30,
            ]
        );

        $supervisor = Employee::updateOrCreate(
            ['email' => 'supervisor@example.com'],
            [
                'EmployeeNumber' => 'SUP-001',
                'FirstName' => 'Supervisor',
                'LastName' => 'User',
                'Gender' => 'Female',
                'DateOfBirth' => '1985-05-05',
                'DepartmentID' => $department->DepartmentID,
                'GradeID' => $grade->GradeID,
                'PositionID' => $position->PositionID,
                'SupervisorID' => $admin->EmployeeNumber,
                'password' => $password,
                'role_id' => $supervisorRole->id,
                'RemainingAnnualLeaveDays' => 25,
            ]
        );

        Employee::updateOrCreate(
            ['email' => 'employee@example.com'],
            [
                'EmployeeNumber' => 'EMP-001',
                'FirstName' => 'Employee',
                'LastName' => 'User',
                'Gender' => 'Male',
                'DateOfBirth' => '1990-10-10',
                'DepartmentID' => $department->DepartmentID,
                'GradeID' => $grade->GradeID,
                'PositionID' => $position->PositionID,
                'SupervisorID' => $supervisor->EmployeeNumber,
                'password' => $password,
                'role_id' => $employeeRole->id,
                'RemainingAnnualLeaveDays' => 20,
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'EmployeeNumber' => 'ADM-001',
                'password' => $password,
                'email' => 'admin@example.com',
                'remaining_leave_days' => 30,
                'profile_photo' => null,
                'gender' => 'Male',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'supervisor@example.com'],
            [
                'name' => 'Supervisor User',
                'EmployeeNumber' => 'SUP-001',
                'password' => $password,
                'email' => 'supervisor@example.com',
                'remaining_leave_days' => 25,
                'profile_photo' => null,
                'gender' => 'Female',
                'role' => 'supervisor',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Employee User',
                'EmployeeNumber' => 'EMP-001',
                'password' => $password,
                'email' => 'employee@example.com',
                'remaining_leave_days' => 20,
                'profile_photo' => null,
                'gender' => 'Male',
                'role' => 'employee',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Demo admin/supervisor/employee users seeded successfully.');
    }
}
