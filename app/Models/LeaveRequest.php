<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'leave_requests';
    protected $primaryKey = 'LeaveRequestID';
    public $timestamps = true;

    protected $fillable = [
        'EmployeeNumber',
        'SupervisorID', // Ensure SupervisorID is mass assignable.
        'LeaveTypeID',
        'StartDate',
        'EndDate',
        'TotalDays',
        'RequestStatus',
        'SupervisorApproval',
        'AdminApproval', // Changed HRApproval to AdminApproval for clarity.
        'RejectionReason',
        'Reason',
        'SupervisorRejectionReason',
        'AdminRejectionReason',
        'SupervisorApprovalNote',
        'AdminApprovalNote',
        'can_be_appealed',
        'appeal_deadline',
        'is_active',
        'is_cancelled',
        'carried_over_days',
        'financial_year',
    ];

    /**
     * Define attribute casting to ensure proper handling of EmployeeNumber & SupervisorID.
     */
    protected $casts = [
        'EmployeeNumber' => 'string',
        'SupervisorID'   => 'string',
        'can_be_appealed' => 'boolean',
        'appeal_deadline' => 'datetime',
        'is_active' => 'boolean',
        'is_cancelled' => 'boolean',
        'StartDate' => 'date',
        'EndDate' => 'date',
    ];

    /**
     * Boot method to automatically set SupervisorID when creating
     * a new leave request.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($leaveRequest) {
            // Ensure SupervisorID is assigned before creating the request
            if (empty($leaveRequest->SupervisorID) && Auth::check()) {
                $employee = Auth::user();

                if (!empty($employee->SupervisorID)) {
                    Log::info("Auto-assigning SupervisorID {$employee->SupervisorID} for Employee {$employee->EmployeeNumber}");
                    $leaveRequest->SupervisorID = (string) $employee->SupervisorID;
                } else {
                    Log::error("SupervisorID missing for Employee: {$employee->EmployeeNumber}");
                }
            }
        });
    }

    /**
     * Relationship: Employee submitting the leave request.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    /**
     * Relationship: Supervisor responsible for approving the leave request.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'SupervisorID', 'EmployeeNumber');
    }

    /**
     * Relationship: Leave Type associated with the leave request.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'LeaveTypeID', 'LeaveTypeID');
    }

    /**
     * Relationship: Appeals for this leave request
     */
    public function appeals()
    {
        return $this->hasMany(LeaveAppeal::class, 'leave_request_id', 'LeaveRequestID');
    }

    /**
     * Relationship: Extensions for this leave request
     */
    public function extensions()
    {
        return $this->hasMany(LeaveExtension::class, 'leave_request_id', 'LeaveRequestID');
    }

    /**
     * Relationship: Cancellation for this leave request
     */
    public function cancellation()
    {
        return $this->hasOne(LeaveCancellation::class, 'leave_request_id', 'LeaveRequestID');
    }

    /**
     * Check if this leave request can be appealed
     */
    public function canBeAppealed(): bool
    {
        if (!$this->can_be_appealed || $this->RequestStatus !== 'Rejected') {
            return false;
        }

        if ($this->appeal_deadline && now()->gt($this->appeal_deadline)) {
            return false;
        }

        // Check if already appealed
        if ($this->appeals()->where('status', '!=', 'Rejected')->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Check if this leave can be extended
     */
    public function canBeExtended(): bool
    {
        // Must be approved and active
        if ($this->RequestStatus !== 'Approved' || !$this->is_active || $this->is_cancelled) {
            return false;
        }

        // Cannot extend if already has pending extension
        if ($this->extensions()->where('status', 'Pending')->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Check if this leave can be cancelled
     */
    public function canBeCancelled(): bool
    {
        // Must be approved and not already cancelled
        if ($this->RequestStatus !== 'Approved' || $this->is_cancelled) {
            return false;
        }

        // Cannot cancel if already has pending cancellation
        if ($this->cancellation && $this->cancellation->status === 'Pending') {
            return false;
        }

        // Cannot cancel if leave has already ended
        if (now()->gt(\Carbon\Carbon::parse($this->EndDate))) {
            return false;
        }

        return true;
    }

    /**
     * Get remaining days in leave period
     */
    public function getRemainingDays(): int
    {
        $today = \Carbon\Carbon::today();
        $endDate = \Carbon\Carbon::parse($this->EndDate);

        if ($today->gt($endDate)) {
            return 0;
        }

        return $today->diffInDays($endDate) + 1;
    }

    /**
     * Mark leave as active
     */
    public function markAsActive(): bool
    {
        if ($this->RequestStatus === 'Approved' && !$this->is_active) {
            $this->is_active = true;
            return $this->save();
        }
        return false;
    }
}
