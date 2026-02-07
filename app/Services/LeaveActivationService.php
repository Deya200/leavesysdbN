<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LeaveActivationService
{
    /**
     * Activate approved leaves that start today.
     * This allows features like 'Extension' and 'Cancellation' (if policy allows cancellation of active leave).
     */
    public function activateLeaves()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        Log::info("Running leave activation for {$today}");

        $leavesToActivate = LeaveRequest::where('StartDate', $today)
            ->where('RequestStatus', 'Approved')
            ->where('is_active', false)
            ->get();

        foreach ($leavesToActivate as $leave) {
            $leave->update(['is_active' => true]);

            Notification::create([
                'EmployeeNumber' => $leave->EmployeeNumber,
                'Message' => "Your leave request #{$leave->id} has started today.",
                'Status' => 'Unread',
            ]);
            
            Log::info("Activated leave request #{$leave->id}");
        }
        
        // Also could mark ended leaves as inactive?
        // Let's optimize: Deactivate leaves that ended yesterday?
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $leavesToDeactivate = LeaveRequest::where('EndDate', $yesterday)
            ->where('is_active', true)
            ->get();
            
        foreach ($leavesToDeactivate as $leave) {
            $leave->update(['is_active' => false]);
            Log::info("Deactivated ended leave request #{$leave->id}");
        }
        
        Log::info("Leave activation process completed.");
    }
}
