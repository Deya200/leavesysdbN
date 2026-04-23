@component('mail::message')
{{-- Header --}}
<div style="text-align: center; margin-bottom: 30px; padding: 20px 0; border-bottom: 3px solid #2E3A87;">
    <div style="font-size: 28px; font-weight: bold; color: #2E3A87; margin-bottom: 5px;">
        ABC Leave Management System
    </div>
    <p style="color: #666; font-size: 13px; margin: 0;">Employee Leave Request Notification</p>
</div>

Hello {{ $supervisor->FirstName }},

A new leave request has been submitted and requires your review.

{{-- Employee Information --}}
@component('mail::section')
**Employee:** {{ $employee->FirstName }} {{ $employee->LastName }}
**Employee Number:** {{ $employee->EmployeeNumber }}
**Leave Type:** {{ $leaveType->LeaveTypeName }}
**Total Days:** {{ $leaveRequest->TotalDays }} day(s)
**Start Date:** {{ \Carbon\Carbon::parse($leaveRequest->StartDate)->format('M d, Y (l)') }}
**End Date:** {{ \Carbon\Carbon::parse($leaveRequest->EndDate)->format('M d, Y (l)') }}
**Reason:** {{ $leaveRequest->Reason }}
@endcomponent

{{-- Status Box --}}
<div style="background-color: #e3f2fd; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #1976D2;">Current Status:</strong> <span style="color: #2E3A87;">{{ $leaveRequest->RequestStatus }}</span>
</div>

@component('mail::button', ['url' => url('/admin/leave-requests'), 'color' => 'primary'])
Review Request
@endcomponent

Please review this leave request and take appropriate action.

---

<div style="text-align: center; color: #999; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px;">
    <p style="margin: 5px 0;">
        © {{ now()->year }} ABC Leave Management System - All Rights Reserved
    </p>
</div>

Thanks,
ABC Leave Management System
@endcomponent
