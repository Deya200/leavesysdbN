<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $employees = Employee::all(); // Fetch all employees for supervisor dropdown
        return view('departments.create', compact('employees'));
    }

    /**
     * Store a newly created department in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'DepartmentName' => 'required|string|max:255',
            'SupervisorID' => 'nullable|exists:employees,EmployeeNumber', // Ensure SupervisorID exists in employees table
        ]);

        // Create the department record
        Department::create($validated);

        session()->flash('success', 'Department created successfully!');
        return redirect()->route('departments.index');
    }

    /**
     * Show the form for editing an existing department.
     *
     * @param string $DepartmentID
     * @return \Illuminate\View\View
     */
    public function edit($DepartmentID)
    {
        $department = Department::findOrFail($DepartmentID); // Find department by ID
        $employees = Employee::all(); // Fetch all employees for supervisor dropdown

        return view('departments.edit', compact('department', 'employees'));
    }

    /**
     * Update an existing department in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $DepartmentID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $DepartmentID)
    {
        // Validate the request data
        $validated = $request->validate([
            'DepartmentName' => 'required|string|max:255',
            'SupervisorID' => 'nullable|exists:employees,EmployeeNumber', // Ensure SupervisorID is valid
        ]);

        // Find the department and update details
        $department = Department::findOrFail($DepartmentID);
        $department->update($validated);

        session()->flash('success', 'Department updated successfully!');
        return redirect()->route('departments.index');
    }

    /**
     * Remove a department from the database.
     *
     * @param string $DepartmentID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($DepartmentID)
    {
        $department = Department::findOrFail($DepartmentID); // Find department by ID
        $department->delete();

        session()->flash('success', 'Department deleted successfully!');
        return redirect()->route('departments.index');
    }

    /**
     * Fetch employees by department ID for filtering.
     *
     * @param string $DepartmentID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeesByDepartment($DepartmentID)
    {
        $employees = Employee::where('DepartmentID', $DepartmentID)
            ->get(['EmployeeNumber', 'FirstName', 'LastName']); // Fetch employees by department

        return response()->json($employees); // Return as JSON for frontend use
    }
    
    /**
     * Assign a supervisor to the department and update all employees in that department.
     *
     * This method:
     * - Validates that the provided SupervisorID exists.
     * - Updates the department's SupervisorID.
     * - Iterates over all employees in the department and updates their SupervisorID.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $DepartmentID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignSupervisorToDepartmentEmployees(Request $request, $DepartmentID)
    {
        $validated = $request->validate([
            'SupervisorID' => 'required|exists:employees,EmployeeNumber',
        ]);
        
        // Find the department and update its SupervisorID.
        $department = Department::findOrFail($DepartmentID);
        $department->SupervisorID = $validated['SupervisorID'];
        $department->save();

        // Update each employee in the department with the new supervisor.
        $employees = Employee::where('DepartmentID', $DepartmentID)->get();

        foreach ($employees as $employee) {
            // Assuming employees table has a SupervisorID field.
            $employee->SupervisorID = $validated['SupervisorID'];
            $employee->save();
        }

        session()->flash('success', 'Supervisor assigned to department and all its employees successfully!');
        return redirect()->route('departments.index');
    }
}
