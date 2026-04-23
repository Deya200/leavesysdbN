<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveRequest;
    public $supervisor;

    /**
     * Create a new message instance.
     */
    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
        $this->supervisor = $leaveRequest->supervisor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: "New Leave Request Submitted - {$this->leaveRequest->employee->FirstName} {$this->leaveRequest->employee->LastName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.leave_request_submitted',
            with: [
                'leaveRequest' => $this->leaveRequest,
                'supervisor' => $this->supervisor,
                'employee' => $this->leaveRequest->employee,
                'leaveType' => $this->leaveRequest->leaveType,
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
