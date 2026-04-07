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
use Illuminate\Support\Facades\DB;

class LeaveSysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fix sequences for Postgres (since ProductionDataSeeder uses hardcoded IDs)
        $this->fixSequence('roles', 'id');
        $this->fixSequence('grades', 'GradeID');
        $this->fixSequence('departments', 'DepartmentID');
        $this->fixSequence('positions', 'PositionID');
        $this->fixSequence('leave_types', 'LeaveTypeID');

        // Create Roles
        $adminRole = Role::updateOrCreate(['name' => 'Admin'], ['description' => 'Administrator with full access']);
        $supervisorRole = Role::updateOrCreate(['name' => 'Supervisor'], ['description' => 'Supervisor with approval rights']);
        $employeeRole = Role::updateOrCreate(['name' => 'Employee'], ['description' => 'Regular employee']);

        // Create Grades
        $gradeA = Grade::updateOrCreate(['GradeName' => 'Grade A'], ['AnnualLeaveDays' => 30]);

        $gradeB = Grade::updateOrCreate(['GradeName' => 'Grade B'], ['AnnualLeaveDays' => 25]);

        $gradeC = Grade::updateOrCreate(['GradeName' => 'Grade C'], ['AnnualLeaveDays' => 21]);

        // Create Departments
        $hrDept = Department::updateOrCreate(['DepartmentName' => 'Human Resources'], ['Description' => 'HR Department']);

        $itDept = Department::updateOrCreate(['DepartmentName' => 'Information Technology'], ['Description' => 'IT Department']);

        // Create Positions
        $managerPos = Position::updateOrCreate(['PositionName' => 'Manager'], ['DepartmentID' => $hrDept->DepartmentID]);

        $developerPos = Position::updateOrCreate(['PositionName' => 'Developer'], ['DepartmentID' => $itDept->DepartmentID]);

        // Create Leave Types
        LeaveType::updateOrCreate(['LeaveTypeName' => 'Annual Leave'], [
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Both',
            'MaxLeaveDays' => 18,
            'MinServiceYears' => 0,
        ]);

        LeaveType::updateOrCreate(['LeaveTypeName' => 'Sick Leave'], [
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Both',
            'MaxLeaveDays' => 60,
            'MinServiceYears' => 1,
        ]);

        LeaveType::updateOrCreate(['LeaveTypeName' => 'Maternity Leave'], [
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Female',
            'MaxLeaveDays' => 56,
            'MinServiceYears' => 0,
        ]);

        LeaveType::updateOrCreate(['LeaveTypeName' => 'Paternity Leave'], [
            'IsPaidLeave' => true,
            'GenderApplicable' => 'Male',
            'MaxLeaveDays' => 7,
            'MinServiceYears' => 0,
        ]);

        LeaveType::updateOrCreate(['LeaveTypeName' => 'Study Leave'], [
            'IsPaidLeave' => false,
            'GenderApplicable' => 'Both',
            'MaxLeaveDays' => 30,
            'MinServiceYears' => 2,
        ]);

        // Create User: Lucious Malizani (Existing Admin)
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
                'SupervisorID' => null,
                'password' => Hash::make('Airtel@2063'),
                'role_id' => $adminRole->id,
                'RemainingAnnualLeaveDays' => 30,
            ]
        );

        // --- Generic Test Users ---

        // 1. Test Admin
        Employee::updateOrCreate(
            ['email' => 'test.admin@example.com'],
            [
                'EmployeeNumber' => 'EMP-TEST-001',
                'FirstName' => 'Test',
                'LastName' => 'Admin',
                'DateOfBirth' => '1990-01-01',
                'Gender' => 'Female',
                'DepartmentID' => $hrDept->DepartmentID,
                'GradeID' => $gradeA->GradeID,
                'PositionID' => $managerPos->PositionID,
                'SupervisorID' => null,
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
                'RemainingAnnualLeaveDays' => 30,
            ]
        );

        // 2. Test Supervisor
        $testSupervisor = Employee::updateOrCreate(
            ['email' => 'test.supervisor@example.com'],
            [
                'EmployeeNumber' => 'EMP-TEST-002',
                'FirstName' => 'Test',
                'LastName' => 'Supervisor',
                'DateOfBirth' => '1992-02-02',
                'Gender' => 'Male',
                'DepartmentID' => $itDept->DepartmentID,
                'GradeID' => $gradeB->GradeID,
                'PositionID' => $managerPos->PositionID,
                'SupervisorID' => $lucious->EmployeeNumber,
                'password' => Hash::make('password123'),
                'role_id' => $supervisorRole->id,
                'RemainingAnnualLeaveDays' => 25,
            ]
        );

        // 3. Test Employee (Ordinary User)
        Employee::updateOrCreate(
            ['email' => 'test.employee@example.com'],
            [
                'EmployeeNumber' => 'EMP-TEST-003',
                'FirstName' => 'Test',
                'LastName' => 'Employee',
                'DateOfBirth' => '1995-03-03',
                'Gender' => 'Female',
                'DepartmentID' => $itDept->DepartmentID,
                'GradeID' => $gradeC->GradeID,
                'PositionID' => $developerPos->PositionID,
                'SupervisorID' => $testSupervisor->EmployeeNumber, // Supervised by Test Supervisor
                'password' => Hash::make('password123'),
                'role_id' => $employeeRole->id,
                'RemainingAnnualLeaveDays' => 20,
            ]
        );

        // Update Regular Employee: John Doe -> Supervised by Test Supervisor
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
                'SupervisorID' => $testSupervisor->EmployeeNumber, // Changed to Test Supervisor
                'password' => Hash::make('password123'),
                'role_id' => $employeeRole->id,
                'RemainingAnnualLeaveDays' => 25,
                'email_notifications_enabled' => true,
                'system_notifications_enabled' => true,
                'carried_over_leave_days' => 0,
            ]
        );

        $this->command->info('Database seeded successfully with user: lumalizani@gmail.com');

        // Propagate department SupervisorID to employees in that department
        $departmentsWithSupervisors = Department::whereNotNull('SupervisorID')->get();
        foreach ($departmentsWithSupervisors as $dept) {
            Employee::where('DepartmentID', $dept->DepartmentID)
                ->where('EmployeeNumber', '!=', $dept->SupervisorID)
                ->update(['SupervisorID' => $dept->SupervisorID]);
        }

        $this->command->info('Propagated SupervisorID from departments to department employees.');
    }

    /**
     * Fix PostgreSQL sequence for a table.
     */
    private function fixSequence($table, $idColumn)
    {
        // Only run for PostgreSQL
        if (DB::connection()->getDriverName() === 'pgsql') {
            try {
                // Quotes are important for mixed-case column names in Postgres
                DB::statement("SELECT setval(pg_get_serial_sequence('\"$table\"', '$idColumn'), COALESCE(MAX(\"$idColumn\"), 1) + 1, false) FROM \"$table\"");
            } catch (\Exception $e) {
                // Ignore if sequence doesn't exist or other minor error, just log info
                $this->command->info("Note: Could not fix sequence for $table.$idColumn (might not be auto-inc).");
            }
        }
    }
}
