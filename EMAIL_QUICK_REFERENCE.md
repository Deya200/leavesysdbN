# Email System - Quick Reference Guide

## What's Been Implemented

### 📧 Email Classes (Mailables)
All email sending logic is encapsulated in mailable classes located in `app/Mail/`:

1. **LeaveRequestSubmittedMail** - Notifies supervisor of new leave request
2. **LeaveRequestApprovedMail** - Notifies employee of approval
3. **LeaveRequestRejectedMail** - Notifies employee of rejection
4. **LeaveAppealSubmittedMail** - Notifies supervisor of appeal
5. **LeaveExtensionRequestedMail** - Notifies supervisor of extension request
6. **LeaveCancellationRequestedMail** - Notifies supervisor of cancellation request
7. **LeaveCancellationApprovedMail** - Notifies employee of approved cancellation
8. **AnnouncementMail** - Sends announcements to employees

### 🎨 Email Templates
Beautiful, responsive email templates in `resources/views/emails/`:
- leave_request_submitted.blade.php
- leave_request_approved.blade.php
- leave_request_rejected.blade.php
- leave_appeal_submitted.blade.php
- leave_extension_requested.blade.php
- leave_cancellation_requested.blade.php
- leave_cancellation_approved.blade.php
- announcement.blade.php

### 🔧 Services & Utilities
- **EmailService** (`app/Services/EmailService.php`) - Helper class for sending emails with error handling
- **AnnouncementController** (`app/Http/Controllers/AnnouncementController.php`) - Manages announcements and bulk email sending

### 📝 Controller Integration
Emails automatically sent from:
- **LeaveRequestController** - On request creation, approval, rejection, appeal, extension, cancellation
- **LeaveCancellationController** - On approval
- **AnnouncementController** - On creation (optional) and manual sending

## Setup Instructions

### 1. Configure Mail Driver

Edit your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  # or your mail provider
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@company.com
MAIL_FROM_NAME="ABC Leave Management System"
```

### 2. Ensure Employee Emails Exist

```sql
-- Check if employees have emails
SELECT EmployeeNumber, FirstName, LastName, email FROM employees WHERE email IS NULL;

-- Update with default if needed
UPDATE employees SET email = CONCAT(LOWER(FirstName), '.', LOWER(LastName), '@company.com') WHERE email IS NULL;
```

### 3. Test Mail Configuration

```bash
php artisan app:test-email your-email@example.com
```

## Common Mail Providers

### Mailtrap (Development)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
```

### Gmail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password  # Use app passwords, not account password
MAIL_ENCRYPTION=tls
```

### Office 365
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=your-email@company.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### AWS SES
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
```

## Usage Examples

### Sending Email in Code

```php
use App\Mail\LeaveRequestApprovedMail;
use Illuminate\Support\Facades\Mail;

// Simple send
Mail::to($employee->email)->send(new LeaveRequestApprovedMail($leaveRequest, 'Approved!'));

// Multiple recipients
Mail::to([$emp1->email, $emp2->email])->send(new AnnouncementMail($announcement));

// Using EmailService
use App\Services\EmailService;

EmailService::send($employee->email, new LeaveRequestApprovedMail($leaveRequest));

// Bulk send with stats
$stats = EmailService::sendBulk($emailArray, new AnnouncementMail($announcement));
echo "Sent: {$stats['sent']}/{$stats['total']}";

// Queue for later sending
EmailService::queue($employee->email, new LeaveRequestApprovedMail($leaveRequest));
```

### For Announcements

```php
// Create and optionally send
Announcement::create([
    'title' => 'Important Update',
    'description' => 'Some content',
    'send_email' => true  // Sends to all employees
]);

// Or send later via controller
$announcement = Announcement::find(1);
redirect()->route('announcements.show', $announcement)
    ->route('announcements.sendEmails', $announcement);
```

## Testing

### Using Log Driver (Development)
```env
MAIL_MAILER=log
MAIL_LOG_CHANNEL=single
```

