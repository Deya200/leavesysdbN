# Email Functionality Documentation

## Overview

The Leave Management System now includes comprehensive email notifications for all key actions related to leave requests, approvals, appeals, cancellations, extensions, and announcements.

## Configuration

### Mail Configuration

Email settings are configured in `config/mail.php`. Update your `.env` file with your mail provider credentials:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@leavesystem.com
MAIL_FROM_NAME="ABC Leave Management System"
```

### Supported Mail Drivers

- **SMTP** - Standard SMTP (recommended)
- **Mailgun** - Mailgun API
- **SES** - Amazon SES
- **Postmark** - Postmark API
- **Resend** - Resend API
- **Log** - Log to file (for testing)
- **Array** - Array driver (for testing)

## Email Types

### 1. Leave Request Submitted
**Sent to:** Supervisor
**When:** Employee submits a new leave request
**Contains:**
- Employee name and number
- Leave type and duration
- Start and end dates
- Leave reason
- Link to review request

**Mailable:** `App\Mail\LeaveRequestSubmittedMail`
**Template:** `resources/views/emails/leave_request_submitted.blade.php`

### 2. Leave Request Approved
**Sent to:** Employee
**When:** Admin approves a leave request
**Contains:**
- Leave details (type, dates, duration)
- Approval note (if provided)
- Success message
- Link to dashboard

**Mailable:** `App\Mail\LeaveRequestApprovedMail`
**Template:** `resources/views/emails/leave_request_approved.blade.php`

### 3. Leave Request Rejected
**Sent to:** Employee
**When:** Supervisor or Admin rejects a leave request
**Contains:**
- Leave details
- Rejection reason
- Rejection source (Supervisor/Admin)
- Information about appeal options
- Link to view details

**Mailable:** `App\Mail\LeaveRequestRejectedMail`
**Template:** `resources/views/emails/leave_request_rejected.blade.php`

### 4. Leave Appeal Submitted
**Sent to:** Supervisor
**When:** Employee submits an appeal for rejected leave
**Contains:**
- Employee information
- Original leave request details
- Appeal reason
- Link to review appeal

**Mailable:** `App\Mail\LeaveAppealSubmittedMail`
**Template:** `resources/views/emails/leave_appeal_submitted.blade.php`

### 5. Leave Extension Requested
**Sent to:** Supervisor
**When:** Employee requests to extend an approved leave
**Contains:**
- Employee information
- Original and requested end dates
- Number of days to extend
- Extension reason
- Link to review request

**Mailable:** `App\Mail\LeaveExtensionRequestedMail`
**Template:** `resources/views/emails/leave_extension_requested.blade.php`

### 6. Leave Cancellation Requested
**Sent to:** Supervisor
**When:** Employee requests to cancel an approved leave
**Contains:**
- Employee information
- Leave dates
- Number of refundable days
- Cancellation reason
- Link to review request

**Mailable:** `App\Mail\LeaveCancellationRequestedMail`
**Template:** `resources/views/emails/leave_cancellation_requested.blade.php`

### 7. Leave Cancellation Approved
**Sent to:** Employee
**When:** Supervisor approves a cancellation request
**Contains:**
- Leave details
- Number of refunded days
- Success message
- Link to dashboard

**Mailable:** `App\Mail\LeaveCancellationApprovedMail`
**Template:** `resources/views/emails/leave_cancellation_approved.blade.php`

### 8. Announcement
**Sent to:** All employees (optional)
**When:** Admin creates/publishes an announcement
**Contains:**
- Announcement title
- Announcement description
- Publication date
- Link to dashboard

**Mailable:** `App\Mail\AnnouncementMail`
**Template:** `resources/views/emails/announcement.blade.php`

## Usage

### In Controllers

All email sending is already integrated into the relevant controllers. Here are the key locations:

#### LeaveRequestController
- `store()` - Sends LeaveRequestSubmittedMail to supervisor
- `supervisorApprove()` - In-app notification only
- `supervisorReject()` - Sends LeaveRequestRejectedMail to employee
- `adminApprove()` - Sends LeaveRequestApprovedMail to employee
- `adminReject()` - Sends LeaveRequestRejectedMail to employee
- `appeal()` - Sends LeaveAppealSubmittedMail to supervisor
- `extend()` - Sends LeaveExtensionRequestedMail to supervisor
- `cancel()` - Sends LeaveCancellationRequestedMail to supervisor

#### LeaveCancellationController
- `approve()` - Sends LeaveCancellationApprovedMail to employee

#### AnnouncementController
- `store()` - Optionally sends AnnouncementMail to all employees
- `sendEmails()` - Manually send announcement emails

### Using the EmailService

For custom email sending, use the `EmailService` class:

```php
use App\Services\EmailService;
use App\Mail\LeaveRequestApprovedMail;

