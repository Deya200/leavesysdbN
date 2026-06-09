@component('mail::message')
# Activate Your Locum Account

Hello **{{ $employee->FullName }}**,

Thank you for joining our locum team at ABC Hospital. To get started, you need to activate your account by setting a secure password.

@component('mail::button', ['url' => $activationUrl])
Activate Account
@endcomponent

## How to Activate:

1. Click the button above to access the activation link
2. Set a strong password for your account
3. Start signing in and out for your locum shifts

Once your account is activated, you'll be able to:
- Sign in and out of locum sessions
- Track your working hours
- View your earnings and session history
- Access the leave management system

## Account Details:
- **Name:** {{ $employee->FullName }}
- **Employee Number:** {{ $employee->EmployeeNumber }}
- **Department:** {{ $employee->department->DepartmentName ?? 'Not Assigned' }}

If you didn't request this activation link or have any questions, please contact the Human Resources department.

---

This link will expire after 24 hours for security reasons. If it expires, please contact your administrator for a new activation link.

Thanks,  
ABC Leave Management System
@endcomponent