Check emails in `storage/logs/laravel.log`:
```bash
tail -f storage/logs/laravel.log | grep -i "mail\|email"
```

### Using Array Driver (Testing)
```env
MAIL_MAILER=array
```

In tests:
```php
Mail::fake();
// Perform action
Mail::assertSent(LeaveRequestApprovedMail::class);
```

### Manual Testing
```bash
php artisan tinker

use App\Models\LeaveRequest;
use App\Mail\LeaveRequestApprovedMail;
use Illuminate\Support\Facades\Mail;

$request = LeaveRequest::first();
Mail::to('test@example.com')->send(new LeaveRequestApprovedMail($request));
```

## Troubleshooting

### "Call to undefined method email()" Error
**Problem:** Employee model doesn't have email field
**Solution:** Ensure `email` column exists in employees table and is populated

### Emails Not Sending
1. Verify `.env` MAIL_* settings
2. Check `MAIL_FROM_ADDRESS` is valid
3. Test with: `php artisan app:test-email test@test.com`
4. Switch to log driver to debug
5. Check `storage/logs/laravel.log` for errors

### SMTP Authentication Failed
- Verify username/password are correct
- For Gmail: Use app-specific password, not account password
- Check firewall isn't blocking SMTP port
- Verify credentials aren't expired

### Bulk Emails Taking Too Long
- Use queue: `QUEUE_CONNECTION=database`
- Run queue worker: `php artisan queue:work`
- Process jobs: `php artisan queue:work --stop-when-empty`

## Performance Tips

### Development
- Use `MAIL_MAILER=log` for instant feedback without SMTP
- Use `MAIL_MAILER=array` for testing without file I/O

### Production
- Use `QUEUE_CONNECTION=database` and run queue workers
- Monitor mail delivery with CloudWatch/Sentry
- Set `MAIL_STREAM_OPTIONS` for TLS if needed
- Consider HTML email fallbacks for plain text

### Bulk Operations
- Queue sends for announcements to all employees
- Use batch operations for large recipient lists
- Monitor SMTP provider rate limits

## Email Flow Diagram

```
User Action
    ↓
Controller Method
    ↓
Create Model Record
    ↓
Send Mailable
    ↓
EmailService.send()
    ↓
Mail::to()->send()
    ↓
SMTP Provider
    ↓
Recipient Email
```

## Files Modified/Created

### New Files
- `app/Mail/LeaveRequestSubmittedMail.php`
- `app/Mail/LeaveRequestApprovedMail.php`
- `app/Mail/LeaveRequestRejectedMail.php`
- `app/Mail/LeaveAppealSubmittedMail.php`
- `app/Mail/LeaveExtensionRequestedMail.php`
- `app/Mail/LeaveCancellationRequestedMail.php`
- `app/Mail/LeaveCancellationApprovedMail.php`
- `app/Mail/AnnouncementMail.php`
- `app/Services/EmailService.php`
- `app/Http/Controllers/AnnouncementController.php`
- `resources/views/emails/` (8 templates)
- `EMAIL_FUNCTIONALITY.md` (full documentation)

### Modified Files
- `app/Http/Controllers/LeaveRequestController.php` (added email sending)
- `app/Http/Controllers/LeaveCancellationController.php` (added email sending)

## Next Steps

1. ✅ Configure `.env` with mail provider credentials
2. ✅ Verify employee email addresses exist in database
3. ✅ Test with `php artisan app:test-email`
4. ✅ Create test leave request to verify flow
5. ⚙️ Optional: Set up queue workers for production
6. ⚙️ Optional: Customize email templates as needed

## Support & Documentation

- Full documentation: See `EMAIL_FUNCTIONALITY.md`
- Mail configuration: See `config/mail.php`
- Laravel mail docs: https://laravel.com/docs/mail
- Test with mailtrap: https://mailtrap.io

---

**Last Updated:** April 2026
**Version:** 1.0