// Send single email
EmailService::send(
    'employee@example.com',
    new LeaveRequestApprovedMail($leaveRequest),
    'Leave Approval'
);

// Send bulk emails
$recipients = ['emp1@example.com', 'emp2@example.com'];
$stats = EmailService::sendBulk(
    $recipients,
    new AnnouncementMail($announcement, 'Employee'),
    'Announcement Distribution'
);

// Queue email (for large batches)
EmailService::queue(
    'employee@example.com',
    new LeaveRequestApprovedMail($leaveRequest),
    'Queued Leave Approval'
);

// Validate email
if (EmailService::isValidEmail('test@example.com')) {
    // Send email
}

// Get valid recipients from array
$validEmails = EmailService::getValidRecipients($emailArray);
```

## Testing

### Using Test Email Command

The application includes a test email command:

```bash
php artisan app:test-email your-email@example.com
```

This sends a test email to verify your mail configuration is working.

### Using Log Driver

For development/testing, set `MAIL_MAILER=log` in your `.env` file. Emails will be logged instead of sent.

```env
MAIL_MAILER=log
MAIL_LOG_CHANNEL=single
```

View logged emails in `storage/logs/laravel.log`

### Local Development with Mailtrap

1. Sign up at [mailtrap.io](https://mailtrap.io)
2. Create a test inbox
3. Update `.env` with credentials from Mailtrap dashboard
4. Emails will appear in your Mailtrap inbox

## Email Templates

All email templates use the Laravel Mail component system and are located in:
`resources/views/emails/`

### Customizing Templates

Edit the blade templates to customize the email appearance. All templates include:
- Branded header with logo
- Professional styling
- Clear action buttons
- Footer with copyright information

Example template structure:
```blade
@component('mail::message')
{{-- Header --}}
<div>...</div>

{{-- Content --}}
{{ $greeting }}

@component('mail::section')
Key information here
@endcomponent

{{-- CTA Button --}}
@component('mail::button', ['url' => $url, 'color' => 'primary'])
Action Button
@endcomponent

{{-- Footer --}}
<div>...</div>
@endcomponent
```

## Troubleshooting

### Emails Not Sending

1. **Check mail configuration**
   ```bash
   php artisan tinker
   Mail::raw('Test', function ($m) { $m->to('test@example.com'); });
   ```

2. **Verify .env settings**
   - Ensure `MAIL_MAILER` is set correctly
   - Check `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`

3. **Check logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Test with log driver**
   - Temporarily set `MAIL_MAILER=log`
   - Check if emails appear in logs

5. **Verify recipient emails exist**
   - Employees must have email addresses in the database
   - Use: `UPDATE employees SET email = 'test@example.com' WHERE EmployeeNumber = 'xxx';`

### SMTP Connection Errors

- Verify SMTP credentials are correct
- Check firewall/network allows port 2525 or 587
- For Gmail: Use [app passwords](https://myaccount.google.com/apppasswords)
- For corporate servers: May need additional SSL certificates

### Queuing Issues

If using the queue feature:

1. Configure queue driver in `config/queue.php`
2. Run queue worker: `php artisan queue:work`
3. Monitor jobs: `php artisan queue:monitor`

## Employee Email Field Requirements

For emails to send successfully, employees must have email addresses. Ensure the `email` field is populated in the employees table:

```sql
UPDATE employees SET email = CONCAT(LOWER(FirstName), '.', LOWER(LastName), '@company.com') WHERE email IS NULL;
```

## Performance Considerations

For production environments with many employees:

1. **Use queuing** - Configure `QUEUE_CONNECTION=database` and run queue workers
2. **Bulk announcements** - The system handles bulk sends with error tracking
3. **Rate limiting** - Consider SMTP provider's rate limits
4. **Monitor logs** - Regularly check logs for mail delivery failures

## Security

- Never hardcode credentials in controllers
- Use `.env` for sensitive configuration
- Email addresses are logged for delivery tracking
- Consider implementing additional logging/auditing

## Future Enhancements

Potential additions to the email system:
- Email templates customization via admin panel
- Scheduled email sending
- Email delivery status tracking
- Email template versioning
- Multi-language email templates
- SMS notifications
- Push notifications

## Support

For issues or questions regarding email functionality:
1. Check the troubleshooting section above
2. Review logs in `storage/logs/laravel.log`
3. Test with the `app:test-email` command
4. Verify employee email records exist
