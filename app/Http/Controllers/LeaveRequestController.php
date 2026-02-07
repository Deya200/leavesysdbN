<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Notification;
use App\Models\Employee;
use App\Models\LeaveAppeal;
use App\Models\LeaveExtension;
use App\Models\LeaveCancellation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    protected $casts = [
        'StartDate' => 'date:Y-m-d',
        'EndDate' => 'date:Y-m-d',
    ];

    // ✅ Calculate remaining annual leave days
    public function calculateRemainingLeaveDays()
    {
        $employee = Employee::where('EmployeeNumber', auth()->id())->firstOrFail();
        $totalLeaveDays = optional($employee->grade)->AnnualLeaveDays ?? 0;

        $usedLeaveDays = LeaveRequest::where('EmployeeNumber', $employee->EmployeeNumber)
            ->where('RequestStatus', 'Approved')
            ->whereHas('leaveType', fn($q) => $q->where('LeaveTypeName', 'Annual Leave'))
            ->sum('TotalDays');

        return max(0, $totalLeaveDays - $usedLeaveDays);
    }

    public function index(Request $request)
    {
        $leaveRequests = LeaveRequest::with([
            'employee:EmployeeNumber,FirstName,LastName',
            'leaveType:LeaveTypeID,LeaveTypeName',
            'supervisor:EmployeeNumber,FirstName,LastName'
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

    public function create()
    {
        $leaveTypes = LeaveType::all();
        return view('leave_requests.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'LeaveTypeID' => 'required|exists:leave_types,LeaveTypeID',
            'StartDate' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:EndDate'],
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

            $leaveType = LeaveType::findOrFail($validated['LeaveTypeID']);

            if ($leaveType->deductsFromAnnual() && $totalDays > $this->calculateRemainingLeaveDays()) {
                return redirect()->back()->with('error', 'Requested days exceed remaining annual leave.');
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

            return redirect()->route('leave_requests.create')->with('success', 'Your leave application was submitted successfully!');
        });
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('update', $leaveRequest);

        $validated = $request->validate([
            'LeaveTypeID' => 'required|exists:leave_types,LeaveTypeID',
            'StartDate' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:EndDate'],
            'EndDate' => [
                'required',
                'date',
                'after_or_equal:StartDate',
                function ($attr, $value, $fail) use ($leaveRequest, $request) {
                    $newDays = Carbon::parse($request->StartDate)
                        ->diffInDays(Carbon::parse($value)) + 1;

                    $leaveType = LeaveType::find($request->LeaveTypeID);

                    if ($leaveType && $leaveType->deductsFromAnnual()) {
                        $remaining = $this->calculateRemainingLeaveDays() + $leaveRequest->TotalDays;

                        if ($newDays > $remaining) {
                            $fail("Exceeds available days by " . ($newDays - $remaining));
                        }
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
            return redirect()->route('dashboards.employee')->with('success', 'Request updated successfully');
        });
    }

    public function review(Request $request)
    {
        $validated = $request->validate([
            'LeaveTypeID' => 'required|exists:leave_types,LeaveTypeID',
            'StartDate' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:EndDate'],
            'EndDate' => 'required|date|after_or_equal:StartDate',
            'Reason' => 'required|string|max:1000',
        ]);

        $leaveType = LeaveType::find($validated['LeaveTypeID']);
        $totalDays = Carbon::parse($validated['StartDate'])
            ->diffInDays(Carbon::parse($validated['EndDate'])) + 1;

        if ($leaveType && $leaveType->deductsFromAnnual() && $totalDays > $this->calculateRemainingLeaveDays()) {
            return redirect()->back()->with('error', 'Requested days exceed your remaining annual leave.');
        }

        return view('leave_requests.review', [
            'data' => $validated,
            'leaveType' => $leaveType,
            'totalDays' => $totalDays,
            'remainingDays' => $this->calculateRemainingLeaveDays()
        ]);
    }

        public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest = LeaveRequest::where('EmployeeNumber', auth()->id())->firstOrFail();
        $leaveRequest->delete();

        return redirect()->route('leave_requests.index')->with('success', 'Leave request deleted successfully.');
    }

    public function supervisorApprove(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('supervisorApprove', $leaveRequest);

        $validated = $request->validate([
            'SupervisorApprovalNote' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated, $leaveRequest) {
            if (strcasecmp($leaveRequest->RequestStatus, 'Pending Supervisor Approval') !== 0) {
                Log::warning("Invalid approval attempt for request: {$leaveRequest->LeaveRequestID}");
                return redirect()->back()->with('error', 'Invalid approval attempt');
            }

            $leaveRequest->update([
                'SupervisorApproval' => true,
                'RequestStatus' => 'Pending Admin Verification',
                'SupervisorApprovalNote' => $validated['SupervisorApprovalNote'] ?? null,
            ]);

            if ($leaveRequest->wasChanged()) {
                Notification::create([
                    'EmployeeNumber' => $leaveRequest->EmployeeNumber,
                    'Message' => 'Your request is pending admin verification. Supervisor Note: ' . ($validated['SupervisorApprovalNote'] ?? 'N/A'),
                    'Status' => 'Unread',
                ]);
            }

            Log::info("Supervisor approved: {$leaveRequest->LeaveRequestID}");
            return redirect()->back()->with('success', 'Request approved successfully.');
        });
    }

    public function supervisorReject(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('supervisorReject', $leaveRequest);

        $validated = $request->validate([
            'SupervisorRejectionReason' => 'required|string|max:500',
        ]);

        return DB::transaction(function () use ($validated, $leaveRequest) {
            if (strcasecmp($leaveRequest->RequestStatus, 'Pending Supervisor Approval') !== 0) {
                Log::warning("Invalid rejection attempt: {$leaveRequest->LeaveRequestID}");
                return redirect()->back()->with('error', 'Invalid rejection attempt');
            }

            $leaveRequest->update([
                'RequestStatus' => 'Rejected',
                'SupervisorRejectionReason' => $validated['SupervisorRejectionReason'],
                'RejectionReason' => $validated['SupervisorRejectionReason'], // Keep both for safety
            ]);

            if ($leaveRequest->wasChanged()) {
                Notification::create([
                    'EmployeeNumber' => $leaveRequest->EmployeeNumber,
                    'Message' => "Request rejected by supervisor: {$validated['SupervisorRejectionReason']}",
                    'Status' => 'Unread',
                ]);
            }

            Log::info("Supervisor rejected: {$leaveRequest->LeaveRequestID}");
            return redirect()->back()->with('success', 'Request rejected successfully.');
        });
    }

    public function adminApprove(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('adminApprove', $leaveRequest);

        $validated = $request->validate([
            'AdminApprovalNote' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated, $leaveRequest) {
            if (strcasecmp($leaveRequest->RequestStatus, 'Pending Admin Verification') !== 0) {
                return redirect()->back()->with('error', 'Invalid approval stage');
            }

            if ($leaveRequest->leaveType->deductsFromAnnual()) {
                $employee = $leaveRequest->employee;
                $employee->update([
                    'RemainingAnnualLeaveDays' => max(
                        0,
                        $employee->RemainingAnnualLeaveDays - $leaveRequest->TotalDays
                    )
                ]);
            }

            $leaveRequest->update([
                'RequestStatus' => 'Approved',
                'AdminApproval' => true,
                'AdminApprovalNote' => $validated['AdminApprovalNote'] ?? null,
            ]);

            if ($leaveRequest->wasChanged()) {
                Notification::create([
                    'EmployeeNumber' => $leaveRequest->EmployeeNumber,
                    'Message' => 'Request approved by admin. Admin Note: ' . ($validated['AdminApprovalNote'] ?? 'N/A'),
                    'Status' => 'Unread',
                ]);
            }

            Log::info("Admin approved: {$leaveRequest->LeaveRequestID}");
            return redirect()->back()->with('success', 'Request approved successfully.');
        });
    }

    public function adminReject(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'AdminRejectionReason' => 'required|string|max:500',
        ]);

        return DB::transaction(function () use ($validated, $leaveRequest) {
            if (strcasecmp($leaveRequest->RequestStatus, 'Pending Admin Verification') !== 0) {
                return redirect()->back()->with('error', 'Invalid rejection stage');
            }

            $leaveRequest->update([
                'RequestStatus' => 'Rejected by Admin',
                'AdminRejectionReason' => $validated['AdminRejectionReason'],
                'RejectionReason' => $validated['AdminRejectionReason'], // Keep both for safety
                'AdminVerified' => false,
            ]);

            if ($leaveRequest->wasChanged()) {
                Notification::create([
                    'EmployeeNumber' => $leaveRequest->EmployeeNumber,
                    'Message' => "Request rejected by Admin: {$validated['AdminRejectionReason']}",
                    'Status' => 'Unread',
                ]);
            }

            Log::info("Admin rejected: {$leaveRequest->LeaveRequestID}");
            return redirect()->back()->with('success', 'Leave request rejected successfully.');
        });
    }

    public function showAdminRejectForm(LeaveRequest $leaveRequest)
    {
        return view('leave_requests.admin_reject_form', compact('leaveRequest'));
    }

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


    public function appeal(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'Reason' => 'required|string|max:1000',
        ]);

        if (!$leaveRequest->canBeAppealed()) {
            return redirect()->back()->with('error', 'This request cannot be appealed.');
        }

        DB::transaction(function () use ($validated, $leaveRequest) {
            LeaveAppeal::create([
                'leave_request_id' => $leaveRequest->id,
                'employee_number' => auth()->user()->EmployeeNumber,
                'appeal_reason' => $validated['Reason'],
                'status' => 'Pending',
            ]);

            $leaveRequest->update(['RequestStatus' => 'Appealed']);

            Notification::create([
                'EmployeeNumber' => $leaveRequest->employee->SupervisorID, // Notify Supervisor
                'Message' => "Appeal submitted for leave request #{$leaveRequest->id}",
                'Status' => 'Unread',
            ]);

             Log::info("Leave appeal submitted: {$leaveRequest->id}");
        });

        return redirect()->back()->with('success', 'Appeal submitted successfully.');
    }

    public function extend(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'ExtensionDays' => 'required|integer|min:1',
            'Reason' => 'required|string|max:1000',
        ]);

        if (!$leaveRequest->canBeExtended()) {
            return redirect()->back()->with('error', 'This request cannot be extended.');
        }

        // Check balance if needed
        if ($leaveRequest->leaveType->deductsFromAnnual()) {
             $remaining = $this->calculateRemainingLeaveDays();
             if ($validated['ExtensionDays'] > $remaining) {
                 return redirect()->back()->with('error', 'Insufficient leave balance for extension.');
             }
        }

        DB::transaction(function () use ($validated, $leaveRequest) {
            LeaveExtension::create([
                'leave_request_id' => $leaveRequest->id,
                'employee_number' => auth()->user()->EmployeeNumber,
                'extension_days' => $validated['ExtensionDays'],
                'reason' => $validated['Reason'],
                'status' => 'Pending',
                'new_end_date' => Carbon::parse($leaveRequest->EndDate)->addDays($validated['ExtensionDays']),
            ]);
            
            // We do NOT update leave request status/end date yet, only after approval
             Notification::create([
                'EmployeeNumber' => $leaveRequest->employee->SupervisorID, 
                'Message' => "Extension requested for leave #{$leaveRequest->id}",
                'Status' => 'Unread',
            ]);

            Log::info("Leave extension requested: {$leaveRequest->id}");
        });

        return redirect()->back()->with('success', 'Extension requested successfully.');
    }

    public function cancel(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'Reason' => 'required|string|max:1000',
        ]);

        if (!$leaveRequest->canBeCancelled()) {
             return redirect()->back()->with('error', 'This request cannot be cancelled.');
        }

        DB::transaction(function () use ($validated, $leaveRequest) {
            // Calculate refund days if applicable
            $refundDays = 0;
            $today = Carbon::today();
            $startDate = Carbon::parse($leaveRequest->StartDate);
            
            // Logic: entirely future leave = full refund. 
            // In-progress leave = refund remaining days? 
            // For now, let's assume cancellation is only for future or current leaves, 
            // and business logic to calculate refund is handled in Admin approval or here.
            
            // Using logic from LeaveCancellation model if available, or simple logic here:
            if ($today->lt($startDate)) {
                $refundDays = $leaveRequest->TotalDays;
            } else {
                 // In progress
                 $endDate = Carbon::parse($leaveRequest->EndDate);
                 if ($today->lt($endDate)) {
                     $refundDays = $today->diffInDays($endDate); 
                 }
            }
            
            LeaveCancellation::create([
                'leave_request_id' => $leaveRequest->id,
                'employee_number' => auth()->user()->EmployeeNumber,
                'cancellation_reason' => $validated['Reason'],
                'status' => 'Pending',
                'refundable_days' => $refundDays,
            ]);

             Notification::create([
                'EmployeeNumber' => $leaveRequest->employee->SupervisorID, 
                'Message' => "Cancellation requested for leave #{$leaveRequest->id}",
                'Status' => 'Unread',
            ]);

            Log::info("Leave cancellation requested: {$leaveRequest->id}");
        });

        return redirect()->back()->with('success', 'Cancellation requested successfully.');
    }
}
