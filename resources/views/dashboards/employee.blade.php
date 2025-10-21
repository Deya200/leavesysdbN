@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('styles')
<style>
    .dashboard-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }
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
    .table thead {
        background-color:rgb(235, 236, 240);
        color: #333;
    }
    .table th, .table td {
        padding: 12px;
    }
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

    $normalizeStatus = fn($s) => trim(strtolower((string) ($s ?? '')));

    $totalAssigned = optional($employee->grade)->AnnualLeaveDays ?? 0;
    $approvedLeaveDays = $leaveRequests
        ->filter(fn($r) => $normalizeStatus($r->RequestStatus) === 'approved')
        ->sum('TotalDays');
    $remainingDays = max(0, $totalAssigned - $approvedLeaveDays);

    $counts = [
        'approved' => $leaveRequests->filter(fn($r) => $normalizeStatus($r->RequestStatus) === 'approved')->count(),
        'rejected' => $leaveRequests->filter(fn($r) => in_array($normalizeStatus($r->RequestStatus), ['rejected', 'rejected by admin']))->count(),
        'pending_supervisor' => $leaveRequests->filter(fn($r) => $normalizeStatus($r->RequestStatus) === 'pending supervisor approval')->count(),
        'pending_admin' => $leaveRequests->filter(fn($r) => $normalizeStatus($r->RequestStatus) === 'pending admin verification')->count(),
    ];

    $priorityMap = [
        'pending supervisor approval' => 1,
        'pending admin verification' => 2,
        'rejected' => 3,
        'rejected by admin' => 3,
        'approved' => 4,
    ];

    $sortedLeaveRequests = $leaveRequests->sortBy(function ($request) use ($normalizeStatus, $priorityMap) {
        $statusKey = $normalizeStatus($request->RequestStatus);
        $priority = $priorityMap[$statusKey] ?? 5;
        $timePriority = -strtotime($request->created_at ?? now());
        return [$priority, $timePriority];
    })->values();
@endphp

@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

<div class="dashboard-container">

    <div class="card card-custom mb-4 text-center" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Welcome, {{ $employee->FirstName ?? $employee->name ?? 'Employee' }}!</h4>
        <p class="mb-2">Here’s your leave overview.</p>
    </div>

    <div class="row text-center g-2 mb-4">
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-check-circle"></i> Approved</h6>
                <p>{{ $counts['approved'] }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-times-circle"></i> Rejected</h6>
                <p>{{ $counts['rejected'] }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-hourglass-half"></i> Pending Supervisor</h6>
                <p>{{ $counts['pending_supervisor'] }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-user-clock"></i> Pending Admin</h6>
                <p>{{ $counts['pending_admin'] }}</p>
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

    <div class="card card-custom">
        <div class="card-body table-responsive" style="background-color: #ffffff;">
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
                            @php
                                $statusNormalized = $normalizeStatus($request->RequestStatus);
                                $isRejected = in_array($statusNormalized, ['rejected', 'rejected by admin']);
                                $badgeClass = $isRejected ? 'badge-rejected'
                                           : ($statusNormalized === 'approved' ? 'badge-approved' : 'badge-pending');
                                $rejectReason = $request->RejectionReason ?? $request->RejectioReason ?? null;
                                $friendlyStatus = ucwords($statusNormalized);
                            @endphp
                            <tr class="hover-up">
                                <td>{{ optional($request->leaveType)->LeaveTypeName ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge {{ $badgeClass }}">
                                        {{ $friendlyStatus }}
                                    </span>
                                </td>
                                <td>{{ $request->StartDate ?? 'N/A' }}</td>
                                <td>{{ $request->EndDate ?? 'N/A' }}</td>
                                <td>
                                    @if ($isRejected && !empty($rejectReason))
                                        {{ $rejectReason }}
                                    @elseif ($isRejected)
                                        Rejected
                                    @elseif ($statusNormalized === 'approved')
                                        Approved
                                    @elseif ($statusNormalized === 'pending supervisor approval')
                                        Pending Supervisor Approval
                                    @elseif ($statusNormalized === 'pending admin verification')
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
