<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\Position;
use App\Models\RosterAttendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicalRosterBulkCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_can_bulk_create_roster_entries_for_a_department(): void
    {
        $department = Department::create([
            'DepartmentID' => 101,
            'DepartmentName' => 'Clinical',
        ]);

        $grade = Grade::create([
            'GradeID' => 101,
            'GradeName' => 'Grade I',
            'AnnualLeaveDays' => 30,
        ]);

        $position = Position::create([
            'PositionID' => 101,
            'PositionName' => 'Nurse',
            'GradeID' => $grade->GradeID,
            'DepartmentID' => $department->DepartmentID,
        ]);

        $supervisor = Employee::create([
            'EmployeeNumber' => 'SUP-999',
            'FirstName' => 'Super',
            'LastName' => 'Visor',
            'Gender' => 'Female',
            'DateOfBirth' => '1980-01-01',
            'DepartmentID' => $department->DepartmentID,
            'GradeID' => $grade->GradeID,
            'PositionID' => $position->PositionID,
            'SupervisorID' => null,
            'email' => 'supervisor@example.com',
            'password' => bcrypt('password123'),
            'role_id' => 2,
            'RemainingAnnualLeaveDays' => 20,
        ]);

        Employee::create([
            'EmployeeNumber' => 'EMP-999',
            'FirstName' => 'Jane',
            'LastName' => 'Doe',
            'Gender' => 'Female',
            'DateOfBirth' => '1990-01-01',
            'DepartmentID' => $department->DepartmentID,
            'GradeID' => $grade->GradeID,
            'PositionID' => $position->PositionID,
            'SupervisorID' => $supervisor->EmployeeNumber,
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
            'role_id' => 3,
            'RemainingAnnualLeaveDays' => 20,
        ]);

        $this->actingAs($supervisor);

        $response = $this->post(route('clinical-rosters.bulkStore'), [
            'department_id' => $department->DepartmentID,
            'date' => '2026-04-15',
            'shift' => 'day',
            'status' => 'present',
            'morning_devotion_attended' => true,
            'notes' => 'Bulk roster created',
        ]);

        $response->assertRedirect();
        $this->assertEquals(2, RosterAttendance::where('AttendanceDate', '2026-04-15')->count());
    }
}
