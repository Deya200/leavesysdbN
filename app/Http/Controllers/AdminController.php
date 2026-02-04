<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Admin dashboard overview.
     */
    public function index()
    {
        $user = auth()->user();
        $employee = Employee::where('EmployeeNumber', $user->EmployeeNumber)->first();

        $totalEmployees = Employee::count();
        $totalLeaveRequests = LeaveRequest::count();
        $pendingLeaves = LeaveRequest::where('RequestStatus', 'Pending Admin Verification')->count();

        // Leave requests for dashboard tab
        $leaveRequests = LeaveRequest::with(['employee', 'leaveType'])->latest()->get();

        // Users for User Management tab
        $users = User::with(['role', 'department', 'employee'])->get();

        return view('dashboards.admin', compact(
            'employee',
            'totalEmployees',
            'totalLeaveRequests',
            'pendingLeaves',
            'leaveRequests',
            'users'
        ));
    }

    /**
     * View all employees with their roles.
     */
    public function employees()
    {
        $employees = Employee::with('role')->get();
        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Approve a leave request.
     */
    public function approveLeave($leaveRequestId)
    {
        $leaveRequest = LeaveRequest::findOrFail($leaveRequestId);

        if ($leaveRequest->RequestStatus !== 'Pending Admin Verification') {
            return redirect()->back()->with('error', 'This leave request is not awaiting admin approval.');
        }

        $leaveRequest->update([
            'RequestStatus' => 'Approved',
            'AdminApproval' => true,
        ]);

        return redirect()->back()->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject a leave request with a reason.
     */
    public function rejectLeave(Request $request, $leaveRequestId)
    {
        $request->validate([
            'RejectionReason' => 'required|string|max:255',
        ]);

        $leaveRequest = LeaveRequest::findOrFail($leaveRequestId);

        if ($leaveRequest->RequestStatus !== 'Pending Admin Verification') {
            return redirect()->back()->with('error', 'This leave request is not pending admin verification.');
        }

        $leaveRequest->update([
            'RequestStatus' => 'Rejected',
            'AdminApproval' => false,
            'RejectionReason' => $request->RejectionReason,
        ]);

        return redirect()->back()->with('success', 'Leave request rejected.');
    }

    /**
     * View all roles.
     */
    public function roles()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Assign a role to an employee.
     * If the role is Supervisor, automatically assign them as supervisor
     * for all employees in their department.
     */
    public function assignRole(Request $request, $employeeNumber)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $employee = Employee::where('EmployeeNumber', $employeeNumber)->firstOrFail();
        $employee->update(['role_id' => $request->role_id]);

        // ✅ Sync role with User record
        $user = User::where('EmployeeNumber', $employeeNumber)->first();
        if ($user) {
            $user->role_id = $request->role_id;
            $user->save();
        }

        // ✅ If Supervisor, cascade assignment
        $role = Role::find($request->role_id);
        if ($role && strtolower($role->name) === 'supervisor') {
            $this->assignSupervisorToDepartment($employee);
        }

        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    /**
     * Helper: Assign supervisor to all employees in their department.
     */
    protected function assignSupervisorToDepartment(Employee $supervisor)
    {
        if (! $supervisor->DepartmentID) {
            Log::warning("Supervisor {$supervisor->EmployeeNumber} has no department.");
            return;
        }

        $supervisorId = $supervisor->EmployeeNumber;
        $departmentId = $supervisor->DepartmentID;

        DB::transaction(function () use ($supervisorId, $departmentId) {
            $affected = Employee::where('DepartmentID', $departmentId)
                ->where('EmployeeNumber', '!=', $supervisorId)
                ->update(['SupervisorID' => $supervisorId]);

            Log::info("Supervisor {$supervisorId} assigned to department {$departmentId}. Employees updated: {$affected}");
        });
    }

    /**
     * View all leave requests.
     */
    public function leaveRequests()
    {
        $leaveRequests = LeaveRequest::with(['employee', 'leaveType'])->get();
        return view('admin.leave_requests', compact('leaveRequests'));
    }
}
