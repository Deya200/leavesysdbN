<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClinicalRosterTest extends TestCase
{
    use RefreshDatabase;

    public function test_clinical_roster_page_loads_and_shows_summary_cards(): void
    {
        $grade = Grade::create([
            'GradeName' => 'Clinical Officer',
            'AnnualLeaveDays' => 21,
        ]);

        $position = Position::create([
            'PositionName' => 'Clinical Officer',
        ]);

        $department = Department::create([
            'DepartmentName' => 'Clinical',
            'Description' => 'Clinical services',
        ]);

        $employee = Employee::create([
            'EmployeeNumber' => 'EMP-100',
            'FirstName' => 'Jane',
            'LastName' => 'Doe',
            'DepartmentID' => $department->DepartmentID,
            'GradeID' => $grade->GradeID,
            'PositionID' => $position->PositionID,
            'Gender' => 'Female',
            'DateOfBirth' => '1990-01-01',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($employee);

        $response = $this->get(route('clinical-rosters.index'));

        $response->assertOk();
        $response->assertSee('Clinical Roster');
        $response->assertSee('Devotion');
    }
}
