<?php

namespace App\Mail;

use App\Models\LeaveCancellation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveCancellationRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveCancellation;
    public $leaveRequest;
    public $supervisor;
    public $employee;

    /**
     * Create a new message instance.
     */
    public function __construct(LeaveCancellation $leaveCancellation)
    {
        $this->leaveCancellation = $leaveCancellation;
        $this->leaveRequest = $leaveCancellation->leaveRequest;
        $this->employee = $leaveCancellation->employee;
        $this->supervisor = $this->leaveRequest->supervisor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: "Leave Cancellation Requested - Request #{$this->leaveRequest->LeaveRequestID} - ABC Leave Management System",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.leave_cancellation_requested',
            with: [
                'leaveCancellation' => $this->leaveCancellation,
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
