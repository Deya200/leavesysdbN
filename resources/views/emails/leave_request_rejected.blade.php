@component('mail::message')
{{-- Header --}}
<div style="text-align: center; margin-bottom: 30px; padding: 20px 0; border-bottom: 3px solid #2E3A87;">
    <div style="font-size: 28px; font-weight: bold; color: #2E3A87; margin-bottom: 5px;">
        ABC Leave Management System
    </div>
    <p style="color: #666; font-size: 13px; margin: 0;">Leave Request Status Update</p>
</div>

Hello {{ $employee->FirstName }},

We regret to inform you that your leave request has been **rejected**.

{{-- Leave Details --}}
@component('mail::section')
**Leave Type:** {{ $leaveType->LeaveTypeName }}
**Total Days:** {{ $leaveRequest->TotalDays }} day(s)
**Start Date:** {{ \Carbon\Carbon::parse($leaveRequest->StartDate)->format('M d, Y (l)') }}
**End Date:** {{ \Carbon\Carbon::parse($leaveRequest->EndDate)->format('M d, Y (l)') }}
**Rejected By:** {{ $rejectedBy }}
@endcomponent

{{-- Rejection Reason Box --}}
<div style="background-color: #ffebee; border-left: 4px solid #f44336; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #d32f2f;">Reason for Rejection:</strong>
    <p style="margin: 10px 0 0 0; color: #555;">{{ $rejectionReason }}</p>
</div>

{{-- Appeal Information --}}
<div style="background-color: #f0f4ff; border-left: 4px solid #2E3A87; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #2E3A87;">📌 Important:</strong>
    <p style="margin: 10px 0 0 0; font-size: 14px; color: #555;">
        You have the option to appeal this decision within 7 days. Please contact your supervisor or HR department if you wish to proceed with an appeal.
    </p>
</div>

@component('mail::button', ['url' => url('/dashboards/employee'), 'color' => 'secondary'])
View Full Details
@endcomponent

For more information or to discuss this decision, please reach out to your supervisor or HR department.

---

<div style="text-align: center; color: #999; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px;">
    <p style="margin: 5px 0;">
        © {{ now()->year }} ABC Leave Management System - All Rights Reserved
    </p>
</div>

Thanks,
ABC Leave Management System
@endcomponent
