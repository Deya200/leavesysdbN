<?php

namespace App\Mail;

use App\Models\LeaveExtension;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveExtensionRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveExtension;
    public $leaveRequest;
    public $supervisor;
    public $employee;

    /**
     * Create a new message instance.
     */
    public function __construct(LeaveExtension $leaveExtension)
    {
        $this->leaveExtension = $leaveExtension;
        $this->leaveRequest = $leaveExtension->leaveRequest;
        $this->employee = $leaveExtension->employee;
        $this->supervisor = $this->leaveRequest->supervisor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: "Leave Extension Requested - Request #{$this->leaveRequest->LeaveRequestID} - ABC Leave Management System",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.leave_extension_requested',
            with: [
                'leaveExtension' => $this->leaveExtension,
                'leaveRequest' => $this->leaveRequest,
                'supervisor' => $this->supervisor,
                'employee' => $this->employee,
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
