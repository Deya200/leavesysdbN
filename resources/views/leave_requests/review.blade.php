@extends('layouts.app')

@section('title', 'Review Leave Request')

@section('styles')
<style>
    .steps {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 2rem;
        position: relative;
    }
    .step {
        text-align: center;
        position: relative;
        flex: 1;
        min-width: 95px;
    }
    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 5px;
        font-size: 1.1rem;
        font-weight: 600;
    }
    .step.active .step-circle, .step.completed .step-circle {
        background: #3a7bd5;
        color: white;
    }
    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 16px;
        left: 60%;
        width: 80%;
        height: 2px;
        background: #e9ecef;
        z-index: 0;
    }
    .step.active::after, .step.completed::after {
        background: #3a7bd5;
    }
    .review-summary-card {
        background: #f8f9fa;
        border-radius: 18px;
        box-shadow: 0 2px 16px rgba(58, 123, 213, 0.07);
        padding: 2.2rem 2.8rem 2rem 2.8rem;
        margin-bottom: 2rem;
        max-width: 520px;
        margin-left: auto;
        margin-right: auto;
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }
    .review-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.1rem;
        gap: 10px;
    }
    .review-label {
        font-weight: 700;
        color: #1e3c72;
        font-size: 1.07rem;
        text-align: left;
        flex: 0 0 160px;
        max-width: 160px;
    }
    .review-value {
        font-size: 1.07rem;
        color: #333;
        background: white;
        border-radius: 8px;
        padding: 0.55rem 1.1rem;
        box-shadow: 0 1px 6px rgba(58,123,213,0.03);
        text-align: left;
        min-width: 180px;
        flex: 1 1 0;
        word-break: break-word;
    }
    .card {
        border-radius: 15px;
        border: none;
    }
    .d-grid.justify-content-md-center {
        justify-content: center !important;
    }
    @media (max-width: 700px) {
        .review-summary-card {
            padding: 1.3rem .7rem;
        }
        .steps {
            flex-direction: column;
            gap: 0.6rem;
        }
        .step {
            min-width: unset;
        }
        .review-row {
            flex-direction: column;
            align-items: stretch;
        }
        .review-label, .review-value {
            max-width: 100%;
            min-width: unset;
            text-align: left;
        }
        .review-label {
            margin-bottom: 0.2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5" style="background: url('https://www.transparenttextures.com/patterns/clean-gray-paper.png');">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg overflow-hidden">
                <div class="card-header text-white" style="background:  #1e3c72;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-umbrella-beach fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Review Leave Request</h5>
                            <small class="opacity-80">Please review your details before submitting</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Steps -->
                    <div class="steps mb-4" style="max-width: 430px; margin: 0 auto 2.1rem;">
                        <div class="step completed">
                            <div class="step-circle">1</div>
                            <div class="step-label">Details</div>
                        </div>
                        <div class="step active">
                            <div class="step-circle">2</div>
                            <div class="step-label">Review</div>
                        </div>
                        <div class="step">
                            <div class="step-circle">3</div>
                            <div class="step-label">Submit</div>
                        </div>
                    </div>
                    <!-- Defensive check to prevent error if not POSTed from form -->
                    @if(isset($leaveType) && isset($data) && isset($totalDays))
                        <div class="review-summary-card">
                            <div class="review-row">
                                <div class="review-label">Leave Type</div>
                                <div class="review-value">{{ $leaveType->LeaveTypeName }}</div>
                            </div>
                            <div class="review-row">
                                <div class="review-label">Start Date</div>
                                <div class="review-value">{{ \Carbon\Carbon::parse($data['StartDate'])->format('l, j F Y') }}</div>
                            </div>
                            <div class="review-row">
                                <div class="review-label">End Date</div>
                                <div class="review-value">{{ \Carbon\Carbon::parse($data['EndDate'])->format('l, j F Y') }}</div>
                            </div>
                            <div class="review-row">
                                <div class="review-label">Total Days</div>
                                <div class="review-value">{{ $totalDays }}</div>
                            </div>
                            <div class="review-row">
                                <div class="review-label">Reason</div>
                                <div class="review-value" style="white-space: pre-line;">{{ $data['Reason'] }}</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('leave_requests.store') }}" class="text-center">
                            @csrf
                            @foreach($data as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                                <a href="{{ route('leave_requests.create') }}" class="btn btn-outline-secondary me-md-2 px-4">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-4 py-3 shadow-sm"
                                        style="background:  #1e3c72; border: none;">
                                    <span class="submit-text">
                                        <i class="fas fa-paper-plane me-2"></i>Confirm & Submit
                                    </span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning mt-4">
                            Please fill out your leave request form first.
                        </div>
                        <a href="{{ route('leave_requests.create') }}" class="btn btn-primary mt-2">Go to Request Form</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('form')?.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.querySelector('.submit-text').classList.add('d-none');
            btn.querySelector('.spinner-border').classList.remove('d-none');
            btn.disabled = true;
        });
    });
</script>
@endsection
