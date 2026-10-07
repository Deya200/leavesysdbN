<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Role;
use App\Models\AuditLog;
use App\Models\LocumSession;
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

        $currentMonth = now();
        $locumSessionsThisMonth = LocumSession::whereYear('session_date', $currentMonth->year)
            ->whereMonth('session_date', $currentMonth->month)
            ->get();

        $totalLocumSessionsThisMonth = $locumSessionsThisMonth->count();
        $totalLocumSpendThisMonth = $locumSessionsThisMonth->sum(function ($session) {
            return $session->total_earnings ?? ($session->hours_worked * ($session->hourly_rate ?? 2000));
        });
        $formattedLocumSpendThisMonth = 'MWK ' . number_format($totalLocumSpendThisMonth, 2);

        $totalRequests = LeaveRequest::count();
        $totalApproved = LeaveRequest::where('RequestStatus', 'Approved')->count();
        $approvalRate = $totalRequests > 0 ? round(($totalApproved / $totalRequests) * 100, 1) : 0;
        $avgDuration = round((float) LeaveRequest::where('RequestStatus', 'Approved')->avg('TotalDays'), 1);

        $statusBreakdown = [
            'Approved' => $totalApproved,
            'Rejected' => LeaveRequest::where('RequestStatus', 'like', '%Rejected%')->count(),
            'Pending' => LeaveRequest::where('RequestStatus', 'like', '%Pending%')->count(),
        ];

        $monthlyVerified = [];
        $monthlyLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $monthlyLabels[] = $d->format('M Y');
            $monthlyVerified[] = LeaveRequest::whereYear('updated_at', $d->year)
                ->whereMonth('updated_at', $d->month)
                ->whereIn('RequestStatus', ['Approved', 'Rejected by Admin'])
                ->count();
        }

        $deptBalanceStats = Department::all()->map(function ($dept) {
            $employees = Employee::with('grade')->where('DepartmentID', $dept->DepartmentID)->get();
            $avgRemaining = $employees->isNotEmpty()
                ? round($employees->avg(fn($e) => $e->leave_days_remaining), 1)
                : 0;
            return ['name' => $dept->DepartmentName, 'avg' => $avgRemaining];
        })->filter(fn($d) => $d['avg'] > 0)->values();

        return view('dashboards.admin', compact(
            'employee',
            'totalEmployees',
            'totalLeaveRequests',
            'pendingLeaves',
            'leaveRequests',
            'totalLocumSessionsThisMonth',
            'formattedLocumSpendThisMonth',
            'statusBreakdown',
            'monthlyVerified',
            'monthlyLabels',
            'deptBalanceStats',
            'approvalRate',
            'avgDuration'
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
