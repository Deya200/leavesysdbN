@component('mail::message')
{{-- Header --}}
<div style="text-align: center; margin-bottom: 30px; padding: 20px 0; border-bottom: 3px solid #2E3A87;">
    <div style="font-size: 28px; font-weight: bold; color: #2E3A87; margin-bottom: 5px;">
        ABC Leave Management System
    </div>
    <p style="color: #666; font-size: 13px; margin: 0;">Leave Request Approved</p>
</div>

Hello {{ $employee->FirstName }},

Great news! Your leave request has been **approved**.

{{-- Leave Details --}}
@component('mail::panel')
**Leave Type:** {{ $leaveType->LeaveTypeName }}
**Total Days:** {{ $leaveRequest->TotalDays }} day(s)
**Start Date:** {{ \Carbon\Carbon::parse($leaveRequest->StartDate)->format('M d, Y (l)') }}
**End Date:** {{ \Carbon\Carbon::parse($leaveRequest->EndDate)->format('M d, Y (l)') }}
@if($approvalNote)
**Approval Note:** {{ $approvalNote }}
@endif
@endcomponent

{{-- Success Box --}}
<div style="background-color: #c8e6c9; border-left: 4px solid #4CAF50; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #388E3C;">✓ Your leave has been approved and is now active.</strong>
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
