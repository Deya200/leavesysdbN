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

        // --- Personal Leave Stats (Supervisor as Employee) ---
        $personalLeaveBalance = $supervisor->RemainingAnnualLeaveDays;
        $personalRecentRequests = LeaveRequest::where('EmployeeNumber', $supervisor->EmployeeNumber)
            ->with('leaveType')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // --- Team Stats & Lists ---
        $employeesUnderSupervisor = Employee::where('SupervisorID', $supervisor->EmployeeNumber)->get();

        $leaveRequests = LeaveRequest::whereIn('EmployeeNumber', $employeesUnderSupervisor->pluck('EmployeeNumber'))
            ->with('employee', 'leaveType')
            ->orderByRaw("CASE \"RequestStatus\" 
                WHEN 'Pending Supervisor Approval' THEN 1 
                WHEN 'Pending Admin Verification' THEN 2 
                WHEN 'Rejected' THEN 3 
                WHEN 'Approved' THEN 4 
                ELSE 5 END")
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingSupervisorRequests = LeaveRequest::whereIn('EmployeeNumber', $employeesUnderSupervisor->pluck('EmployeeNumber'))
            ->where('RequestStatus', 'Pending Supervisor Approval')
            ->count();
            
        $approvedTeamRequests = LeaveRequest::whereIn('EmployeeNumber', $employeesUnderSupervisor->pluck('EmployeeNumber'))
            ->where('RequestStatus', 'Approved')
            ->count();

        $rejectedTeamRequests = LeaveRequest::whereIn('EmployeeNumber', $employeesUnderSupervisor->pluck('EmployeeNumber'))
            ->where('RequestStatus', 'Rejected')
            ->count();
            
        $totalTeamRequests = LeaveRequest::whereIn('EmployeeNumber', $employeesUnderSupervisor->pluck('EmployeeNumber'))
            ->count();

        // Employees Currently on Leave (Team only)
        $today = now()->format('Y-m-d');
        $employeesOnLeave = LeaveRequest::with(['employee', 'employee.department', 'leaveType'])
            ->whereIn('EmployeeNumber', $employeesUnderSupervisor->pluck('EmployeeNumber'))
            ->where('RequestStatus', 'Approved')
            ->where('StartDate', '<=', $today)
            ->where('EndDate', '>=', $today)
            ->get();

        $totalEmployees = $employeesUnderSupervisor->count();
        $totalFemaleEmployees = $employeesUnderSupervisor->where('Gender', 'Female')->count();
        $totalMaleEmployees = $employeesUnderSupervisor->where('Gender', 'Male')->count();

        return view('dashboards.supervisor', compact(
            'leaveRequests',
            'pendingSupervisorRequests',
            'approvedTeamRequests',
            'rejectedTeamRequests',
            'totalTeamRequests',
            'employeesOnLeave',
            'totalEmployees',
            'totalFemaleEmployees',
            'totalMaleEmployees',
            'employeesUnderSupervisor',
            'personalLeaveBalance',
            'personalRecentRequests',
            'supervisor'
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


}
