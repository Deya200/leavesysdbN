<?php

namespace App\Http\Controllers;

use App\Models\LeaveCancellation;
use App\Models\Notification;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveCancellationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $cancellations = LeaveCancellation::with(['leaveRequest', 'employee'])
            ->whereHas('leaveRequest', function ($query) use ($user) {
                $query->where('SupervisorID', $user->EmployeeNumber);
            })
            ->where('status', 'Pending')
            ->latest()
            ->paginate(10);
            
        return view('leave_cancellations.index', compact('cancellations'));
    }

    public function approve(LeaveCancellation $leaveCancellation)
    {
        return DB::transaction(function () use ($leaveCancellation) {
            $leaveCancellation->update([
                'status' => 'Approved',
            ]);

            // Update Leave Request
            $leaveRequest = $leaveCancellation->leaveRequest;
            $leaveRequest->update([
                'RequestStatus' => 'Cancelled',
                'is_cancelled' => true,
            ]);

            // Refund logic
            // Only refund if it was deducted (LeaveType deductsFromAnnual)
            if ($leaveRequest->leaveType->deductsFromAnnual()) {
                $employee = $leaveRequest->employee;
                $refundDays = $leaveCancellation->refundable_days;
                
                if ($refundDays > 0) {
                    $employee->increment('RemainingAnnualLeaveDays', $refundDays);
                    
                    Log::info("Refunded {$refundDays} days to Employee {$employee->EmployeeNumber}");
                }
            }

            Notification::create([
                'EmployeeNumber' => $leaveCancellation->employee_number,
                'Message' => "Cancellation approved. {$leaveCancellation->refundable_days} days refunded.",
                'Status' => 'Unread',
            ]);

            return redirect()->back()->with('success', 'Cancellation approved and days refunded.');
        });
    }
}
