@component('mail::message')
{{-- Header with Logo --}}
<div style="text-align: center; margin-bottom: 40px; padding: 20px 0; border-bottom: 3px solid #2E3A87;">
    <div style="margin-bottom: 15px;">
        <img src="{{ asset('logo3.png') }}" alt="ABC Leave Management System Logo" style="max-width: 150px; height: auto;">
    </div>
    <div style="font-size: 32px; font-weight: bold; color: #2E3A87; margin-bottom: 5px;">
        ABC Leave Management System
    </div>
    <p style="color: #666; font-size: 14px; margin: 0;">Employee Management Portal</p>
</div>

{{-- Main Content --}}
{{ $greeting }}

A password reset request has been initiated for your account.

{{-- Admin and Time Information --}}
<div style="background-color: #f0f4ff; padding: 20px; border-left: 4px solid #2E3A87; border-radius: 4px; margin: 30px 0;">
    <table style="width: 100%; font-size: 14px;">
        <tr>
            <td style="padding: 8px 0; color: #555;">
                <strong>Initiated by:</strong>
            </td>
            <td style="padding: 8px 0; color: #2E3A87; font-weight: bold; text-align: right;">
                {{ $adminName ?? 'System Administrator' }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #555;">
                <strong>Date & Time:</strong>
            </td>
            <td style="padding: 8px 0; color: #2E3A87; font-weight: bold; text-align: right;">
                {{ $resetTime ?? now()->format('M d, Y \a\t h:i A') }}
            </td>
        </tr>
    </table>
</div>

{{-- CTA Button --}}
@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
{{ $actionText }}
@endcomponent

{{-- Expiration Info --}}
<div style="background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; padding: 15px; margin: 25px 0; font-size: 13px;">
    <strong style="color: #856404;">⚠️ Important:</strong> This password reset link will expire in {{ config('auth.passwords.users.expire') }} minutes. Please reset your password promptly.
</div>

If you did not request this password reset, you can safely ignore this email. Your account remains secure.

---

{{-- Footer --}}
<div style="text-align: center; color: #999; font-size: 12px; margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
    <p style="margin: 5px 0;">
        <strong>ABC Leave Management System</strong>
    </p>
    <p style="margin: 5px 0;">
        © {{ now()->year }} All Rights Reserved
    </p>
    <p style="margin: 15px 0 0 0; font-size: 11px;">
        For support, please contact your HR department.
    </p>
</div>

Thanks<br>
ABC Leave Management System
@endcomponent

