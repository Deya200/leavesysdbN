<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\RosterAttendance;
use App\Models\ShiftAssignment;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ClinicalRosterController extends Controller
{
    /**
     * Display the clinical roster index for a specific date and department.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $departmentId = $request->get('department_id', null);
        $rosterDate = $request->get('date', now()->format('Y-m-d'));

        // If user is not admin, restrict to their department
        if (!$user->isAdmin()) {
            $departmentId = $user->DepartmentID;
        }

        // Get all departments or filter by selected
        $departments = Department::all();
        if (!$user->isAdmin()) {
            $departments = $departments->where('DepartmentID', '==', $user->DepartmentID);
        }

        // Get selected department (default: first department if clinical or user's dept)
        $selectedDepartment = null;
        if ($departmentId) {
            $selectedDepartment = Department::find($departmentId);
        }
        if (!$selectedDepartment && $departments->count() > 0) {
            $selectedDepartment = $departments->first();
        }

        // Get wards in department
        $wards = [];
        $staffByWard = [];
        if ($selectedDepartment) {
            $wards = Ward::where('DepartmentID', $selectedDepartment->DepartmentID)->get();

            // Get employees in department with their shift assignments and attendance for the date
            $employees = Employee::where('DepartmentID', $selectedDepartment->DepartmentID)
                ->with('shiftAssignments', 'rosterAttendance')
                ->get();

            foreach ($wards as $ward) {
                $staffByWard[$ward->WardID] = [];
            }

            // Build staff roster by ward
            foreach ($employees as $employee) {
                // Get shift assignment for the date
                $shiftAssignment = ShiftAssignment::where('EmployeeNumber', $employee->EmployeeNumber)
                    ->where('AssignmentDate', $rosterDate)
                    ->first();

                // Get attendance record for the date
                $dayAttendance = RosterAttendance::where('EmployeeNumber', $employee->EmployeeNumber)
                    ->where('AttendanceDate', $rosterDate)
                    ->where('Shift', 'day')
                    ->first();

                $nightAttendance = RosterAttendance::where('EmployeeNumber', $employee->EmployeeNumber)
                    ->where('AttendanceDate', $rosterDate)
                    ->where('Shift', 'night')
                    ->first();

                $wardId = $shiftAssignment?->WardID ?? 0;
                if (!isset($staffByWard[$wardId])) {
                    $staffByWard[$wardId] = [];
                }

                $staffByWard[$wardId][] = [
                    'employee' => $employee,
                    'shift_assignment' => $shiftAssignment,
                    'day_attendance' => $dayAttendance,
                    'night_attendance' => $nightAttendance,
                ];
            }
        }

        $allRosterRows = collect($staffByWard)->flatten(1);
        $summary = [
            'total_staff' => $allRosterRows->count(),
            'on_duty_day' => $allRosterRows->filter(fn($s) => $s['day_attendance']?->Status === 'present')->count(),
            'on_duty_night' => $allRosterRows->filter(fn($s) => $s['night_attendance']?->Status === 'present')->count(),
            'on_sick_leave' => $allRosterRows->filter(fn($s) => $s['day_attendance']?->Status === 'sick' || $s['night_attendance']?->Status === 'sick')->count(),
            'on_holiday' => $allRosterRows->filter(fn($s) => $s['day_attendance']?->Status === 'on_holiday' || $s['night_attendance']?->Status === 'on_holiday')->count(),
            'devotion_attended' => $allRosterRows->filter(fn($s) => $s['day_attendance']?->MorningDevotionAttended)->count(),
        ];

        return view('clinical-rosters.index', [
            'departments' => $departments,
            'selectedDepartment' => $selectedDepartment,
            'wards' => $wards,
            'staffByWard' => $staffByWard,
            'rosterDate' => $rosterDate,
            'summary' => $summary,
        ]);
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,DepartmentID',
            'date' => 'required|date',
            'shift' => 'required|in:day,night',
            'status' => 'required|in:present,sick,on_holiday,absent',
            'morning_devotion_attended' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $employees = Employee::where('DepartmentID', $validated['department_id'])->get();

        foreach ($employees as $employee) {
            $wardId = Ward::where('DepartmentID', $validated['department_id'])->first()?->WardID;

            ShiftAssignment::updateOrCreate(
                [
                    'EmployeeNumber' => $employee->EmployeeNumber,
                    'AssignmentDate' => $validated['date'],
                    'Shift' => $validated['shift'],
                ],
                [
                    'WardID' => $wardId,
                    'Notes' => $validated['notes'] ?? null,
                ]
            );

            RosterAttendance::updateOrCreate(
                [
                    'EmployeeNumber' => $employee->EmployeeNumber,
                    'AttendanceDate' => $validated['date'],
                    'Shift' => $validated['shift'],
                ],
                [
                    'Status' => $validated['status'],
                    'MorningDevotionAttended' => $validated['morning_devotion_attended'] ?? false,
                    'Notes' => $validated['notes'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Roster created for the selected department and date.');
    }

    /**
     * Store or update shift assignment for an employee.
     */
    public function storeShiftAssignment(Request $request)
    {
        $validated = $request->validate([
            'EmployeeNumber' => 'required|exists:employees,EmployeeNumber',
            'Shift' => 'required|in:day,night',
            'AssignmentDate' => 'required|date',
            'WardID' => 'nullable|exists:wards,WardID',
        ]);

        ShiftAssignment::updateOrCreate(
            [
                'EmployeeNumber' => $validated['EmployeeNumber'],
                'AssignmentDate' => $validated['AssignmentDate'],
                'Shift' => $validated['Shift'],
            ],
            ['WardID' => $validated['WardID'] ?? null]
        );

        return back()->with('success', 'Shift assignment updated.');
    }

    /**
     * Store or update attendance record for an employee.
     */
    public function storeAttendance(Request $request)
    {
        $validated = $request->validate([
            'EmployeeNumber' => 'required|exists:employees,EmployeeNumber',
            'AttendanceDate' => 'required|date',
            'Shift' => 'required|in:day,night',
            'Status' => 'required|in:present,sick,on_holiday,absent',
            'MorningDevotionAttended' => 'boolean',
        ]);

        RosterAttendance::updateOrCreate(
            [
                'EmployeeNumber' => $validated['EmployeeNumber'],
                'AttendanceDate' => $validated['AttendanceDate'],
                'Shift' => $validated['Shift'],
            ],
            [
                'Status' => $validated['Status'],
                'MorningDevotionAttended' => $validated['MorningDevotionAttended'] ?? false,
            ]
        );

        return back()->with('success', 'Attendance record updated.');
    }
}
