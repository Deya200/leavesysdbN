<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Notification as CustomNotification;

class EmergencyLocumNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $supervisor;

    public function __construct($message, $supervisor)
    {
        $this->message = $message;
        $this->supervisor = $supervisor;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Emergency Locum Notification from ' . $this->supervisor->FirstName . ' ' . $this->supervisor->LastName)
            ->greeting('Dear ' . $notifiable->FirstName . ',')
            ->line('Your supervisor has sent an emergency locum notification:')
            ->line($this->message)
            ->action('View Dashboard', url('/dashboard'))
            ->line('Please take appropriate action.')
            ->salutation('Best regards, ' . $this->supervisor->FirstName . ' ' . $this->supervisor->LastName);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Emergency Locum Notification',
            'message' => $this->message,
            'supervisor' => $this->supervisor->FirstName . ' ' . $this->supervisor->LastName,
            'type' => 'emergency_locum',
        ];
    }

    // Custom method to store in the custom notifications table
    public function toCustomNotification($notifiable)
    {
        CustomNotification::create([
            'EmployeeNumber' => $notifiable->EmployeeNumber,
            'Message' => 'Emergency Locum Notification from ' . $this->supervisor->FirstName . ' ' . $this->supervisor->LastName . ': ' . $this->message,
            'Status' => 'Unread',
        ]);
    }
}