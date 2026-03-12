<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Position;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of users (employees with login access).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Show all employees as potential users
        $users = Employee::with(['department', 'grade', 'position', 'role'])->orderBy('FirstName', 'asc')->get();
        return view('user_management', compact('users'));
    }
    
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::all();
        $grades = Grade::all();
        $positions = Position::all();
        $roles = Role::all();
        
        return view('user.create', compact('departments', 'grades', 'positions', 'roles'));
    }

    /**
     * Store a newly created user (employee) in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'EmployeeNumber' => 'required|string|unique:employees,EmployeeNumber',
            'FirstName' => 'required|string|max:100',
            'LastName' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email',
            'Gender' => 'required|in:Male,Female,Other',
            'DateOfBirth' => 'required|date',
            'DepartmentID' => 'required|exists:departments,DepartmentID',
            'GradeID' => 'required|exists:grades,GradeID',
            'PositionID' => 'required|exists:positions,PositionID',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Generate a temporary password
        $tempPassword = Str::random(12);
        $validatedData['password'] = bcrypt($tempPassword);

        // Create the employee record
        $employee = Employee::create($validatedData);

        // Send password reset invitation
        try {
            $token = Password::getRepository()->create($employee);
            $employee->sendPasswordResetNotification($token);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send invitation to new user: " . $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User has been successfully added! An invitation has been sent to their email.');
    }

    /**
     * Show the form for editing an existing user.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = Employee::findOrFail($id);
        $departments = Department::all();
        $grades = Grade::all();
        $positions = Position::all();
        $roles = Role::all();
        
        return view('user.edit', compact('user', 'departments', 'grades', 'positions', 'roles'));
    }

    /**
     * Toggle user active status.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus($id)
    {
        // Note: This would require an is_active column in the employees table
        // For now, we'll just return a message
        return redirect()->route('users.index')->with('info', 'Status toggle not yet implemented for employees.');
    }

    /**
     * Update a user's (employee's) information in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validatedData = $request->validate([
            'FirstName' => 'required|string|max:100',
            'LastName' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email,' . $id . ',EmployeeNumber',
            'Gender' => 'required|in:Male,Female,Other',
            'DateOfBirth' => 'required|date',
            'DepartmentID' => 'required|exists:departments,DepartmentID',
            'GradeID' => 'required|exists:grades,GradeID',
            'PositionID' => 'required|exists:positions,PositionID',
            'role_id' => 'required|exists:roles,id',
        ]);

        $employee->update($validatedData);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove a user (employee) from the database.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $firstName = $employee->FirstName;
        $lastName = $employee->LastName;
        
        $employee->delete();

        return redirect()->route('users.index')->with('success', "User {$firstName} {$lastName} has been successfully deleted!");
    }
}