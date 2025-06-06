@extends('layouts.app')

@section('title', 'Leave Request Details')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Leave Request Details
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Employee:</div>
                        <div class="col-md-8">{{ $leaveRequest->employee->FullName }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Leave Type:</div>
                        <div class="col-md-8">{{ $leaveRequest->leaveType->LeaveTypeName }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Dates:</div>
                        <div class="col-md-8">
                            {{ $leaveRequest->StartDate->format('M d, Y') }} -
                            {{ $leaveRequest->EndDate->format('M d, Y') }}
                            ({{ $leaveRequest->TotalDays }} days)
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Status:</div>
                        <div class="col-md-8">
                            <span class="badge
                                @if($leaveRequest->RequestStatus === 'Approved') bg-success
                                @elseif($leaveRequest->RequestStatus === 'Rejected') bg-danger
                                @elseif($leaveRequest->RequestStatus === 'Pending Admin Verification') bg-info
                                @else bg-warning @endif">
                                {{ $leaveRequest->RequestStatus }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Reason:</div>
                        <div class="col-md-8">{{ $leaveRequest->Reason }}</div>
                    </div>

                    @if($leaveRequest->RejectionReason)
                        <div class="row mb-4">
                            <div class="col-md-4 fw-bold text-danger">Rejection Reason:</div>
                            <div class="col-md-8">{{ $leaveRequest->RejectionReason }}</div>
                        </div>
                    @endif

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('leave_requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>

                        @if(in_array($leaveRequest->RequestStatus, ['Pending Supervisor Approval', 'Pending Admin Verification']))
                            <a href="{{ route('leave_requests.edit', $leaveRequest) }}"
                               class="btn btn-primary ms-2">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
