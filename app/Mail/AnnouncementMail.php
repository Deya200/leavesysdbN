<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $announcement;
    public $employeeName;

    /**
     * Create a new message instance.
     */
    public function __construct(Announcement $announcement, $employeeName = null)
    {
        $this->announcement = $announcement;
        $this->employeeName = $employeeName ?? 'Valued Employee';
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: "Important Announcement: {$this->announcement->title} - ABC Leave Management System",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.announcement',
            with: [
                'announcement' => $this->announcement,
                'employeeName' => $this->employeeName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments()
    {
        return [];
    }
}
