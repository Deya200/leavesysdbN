<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Notification;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    protected $casts = [
        'StartDate' => 'date:Y-m-d',
        'EndDate' => 'date:Y-m-d',
    ];

    // Calculate remaining leave days
    public function calculateRemainingLeaveDays()
    {
        $employee = Employee::where('EmployeeNumber', auth()->id())->firstOrFail();

        if ($employee->RemainingAnnualLeaveDays !== null) {
            return $employee->RemainingAnnualLeaveDays;
        }

        $totalLeaveDays = optional($employee->grade)->AnnualLeaveDays ?? 0;
        $usedLeaveDays = LeaveRequest::where('EmployeeNumber', $employee->EmployeeNumber)
            ->where('RequestStatus', 'Approved')
            ->sum('TotalDays');

        return max(0, $totalLeaveDays - $usedLeaveDays);
    }

    // Display leave requests
    public function index(Request $request)
    {
        $leaveRequests = LeaveRequest::with([
            'employee:id,FirstName,LastName',
            'leaveType:id,TypeName',
            'supervisor:id,FirstName,LastName'
        ])
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('EmployeeNumber', 'like', "%{$search}%")
                  ->orWhereHas('employee', fn($q) => $q->where('FullName', 'like', "%{$search}%"))
                  ->orWhere('RequestStatus', 'like', "%{$search}%")
                  ->orWhereHas('leaveType', fn($q) => $q->where('TypeName', 'like', "%{$search}%"));
            });
        })
        ->orderByDesc('created_at')
        ->paginate(15);

        return view('leave_requests.index', compact('leaveRequests'));
    }

    // Show create form
    public function create()
    {
        $leaveTypes = LeaveType::all();
        return view('leave_requests.create', compact('leaveTypes'));
    }

    // Store new request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'LeaveTypeID' => 'required|exists:leave_types,LeaveTypeID',
            'StartDate' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:EndDate'
            ],
            'EndDate' => 'required|date|after_or_equal:StartDate',
            'Reason' => 'required|string|max:1000',
        ]);

        return DB::transaction(function () use ($validated) {
            $employee = Employee::where('EmployeeNumber', auth()->id())->firstOrFail();

            if (!$employee->SupervisorID) {
                Log::error("Missing supervisor for employee: {$employee->EmployeeNumber}");
                return redirect()->back()->with('error', 'Supervisor not assigned. Contact HR.');
            }

            $totalDays = Carbon::parse($validated['StartDate'])
                ->diffInDays(Carbon::parse($validated['EndDate'])) + 1;

            if ($totalDays > $this->calculateRemainingLeaveDays()) {
                return redirect()->back()->with('error', 'Insufficient remaining leave days');
            }

            $leaveRequest = LeaveRequest::create([
                'EmployeeNumber' => $employee->EmployeeNumber,
                'SupervisorID' => $employee->SupervisorID,
                'LeaveTypeID' => $validated['LeaveTypeID'],
                'StartDate' => $validated['StartDate'],
                'EndDate' => $validated['EndDate'],
                'TotalDays' => $totalDays,
                'RequestStatus' => 'Pending Supervisor Approval',
                'Reason' => $validated['Reason'],
            ]);

            Log::info("Leave request created: {$leaveRequest->id}");
            return redirect()->route('dashboards.employee')->with('success', 'Request submitted!');
        });
    }

    // Edit request
    public function edit(LeaveRequest $leaveRequest)
    {
        $this->authorize('update', $leaveRequest);

        $leaveTypes = LeaveType::all();
        return view('leave_requests.edit', compact('leaveRequest', 'leaveTypes'));
    }

    // Update request
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('update', $leaveRequest);

        $validated = $request->validate([
            'LeaveTypeID' => 'required|exists:leave_types,LeaveTypeID',
            'StartDate' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:EndDate'
            ],
            'EndDate' => [
                'required',
                'date',
                'after_or_equal:StartDate',
                function ($attr, $value, $fail) use ($leaveRequest, $request) {
                    $newDays = Carbon::parse($request->StartDate)
                        ->diffInDays(Carbon::parse($value)) + 1;
                    $remaining = $this->calculateRemainingLeaveDays() + $leaveRequest->TotalDays;

                    if ($newDays > $remaining) {
                        $fail("Exceeds available days by " . ($newDays - $remaining));
                    }
                }
            ],
            'Reason' => 'required|string|max:1000',
        ]);

        return DB::transaction(function () use ($validated, $leaveRequest) {
            $validated['TotalDays'] = Carbon::parse($validated['StartDate'])
                ->diffInDays(Carbon::parse($validated['EndDate'])) + 1;

            $leaveRequest->update($validated);

            Log::info("Leave request updated: {$leaveRequest->id}");
            return redirect()->route('dashboards.employee')
                ->with('success', 'Request updated successfully');
        });
    }

    public function destroy(LeaveRequest $leaveRequest)
{
    // Authorization check
    $leaveRequest= LeaveRequest::where('EmployeeNumber', auth()->id())->firstOrFail();

    // Delete the leave request
    $leaveRequest->delete();

    // Redirect back with a success message
    return redirect()->route('leave_requests.index')
    ->with('success', 'Leave request deleted successfully.');
}


    // Supervisor approval
    public function supervisorApprove(LeaveRequest $leaveRequest)
    {
        $this->authorize('supervisorApprove', $leaveRequest);

        return DB::transaction(function () use ($leaveRequest) {
            if ($leaveRequest->RequestStatus !== 'Pending Supervisor Approval') {
                Log::warning("Invalid approval attempt for request: {$leaveRequest->id}");
                return redirect()->back()->with('error', 'Invalid approval attempt');
            }

            $leaveRequest->update([
                'SupervisorApproval' => true,
                'RequestStatus' => 'Pending Admin Verification',
            ]);

            if ($leaveRequest->wasChanged()) {
                Notification::create([
                    'EmployeeNumber' => $leaveRequest->EmployeeNumber,
                    'Message' => 'Your request is pending admin verification',
                    'Status' => 'Unread',
                ]);
            }

            Log::info("Supervisor approved: {$leaveRequest->id}");
            return redirect()->back()->with('success', 'Request approved');
        });
    }

    // Supervisor rejection
    public function supervisorReject(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('supervisorReject', $leaveRequest);

        $validated = $request->validate([
            'RejectionReason' => 'required|string|max:255',
        ]);

        return DB::transaction(function () use ($validated, $leaveRequest) {
            if ($leaveRequest->RequestStatus !== 'Pending Supervisor Approval') {
                Log::warning("Invalid rejection attempt: {$leaveRequest->id}");
                return redirect()->back()->with('error', 'Invalid rejection attempt');
            }

            $leaveRequest->update([
                'RequestStatus' => 'Rejected',
                'RejectionReason' => $validated['RejectionReason'],
            ]);

            if ($leaveRequest->wasChanged()) {
                Notification::create([
                    'EmployeeNumber' => $leaveRequest->EmployeeNumber,
                    'Message' => "Request rejected: {$validated['RejectionReason']}",
                    'Status' => 'Unread',
                ]);
            }

            Log::info("Supervisor rejected: {$leaveRequest->id}");
            return redirect()->back()->with('success', 'Request rejected');
        });
    }

    //Administrator Functions
    // Admin approval
    public function adminApprove(LeaveRequest $leaveRequest)
    {
        $this->authorize('adminApprove', $leaveRequest);

        return DB::transaction(function () use ($leaveRequest) {
            if ($leaveRequest->RequestStatus !== 'Pending Admin Verification') {
                return redirect()->back()->with('error', 'Invalid approval stage');
            }

            if ($leaveRequest->leaveType->isAnnualLeave()) {
                $employee = $leaveRequest->employee;
                $employee->update([
                    'RemainingAnnualLeaveDays' => max(
                        0,
                        $employee->RemainingAnnualLeaveDays - $leaveRequest->TotalDays
                    )
                ]);
            }

            $leaveRequest->update(['RequestStatus' => 'Approved']);

            if ($leaveRequest->wasChanged()) {
                Notification::create([
                    'EmployeeNumber' => $leaveRequest->EmployeeNumber,
                    'Message' => 'Request approved by admin',
                    'Status' => 'Unread',
                ]);
            }

            Log::info("Admin approved: {$leaveRequest->id}");
            return redirect()->back()->with('success', 'Request approved');
        });
    }

    //Modified Admin Reject
    public function adminReject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'RejectionReason' => 'required|string|max:255',
        ]);

        $leaveRequest->RejectionReason = $request->input('RejectionReason');
        $leaveRequest->RequestStatus = 'Rejected by Admin';
        $leaveRequest->AdminVerified = 0; // or whatever logic you use
        $leaveRequest->save();

        return redirect()->back()->with('status', 'Leave request rejected successfully.');
    }

    //Modified Reject
   public function showAdminRejectForm(LeaveRequest $leaveRequest)
    {
        return view('leave_requests.admin_reject_form', compact('leaveRequest'));
    }

    // Employee dashboard
    public function employeeDashboard()
    {
        $employee = auth()->user();

        return view('dashboards.employee', [
            'totalLeaveDays' => $this->calculateRemainingLeaveDays(),
            'totalLeaveRequests' => $employee->leaveRequests()->count(),
            'leaveRequests' => $employee->leaveRequests()
                ->with('leaveType')
                ->latest()
                ->paginate(10)
        ]);
    }
}
