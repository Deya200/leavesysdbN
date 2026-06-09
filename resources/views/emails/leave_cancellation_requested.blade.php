@component('mail::message')
{{-- Header --}}
<div style="text-align: center; margin-bottom: 30px; padding: 20px 0; border-bottom: 3px solid #2E3A87;">
    <div style="font-size: 28px; font-weight: bold; color: #2E3A87; margin-bottom: 5px;">
        ABC Leave Management System
    </div>
    <p style="color: #666; font-size: 13px; margin: 0;">Leave Cancellation Request Notification</p>
</div>

Hello {{ $supervisor->FirstName }},

A leave cancellation request has been submitted and requires your approval.

{{-- Cancellation Details --}}
@component('mail::panel')
**Employee:** {{ $employee->FirstName }} {{ $employee->LastName }}
**Employee Number:** {{ $employee->EmployeeNumber }}
**Leave Request ID:** #{{ $leaveRequest->LeaveRequestID }}
**Leave Type:** {{ $leaveRequest->leaveType->LeaveTypeName }}
**Leave Dates:** {{ \Carbon\Carbon::parse($leaveRequest->StartDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($leaveRequest->EndDate)->format('M d, Y') }}
**Refundable Days:** {{ $leaveCancellation->refundable_days }} day(s)
@endcomponent

{{-- Cancellation Reason Box --}}
<div style="background-color: #ffe0b2; border-left: 4px solid #FF9800; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #E65100;">Cancellation Reason:</strong>
    <p style="margin: 10px 0 0 0; color: #555;">{{ $leaveCancellation->cancellation_reason }}</p>
</div>

<div style="background-color: #f0f4ff; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #1976D2;">Status:</strong> <span style="color: #2E3A87;">{{ $leaveCancellation->status }}</span>
</div>

@component('mail::button', ['url' => url('/admin/leave-cancellations'), 'color' => 'primary'])
Review Cancellation
@endcomponent

Please review and approve or reject this cancellation request.

---

<div style="text-align: center; color: #999; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px;">
    <p style="margin: 5px 0;">
        © {{ now()->year }} ABC Leave Management System - All Rights Reserved
    </p>
</div>

Thanks,
ABC Leave Management System
@endcomponent
