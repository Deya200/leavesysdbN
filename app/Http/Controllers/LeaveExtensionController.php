<?php

namespace App\Http\Controllers;

use App\Models\LeaveExtension;
use App\Models\Notification;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveExtensionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Supervisors view extensions
        $extensions = LeaveExtension::with(['leaveRequest', 'employee'])
            ->whereHas('leaveRequest', function ($query) use ($user) {
                $query->where('SupervisorID', $user->EmployeeNumber);
            })
            ->where('status', 'Pending')
            ->latest()
            ->paginate(10);
            
        return view('leave_extensions.index', compact('extensions'));
    }

    public function approve(LeaveExtension $leaveExtension)
    {
        return DB::transaction(function () use ($leaveExtension) {
            $leaveExtension->update([
                'status' => 'Approved',
                'supervisor_approval' => true,
            ]);

            // Update the actual leave request
            $leaveRequest = $leaveExtension->leaveRequest;

            // Ensure requested end date exists
            $newEnd = $leaveExtension->requested_end_date ?? null;
            if (is_null($newEnd)) {
                Log::error("LeaveExtension id {$leaveExtension->id} missing requested_end_date");
                return redirect()->back()->with('error', 'Requested end date is missing for this extension.');
            }

            $leaveRequest->update([
                'EndDate' => $newEnd,
                'TotalDays' => $leaveRequest->TotalDays + (int) $leaveExtension->extension_days,
            ]);

            // Determine if Admin approval is needed? 
            // For now, let's assume Supervisor approval is sufficient or it goes to Admin next.
            // If we follow main flow: Pending Admin Verification?
            // Let's keep it simple: Supervisor approval finalizes it for now, 
            // OR if strictly following flow, maybe set to Pending Admin Verification if needed.
            
            // Deduct from Balance if needed (Admin usually does this on verification)
            // If we update TotalDays, the Admin Verification logic will handle deduction difference?
            // No, simplified: Supervisor approves extension.
            
            // Note: If using LeaveRequestController::calculateRemaining, it reads TotalDays.
            // But deduction happens on Admin Verification usually.
            // Let's assume extensions require Admin verification too.
            
            $leaveRequest->update(['RequestStatus' => 'Pending Admin Verification']); // Re-verify for the extra days

            Notification::create([
                'EmployeeNumber' => $leaveExtension->employee_number,
                'Message' => "Extension request approved by Supervisor. Pending Admin Verification.",
                'Status' => 'Unread',
            ]);

            Log::info("Extension approved: {$leaveExtension->id}");
            
            return redirect()->back()->with('success', 'Extension approved. Sent to Admin.');
        });
    }

    public function reject(Request $request, LeaveExtension $leaveExtension)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $leaveExtension->update([
            'status' => 'Rejected',
            // 'rejection_reason' => $validated['reason'], // If column exists, otherwise log it or use status
        ]);

        Notification::create([
            'EmployeeNumber' => $leaveExtension->employee_number,
            'Message' => "Extension request rejected: {$validated['reason']}",
            'Status' => 'Unread',
        ]);

        return redirect()->back()->with('success', 'Extension rejected.');
    }
}
