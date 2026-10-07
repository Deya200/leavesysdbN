@component('mail::message')
{{-- Header --}}
<div style="text-align: center; margin-bottom: 30px; padding: 20px 0; border-bottom: 3px solid #2E3A87;">
    <div style="font-size: 28px; font-weight: bold; color: #2E3A87; margin-bottom: 5px;">
        ABC Leave Management System
    </div>
    <p style="color: #666; font-size: 13px; margin: 0;">Leave Cancellation Approved</p>
</div>

Hello {{ $employee->FirstName }},

Your leave cancellation request has been **approved**.

{{-- Cancellation Details --}}
@component('mail::panel')
**Leave Type:** {{ $leaveRequest->leaveType->LeaveTypeName }}
**Leave Dates:** {{ \Carbon\Carbon::parse($leaveRequest->StartDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($leaveRequest->EndDate)->format('M d, Y') }}
**Refunded Days:** {{ $leaveCancellation->refundable_days }} day(s)
@endcomponent

{{-- Success Box --}}
<div style="background-color: #c8e6c9; border-left: 4px solid #4CAF50; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #388E3C;">✓ Your cancellation has been approved.</strong>
    <p style="margin: 10px 0 0 0; color: #555;">{{ $leaveCancellation->refundable_days }} day(s) have been returned to your leave balance.</p>
</div>

@component('mail::button', ['url' => url('/dashboards/employee'), 'color' => 'success'])
View Your Dashboard
@endcomponent

If you have any questions, please contact your HR department.

---

<div style="text-align: center; color: #999; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px;">
    <p style="margin: 5px 0;">
        © {{ now()->year }} ABC Leave Management System - All Rights Reserved
    </p>
</div>

Thanks,
ABC Leave Management System
@endcomponent
