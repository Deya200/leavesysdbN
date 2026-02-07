<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LeaveAppeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_request_id',
        'employee_number',
        'appeal_reason',
        'status',
        'reviewer_id',
        'review_reason',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: The leave request being appealed
     */
    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class, 'leave_request_id', 'LeaveRequestID');
    }

    /**
     * Relationship: Employee who submitted the appeal
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_number', 'EmployeeNumber');
    }

    /**
     * Relationship: Reviewer (supervisor/admin) who reviewed the appeal
     */
    public function reviewer()
    {
        return $this->belongsTo(Employee::class, 'reviewer_id', 'EmployeeNumber');
    }

    /**
     * Scope: Filter pending appeals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope: Filter approved appeals
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    /**
     * Scope: Filter rejected appeals
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    /**
     * Check if appeal is still pending
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Approve the appeal
     */
    public function approve(string $reviewerId, ?string $reason = null): bool
    {
        $this->status = 'Approved';
        $this->reviewer_id = $reviewerId;
        $this->review_reason = $reason;
        $this->reviewed_at = Carbon::now();
        return $this->save();
    }

    /**
     * Reject the appeal
     */
    public function reject(string $reviewerId, string $reason): bool
    {
        $this->status = 'Rejected';
        $this->reviewer_id = $reviewerId;
        $this->review_reason = $reason;
        $this->reviewed_at = Carbon::now();
        return $this->save();
    }
}
