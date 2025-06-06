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
    ];

    /**
     * Define attribute casting to ensure proper handling of EmployeeNumber & SupervisorID.
     */
    protected $casts = [
        'EmployeeNumber' => 'string',
        'SupervisorID'   => 'string',
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
}
