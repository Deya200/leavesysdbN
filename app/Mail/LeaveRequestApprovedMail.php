<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveRequest;
    public $approvalNote;

    /**
     * Create a new message instance.
     */
    public function __construct(LeaveRequest $leaveRequest, $approvalNote = null)
    {
        $this->leaveRequest = $leaveRequest;
        $this->approvalNote = $approvalNote;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: "Your Leave Request Has Been Approved - ABC Leave Management System",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.leave_request_approved',
            with: [
                'leaveRequest' => $this->leaveRequest,
                'employee' => $this->leaveRequest->employee,
                'leaveType' => $this->leaveRequest->leaveType,
                'approvalNote' => $this->approvalNote,
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
