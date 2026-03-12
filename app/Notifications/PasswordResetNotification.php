<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class PasswordResetNotification extends Notification
{

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The admin user who initiated the reset.
     *
     * @var \App\Models\Employee
     */
    public $admin;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     * @param \App\Models\Employee $admin
     * @return void
     */
    public function __construct($token, $admin = null)
    {
        $this->token = $token;
        $this->admin = $admin;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $resetUrl = url(config('app.url') . route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $adminName = $this->admin ? "{$this->admin->FirstName} {$this->admin->LastName}" : 'System Administrator';
        $resetTime = now()->format('M d, Y \a\t h:i A');

        return (new MailMessage)
            ->subject('Reset Your Password - ABC Leave Management System')
            ->greeting("Hello {$notifiable->FirstName},")
            ->line("A password reset request was initiated by **{$adminName}** on {$resetTime}.")
            ->line('')
            ->action('Reset Your Password', $resetUrl)
            ->line('This password reset link will expire in ' . config('auth.passwords.users.expire') . ' minutes.')
            ->line('')
            ->line('If you did not request a password reset, no further action is required. Your account remains secure.')
            ->line('')
            ->salutation('Best regards,')
            ->with([
                'adminName' => $adminName,
                'resetTime' => $resetTime,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'token' => $this->token,
        ];
    }
}
