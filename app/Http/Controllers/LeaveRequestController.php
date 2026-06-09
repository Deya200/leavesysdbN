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
use App\Models\AuditLog;
use App\Mail\LeaveRequestSubmittedMail;
use App\Mail\LeaveRequestApprovedMail;
use App\Mail\LeaveRequestRejectedMail;
use App\Mail\LeaveAppealSubmittedMail;
use App\Mail\LeaveExtensionRequestedMail;
use App\Mail\LeaveCancellationRequestedMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        return $employee->leave_days_remaining;
    }

    // Helper: Validate leave balance against configurable limits
    private function checkLeaveLimit($employee, $leaveType, $days, $ignoreRequestId = null)
    {
        // 1. Annual Leave (Grade-based Logic)
        if ($leaveType->deductsFromAnnual()) {
            $remaining = $employee->leave_days_remaining; // Uses getLeaveDaysRemainingAttribute
            if ($days > $remaining) {
                return "Requested days ({$days}) exceed your remaining annual leave balance ({$remaining}).";
            }
            return null;
        }

        // 2. Configurable Limits (MaxLeaveDays > 0)
        if ($leaveType->MaxLeaveDays > 0) {
            $currentYear = now()->year;
            $query = LeaveRequest::where('EmployeeNumber', $employee->EmployeeNumber)
                ->where('LeaveTypeID', $leaveType->LeaveTypeID)
                ->where('RequestStatus', '!=', 'Rejected')
                ->where('RequestStatus', '!=', 'Rejected by Admin')
                ->where('RequestStatus', '!=', 'Cancelled')
                ->whereYear('StartDate', $currentYear);

            if ($ignoreRequestId) {
                $query->where('LeaveRequestID', '!=', $ignoreRequestId);
            }

            $usedDays = $query->sum('TotalDays');
            $remaining = $leaveType->MaxLeaveDays - $usedDays;

            if ($days > $remaining) {
                return "Requested days ({$days}) exceed the {$leaveType->LeaveTypeName} annual limit of {$leaveType->MaxLeaveDays} days. ({$remaining} days remaining).";
            }
        }

        // 3. Unlimited (MaxLeaveDays is null)
        return null;
    }

    public function index(Request $request)
    {
        $query = LeaveRequest::with([
            'employee:EmployeeNumber,FirstName,LastName,DepartmentID',
            'leaveType:LeaveTypeID,LeaveTypeName',
            'supervisor:EmployeeNumber,FirstName,LastName'
        ]);

        // ✅ ROLE-BASED FILTERING
        $user = auth()->user();

        if ($user->role_id === 2) {
            // SUPERVISOR: Only see leave requests from employees in their department
            $supervisorDepartment = $user->DepartmentID;
            $query->whereHas('employee', function ($q) use ($supervisorDepartment) {
                $q->where('DepartmentID', $supervisorDepartment);
            });
        } elseif ($user->role_id === 3) {
            // EMPLOYEE: Only see their own leave requests and exclude archived ones
            $query->where('EmployeeNumber', $user->EmployeeNumber);
            $query->where('is_archived', false);
        }
        // Admin (role_id === 1): See all leave requests (no filter)

        // Apply Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('EmployeeNumber', 'like', "%{$search}%")
                    ->orWhere('RequestStatus', 'like', "%{$search}%")
                    ->orWhereHas(
                        'employee',
                        function ($q) use ($search) {
                            $q->where('FirstName', 'like', "%{$search}%")
                                ->orWhere('LastName', 'like', "%{$search}%");
                        }
                    )
                    ->orWhereHas(
                        'leaveType',
                        function ($q) use ($search) {
                            $q->where('LeaveTypeName', 'like', "%{$search}%");
                        }
                    );
            });
        }

        // Apply Status Filter
        if ($request->status) {
            $query->where('RequestStatus', $request->status);
        }

        // Apply Archived Filter (Admin Only)
        if ($request->filled('archived')) {
            $archived = $request->input('archived');
            if ($archived === '0' || $archived === 0) {
                $query->where('is_archived', false);
            } elseif ($archived === '1' || $archived === 1) {
                $query->where('is_archived', true);
            }
        }

        // Clone query for stats BEFORE pagination
        $statsQuery = clone $query;
        $totalCount = $statsQuery->count();
        $approvedCount = (clone $statsQuery)->where('RequestStatus', 'Approved')->count();
        $rejectedCount = (clone $statsQuery)->where('RequestStatus', 'Rejected')->count();
        $pendingCount = (clone $statsQuery)->where('RequestStatus', 'Pending Admin Verification')->count();

        $leaveRequests = $query->orderByDesc('created_at')->paginate(15);

        return view('leave_requests.index', [
            'leaveRequests' => $leaveRequests,
            'totalCount' => $totalCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'pendingCount' => $pendingCount,
            'employee' => auth()->user(),
        ]);
    }

    public function create()
    {
        $employee = Employee::where('EmployeeNumber', auth()->id())->firstOrFail();

        // Filter leave types based on employee's gender
        $leaveTypes = LeaveType::where(function ($query) use ($employee) {
            $query->where('GenderApplicable', 'Both')
                ->orWhere('GenderApplicable', $employee->Gender);
        })->get();

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

            $error = $this->checkLeaveLimit($employee, $leaveType, $totalDays);
            if ($error) {
                return redirect()->back()->with('error', $error);
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

            // Send email to supervisor
            if ($leaveRequest->supervisor && $leaveRequest->supervisor->email) {
                Mail::to($leaveRequest->supervisor->email)->send(new LeaveRequestSubmittedMail($leaveRequest));
            }

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

                    if ($leaveType) {
                        $error = $this->checkLeaveLimit($leaveRequest->employee, $leaveType, $newDays, $leaveRequest->LeaveRequestID);
                        if ($error) {
                            $fail($error);
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

        $employee = Employee::where('EmployeeNumber', auth()->id())->firstOrFail();
        $leaveType = LeaveType::find($validated['LeaveTypeID']);

        // Validate that the leave type is applicable to the employee's gender
        if ($leaveType->GenderApplicable !== 'Both' && $leaveType->GenderApplicable !== $employee->Gender) {
            return redirect()->back()->with('error', 'The selected leave type is not applicable to your gender.');
        }

        $totalDays = Carbon::parse($validated['StartDate'])
            ->diffInDays(Carbon::parse($validated['EndDate'])) + 1;

        $error = $this->checkLeaveLimit($employee, $leaveType, $totalDays);

        if ($error) {
            return redirect()->back()->with('error', $error);
        }

        return view('leave_requests.review', [
            'data' => $validated,
            'leaveType' => $leaveType,
            'totalDays' => $totalDays,
            'remainingDays' => $this->calculateRemainingLeaveDays()
        ]);
    }

    public function showReview()
    {
        if (session()->has('leave_review_data')) {
            $data = session('leave_review_data');
            $leaveType = isset($data['LeaveTypeID']) ? LeaveType::find($data['LeaveTypeID']) : null;
            $totalDays = null;
            if (isset($data['StartDate']) && isset($data['EndDate'])) {
                $totalDays = Carbon::parse($data['StartDate'])->diffInDays(Carbon::parse($data['EndDate'])) + 1;
            }

            return view('leave_requests.review', [
                'data' => $data,
                'leaveType' => $leaveType,
                'totalDays' => $totalDays,
                'remainingDays' => $this->calculateRemainingLeaveDays()
            ]);
        }

        return view('leave_requests.review');
    }

    public function show(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee', 'leaveType']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'employee' => $leaveRequest->employee->FirstName . ' ' . $leaveRequest->employee->LastName,
                'leaveType' => $leaveRequest->leaveType->LeaveTypeName,
                'startDate' => \Carbon\Carbon::parse($leaveRequest->StartDate)->format('M d, Y'),
                'endDate' => \Carbon\Carbon::parse($leaveRequest->EndDate)->format('M d, Y'),
                'totalDays' => $leaveRequest->TotalDays,
                'status' => $leaveRequest->RequestStatus,
                'reason' => $leaveRequest->Reason,
                'rejectionReason' => $leaveRequest->RejectionReason ?? $leaveRequest->AdminRejectionReason ?? $leaveRequest->SupervisorRejectionReason,
            ]);
        }

        return view('leave_requests.show', compact('leaveRequest'));
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

            AuditLog::record(
                auth()->user()->EmployeeNumber,
                'Supervisor approved leave request',
                'leave_requests',
                $leaveRequest->LeaveRequestID
            );

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
                'can_be_appealed' => true,
                'appeal_deadline' => now()->addDays(7),
            ]);

            if ($leaveRequest->wasChanged()) {
                Notification::create([
                    'EmployeeNumber' => $leaveRequest->EmployeeNumber,
                    'Message' => "Request rejected by supervisor: {$validated['SupervisorRejectionReason']}",
                    'Status' => 'Unread',
                ]);

                // Send rejection email to employee
                if ($leaveRequest->employee && $leaveRequest->employee->email) {
                    Mail::to($leaveRequest->employee->email)->send(
                        new LeaveRequestRejectedMail($leaveRequest, $validated['SupervisorRejectionReason'], 'Supervisor')
                    );
                }
            }

            AuditLog::record(
                auth()->user()->EmployeeNumber,
                'Supervisor rejected leave request',
                'leave_requests',
                $leaveRequest->LeaveRequestID
            );

            Log::info("Supervisor rejected: {$leaveRequest->LeaveRequestID}");
            return redirect()->back()->with('success', 'Request rejected successfully.');
        });
    }

    public function adminApprove(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('adminApprove', $leaveRequest);

        $validated = $request->validate([
            'AdminApprovalNote' => 'nullable|string|max:500',
            'ApprovalNote' => 'nullable|string|max:500',
        ]);

        $approvalNote = $validated['AdminApprovalNote'] ?? $validated['ApprovalNote'] ?? null;

        return DB::transaction(function () use ($approvalNote, $leaveRequest) {
            if (strcasecmp($leaveRequest->RequestStatus, 'Pending Admin Verification') !== 0) {
                return redirect()->back()->with('error', 'Invalid approval stage');
            }

            if ($leaveRequest->leaveType->deductsFromAnnual()) {
                $employee = $leaveRequest->employee;
                $oldBalance = $employee->RemainingAnnualLeaveDays;
                $newBalance = max(0, $oldBalance - $leaveRequest->TotalDays);

                $employee->update(['RemainingAnnualLeaveDays' => $newBalance]);

                Log::info("Admin Approval: Deducted {$leaveRequest->TotalDays} days from Employee {$employee->EmployeeNumber}. (Old: {$oldBalance}, New: {$newBalance})");
            } else {
                Log::info("Admin Approval: No annual leave deduction for type '{$leaveRequest->leaveType->LeaveTypeName}'");
            }

            $leaveRequest->update([
                'RequestStatus' => 'Approved',
                'AdminApproval' => true,
                'AdminApprovalNote' => $approvalNote,
                'is_active' => true,
            ]);

            if ($leaveRequest->wasChanged()) {
                Notification::create([
                    'EmployeeNumber' => $leaveRequest->EmployeeNumber,
                    'Message' => 'Request approved by admin. Admin Note: ' . ($approvalNote ?? 'N/A'),
                    'Status' => 'Unread',
                ]);

                // Send approval email to employee
                if ($leaveRequest->employee && $leaveRequest->employee->email) {
                    Mail::to($leaveRequest->employee->email)->send(
                        new LeaveRequestApprovedMail($leaveRequest, $approvalNote)
                    );
                }
            }

            AuditLog::record(
                auth()->user()->EmployeeNumber,
                'Admin approved leave request',
                'leave_requests',
                $leaveRequest->LeaveRequestID
            );

            Log::info("Admin approved: {$leaveRequest->LeaveRequestID}");
            return redirect()->back()->with('success', 'Request approved successfully.');
        });
    }

    public function adminReject(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'AdminRejectionReason' => 'nullable|string|max:500',
            'RejectionReason' => 'nullable|string|max:500',
        ]);

        $rejectionReason = $validated['AdminRejectionReason'] ?? $validated['RejectionReason'] ?? null;
        if (!$rejectionReason) {
            return redirect()->back()->with('error', 'Rejection reason is required');
        }

        return DB::transaction(function () use ($rejectionReason, $leaveRequest) {
            if (strcasecmp($leaveRequest->RequestStatus, 'Pending Admin Verification') !== 0) {
                return redirect()->back()->with('error', 'Invalid rejection stage');
            }

            $leaveRequest->update([
                'RequestStatus' => 'Rejected by Admin',
                'AdminRejectionReason' => $rejectionReason,
                'AdminVerified' => false,
                'can_be_appealed' => true,
                'appeal_deadline' => now()->addDays(7),
            ]);

            if ($leaveRequest->wasChanged()) {
                Notification::create([
                    'EmployeeNumber' => $leaveRequest->EmployeeNumber,
                    'Message' => "Request rejected by Admin: {$rejectionReason}",
                    'Status' => 'Unread',
                ]);

                // Send rejection email to employee
                if ($leaveRequest->employee && $leaveRequest->employee->email) {
                    Mail::to($leaveRequest->employee->email)->send(
                        new LeaveRequestRejectedMail($leaveRequest, $rejectionReason, 'Admin')
                    );
                }
            }

            AuditLog::record(
                auth()->user()->EmployeeNumber,
                'Admin rejected leave request',
                'leave_requests',
                $leaveRequest->LeaveRequestID
            );

            Log::info("Admin rejected: {$leaveRequest->LeaveRequestID}");
            return redirect()->back()->with('success', 'Leave request rejected successfully.');
        });
    }

    public function showAdminRejectForm(LeaveRequest $leaveRequest)
    {
        return view('leave_requests.admin_reject_form', compact('leaveRequest'));
    }

    public function showAdminApproveForm(LeaveRequest $leaveRequest)
    {
        return view('leave_requests.admin_approve_form', compact('leaveRequest'));
    }

    public function employeeDashboard()
    {
        $employee = auth()->user();
        if (!$employee) {
            return redirect()->route('login');
        }

        $leaveTypes = LeaveType::all();
        $leaveRequests = $employee->leaveRequests()
            ->where('is_archived', false)
            ->with(['leaveType'])
            ->orderByDesc('created_at')
            ->get();

        $dashboardData = $leaveTypes->map(function ($type) use ($employee, $leaveRequests) {
            $taken = $leaveRequests->where('LeaveTypeID', $type->LeaveTypeID)
                ->where('RequestStatus', 'Approved')
                ->sum('TotalDays');

            if ($type->deductsFromAnnual()) {
                $total = $employee->getTotalAvailableLeaveDays();
                $remaining = $employee->leave_days_remaining;
                $isUnlimited = false;
            } else {
                $total = $type->MaxLeaveDays;
                $isUnlimited = is_null($total) || $total <= 0;
                $remaining = $isUnlimited ? null : max(0, $total - $taken);
            }

            return [
                'type' => $type,
                'taken' => $taken,
                'remaining' => $remaining,
                'total' => $total,
                'isUnlimited' => $isUnlimited,
            ];
        });

        $counts = [
            'approved' => $leaveRequests->where('RequestStatus', 'Approved')->count(),
            'rejected' => $leaveRequests->whereIn('RequestStatus', ['Rejected', 'Rejected by Admin'])->count(),
            'pending' => $leaveRequests->whereIn('RequestStatus', ['Pending', 'Pending Supervisor Approval', 'Pending Admin Verification'])->count(),
            'remaining' => $employee->leave_days_remaining,
        ];

        // Fetch recent tasks and notifications
        $activeTasks = $employee->tasks()->where('status', '!=', 'Completed')->orderBy('due_date')->take(5)->get();
        $recentNotifications = $employee->notifications()->orderByDesc('created_at')->take(10)->get();
        $locumInvitations = $recentNotifications->filter(function ($notification) {
            return str_contains(strtolower($notification->Message), 'locum');
        });

        return view('dashboards.employee', [
            'dashboardData' => $dashboardData,
            'counts' => $counts,
            'leaveRequests' => $leaveRequests->take(10),
            'employee' => $employee,
            'activeTasks' => $activeTasks,
            'recentNotifications' => $recentNotifications,
            'locumInvitations' => $locumInvitations,
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
            $leaveAppeal = LeaveAppeal::create([
                'leave_request_id' => $leaveRequest->LeaveRequestID,
                'employee_number' => auth()->user()->EmployeeNumber,
                'appeal_reason' => $validated['Reason'],
                'status' => 'Pending',
            ]);

            $leaveRequest->update(['RequestStatus' => 'Appealed']);

            Notification::create([
                'EmployeeNumber' => $leaveRequest->employee->SupervisorID, // Notify Supervisor
                'Message' => "Appeal submitted for leave request #{$leaveRequest->LeaveRequestID}",
                'Status' => 'Unread',
            ]);

            // Send appeal email to supervisor
            if ($leaveRequest->supervisor && $leaveRequest->supervisor->email) {
                Mail::to($leaveRequest->supervisor->email)->send(new LeaveAppealSubmittedMail($leaveAppeal));
            }

            Log::info("Leave appeal submitted: {$leaveRequest->LeaveRequestID}");
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
            $extensionDays = (int) $validated['ExtensionDays'];

            $leaveExtension = LeaveExtension::create([
                'leave_request_id' => $leaveRequest->LeaveRequestID,
                'employee_number' => auth()->user()->EmployeeNumber,
                'original_end_date' => $leaveRequest->EndDate,
                'requested_end_date' => Carbon::parse($leaveRequest->EndDate)->addDays($extensionDays),
                'extension_days' => $extensionDays,
                'reason' => $validated['Reason'],
                'status' => 'Pending',
            ]);

            // We do NOT update leave request status/end date yet, only after approval
            Notification::create([
                'EmployeeNumber' => $leaveRequest->employee->SupervisorID,
                'Message' => "Extension requested for leave #{$leaveRequest->LeaveRequestID}",
                'Status' => 'Unread',
            ]);

            // Send extension email to supervisor
            if ($leaveRequest->supervisor && $leaveRequest->supervisor->email) {
                Mail::to($leaveRequest->supervisor->email)->send(new LeaveExtensionRequestedMail($leaveExtension));
            }

            Log::info("Leave extension requested: {$leaveRequest->LeaveRequestID}");
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

            // Logic: entirely future leave = full refund. In-progress = refund remaining days.
            if ($today->lt($startDate)) {
                $refundDays = $leaveRequest->TotalDays;
            } else {
                // In progress
                $endDate = Carbon::parse($leaveRequest->EndDate);
                if ($today->lt($endDate)) {
                    $refundDays = $today->diffInDays($endDate);
                }
            }

            $leaveCancellation = LeaveCancellation::create([
                'leave_request_id' => $leaveRequest->LeaveRequestID,
                'employee_number' => auth()->user()->EmployeeNumber,
                'cancellation_reason' => $validated['Reason'],
                'status' => 'Pending',
                'refundable_days' => $refundDays,
            ]);

            Notification::create([
                'EmployeeNumber' => $leaveRequest->employee->SupervisorID,
                'Message' => "Cancellation requested for leave #{$leaveRequest->LeaveRequestID}",
                'Status' => 'Unread',
            ]);

            // Send cancellation email to supervisor
            if ($leaveRequest->supervisor && $leaveRequest->supervisor->email) {
                Mail::to($leaveRequest->supervisor->email)->send(new LeaveCancellationRequestedMail($leaveCancellation));
            }

            Log::info("Leave cancellation requested: {$leaveRequest->LeaveRequestID}");
        });

        return redirect()->back()->with('success', 'Cancellation requested successfully.');
    }

    /**
     * Archive a leave request (admin only) - Returns days to employee if annual leave
     */
    public function archive(LeaveRequest $leaveRequest)
    {
        // Check if user is admin
        if (auth()->user()->role_id !== 1) {
            return redirect()->back()->with('error', 'You do not have permission to archive leave requests.');
        }

        // If archiving an APPROVED annual leave, return the days to employee
        if (
            $leaveRequest->RequestStatus === 'Approved' &&
            $leaveRequest->leaveType->deductsFromAnnual()
        ) {
            $employee = $leaveRequest->employee;
            $employee->increment('RemainingAnnualLeaveDays', $leaveRequest->TotalDays);
            Log::info("Returned {$leaveRequest->TotalDays} annual leave days to {$employee->EmployeeNumber} (Request: {$leaveRequest->LeaveRequestID})");
        }

        $leaveRequest->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);

        AuditLog::record(
            auth()->user()->EmployeeNumber,
            'Archived leave request',
            'leave_requests',
            $leaveRequest->LeaveRequestID
        );

        Log::info("Leave request archived: {$leaveRequest->LeaveRequestID} by " . auth()->user()->EmployeeNumber);

        return redirect()->back()->with('success', 'Leave request archived successfully. Days returned to employee if applicable.');
    }

    /**
     * Restore an archived leave request (admin only) - Deducts days from employee if annual leave
     */
    public function restore(LeaveRequest $leaveRequest)
    {
        // Check if user is admin
        if (auth()->user()->role_id !== 1) {
            return redirect()->back()->with('error', 'You do not have permission to restore leave requests.');
        }

        // If restoring an APPROVED annual leave, deduct the days from employee
        if (
            $leaveRequest->RequestStatus === 'Approved' &&
            $leaveRequest->leaveType->deductsFromAnnual()
        ) {
            $employee = $leaveRequest->employee;
            $employee->decrement('RemainingAnnualLeaveDays', $leaveRequest->TotalDays);
            Log::info("Deducted {$leaveRequest->TotalDays} annual leave days from {$employee->EmployeeNumber} (Request: {$leaveRequest->LeaveRequestID})");
        }

        $leaveRequest->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);

        AuditLog::record(
            auth()->user()->EmployeeNumber,
            'Restored leave request',
            'leave_requests',
            $leaveRequest->LeaveRequestID
        );

        Log::info("Leave request restored: {$leaveRequest->LeaveRequestID} by " . auth()->user()->EmployeeNumber);

        return redirect()->back()->with('success', 'Leave request restored successfully.');
    }

    /**
     * Show archive management page (admin only)
     */
    public function showArchiveManager()
    {
        if (auth()->user()->role_id !== 1) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        }

        $year = request('year', null); // Optional year filter

        // Get ALL completed leave requests that are not archived yet (not limited by year)
        $query = LeaveRequest::with([
            'employee:EmployeeNumber,FirstName,LastName,DepartmentID,RemainingAnnualLeaveDays',
            'leaveType:LeaveTypeID,LeaveTypeName',
        ])
            ->where('is_archived', false)
            ->whereIn('RequestStatus', ['Approved', 'Rejected', 'Rejected by Admin']);

        // Apply year filter only if selected
        if ($year) {
            $query->whereYear('EndDate', $year);
        }

        $leaveRequests = $query->orderByDesc('EndDate')->paginate(20);

        $years = range(now()->year - 5, now()->year);

        return view('admin.archive_leaves', [
            'leaveRequests' => $leaveRequests,
            'selectedYear' => $year,
            'years' => $years,
        ]);
    }

    /**
     * Bulk archive leave requests (admin only)
     */
    public function bulkArchive(Request $request)
    {
        if (auth()->user()->role_id !== 1) {
            return redirect()->back()->with('error', 'You do not have permission to perform this action.');
        }

        $leaveRequestIds = $request->input('leave_request_ids', []);

        if (empty($leaveRequestIds)) {
            return redirect()->back()->with('error', 'No leave requests selected.');
        }

        $archivedCount = 0;
        $daysReturned = 0;

        foreach ($leaveRequestIds as $id) {
            $leaveRequest = LeaveRequest::find($id);

            if ($leaveRequest && !$leaveRequest->is_archived) {
                // Return days if applicable
                if (
                    $leaveRequest->RequestStatus === 'Approved' &&
                    $leaveRequest->leaveType->deductsFromAnnual()
                ) {
                    $employee = $leaveRequest->employee;
                    $employee->increment('RemainingAnnualLeaveDays', $leaveRequest->TotalDays);
                    $daysReturned += $leaveRequest->TotalDays;
                }

                // Archive the request
                $leaveRequest->update([
                    'is_archived' => true,
                    'archived_at' => now(),
                ]);

                $archivedCount++;
            }
        }

        AuditLog::record(
            auth()->user()->EmployeeNumber,
            "Bulk archived {$archivedCount} leave requests",
            'leave_requests',
            0
        );

        Log::info("Bulk archived {$archivedCount} leave requests, returned {$daysReturned} days by " . auth()->user()->EmployeeNumber);

        return redirect()->back()->with('success', "Archived {$archivedCount} leave requests and returned {$daysReturned} annual leave days.");
    }

    /**
     * View all archived leave requests with restore option
     */
    public function viewArchived()
    {
        if (auth()->user()->role_id !== 1) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        }

        $year = request('year', null);

        // Get ALL archived leave requests
        $query = LeaveRequest::with([
            'employee:EmployeeNumber,FirstName,LastName,DepartmentID',
            'leaveType:LeaveTypeID,LeaveTypeName',
        ])
            ->where('is_archived', true);

        // Apply year filter only if selected
        if ($year) {
            $query->whereYear('archived_at', $year);
        }

        $archivedLeaves = $query->orderByDesc('archived_at')->paginate(20);

        $years = range(now()->year - 5, now()->year);

        return view('admin.view_archived_leaves', [
            'archivedLeaves' => $archivedLeaves,
            'selectedYear' => $year,
            'years' => $years,
        ]);
    }

    /**
     * Employee: View full leave request history
     */
    public function employeeFullHistory()
    {
        $employee = auth()->user();
        $search = request('search', '');
        $status = request('status', '');

        $query = LeaveRequest::where('EmployeeNumber', $employee->EmployeeNumber)
            ->where('is_archived', false)
            ->with('leaveType');

        // Search filter
        if ($search) {
            $query->whereHas('leaveType', function ($q) use ($search) {
                $q->where('LeaveTypeName', 'like', "%{$search}%");
            })->orWhere('RequestStatus', 'like', "%{$search}%");
        }

        // Status filter
        if ($status) {
            $query->where('RequestStatus', $status);
        }

        $leaveRequests = $query->orderByDesc('created_at')->paginate(20);

        return view('leave_requests.employee_history', [
            'leaveRequests' => $leaveRequests,
            'search' => $search,
            'status' => $status,
            'employee' => $employee
        ]);
    }

    /**
     * Admin: View all leave requests in the system
     */
    public function adminAllRequests()
    {
        if (auth()->user()->role_id !== 1) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        }

        $search = request('search', '');
        $status = request('status', '');
        $archived = request('archived', 'active');

        $query = LeaveRequest::with('employee:EmployeeNumber,FirstName,LastName,DepartmentID', 'leaveType:LeaveTypeID,LeaveTypeName');

        // Archived filter
        if ($archived === 'active') {
            $query->where('is_archived', false);
        } elseif ($archived === 'archived') {
            $query->where('is_archived', true);
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('employee', function ($subq) use ($search) {
                    $subq->where('FirstName', 'like', "%{$search}%")
                        ->orWhere('LastName', 'like', "%{$search}%")
                        ->orWhere('EmployeeNumber', 'like', "%{$search}%");
                })
                    ->orWhereHas('leaveType', function ($subq) use ($search) {
                        $subq->where('LeaveTypeName', 'like', "%{$search}%");
                    })
                    ->orWhere('RequestStatus', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status) {
            $query->where('RequestStatus', $status);
        }

        $allRequests = $query->orderByDesc('created_at')->paginate(20);

        $statuses = ['Pending Supervisor Approval', 'Pending Admin Verification', 'Approved', 'Rejected', 'Rejected by Admin'];

        return view('admin.all_requests', [
            'allRequests' => $allRequests,
            'search' => $search,
            'status' => $status,
            'archived' => $archived,
            'statuses' => $statuses
        ]);
    }
}

