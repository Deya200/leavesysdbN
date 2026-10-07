@component('mail::message')
{{-- Header --}}
<div style="text-align: center; margin-bottom: 30px; padding: 20px 0; border-bottom: 3px solid #2E3A87;">
    <div style="font-size: 28px; font-weight: bold; color: #2E3A87; margin-bottom: 5px;">
        ABC Leave Management System
    </div>
    <p style="color: #666; font-size: 13px; margin: 0;">Leave Extension Request Notification</p>
</div>

Hello {{ $supervisor->FirstName }},

A leave extension request has been submitted and requires your review.

{{-- Extension Details --}}
@component('mail::panel')
**Employee:** {{ $employee->FirstName }} {{ $employee->LastName }}
**Employee Number:** {{ $employee->EmployeeNumber }}
**Original Leave Request ID:** #{{ $leaveRequest->LeaveRequestID }}
**Leave Type:** {{ $leaveRequest->leaveType->LeaveTypeName }}
**Extension Days Requested:** {{ $leaveExtension->extension_days }} day(s)
**Original End Date:** {{ \Carbon\Carbon::parse($leaveExtension->original_end_date)->format('M d, Y') }}
**Requested New End Date:** {{ \Carbon\Carbon::parse($leaveExtension->requested_end_date)->format('M d, Y') }}
@endcomponent

{{-- Extension Reason Box --}}
<div style="background-color: #e1f5fe; border-left: 4px solid #03A9F4; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #01579B;">Reason for Extension:</strong>
    <p style="margin: 10px 0 0 0; color: #555;">{{ $leaveExtension->reason }}</p>
</div>

<div style="background-color: #f0f4ff; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #1976D2;">Status:</strong> <span style="color: #2E3A87;">{{ $leaveExtension->status }}</span>
</div>

@component('mail::button', ['url' => url('/admin/leave-extensions'), 'color' => 'primary'])
Review Extension Request
@endcomponent

Please review and approve or reject this extension request.

---

<div style="text-align: center; color: #999; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px;">
    <p style="margin: 5px 0;">
        © {{ now()->year }} ABC Leave Management System - All Rights Reserved
    </p>
</div>

Thanks,
ABC Leave Management System
@endcomponent
