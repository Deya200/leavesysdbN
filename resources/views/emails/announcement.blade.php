@component('mail::message')
{{-- Header --}}
<div style="text-align: center; margin-bottom: 30px; padding: 20px 0; border-bottom: 3px solid #2E3A87;">
    <div style="font-size: 28px; font-weight: bold; color: #2E3A87; margin-bottom: 5px;">
        ABC Leave Management System
    </div>
    <p style="color: #666; font-size: 13px; margin: 0;">Company Announcement</p>
</div>

Hello {{ $employeeName }},

We have an important announcement for you.

{{-- Announcement --}}
<div style="background-color: #f5f5f5; border-radius: 4px; padding: 20px; margin: 20px 0;">
    <h2 style="color: #2E3A87; margin-top: 0; margin-bottom: 15px;">{{ $announcement->title }}</h2>
    <div style="line-height: 1.6; color: #555;">
        {!! nl2br(e($announcement->description)) !!}
    </div>
</div>

<div style="background-color: #e3f2fd; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #1976D2;">Posted:</strong> {{ $announcement->created_at->format('M d, Y \a\t g:i A') }}
</div>

@component('mail::button', ['url' => url('/dashboards/employee'), 'color' => 'primary'])
View More
@endcomponent

Thank you for your attention to this announcement.

---

<div style="text-align: center; color: #999; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px;">
    <p style="margin: 5px 0;">
        © {{ now()->year }} ABC Leave Management System - All Rights Reserved
    </p>
</div>

Thanks,
ABC Leave Management System
@endcomponent
