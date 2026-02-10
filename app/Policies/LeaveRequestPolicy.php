<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\Employee;

class LeaveRequestPolicy
{
    public function viewAny(Employee $user)
    {
        return $user->isAdmin() || $user->isSupervisor();
    }

    public function view(Employee $user, LeaveRequest $leaveRequest)
    {
        return $user->EmployeeNumber === $leaveRequest->EmployeeNumber
            || $user->isAdmin()
            || $user->isSupervisor();
    }

    public function create(Employee $user)
    {
        return $user->isEmployee(); // Or ensureDefaultRole logic
    }

    public function update(Employee $user, LeaveRequest $leaveRequest)
    {
        return $user->EmployeeNumber === $leaveRequest->EmployeeNumber
            && in_array(strtolower($leaveRequest->RequestStatus), [
                'pending supervisor approval',
                'pending admin verification'
            ]);
    }

    public function delete(Employee $user, LeaveRequest $leaveRequest)
    {
        return $user->isAdmin();
    }

    public function supervisorApprove(Employee $user, LeaveRequest $leaveRequest)
    {
        // Allow admins to approve at any stage, or supervisors for their direct reports
        if ($user->isAdmin()) {
            return strcasecmp($leaveRequest->RequestStatus, 'Pending Supervisor Approval') === 0;
        }
        
        return $user->isSupervisor()
            && $user->EmployeeNumber === $leaveRequest->employee->SupervisorID
            && strcasecmp($leaveRequest->RequestStatus, 'Pending Supervisor Approval') === 0;
    }

    public function adminApprove(Employee $user, LeaveRequest $leaveRequest)
    {
        return $user->isAdmin()
            && strcasecmp($leaveRequest->RequestStatus, 'Pending Admin Verification') === 0;
    }

    public function supervisorReject(Employee $user, LeaveRequest $leaveRequest)
    {
        // Allow admins to reject at any stage, or supervisors for their direct reports
        if ($user->isAdmin()) {
            return strcasecmp($leaveRequest->RequestStatus, 'Pending Supervisor Approval') === 0;
        }
        
        return $user->isSupervisor()
            && $user->EmployeeNumber === $leaveRequest->employee->SupervisorID
            && strcasecmp($leaveRequest->RequestStatus, 'Pending Supervisor Approval') === 0;
    }

    public function adminReject(Employee $user, LeaveRequest $leaveRequest)
    {
        return $user->isAdmin()
            && strcasecmp($leaveRequest->RequestStatus, 'Pending Admin Verification') === 0;
    }
}
