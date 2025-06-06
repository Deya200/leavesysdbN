<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;

class SupervisorController extends Controller
{
    public function index()
    {
        $supervisor = auth()->user();

        // Ensure supervisor exists and has an EmployeeNumber
        if (!is_object($supervisor) || empty($supervisor->EmployeeNumber)) {
            abort(404, 'Supervisor not found or EmployeeNumber missing.');
        }

        // Fetch employees supervised by this supervisor
        $employeesUnderSupervisor = Employee::where('SupervisorID', $supervisor->EmployeeNumber)->get();

        // Filter leave requests **ONLY** from employees supervised by the logged-in supervisor
        $leaveRequests = LeaveRequest::whereIn('EmployeeNumber', $employeesUnderSupervisor->pluck('EmployeeNumber'))
            ->with('employee', 'leaveType')
            ->orderByRaw("FIELD(RequestStatus, 'Pending Supervisor Approval', 'Pending Admin Verification', 'Rejected', 'Approved')")
            ->orderBy('created_at', 'desc')
            ->get();

        // Count pending approvals
        $pendingSupervisorRequests = $leaveRequests->where('RequestStatus', 'Pending Supervisor Approval')->count();
        $pendingAdminRequests = LeaveRequest::where('RequestStatus', 'Pending Admin Verification')->count();

        // Count employees currently on leave
        $employeesOnLeave = LeaveRequest::whereIn('EmployeeNumber', $employeesUnderSupervisor->pluck('EmployeeNumber'))
            ->where('RequestStatus', 'Approved')
            ->where('EndDate', '>=', now())
            ->count();

        // Count total employees supervised
        $totalEmployees = $employeesUnderSupervisor->count();
        $totalFemaleEmployees = $employeesUnderSupervisor->where('Gender', 'Female')->count();
        $totalMaleEmployees = $employeesUnderSupervisor->where('Gender', 'Male')->count();

        return view('dashboards.supervisor', compact(
            'leaveRequests',
            'pendingSupervisorRequests',
            'pendingAdminRequests',
            'employeesOnLeave',
            'totalEmployees',
            'totalFemaleEmployees',
            'totalMaleEmployees',
            'employeesUnderSupervisor'
        ));
    }

    /**
     * Approve a leave request.
     */
    public function approve($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        // Ensure the request belongs to an employee supervised by the logged-in supervisor
        if ($leaveRequest->RequestStatus !== 'Pending Supervisor Approval' ||
            $leaveRequest->employee->SupervisorID !== auth()->user()->EmployeeNumber) {
            return redirect()->back()->with('error', 'This leave request cannot be approved.');
        }

        // Approve the request
        $leaveRequest->SupervisorApproval = true;
        $leaveRequest->RequestStatus = 'Pending Admin Verification';
        $leaveRequest->save();

        Log::info("Supervisor approved leave request ID {$id} by supervisor " . auth()->user()->EmployeeNumber);
        return redirect()->back()->with('success', 'Leave request approved. Now pending admin verification.');
    }

    /**
     * Reject a leave request.
     */
    public function reject(Request $request, $id)
    {
        $request->validate(['RejectionReason' => 'required|string|max:255']);

        $leaveRequest = LeaveRequest::findOrFail($id);

        // Ensure the request belongs to an employee supervised by the logged-in supervisor
        if ($leaveRequest->RequestStatus !== 'Pending Supervisor Approval' ||
            $leaveRequest->employee->SupervisorID !== auth()->user()->EmployeeNumber) {
            return redirect()->back()->with('error', 'This leave request cannot be rejected.');
        }

        // Reject the request
        $leaveRequest->SupervisorApproval = false;
        $leaveRequest->RequestStatus = 'Rejected';
        $leaveRequest->RejectionReason = $request->RejectionReason;
        $leaveRequest->save();

        Log::info("Supervisor rejected leave request ID {$id} | Reason: " . $request->RejectionReason);
        return redirect()->back()->with('success', 'Leave request rejected by supervisor.');
    }
}
