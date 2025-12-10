<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveRejectedBySupervisor; // Optional: create this notification

class SupervisorController extends Controller
{
    public function index()
    {
        $supervisor = auth()->user();

        if (!is_object($supervisor) || empty($supervisor->EmployeeNumber)) {
            abort(404, 'Supervisor not found or EmployeeNumber missing.');
        }

        $employeesUnderSupervisor = Employee::where('SupervisorID', $supervisor->EmployeeNumber)->get();

        $leaveRequests = LeaveRequest::whereIn('EmployeeNumber', $employeesUnderSupervisor->pluck('EmployeeNumber'))
            ->with('employee', 'leaveType')
            ->orderByRaw("FIELD(RequestStatus, 'Pending Supervisor Approval', 'Pending Admin Verification', 'Rejected', 'Approved')")
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingSupervisorRequests = $leaveRequests->where('RequestStatus', 'Pending Supervisor Approval')->count();
        $pendingAdminRequests = LeaveRequest::where('RequestStatus', 'Pending Admin Verification')->count();

        $employeesOnLeave = LeaveRequest::whereIn('EmployeeNumber', $employeesUnderSupervisor->pluck('EmployeeNumber'))
            ->where('RequestStatus', 'Approved')
            ->where('EndDate', '>=', now())
            ->count();

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

    public function approve($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($leaveRequest->RequestStatus !== 'Pending Supervisor Approval' ||
            $leaveRequest->employee->SupervisorID !== auth()->user()->EmployeeNumber) {
            return redirect()->back()->with('error', 'This leave request cannot be approved.');
        }

        $leaveRequest->SupervisorApproval = true;
        $leaveRequest->RequestStatus = 'Pending Admin Verification';
        $leaveRequest->save();

        Log::info("Supervisor approved leave request ID {$id} by supervisor " . auth()->user()->EmployeeNumber);
        return redirect()->back()->with('success', 'Leave request approved. Now pending admin verification.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['RejectionReason' => 'required|string|max:255']);

        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($leaveRequest->RequestStatus !== 'Pending Supervisor Approval' ||
            $leaveRequest->employee->SupervisorID !== auth()->user()->EmployeeNumber) {
            return redirect()->back()->with('error', 'This leave request cannot be rejected.');
        }

        $leaveRequest->SupervisorApproval = false;
        $leaveRequest->RequestStatus = 'Rejected by Supervisor';
        $leaveRequest->RejectionReason = $request->RejectionReason;
        $leaveRequest->SupervisorRejectionReason = $request->RejectionReason;

        // ✅ Point 1: Timestamp for rejection
        $leaveRequest->updated_at = now();

        $leaveRequest->save();

        Log::info("Supervisor rejected leave request ID {$id} | Reason: " . $request->RejectionReason);

        // ✅ Point 3: Notify admin (optional implementation)
        // You can create a notification class LeaveRejectedBySupervisor and send it to all admins
        /*
        $admins = Employee::where('role_id', 1)->get();
        Notification::send($admins, new LeaveRejectedBySupervisor($leaveRequest));
        */

        return redirect()->back()->with('success', 'Leave request rejected by supervisor.');
    }
}
