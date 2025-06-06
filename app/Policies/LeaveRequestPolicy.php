<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isSupervisor();
    }

    public function view(User $user, LeaveRequest $leaveRequest)
    {
        return $user->EmployeeNumber === $leaveRequest->EmployeeNumber
            || $user->isAdmin()
            || $user->isSupervisor();
    }

    public function create(User $user)
    {
        return $user->isEmployee();
    }

    public function update(User $user, LeaveRequest $leaveRequest)
    {
        return $user->EmployeeNumber === $leaveRequest->EmployeeNumber
            && in_array($leaveRequest->RequestStatus, [
                'Pending Supervisor Approval',
                'Pending Admin Verification'
            ]);
    }

    public function delete(User $user, LeaveRequest $leaveRequest)
    {
        return $user->isAdmin();
    }

    public function supervisorApprove(User $user, LeaveRequest $leaveRequest)
    {
        return $user->isSupervisor()
            && $user->EmployeeNumber === $leaveRequest->employee->SupervisorID
            && $leaveRequest->RequestStatus === 'Pending Supervisor Approval';
    }

    public function adminApprove(User $user, LeaveRequest $leaveRequest)
    {
        return $user->isAdmin()
            && $leaveRequest->RequestStatus === 'Pending Admin Verification';
    }

    public function supervisorReject(User $user, LeaveRequest $leaveRequest)
    {
        return $this->supervisorApprove($user, $leaveRequest);
    }
}
