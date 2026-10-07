<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Position;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;

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
        $sortColumn = $request->input('sort', 'FirstName'); // default sort column
        $sortOrder = $request->input('order', 'asc');

        // Fetch employees with optional search filtering
        $employees = Employee::with(['department', 'role'])
            ->when($search, function ($query, $search) {
                $query->where('FirstName', 'like', "%{$search}%")
                    ->orWhere('LastName', 'like', "%{$search}%")
                    ->orWhereHas(
                        'department',
                        function ($departmentQuery) use ($search) {
                            $departmentQuery->where('DepartmentName', 'like', "%{$search}%");
                        }
                    );
            })
            ->orderBy('FirstName', 'asc')
            ->paginate(10) //
            ->appends([
                'search' => $search,
                'sort' => $sortColumn,
                'order' => $sortOrder,
            ]); //

        // Return the view with the employees and search query
        return view('employees.index', compact('employees', 'search', 'sortColumn', 'sortOrder'));
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
        $roles = Role::all();

        return view('employees.create', compact('departments', 'grades', 'positions', 'roles'));
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
            'national_id' => 'required|string|max:20|unique:employees,national_id',
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'DateOfBirth' => 'required|date',
            'DepartmentID' => 'required|exists:departments,DepartmentID',
            'GradeID' => 'required|exists:grades,GradeID',
            'PositionID' => 'required|exists:positions,PositionID',
            'Gender' => 'required|in:Male,Female,Other',
            'employment_type' => 'nullable|in:Permanent,Temporary,Locum,Contract',
            'is_locum' => 'nullable|boolean',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
        ]);

        $emailVerification = (new \App\Services\EmailVerificationService())->verify($validatedData['email']);
        if (!$emailVerification['valid']) {
            return redirect()->back()->withInput()->withErrors(['email' => $emailVerification['reason']]);
        }

        // Set defaults for new fields
        $validatedData['employment_type'] = $validatedData['employment_type'] ?? 'Permanent';
        $validatedData['is_locum'] = $request->has('is_locum') ? 1 : 0;

        // Set a default role_id for a new employee.
        // Adjust the value as needed (for example, if role "Employee" has an id of 2).
        $defaultRole = Role::where('name', 'Employee')->first();
        $validatedData['role_id'] = $defaultRole ? $defaultRole->id : null;


        $employee = Employee::create($validatedData);

        // Send invitation or activation email
        if ($employee->email) {
            try {
                if ($employee->is_locum) {
                    // For locum employees, send activation email with token
                    $token = $employee->generateActivationToken();
                    \Illuminate\Support\Facades\Mail::to($employee->email)
                        ->send(new \App\Mail\LocumActivationMail($employee, $token));
                } else {
                    // For regular employees, send password reset invitation
                    $token = \Illuminate\Support\Facades\Password::getRepository()->create($employee);
                    $employee->sendPasswordResetNotification($token);
                }
            } catch (\Exception $e) {
                // Silently fail or log if mail fails, but don't break the redirect
                \Illuminate\Support\Facades\Log::error("Failed to send invitation to new employee: " . $e->getMessage());
            }
        }

        AuditLog::record(
            auth()->user()->EmployeeNumber,
            "Created employee {$employee->EmployeeNumber}",
            'employees',
            is_numeric($employee->EmployeeNumber) ? intval($employee->EmployeeNumber) : 0
        );

        $message = 'Employee successfully added!';
        if ($employee->email) {
            $message .= $employee->is_locum 
                ? ' An activation link has been sent to the locum employee.' 
                : ' An invitation has been sent.';
        }

        return redirect()->route('employees.index')
            ->with('success', $message);
    }

    /**
     * Show the form for editing an existing employee.
     *
     * @param string $EmployeeNumber
     * @return \Illuminate\View\View
     */
    public function edit(Employee $employee)
    {
        $employee->load(['department', 'grade', 'position']);
        $departments = Department::all();
        $grades = Grade::all();
        $positions = Position::all();
        $roles = Role::all();

        return view('employees.edit', compact('employee', 'departments', 'grades', 'positions', 'roles'));
    }

    /**
     * Update an employee's information in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $EmployeeNumber
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'national_id' => 'required|string|max:20|unique:employees,national_id,' . $employee->EmployeeNumber . ',EmployeeNumber',
            'email' => 'required|email|unique:employees,email,' . $employee->EmployeeNumber . ',EmployeeNumber',
            'DateOfBirth' => 'required|date',
            'DepartmentID' => 'required|exists:departments,DepartmentID',
            'GradeID' => 'required|exists:grades,GradeID',
            'PositionID' => 'required|exists:positions,PositionID',
            'Gender' => 'required|in:Male,Female,Other',
            'role_id' => 'required|integer',
            'employment_type' => 'nullable|in:Permanent,Temporary,Locum,Contract',
            'is_locum' => 'nullable|boolean',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
        ]);

        $emailVerification = (new \App\Services\EmailVerificationService())->verify($validatedData['email']);
        if (!$emailVerification['valid']) {
            return redirect()->back()->withInput()->withErrors(['email' => $emailVerification['reason']]);
        }

        // Set defaults for new fields
        $validatedData['employment_type'] = $validatedData['employment_type'] ?? 'Permanent';
        $validatedData['is_locum'] = $request->has('is_locum') ? 1 : 0;

        // Check if employee is being converted to locum and doesn't have an activation token yet
        $wasNotLocum = !$employee->is_locum;
        $isNowLocum = $validatedData['is_locum'];
        $needsActivation = $wasNotLocum && $isNowLocum && !$employee->activation_token;

        $employee->update($validatedData);

        // Send activation email if employee is newly converted to locum
        if ($needsActivation && $employee->email) {
            try {
                $token = $employee->generateActivationToken();
                \Illuminate\Support\Facades\Mail::to($employee->email)
                    ->send(new \App\Mail\LocumActivationMail($employee, $token));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send locum activation email: " . $e->getMessage());
            }
        }

        AuditLog::record(
            auth()->user()->EmployeeNumber,
            "Updated employee {$employee->EmployeeNumber}",
            'employees',
            is_numeric($employee->EmployeeNumber) ? intval($employee->EmployeeNumber) : 0
        );

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
    public function destroy(Employee $employee)
    {
        // Simply delete the employee record; no detaching of roles/permissions is needed.
        $employee->delete();

        AuditLog::record(
            auth()->user()->EmployeeNumber,
            "Deleted employee {$employee->EmployeeNumber}",
            'employees',
            is_numeric($employee->EmployeeNumber) ? intval($employee->EmployeeNumber) : 0
        );

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

        // Update all employees in this department to have this supervisor
        Employee::where('DepartmentID', $validatedData['DepartmentID'])
            ->where('EmployeeNumber', '!=', $employee->EmployeeNumber)
            ->update(['SupervisorID' => $employee->EmployeeNumber]);

        // Update the employee's role to Supervisor.
        $supervisorRole = Role::where('name', 'Supervisor')->first();
        if ($supervisorRole) {
            $employee->role_id = $supervisorRole->id;
            $employee->save();
        }

        AuditLog::record(
            auth()->user()->EmployeeNumber,
            "Assigned {$employee->EmployeeNumber} as supervisor for department {$department->DepartmentID}",
            'departments',
            is_numeric($department->DepartmentID) ? intval($department->DepartmentID) : 0
        );

        return redirect()->route('employees.index')
            ->with('success', 'Employee assigned as Supervisor successfully! All department employees updated.');
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

    public function show(Employee $employee)
    {
        // Optionally eager load relationships
        $employee->load(['department', 'grade', 'position', 'subordinates']); // etc.

        return view('employees.show', compact('employee'));
    }

    /**
     * Send a password reset link as an invitation.
     */
    public function sendInvitation(Employee $employee)
    {
        if (!$employee->email) {
            return back()->with('error', "Employee {$employee->FirstName} does not have an email address.");
        }

        $token = Password::getRepository()->create($employee);
        $admin = auth()->user();
        /** @var \App\Models\Employee|null $admin */
        $employee->sendPasswordResetNotification($token, $admin);

        return back()->with('success', "Invitation sent to {$employee->FirstName} ({$employee->email}).");
    }

    public function bulkSendInvitations(Request $request)
    {
        $employeeNumbers = $request->input('employee_numbers', []);

        if ($request->input('scope') === 'all') {
            $employees = Employee::whereNotNull('email')->get();
        } else {
            $employees = Employee::whereIn('EmployeeNumber', $employeeNumbers)->whereNotNull('email')->get();
        }

        if ($employees->isEmpty()) {
            return back()->with('error', "No employees with email addresses selected.");
        }

        $admin = auth()->user();
        foreach ($employees as $employee) {
            /** @var \App\Models\Employee $employee */
            $token = Password::getRepository()->create($employee);
            /** @var \App\Models\Employee|null $admin */
            $employee->sendPasswordResetNotification($token, $admin);
        }

        return back()->with('success', "Invitations sent to " . $employees->count() . " employees.");
    }
}
