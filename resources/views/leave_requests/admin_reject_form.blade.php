@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Rejection Modal Card -->
            <div class="card border-0 shadow-lg" style="border-radius: 1.5rem; overflow: hidden;">
                <!-- Header -->
                <div class="card-header border-0 p-0" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); min-height: 120px;">
                    <div class="p-4 text-white position-relative">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2);">
                                <i class="fas fa-times-circle fs-1"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 fw-bold">Reject Leave Request</h4>
                                <p class="mb-0 opacity-75">Request #{{ $leaveRequest->LeaveRequestID }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-4">
                    <!-- Request Summary -->
                    <div class="alert alert-light border-1 mb-4" style="border-color: #e5e7eb; border-radius: 0.75rem;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Employee</small>
                                <p class="mb-0 fw-semibold">
                                    {{ $leaveRequest->employee->FirstName }} {{ $leaveRequest->employee->LastName }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Leave Type</small>
                                <p class="mb-0 fw-semibold">{{ $leaveRequest->leaveType->LeaveTypeName }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Duration</small>
                                <p class="mb-0 fw-semibold">
                                    {{ \Carbon\Carbon::parse($leaveRequest->StartDate)->format('M d, Y') }} 
                                    to 
                                    {{ \Carbon\Carbon::parse($leaveRequest->EndDate)->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Total Days</small>
                                <p class="mb-0 fw-semibold">{{ $leaveRequest->TotalDays }} days</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rejection Reason Form -->
                    <form action="{{ route('leave_requests.admin.reject', $leaveRequest->LeaveRequestID) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="AdminRejectionReason" class="form-label fw-bold mb-3" style="font-size: 0.95rem;">
                                <i class="fas fa-comment-alt me-2 text-danger"></i>Rejection Reason
                            </label>
                            <textarea 
                                name="AdminRejectionReason" 
                                id="AdminRejectionReason" 
                                class="form-control form-control-lg @error('AdminRejectionReason') is-invalid @enderror"
                                rows="4"
                                placeholder="Please provide a detailed reason for rejecting this leave request..."
                                required
                                style="border-radius: 0.75rem; border: 2px solid #e5e7eb; padding: 0.75rem 1rem; font-size: 0.95rem;"
                            ></textarea>
                            @error('AdminRejectionReason')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i> Be clear and professional in your rejection reason.
                            </small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 mt-5">
                            <a href="{{ route('admin.verification') }}" class="btn btn-outline-secondary btn-lg flex-grow-1" style="border-radius: 0.75rem; font-weight: 600;">
                                <i class="fas fa-arrow-left me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-danger btn-lg flex-grow-1" style="border-radius: 0.75rem; font-weight: 600; background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); border: none;">
                                <i class="fas fa-check me-2"></i> Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="alert alert-warning mt-4 rounded-3 border-0" style="background-color: #fef3c7; color: #92400e;">
                <div class="d-flex gap-3">
                    <div>
                        <i class="fas fa-exclamation-triangle fa-lg mt-1"></i>
                    </div>
                    <div>
                        <strong>Important:</strong> Once rejected, the employee will be notified and can appeal this decision within 7 days if allowed.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        min-height: 100vh;
    }

    .form-control-lg:focus {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25) !important;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection
