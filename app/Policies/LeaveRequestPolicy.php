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
        // Only pending supervisor approval can be handled here.
        if (strcasecmp($leaveRequest->RequestStatus, 'Pending Supervisor Approval') !== 0) {
            return false;
        }

        // Admin can approve any pending supervisor request.
        if ($user->isAdmin()) {
            return true;
        }

        // Supervisors can approve their direct reports or any employee in their department.
        $employee = $leaveRequest->employee;
        if (!$employee) {
            return false;
        }

        return $user->isSupervisor()
            && (
                $user->EmployeeNumber === $employee->SupervisorID
                || $user->DepartmentID === $employee->DepartmentID
            );
    }

    public function adminApprove(Employee $user, LeaveRequest $leaveRequest)
    {
        return $user->isAdmin()
            && strcasecmp($leaveRequest->RequestStatus, 'Pending Admin Verification') === 0;
    }

    public function supervisorReject(Employee $user, LeaveRequest $leaveRequest)
    {
        if (strcasecmp($leaveRequest->RequestStatus, 'Pending Supervisor Approval') !== 0) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        $employee = $leaveRequest->employee;
        if (!$employee) {
            return false;
        }

        return $user->isSupervisor()
            && (
                $user->EmployeeNumber === $employee->SupervisorID
                || $user->DepartmentID === $employee->DepartmentID
            );
    }

    public function adminReject(Employee $user, LeaveRequest $leaveRequest)
    {
        return $user->isAdmin()
            && strcasecmp($leaveRequest->RequestStatus, 'Pending Admin Verification') === 0;
    }
}
