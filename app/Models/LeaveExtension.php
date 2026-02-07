<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LeaveExtension extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_request_id',
        'employee_number',
        'original_end_date',
        'requested_end_date',
        'extension_days',
        'reason',
        'status',
        'supervisor_approval',
        'admin_approval',
        'reviewer_id',
        'rejection_reason',
    ];

    protected $casts = [
        'original_end_date' => 'date',
        'requested_end_date' => 'date',
        'supervisor_approval' => 'boolean',
        'admin_approval' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: The leave request being extended
     */
    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class, 'leave_request_id', 'LeaveRequestID');
    }

    /**
     * Relationship: Employee requesting extension
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_number', 'EmployeeNumber');
    }

    /**
     * Relationship: Reviewer who processed the extension
     */
    public function reviewer()
    {
        return $this->belongsTo(Employee::class, 'reviewer_id', 'EmployeeNumber');
    }

    /**
     * Scope: Filter pending extensions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope: Filter approved extensions
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    /**
     * Check if extension is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Approve the extension (supervisor)
     */
    public function supervisorApprove(string $supervisorId): bool
    {
        $this->supervisor_approval = true;
        $this->status = 'Pending Admin Approval';
        $this->reviewer_id = $supervisorId;
        return $this->save();
    }

    /**
     * Approve the extension (admin) - final approval
     */
    public function adminApprove(string $adminId): bool
    {
        $this->admin_approval = true;
        $this->status = 'Approved';
        $this->reviewer_id = $adminId;
        return $this->save();
    }

    /**
     * Reject the extension
     */
    public function reject(string $reviewerId, string $reason): bool
    {
        $this->status = 'Rejected';
        $this->reviewer_id = $reviewerId;
        $this->rejection_reason = $reason;
        return $this->save();
    }
}
