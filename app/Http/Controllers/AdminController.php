<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Optional middleware for admin-only access
    // public function __construct()
    // {
    //     $this->middleware('admin');
    // }

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
        $leaveRequests = LeaveRequest::with(['employee', 'leaveType'])->latest()->get();

        return view('dashboards.admin', compact(
            'employee',
            'totalEmployees',
            'totalLeaveRequests',
            'pendingLeaves',
            'leaveRequests'
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

        AuditLog::record(
            Auth::user()->EmployeeNumber,
            'Approved leave request',
            'leave_requests',
            $leaveRequest->LeaveRequestID
        );

        return redirect()->back()->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject a leave request with a reason.
     * Standardized to use 'Rejected' as the status.
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
            'RequestStatus' => 'Rejected', // Standardized status
            'AdminApproval' => false,
            'RejectionReason' => $request->RejectionReason,
        ]);

        AuditLog::record(
            Auth::user()->EmployeeNumber,
            'Rejected leave request',
            'leave_requests',
            $leaveRequest->LeaveRequestID
        );

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
     */
    public function assignRole(Request $request, $employeeNumber)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $employee = Employee::where('EmployeeNumber', $employeeNumber)->firstOrFail();
        $employee->update(['role_id' => $request->role_id]);

        AuditLog::record(
            Auth::user()->EmployeeNumber,
            "Assigned role {$request->role_id} to employee {$employee->EmployeeNumber}",
            'employees',
            intval($employee->EmployeeNumber)
        );

        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    /**
     * View all leave requests.
     */
    public function leaveRequests()
    {
        $leaveRequests = LeaveRequest::with(['employee', 'leaveType'])->get();
        return view('admin.leave_requests', compact('leaveRequests'));
    }

    /**
     * Audit trail listing for admins.
     */
    public function auditTrail(Request $request)
    {
        if (auth()->user()->role_id !== 1) {
            return redirect()->back()->with('error', 'You do not have permission to access audit logs.');
        }

        $query = AuditLog::with('employee')
            ->orderByDesc('timestamp');

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('timestamp', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('timestamp', '<=', $request->date_to);
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->filled('employee_number')) {
            $query->where('EmployeeNumber', $request->employee_number);
        }

        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        $auditLogs = $query->paginate(25)->appends($request->query());

        return view('admin.audit_trail', compact('auditLogs'));
    }
}
