<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Grade;
use App\Models\Department;
use App\Models\Position;
use Carbon\Carbon;

class WorkflowTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Prerequisites (Ensuring we have at least one of each to link to)
        $roleAdmin = Role::where('name', 'Admin')->first() ?? Role::create(['id' => 1, 'name' => 'Admin']);
        $roleSupervisor = Role::where('name', 'Supervisor')->first() ?? Role::create(['id' => 2, 'name' => 'Supervisor']);
        $roleEmployee = Role::where('name', 'Employee')->first() ?? Role::create(['id' => 3, 'name' => 'Employee']);

        $grade = Grade::first() ?? Grade::create(['GradeID' => 1, 'GradeName' => 'Grade I', 'AnnualLeaveDays' => 30]);
        $department = Department::first() ?? Department::create(['DepartmentID' => 1, 'DepartmentName' => 'Administration']);
        $position = Position::first() ?? Position::create(['PositionID' => 1, 'PositionName' => 'Staff', 'GradeID' => $grade->GradeID, 'DepartmentID' => $department->DepartmentID]);

        $password = Hash::make('password123');

        // 2. Create Test Admin
        Employee::updateOrCreate(
        ['email' => 'admin@test.com'],
        [
            'EmployeeNumber' => 'WF-ADMIN-001',
            'FirstName' => 'Workflow',
            'LastName' => 'Admin',
            'Gender' => 'Other',
            'DateOfBirth' => '1980-01-01',
            'DepartmentID' => $department->DepartmentID,
            'GradeID' => $grade->GradeID,
            'PositionID' => $position->PositionID,
            'SupervisorID' => null,
            'role_id' => $roleAdmin->id,
            'password' => $password,
            'RemainingAnnualLeaveDays' => 30,
        ]
        );

        // 3. Create Test Supervisor
        $supervisor = Employee::updateOrCreate(
        ['email' => 'supervisor@test.com'],
        [
            'EmployeeNumber' => 'WF-SUP-001',
            'FirstName' => 'Workflow',
            'LastName' => 'Supervisor',
            'Gender' => 'Other',
            'DateOfBirth' => '1985-05-05',
            'DepartmentID' => $department->DepartmentID,
            'GradeID' => $grade->GradeID,
            'PositionID' => $position->PositionID,
            'SupervisorID' => 'WF-ADMIN-001',
            'role_id' => $roleSupervisor->id,
            'password' => $password,
            'RemainingAnnualLeaveDays' => 30,
        ]
        );

        // 4. Create Test Employee (Supervised by Test Supervisor)
        Employee::updateOrCreate(
        ['email' => 'employee@test.com'],
        [
            'EmployeeNumber' => 'WF-EMP-001',
            'FirstName' => 'Workflow',
            'LastName' => 'Employee',
            'Gender' => 'Other',
            'DateOfBirth' => '1990-10-10',
            'DepartmentID' => $department->DepartmentID,
            'GradeID' => $grade->GradeID,
            'PositionID' => $position->PositionID,
            'SupervisorID' => $supervisor->EmployeeNumber,
            'role_id' => $roleEmployee->id,
            'password' => $password,
            'RemainingAnnualLeaveDays' => 30,
        ]
        );

        $this->command->info('Workflow test users seeded successfully!');
    }
}
