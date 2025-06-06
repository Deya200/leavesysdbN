<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Position;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\Role;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     *
     * @return \Illuminate\View\View
     */
 
    public function index(Request $request)
    {
         // Get the search query from the request
        $search = $request->input('search');

       // Fetch employees with optional search filtering
       $employees = Employee::with('department') // Assuming a relationship exists for the department
        ->when($search, function ($query, $search) {
            $query->where('FirstName', 'like', "%{$search}%")
                  ->orWhere('LastName', 'like', "%{$search}%")
                  ->orWhereHas('department', function ($departmentQuery) use ($search) {
                      $departmentQuery->where('DepartmentName', 'like', "%{$search}%");
                  });
        })
        ->orderBy('FirstName', 'asc')
        ->get();

    // Return the view with the employees and search query
    return view('employees.index', compact('employees', 'search'));
 }

    /**
     * Show the form for creating a new employee.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::all();
        $grades = Grade::all();
        $positions = Position::all();

        return view('employees.create', compact('departments', 'grades', 'positions'));
    }

    /**
     * Store a newly created employee in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'EmployeeNumber' => 'required|unique:employees,EmployeeNumber',
            'FirstName'      => 'required|string|max:255',
            'LastName'       => 'required|string|max:255',
            'DateOfBirth'    => 'required|date',
            'DepartmentID'   => 'required|exists:departments,DepartmentID',
            'GradeID'        => 'required|exists:grades,GradeID',
            'PositionID'     => 'required|exists:positions,PositionID',
            'Gender'         => 'required|in:Male,Female,Other',
        ]);

        // Set a default role_id for a new employee.
        // Adjust the value as needed (for example, if role "Employee" has an id of 2).
        $validatedData['role_id'] = 2;

        $employee = Employee::create($validatedData);
        // No need to call assignRole since role_id defines the employee’s role.

        return redirect()->route('employees.index')
                         ->with('success', 'Employee successfully added!');
    }

    /**
     * Show the form for editing an existing employee.
     *
     * @param string $EmployeeNumber
     * @return \Illuminate\View\View
     */
    public function edit($EmployeeNumber)
    {
        $employee = Employee::with(['department', 'grade', 'position'])
            ->where('EmployeeNumber', $EmployeeNumber)
            ->firstOrFail();

        $departments = Department::all();
        $grades = Grade::all();
        $positions = Position::all();
        $roles = Role::all(); // Fetch available roles

        return view('employees.edit', compact('employee', 'departments', 'grades', 'positions', 'roles'));
    }

    /**
     * Update an employee's information in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $EmployeeNumber
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $EmployeeNumber)
    {
        $validatedData = $request->validate([
            'FirstName'      => 'required|string|max:255',
            'LastName'       => 'required|string|max:255',
            'DateOfBirth'    => 'required|date',
            'DepartmentID'   => 'required|exists:departments,DepartmentID',
            'GradeID'        => 'required|exists:grades,GradeID',
            'PositionID'     => 'required|exists:positions,PositionID',
            'Gender'         => 'required|in:Male,Female,Other',
            'role_id'        => 'required|integer',
        ]);

        $employee = Employee::where('EmployeeNumber', $EmployeeNumber)->firstOrFail();
        $employee->update($validatedData);
        // Since role_id is updated in the employee record, no further role syncing is required.
        // Optionally, you can verify the role exists:
        Role::findOrFail($validatedData['role_id']);

        return redirect()->route('employees.index')
                         ->with('success', 'Employee successfully updated!');
    }

    /**
     * Remove an employee from the database.
     *
     * @param string $EmployeeNumber
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($EmployeeNumber)
    {
        $employee = Employee::where('EmployeeNumber', $EmployeeNumber)->firstOrFail();
        // Simply delete the employee record; no detaching of roles/permissions is needed.
        $employee->delete();

        return redirect()->route('employees.index')
                         ->with('success', 'Employee successfully deleted!');
    }

    /**
     * Assign an employee as a supervisor and update their role.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $EmployeeNumber
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignSupervisor(Request $request, $EmployeeNumber)
    {
        $validatedData = $request->validate([
            'DepartmentID' => 'required|exists:departments,DepartmentID',
        ]);

        $employee = Employee::where('EmployeeNumber', $EmployeeNumber)->firstOrFail();
        $department = Department::findOrFail($validatedData['DepartmentID']);
        $department->SupervisorID = $employee->EmployeeNumber;
        $department->save();

        // Update the employee's role to Supervisor.
        // Assuming the Supervisor role has an id of 3.
        $employee->role_id = 3;
        $employee->save();

        return redirect()->route('employees.index')
                         ->with('success', 'Employee assigned as Supervisor successfully!');
    }

    /**
     * Update an employee's role dynamically.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $EmployeeNumber
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRole(Request $request, $EmployeeNumber)
    {
        $validatedData = $request->validate([
            'role_id' => 'required|integer',
        ]);

        $employee = Employee::where('EmployeeNumber', $EmployeeNumber)->firstOrFail();
        $employee->update(['role_id' => $validatedData['role_id']]);

        // If you want to do additional actions based on role (such as updating permissions),
        // implement that logic here. For now, updating role_id is enough.

        return redirect()->route('employees.index')
       
        ->with('success', 'Employee role updated successfully!');
    }

    public function getGenderByEmployeeNumber($employeeNumber)
{
    // Adjust the column name if needed (here we assume your table column is "Gender")
    $employee = Employee::where('EmployeeNumber', $employeeNumber)->first();

    if ($employee) {
        return response()->json(['gender' => $employee->Gender]);
    }
    
    return response()->json(['error' => 'Employee record not found.'], 404);
}


}
