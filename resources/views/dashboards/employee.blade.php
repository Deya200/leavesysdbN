@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('styles')
<style>
    .dashboard-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }
    /* Card Custom */
    .card-custom {
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        padding: 10px 12px;
        background: white;
    }
    .card-custom:hover {
        transform: scale(1.01);
    }
    /* Status Badge */
    .status-badge {
        font-size: 13px;
        padding: 4px 10px;
        border-radius: 12px;
    }
    .badge-approved {
        background-color: #28a745;
        color: white;
    }
    .badge-rejected {
        background-color: #dc3545;
        color: white;
    }
    .badge-pending {
        background-color: #2E3A87;
        color: white;
    }
    /* Summary Cards */
    .summary-card h6 {
        font-size: 13px;
        margin-bottom: 4px;
        color: #333;
    }
    .summary-card p {
        font-size: 18px;
        font-weight: bold;
        margin: 0;
        color: #2E3A87;
    }
    /* Table Styling */
    .table thead {
        background-color:rgb(235, 236, 240);
        color: white;
    }
    .table th, .table td {
        padding: 12px;
    }
    /* Hover effect for table rows */
    .hover-up:hover {
        transform: translateY(-3px);
        transition: transform 0.3s ease;
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

<div class="dashboard-container">

    <!-- Welcome Section -->
    <div class="card card-custom mb-4 text-center" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Welcome, {{ $employee->FirstName ?? 'Employee' }}!</h4>
        <p class="mb-2">Here’s your leave overview.</p>
    </div>

    <!-- Summary Cards Row -->
    <div class="row text-center g-2 mb-4">
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-check-circle"></i> Approved</h6>
                <p>{{ $leaveRequests->where('RequestStatus', 'Approved')->count() }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-times-circle"></i> Rejected</h6>
                <p>{{ $leaveRequests->where('RequestStatus', 'Rejected')->count() }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-hourglass-half"></i> Pending Supervisor</h6>
                <p>{{ $leaveRequests->where('RequestStatus', 'Pending Supervisor Approval')->count() }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-user-clock"></i> Pending Admin</h6>
                <p>{{ $leaveRequests->where('RequestStatus', 'Pending Admin Verification')->count() }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-calendar-alt"></i> Annual Days</h6>
                <p>{{ $totalAssigned }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-calendar-check"></i> Days Left</h6>
                <p>{{ $remainingDays }}</p>
            </div>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="card card-custom">
        <div class="card-body table-responsive">
            @if ($sortedLeaveRequests->isNotEmpty())
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sortedLeaveRequests as $request)
                            <tr class="hover-up">
                                <td>{{ optional($request->leaveType)->LeaveTypeName ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $status = $request->RequestStatus;
                                        $badgeClass = $status === 'Approved' ? 'badge-approved' :
                                                      ($status === 'Rejected' ? 'badge-rejected' : 'badge-pending');
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>{{ $request->StartDate ?? 'N/A' }}</td>
                                <td>{{ $request->EndDate ?? 'N/A' }}</td>
                                <td>
                                    @if($status === 'Rejected' && $request->RejectionReason)
                                        {{ $request->RejectionReason }}
                                    @elseif($status === 'Approved')
                                        Approved
                                    @elseif($status === 'Pending Supervisor Approval')
                                        Pending Supervisor Approval
                                    @elseif($status === 'Pending Admin Verification')
                                        Pending Admin Verification
                                    @else
                                        Pending
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center">No leave requests found.</p>
            @endif
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Additional JavaScript can be added here if needed in the future.
</script>
@endsection
