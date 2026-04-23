<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveRequest;
    public $rejectionReason;
    public $rejectedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(LeaveRequest $leaveRequest, $rejectionReason, $rejectedBy = 'Supervisor')
    {
        $this->leaveRequest = $leaveRequest;
        $this->rejectionReason = $rejectionReason;
        $this->rejectedBy = $rejectedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: "Your Leave Request Has Been Rejected - ABC Leave Management System",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.leave_request_rejected',
            with: [
                'leaveRequest' => $this->leaveRequest,
                'employee' => $this->leaveRequest->employee,
                'leaveType' => $this->leaveRequest->leaveType,
                'rejectionReason' => $this->rejectionReason,
                'rejectedBy' => $this->rejectedBy,
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
