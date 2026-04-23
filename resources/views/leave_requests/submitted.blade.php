@extends('layouts.app')

@section('title', 'Leave Request Submitted')

@section('styles')
<style>
    .steps {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        position: relative;
        max-width: 430px;
        margin-left: auto;
        margin-right: auto;
    }
    .step {
        text-align: center;
        position: relative;
        flex: 1;
    }
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #28a745;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-size: 1.2rem;
        font-weight: 600;
    }
    .step-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #333;
    }
    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 60%;
        width: 80%;
        height: 2px;
        background: #28a745;
        z-index: 0;
    }

    .confirmation-card {
        background: #f8f9fa;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(58, 123, 213, 0.11);
        padding: 2.5rem 2.5rem 1.5rem 2.5rem;
        max-width: 540px;
        margin: 3rem auto 0 auto;
        text-align: center;
    }
    .confirmation-title {
        color: #1e3c72;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    .confirmation-icon {
        font-size: 3rem;
        color: #28a745;
        margin-bottom: 1.2rem;
    }
    .confirmation-details {
        margin: 2rem 0 1.5rem;
        text-align: left;
    }
    .confirmation-label {
        font-weight: 600;
        color: #1e3c72;
        width: 150px;
        display: inline-block;
    }
    .confirmation-value {
        color: #333;
        margin-left: 0.7rem;
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Steps -->
    <div class="steps">
        <div class="step">
            <div class="step-circle"><i class="fas fa-check"></i></div>
            <div class="step-label">Details</div>
        </div>
        <div class="step">
            <div class="step-circle"><i class="fas fa-check"></i></div>
            <div class="step-label">Review</div>
        </div>
        <div class="step">
            <div class="step-circle"><i class="fas fa-check"></i></div>
            <div class="step-label">Submit</div>
        </div>
    </div>

<div class="confirmation-card">
    <div class="confirmation-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="confirmation-title">
        Your Leave Request Has Been Submitted!
    </div>
    <div class="confirmation-details">
        <div><span class="confirmation-label">Leave Type:</span><span class="confirmation-value">{{ $leaveRequest->leaveType->LeaveTypeName }}</span></div>
        <div><span class="confirmation-label">Start Date:</span><span class="confirmation-value">{{ \Carbon\Carbon::parse($leaveRequest->StartDate)->format('l, j F Y') }}</span></div>
        <div><span class="confirmation-label">End Date:</span><span class="confirmation-value">{{ \Carbon\Carbon::parse($leaveRequest->EndDate)->format('l, j F Y') }}</span></div>
        <div><span class="confirmation-label">Total Days:</span><span class="confirmation-value">{{ $leaveRequest->TotalDays }}</span></div>
        <div><span class="confirmation-label">Reason:</span><span class="confirmation-value">{{ $leaveRequest->Reason }}</span></div>
        <div><span class="confirmation-label">Status:</span><span class="confirmation-value">{{ $leaveRequest->RequestStatus }}</span></div>
    </div>
    <a href="{{ route('dashboards.employee') }}" class="btn btn-primary px-4 py-2" style="background: #1e3c72; border:none;">
        Back to Dashboard
    </a>
    <a href="{{ route('leave_requests.create') }}">Submit another request</a>
</div>
</div>

<!-- Optional: Auto-redirect to dashboard after 7 seconds -->
<script>
    setTimeout(function() {
        window.location.href = "{{ route('dashboards.employee') }}";
    }, 7000);
</script>
@endsection
