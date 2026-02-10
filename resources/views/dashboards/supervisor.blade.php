@extends('layouts.app')

@section('title', 'Supervisor Dashboard')

@section('styles')
<style>
    .dashboard-container {
        /* Layout handled globally */
    }

    .card-custom {
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
        padding: 10px 12px;
        background: white;
    }

    .card-custom:hover {
        transform: scale(1.01);
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
        background-color: rgb(235, 236, 240);
        color: #d4d6ddff;
    }

    .table th,
    .table td {
        padding: 12px;
        text-align: center;
        vertical-align: middle;
        border: none;
    }

    .hover-up:hover {
        transform: translateY(-3px);
        transition: transform 0.3s ease;
    }

    .badge {
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

    .btn-outline-primary,
    .btn-outline-secondary {
        font-size: 0.85rem;
        padding: 6px 16px;
        border-radius: 6px;
    }

    .hidden-row {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">

    <!-- Welcome Section -->
    <div class="card card-custom mb-4 text-center" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Welcome, {{ auth()->user()->FirstName ?? 'Supervisor' }}!</h4>
        <p class="mb-2">Here’s an overview of your team’s leave activity.</p>
    </div>

    <!-- My Personal Leave Section (Supervisor as Employee) -->
    <div class="card-custom mb-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
            <h5 class="mb-0 text-primary"><i class="fas fa-user-circle"></i> My Personal Leave</h5>
            <a href="{{ route('leave_requests.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Apply for Leave</a>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded bg-light border text-center">
                    <h6 class="text-muted small text-uppercase fw-bold">My Leave Balance</h6>
                    <h2 class="fw-bold text-primary mb-0">{{ $personalLeaveBalance }} <small class="fs-6 text-muted">days</small></h2>
                </div>
            </div>
            <div class="col-md-8">
                <h6 class="text-muted small text-uppercase fw-bold mb-2">My Recent Requests</h6>
                @if($personalRecentRequests->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Dates</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($personalRecentRequests as $req)
                                    <tr>
                                        <td>{{ $req->leaveType->LeaveTypeName }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($req->StartDate)->format('M d') }} - 
                                            {{ \Carbon\Carbon::parse($req->EndDate)->format('M d') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ match($req->RequestStatus) { 'Approved' => 'success', 'Rejected' => 'danger', default => 'warning' } }}">
                                                {{ $req->RequestStatus }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted small fst-italic">No recent personal requests.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Management Actions -->
    <div class="row text-center g-2 mb-4">
        <div class="col-md-4">
            <a href="{{ route('leave-appeals.index') }}" class="text-decoration-none">
                <div class="card card-custom h-100 border-warning border-2">
                    <h5 class="text-warning"><i class="fas fa-gavel"></i> Manage Appeals</h5>
                    <p class="text-muted small">Review rejected leave appeals</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('leave-extensions.index') }}" class="text-decoration-none">
                <div class="card card-custom h-100 border-info border-2">
                    <h5 class="text-info"><i class="fas fa-clock"></i> Manage Extensions</h5>
                    <p class="text-muted small">Review leave extension requests</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('leave-cancellations.index') }}" class="text-decoration-none">
                <div class="card card-custom h-100 border-danger border-2">
                    <h5 class="text-danger"><i class="fas fa-ban"></i> Manage Cancellations</h5>
                    <p class="text-muted small">Review leave cancellation requests</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4 animate__animated animate__fadeInUp">
        <div class="col-6 col-md-2">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="fas fa-users fa-lg opacity-50"></i>
                        <span class="badge bg-white bg-opacity-20 rounded-pill small">Team</span>
                    </div>
                    <h6 class="text-white text-opacity-75 small mb-1">Supervised</h6>
                    <h3 class="mb-0 fw-bold">{{ $totalEmployees }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="fas fa-user-clock fa-lg opacity-50"></i>
                        <span class="badge bg-white bg-opacity-20 rounded-pill small">Now</span>
                    </div>
                    <h6 class="text-white text-opacity-75 small mb-1">On Leave</h6>
                    <h3 class="mb-0 fw-bold">{{ $employeesOnLeave->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #4b5563 0%, #1f2937 100%);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="fas fa-list fa-lg opacity-50"></i>
                        <span class="badge bg-white bg-opacity-20 rounded-pill small">History</span>
                    </div>
                    <h6 class="text-white text-opacity-75 small mb-1">Team Total</h6>
                    <h3 class="mb-0 fw-bold">{{ $totalTeamRequests }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="fas fa-check-circle fa-lg opacity-50"></i>
                        <span class="badge bg-white bg-opacity-20 rounded-pill small">Done</span>
                    </div>
                    <h6 class="text-white text-opacity-75 small mb-1">Approved</h6>
                    <h3 class="mb-0 fw-bold">{{ $approvedTeamRequests }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="fas fa-times-circle fa-lg opacity-50"></i>
                        <span class="badge bg-white bg-opacity-20 rounded-pill small">Rejected</span>
                        </div>
                    <h6 class="text-white text-opacity-75 small mb-1">Rejected</h6>
                    <h3 class="mb-0 fw-bold">{{ $rejectedTeamRequests }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <i class="fas fa-hourglass-half fa-lg opacity-50"></i>
                        <span class="badge bg-white bg-opacity-20 rounded-pill small">Action</span>
                    </div>
                    <h6 class="text-white text-opacity-75 small mb-1">Supervisor Pending</h6>
                    <h3 class="mb-0 fw-bold">{{ $pendingSupervisorRequests }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members Currently on Leave -->
    <div class="card-custom mb-4">
        <div id="table-header" class="bg-warning text-dark p-3 rounded-top">
            <h5 class="mb-0"><i class="fas fa-plane-departure"></i> Team Members Currently on Leave</h5>
        </div>
        <div class="table-responsive p-3">
            @if(isset($employeesOnLeave) && $employeesOnLeave->isNotEmpty())
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Due Back</th>
                            <th>Remaining Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employeesOnLeave as $onLeave)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $onLeave->employee->FirstName }} {{ $onLeave->employee->LastName }}</div>
                                    <small class="text-muted">{{ $onLeave->EmployeeNumber }}</small>
                                </td>
                                <td><span class="badge bg-secondary">{{ $onLeave->leaveType->LeaveTypeName }}</span></td>
                                <td>
                                    <span class="fw-bold text-danger">
                                        {{ \Carbon\Carbon::parse($onLeave->EndDate)->addDay()->format('M d, Y') }}
                                    </span>
                                    <small class="d-block text-muted">Ends: {{ \Carbon\Carbon::parse($onLeave->EndDate)->format('M d') }}</small>
                                </td>
                                <td class="fw-bold text-center">{{ $onLeave->employee->RemainingAnnualLeaveDays }} days</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-light text-center border m-0">
                    <i class="fas fa-check-circle text-success me-2"></i> No team members are currently on leave.
                </div>
            @endif
        </div>
    </div>

   <!-- Leave Requests Table -->
<div class="card card-custom mb-4">
    <h5 class="fw-bold text-center mt-3">Leave Requests</h5>
    <div class="table-responsive p-3">
        <table class="table table-bordered align-middle" id="leaveRequestsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Days</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaveRequests as $index => $request)
                    <tr class="hover-up {{ $index >= 5 ? 'hidden-row' : '' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                        <td>{{ $request->leaveType->LeaveTypeName }}</td>
                        <td>{{ $request->StartDate }}</td>
                        <td>{{ $request->EndDate }}</td>
                        <td>{{ $request->TotalDays }}</td>
                        <td>{{ $request->Reason }}</td>
                        <td>
                            <span class="badge text-dark bg-light border">{{ ucfirst($request->RequestStatus) }}</span>
                        </td>
                        <td>
                            @if ($request->RequestStatus === 'Pending Supervisor Approval')
                                <div class="d-flex gap-2 justify-content-center">
                                    <form action="{{ route('leave_requests.supervisor.approve', $request->LeaveRequestID) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="toggleRejectForm('{{ $request->LeaveRequestID }}')">Reject</button>
                                </div>
                                <div id="rejectForm-{{ $request->LeaveRequestID }}" style="display:none;" class="mt-2">
                                    <form action="{{ route('leave_requests.supervisor.reject', $request->LeaveRequestID) }}" method="POST">
                                        @csrf
                                        <textarea name="SupervisorRejectionReason" class="form-control form-control-sm mb-1" placeholder="Enter reason" required></textarea>
                                        <button type="submit" class="btn btn-danger btn-sm w-100">Confirm Rejection</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- PDF Report Button -->
        <div class="text-center mt-3">
            <a href="{{ route('leave.report.pdf') }}" class="btn btn-outline-secondary shadow-sm btn-sm">
                📄 Download Leave Report (PDF)
            </a>
        </div>

        <div class="text-center mt-3">
            <button class="btn btn-outline-primary shadow-sm btn-sm" onclick="toggleLeaveTable()" id="leaveToggleButton">See More</button>
            <button class="btn btn-outline-secondary shadow-sm btn-sm" onclick="toggleLeaveTable()" id="leaveLessButton" style="display: none;">See Less</button>
        </div>
    </div>
</div>

<!-- Employees Under Supervision -->
<div class="card card-custom">
    <h5 class="fw-bold text-center mt-3">Employees You Supervise</h5>
    @if ($employeesUnderSupervisor->count() > 0)
        <div class="table-responsive p-3">
            <table class="table table-bordered align-middle text-dark bg-white" id="employeeTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee No.</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Annual Leave</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employeesUnderSupervisor as $index => $employee)
                        <tr class="hover-up {{ $index >= 5 ? 'hidden-row' : '' }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $employee->EmployeeNumber }}</td>
                            <td>{{ $employee->FirstName }} {{ $employee->LastName }}</td>
                            <td>{{ $employee->department->DepartmentName ?? 'N/A' }}</td>
                            <td>{{ $employee->position->PositionName ?? 'N/A' }}</td>
                            <td>{{ optional($employee->grade)->AnnualLeaveDays ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center mt-3">
                <button class="btn btn-outline-primary shadow-sm btn-sm" onclick="toggleEmployeeTable()" id="toggleButton">See More</button>
                <button class="btn btn-outline-secondary shadow-sm btn-sm" onclick="toggleEmployeeTable()" id="toggleLessButton" style="display: none;">See Less</button>
            </div>
        </div>
    @endif
</div>

</div>
@endsection

@section('scripts')
<script>
    function toggleRejectForm(id) {
        const form = document.getElementById('rejectForm-' + id);
        if (form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    }

    function toggleLeaveTable() {
        const hiddenRows = document.querySelectorAll('#leaveRequestsTable .hidden-row');
        const moreBtn = document.getElementById('leaveToggleButton');
        const lessBtn = document.getElementById('leaveLessButton');

        hiddenRows.forEach(row => {
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        });

        const showingAll = moreBtn.style.display === 'none';
        moreBtn.style.display = showingAll ? 'inline-block' : 'none';
        lessBtn.style.display = showingAll ? 'none' : 'inline-block';
    }

    function toggleEmployeeTable() {
        const hiddenRows = document.querySelectorAll('#employeeTable .hidden-row');
        const moreBtn = document.getElementById('toggleButton');
        const lessBtn = document.getElementById('toggleLessButton');

        hiddenRows.forEach(row => {
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        });

        const showingAll = moreBtn.style.display === 'none';
        moreBtn.style.display = showingAll ? 'inline-block' : 'none';
        lessBtn.style.display = showingAll ? 'none' : 'inline-block';
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.hidden-row').forEach(row => {
            row.style.display = 'none';
        });

        // Prevent double form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (form.classList.contains('submitting')) {
                    e.preventDefault();
                    return false;
                }
                form.classList.add('submitting');
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Processing...';
                }
            });
        });
    });
</script>
@endsection
