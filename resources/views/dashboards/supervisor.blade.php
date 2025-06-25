@extends('layouts.app')

@section('title', 'Supervisor Dashboard')

@section('styles')
<style>
    .dashboard-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
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
        color: #2E3A87;
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

    <!-- Summary Cards -->
    <div class="row text-center g-2 mb-4">
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-users"></i> Supervised</h6>
                <p>{{ $totalEmployees }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-female"></i> Female</h6>
                <p>{{ $totalFemaleEmployees }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-male"></i> Male</h6>
                <p>{{ $totalMaleEmployees }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-user-clock"></i> On Leave</h6>
                <p>{{ $employeesOnLeave }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-hourglass-half"></i> Supervisor Pending</h6>
                <p>{{ $pendingSupervisorRequests }}</p>
            </div>
        </div>
        <div class="col-6 col-md-2 summary-card">
            <div class="card-custom">
                <h6><i class="fas fa-user-check"></i> Admin Pending</h6>
                <p>{{ $pendingAdminRequests }}</p>
            </div>
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
                                @php
                                    $status = $request->RequestStatus;
                                    $badgeClass = $status === 'Approved' ? 'badge-approved'
                                        : ($status === 'Rejected' ? 'badge-rejected' : 'badge-pending');
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td>
                                @if ($status === 'Pending Supervisor Approval')
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
                                            <textarea name="RejectionReason" class="form-control form-control-sm mb-1" placeholder="Enter reason" required></textarea>
                                            <button type="submit" class="btn btn-danger btn-sm w-100">Confirm Rejection</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                <table class="table table-bordered align-middle" id="employeeTable">
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
                                <td>{{ optional($employee->grade)->AnnualLeaveDays ?? 'N/A' }}</td
                                
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
    });
</script>
@endsection
