<?php

namespace App\Http\Controllers;

use App\Models\LeaveAppeal;
use App\Models\LeaveRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveAppealController extends Controller
{
    public function index()
    {
        // For supervisors/admins to view appeals
        $user = auth()->user();
        
        // If supervisor, show appeals from their subordinates
        // If admin, show all? Or admin verification pending?
        
        // For simplicity, let's assume supervisors handle appeals for now, matching the LeaveRequest flow
        $appeals = LeaveAppeal::with(['leaveRequest', 'employee'])
            ->whereHas('leaveRequest', function ($query) use ($user) {
                $query->where('SupervisorID', $user->EmployeeNumber);
            })
            ->where('status', 'Pending')
            ->latest()
            ->paginate(10);
            
        return view('leave_appeals.index', compact('appeals'));
    }

    public function approve(LeaveAppeal $leaveAppeal)
    {
        // Authorization check needed
        
        return DB::transaction(function () use ($leaveAppeal) {
            $leaveAppeal->update([
                'status' => 'Approved',
                'reviewer_id' => auth()->user()->EmployeeNumber,
            ]);

            // Reactivate the leave request
            $leaveAppeal->leaveRequest->update([
                'RequestStatus' => 'Pending Supervisor Approval', // Reset to pending for re-evaluation? Or Pending Admin?
                // Usually an appeal means "Please look at this again". So back to Pending Supervisor.
                'SupervisorApproval' => false,
                'SupervisorRejectionReason' => null, // Clear rejection
            ]);

            Notification::create([
                'EmployeeNumber' => $leaveAppeal->employee_number,
                'Message' => "Your appeal for leave #{$leaveAppeal->leave_request_id} has been approved. Request is under review.",
                'Status' => 'Unread',
            ]);

            Log::info("Appeal approved: {$leaveAppeal->id}");
            
            return redirect()->back()->with('success', 'Appeal approved. Leave request is now pending review.');
        });
    }

    public function reject(Request $request, LeaveAppeal $leaveAppeal)
    {
        $validated = $request->validate([
            'review_reason' => 'required|string|max:255',
        ]);

        $leaveAppeal->update([
            'status' => 'Rejected',
            'reviewer_id' => auth()->user()->EmployeeNumber,
            'review_reason' => $validated['review_reason'],
        ]);
        
        $leaveAppeal->leaveRequest->update([
            'RequestStatus' => 'Rejected', // Should stay rejected
        ]);

        Notification::create([
            'EmployeeNumber' => $leaveAppeal->employee_number,
            'Message' => "Appeal rejected: {$validated['review_reason']}",
            'Status' => 'Unread',
        ]);

        return redirect()->back()->with('success', 'Appeal rejected.');
    }
}
