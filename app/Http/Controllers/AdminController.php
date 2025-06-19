<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

// public function __construct()
//{
//    $this->middleware('admin');
//}

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
     * View all employees.
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
        $request->validate(['RejectionReason' => 'required|string|max:255']);

        $leaveRequest = LeaveRequest::findOrFail($leaveRequestId);

        if ($leaveRequest->RequestStatus !== 'Pending Admin Verification') {
            return redirect()->back()->with('error', 'This leave request is not pending admin verification.');
        }

        $leaveRequest->update([
            'RequestStatus' => 'Rejected by Admin',
            'AdminApproval' => false,
            'RejectionReason' => $request->RejectionReason,
        ]);

        return redirect()->back()->with('success', 'Leave request rejected.');
    }

    /**
     * Manage roles - view all roles.
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

        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    /**
     * Fetch all leave requests for admin verification.
     */
    public function leaveRequests()
    {
        $leaveRequests = LeaveRequest::with(['employee', 'leaveType'])->get();
        return view('admin.leave_requests', compact('leaveRequests'));
    }
}
