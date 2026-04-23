<?php

namespace App\Mail;

use App\Models\LeaveAppeal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveAppealSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveAppeal;
    public $leaveRequest;
    public $supervisor;
    public $employee;

    /**
     * Create a new message instance.
     */
    public function __construct(LeaveAppeal $leaveAppeal)
    {
        $this->leaveAppeal = $leaveAppeal;
        $this->leaveRequest = $leaveAppeal->leaveRequest;
        $this->employee = $leaveAppeal->employee;
        $this->supervisor = $this->leaveRequest->supervisor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: "Leave Appeal Submitted - Request #{$this->leaveRequest->LeaveRequestID} - ABC Leave Management System",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.leave_appeal_submitted',
            with: [
                'leaveAppeal' => $this->leaveAppeal,
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
