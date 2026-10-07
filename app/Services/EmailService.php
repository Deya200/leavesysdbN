<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class EmailService
{
    /**
     * Safely send an email with error handling and logging
     *
     * @param string|array $recipients Email address(es) to send to
     * @param \Illuminate\Mail\Mailable $mailable The mailable instance
     * @param string $context Context for logging purposes
     * @return bool
     */
    public static function send($recipients, $mailable, $context = 'Email'): bool
    {
        try {
            // Handle both string and array of recipients
            if (is_string($recipients)) {
                Mail::to($recipients)->send($mailable);
            } elseif (is_array($recipients)) {
                Mail::to($recipients)->send($mailable);
            } else {
                Log::warning("{$context}: Invalid recipient format");
                return false;
            }

            Log::info("{$context}: Email sent successfully");
            return true;
        } catch (Exception $e) {
            Log::error("{$context}: Failed to send email - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email to multiple recipients
     *
     * @param array $recipients Array of email addresses
     * @param \Illuminate\Mail\Mailable $mailable The mailable instance
     * @param string $context Context for logging purposes
     * @return array Statistics about sent/failed emails
     */
    public static function sendBulk(array $recipients, $mailable, $context = 'Bulk Email'): array
    {
        $stats = [
            'total' => count($recipients),
            'sent' => 0,
            'failed' => 0,
            'failed_recipients' => [],
        ];

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient)->send($mailable);
                $stats['sent']++;
            } catch (Exception $e) {
                $stats['failed']++;
                $stats['failed_recipients'][] = [
                    'email' => $recipient,
                    'error' => $e->getMessage(),
                ];
                Log::error("{$context}: Failed to send to {$recipient} - " . $e->getMessage());
            }
        }

        Log::info("{$context}: Sent to {$stats['sent']}/{$stats['total']} recipients");

        return $stats;
    }

    /**
     * Queue an email for sending
     *
     * @param string|array $recipients Email address(es) to send to
     * @param \Illuminate\Mail\Mailable $mailable The mailable instance
     * @param string $context Context for logging purposes
     * @return void
     */
    public static function queue($recipients, $mailable, $context = 'Queued Email'): void
    {
        try {
            if (is_string($recipients)) {
                Mail::to($recipients)->queue($mailable);
            } elseif (is_array($recipients)) {
                Mail::to($recipients)->queue($mailable);
            }

            Log::info("{$context}: Email queued for sending");
        } catch (Exception $e) {
            Log::error("{$context}: Failed to queue email - " . $e->getMessage());
        }
    }

    /**
     * Validate email address format
     *
     * @param string $email
     * @return bool
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Get valid recipients from an array, filtering out invalid emails
     *
     * @param array $recipients
     * @return array
     */
    public static function getValidRecipients(array $recipients): array
    {
        return array_filter($recipients, function ($email) {
            return self::isValidEmail($email);
        });
    }
}
