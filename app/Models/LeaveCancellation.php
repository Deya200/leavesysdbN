<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LeaveCancellation extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_request_id',
        'employee_number',
        'cancellation_reason',
        'status',
        'cancelled_days',
        'refunded_days',
        'approved',
        'reviewer_id',
        'approved_at',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: The leave request being cancelled
     */
    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class, 'leave_request_id', 'LeaveRequestID');
    }

    /**
     * Relationship: Employee requesting cancellation
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_number', 'EmployeeNumber');
    }

    /**
     * Relationship: Reviewer who approved the cancellation
     */
    public function reviewer()
    {
        return $this->belongsTo(Employee::class, 'reviewer_id', 'EmployeeNumber');
    }

    /**
     * Scope: Filter pending cancellations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope: Filter approved cancellations
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    /**
     * Check if cancellation is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Approve the cancellation and calculate refund
     */
    public function approveCancellation(string $reviewerId): bool
    {
        $this->approved = true;
        $this->status = 'Approved';
        $this->reviewer_id = $reviewerId;
        $this->approved_at = Carbon::now();
        
        // Calculate refunded days (only future days)
        $leaveRequest = $this->leaveRequest;
        $today = Carbon::today();
        $startDate = Carbon::parse($leaveRequest->StartDate);
        $endDate = Carbon::parse($leaveRequest->EndDate);
        
        if ($today->lt($startDate)) {
            // Leave hasn't started - refund all days
            $this->refunded_days = $leaveRequest->TotalDays;
            $this->cancelled_days = $leaveRequest->TotalDays;
        } elseif ($today->lte($endDate)) {
            // Leave is ongoing - refund remaining days
            $this->refunded_days = $today->diffInDays($endDate);
            $this->cancelled_days = $leaveRequest->TotalDays;
        } else {
            // Leave has ended - no refund
            $this->refunded_days = 0;
            $this->cancelled_days = 0;
        }
        
        return $this->save();
    }

    /**
     * Reject the cancellation
     */
    public function reject(string $reviewerId): bool
    {
        $this->status = 'Rejected';
        $this->reviewer_id = $reviewerId;
        return $this->save();
    }
}
