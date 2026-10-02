<?php

namespace Tests\Feature;

use App\Models\LeaveRequest;
use Tests\TestCase;

class LeaveRequestAdminActionStatusTest extends TestCase
{
    public function test_leave_requests_pending_admin_verification_are_ready_for_admin_action(): void
    {
        $leaveRequest = new LeaveRequest(['RequestStatus' => 'Pending Admin Verification']);

        $this->assertTrue($leaveRequest->isAwaitingAdminAction());
    }

    public function test_leave_requests_pending_admin_approval_are_ready_for_admin_action(): void
    {
        $leaveRequest = new LeaveRequest(['RequestStatus' => 'Pending Admin Approval']);

        $this->assertTrue($leaveRequest->isAwaitingAdminAction());
    }
}
