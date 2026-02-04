<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index()
    {
        $departments = Department::with('supervisor')->get();
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        // For new department creation, allow admin to pick from all supervisors
        $supervisors = Employee::whereHas('role', function ($q) {
            $q->where('name', 'Supervisor');
        })->get();

        return view('departments.create', compact('supervisors'));
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'DepartmentName' => 'required|string|max:255',
            'SupervisorID'   => 'nullable|exists:employees,EmployeeNumber',
        ]);

        $department = Department::create($validated);

        // Cascade supervisor assignment if provided
        if (!empty($validated['SupervisorID'])) {
            Employee::where('DepartmentID', $department->DepartmentID)
                ->where('EmployeeNumber', '!=', $validated['SupervisorID'])
                ->update(['SupervisorID' => $validated['SupervisorID']]);

            // 🔑 Update corresponding user record
            $supervisor = Employee::where('EmployeeNumber', $validated['SupervisorID'])->first();
            if ($supervisor && $supervisor->user) {
                $supervisor->user->role_id = 2; // Supervisor role
                $supervisor->user->save();
            }
        }

        return redirect()->route('departments.index')->with('success', 'Department created successfully!');
    }

    /**
     * Show the form for editing an existing department.
     */
    public function edit($DepartmentID)
    {
        $department = Department::findOrFail($DepartmentID);

        // Only supervisors in this department
        $supervisors = Employee::where('DepartmentID', $department->DepartmentID)
            ->whereHas('role', function ($q) {
                $q->where('name', 'Supervisor');
            })
            ->get();

        return view('departments.edit', compact('department', 'supervisors'));
    }

    /**
     * Update an existing department.
     */
    public function update(Request $request, $DepartmentID)
    {
        $validated = $request->validate([
            'DepartmentName' => 'required|string|max:255',
            'SupervisorID'   => 'nullable|exists:employees,EmployeeNumber',
        ]);

        $department = Department::findOrFail($DepartmentID);
        $department->update($validated);

        // Cascade supervisor assignment if provided
        if (!empty($validated['SupervisorID'])) {
            Employee::where('DepartmentID', $DepartmentID)
                ->where('EmployeeNumber', '!=', $validated['SupervisorID'])
                ->update(['SupervisorID' => $validated['SupervisorID']]);

            // 🔑 Update corresponding user record
            $supervisor = Employee::where('EmployeeNumber', $validated['SupervisorID'])->first();
            if ($supervisor && $supervisor->user) {
                $supervisor->user->role_id = 2; // Supervisor role
                $supervisor->user->save();
            }
        }

        return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
    }

    /**
     * Remove a department.
     */
    public function destroy($DepartmentID)
    {
        Department::findOrFail($DepartmentID)->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
    }

    /**
     * Fetch employees by department ID.
     */
    public function getEmployeesByDepartment($DepartmentID)
    {
        $employees = Employee::where('DepartmentID', $DepartmentID)
            ->get(['EmployeeNumber', 'FirstName', 'LastName']);

        return response()->json($employees);
    }

    /**
     * Assign a supervisor to the department and cascade to employees.
     */
    public function assignSupervisorToDepartmentEmployees(Request $request, $DepartmentID)
    {
        $validated = $request->validate([
            'SupervisorID' => 'required|exists:employees,EmployeeNumber',
        ]);

        $department = Department::findOrFail($DepartmentID);
        $department->SupervisorID = $validated['SupervisorID'];
        $department->save();

        // Bulk update employees in this department, excluding the supervisor themself
        Employee::where('DepartmentID', $DepartmentID)
            ->where('EmployeeNumber', '!=', $validated['SupervisorID'])
            ->update(['SupervisorID' => $validated['SupervisorID']]);

        // 🔑 Update corresponding user record
        $supervisor = Employee::where('EmployeeNumber', $validated['SupervisorID'])->first();
        if ($supervisor && $supervisor->user) {
            $supervisor->user->role_id = 2; // Supervisor role
            $supervisor->user->save();
        }

        return redirect()->route('departments.index')->with('success', 'Supervisor assigned to department and all its employees successfully!');
    }
}
