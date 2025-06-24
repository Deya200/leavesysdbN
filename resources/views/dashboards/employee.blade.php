@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>

/* Dashboard Container */
.dashboard-container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* Cards Styling */
.card-custom {
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease-in-out;
}
.card-custom:hover {
    transform: scale(1.02);
}

/* Leave Request Table */
.table-responsive {
    border-radius: 10px;
    overflow: hidden;
}
.table th, .table td {
    vertical-align: middle;
    border: none;
    padding: 12px;
}
.table-primary {
    background-color: #5169C4;
    color: white;
}

/* Status Badges */
.badge {
    font-size: 14px;
    padding: 6px 12px;
    border-radius: 5px;
}
.badge-success { background-color: #28a745; }
.badge-danger { background-color: #dc3545; }
.badge-warning { background-color: #ffc107; }
.badge-primary { background-color: #5169C4; } /* Pending Admin Verification */

/* Button Styling */
.btn-action {
    padding: 6px 10px;
    border-radius: 6px;
    transition: background 0.3s ease-in-out;
}
.btn-action:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

/* Welcome Banner */
.welcome-banner {
    background-color: #fff;
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Leave Application Button */
.apply-leave-container {
    display: flex;
    justify-content: end;
    margin-bottom: 20px;
}

/* Notification Icon */
.notification-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #dc3545;
    color: white;
    font-size: 12px;
    padding: 5px 8px;
    border-radius: 50%;
}

</style>
@endsection

@section('content')

@php
    $employee = auth()->user();
    $leaveRequests = \App\Models\LeaveRequest::where('EmployeeNumber', $employee->EmployeeNumber)
        ->orderBy('created_at', 'desc')
        ->get();
    $totalAssigned = optional($employee->grade)->AnnualLeaveDays ?? 0;
    $approvedLeaveDays = $leaveRequests->where('RequestStatus', 'Approved')->sum('TotalDays');
    $remainingDays = max(0, $totalAssigned - $approvedLeaveDays);

    // Sorting leave requests by priority
    $sortedLeaveRequests = $leaveRequests->sortBy(function ($request) {
        return [
            $request->RequestStatus === 'Pending Supervisor Approval' ? 1 : 0,
            $request->RequestStatus === 'Pending Admin Verification' ? 2 : 0,
            $request->RequestStatus === 'Rejected' ? 3 : 0,
            $request->RequestStatus === 'Approved' ? 4 : 0,
        ];
    });
@endphp

@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

<div class="dashboard-container">

    <!-- Welcome Banner -->
    <div class="welcome-banner animate__animated animate__fadeInDown" style="background-color: #5169C4; color: white; padding: 20px; border-radius: 10px;">
    <img src="{{ asset('welcomedash.svg') }}" alt="Welcome Illustration" class="illustration">
    <h3 class="mt-3">Welcome, {{ $employee->FirstName ?? 'Employee' }}!</h3>
</div>


    <!-- Leave Application Button -->
    <div class="apply-leave-container">
        <a href="{{ route('leave_requests.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus-circle"></i> Apply for Leave
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card card-custom">
                <div class="card-body text-center text-white" style="background-color: #28a745;">
                    <h5>Approved Leaves</h5>
                    <p class="fs-4 fw-bold">{{ $leaveRequests->where('RequestStatus', 'Approved')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom">
                <div class="card-body text-center text-white" style="background-color: #dc3545;">
                    <h5>Rejected Leaves</h5>
                    <p class="fs-4 fw-bold">{{ $leaveRequests->where('RequestStatus', 'Rejected')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom">
                <div class="card-body text-center text-white" style="background-color: #ffc107;">
                    <h5>Pending Supervisor Approval</h5>
                    <p class="fs-4 fw-bold">{{ $leaveRequests->where('RequestStatus', 'Pending Supervisor Approval')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom">
                <div class="card-body text-center text-white" style="background-color: #5169C4;">
                    <h5>Pending Admin Verification</h5>
                    <p class="fs-4 fw-bold">{{ $leaveRequests->where('RequestStatus', 'Pending Admin Verification')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="card card-custom mt-4">
        <div class="card-header text-white" style="background-color: #5169C4;">
            <h5 class="m-0">All Leave Requests</h5>
        </div>
        <div class="card-body table-responsive">
            @if ($sortedLeaveRequests->isNotEmpty())
                <table class="table table-hover">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Leave Type</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sortedLeaveRequests as $request)
                            <tr>
                                <td>{{ optional($request->leaveType)->LeaveTypeName ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $request->RequestStatus === 'Approved' ? 'badge-success' : ($request->RequestStatus === 'Rejected' ? 'badge-danger' : ($request->RequestStatus === 'Pending Admin Verification' ? 'badge-primary' : 'badge-warning')) }}">
                                        {{ ucfirst($request->RequestStatus ?? 'Pending') }}
                                    </span>
                                </td>
                                <td>{{ $request->StartDate ?? 'N/A' }}</td>
                                <td>{{ $request->EndDate ?? 'N/A' }}</td>
                                <td>{{ $request->RejectionReason ?? 'Pending' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection
