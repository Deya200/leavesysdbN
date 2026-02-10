@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .dashboard-container {
        /* max-width handled by app.blade.php */
    }

    .card-custom {
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        background-color: white;
        transition: transform 0.2s ease-in-out;
        padding: 12px 18px;
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

    #table-header {
        background-color: #2E3A87;
        padding: 12px 20px;
        border-radius: 10px 10px 0 0;
        color: white;
    }

    .table thead {
        background-color: rgb(235, 236, 240);
        color: #2E3A87;
    }

    .table th, .table td {
        text-align: center;
        padding: 12px;
        vertical-align: middle;
        border: none;
    }

    .alert-info {
        margin: 0;
        font-size: 14px;
    }

    .illustration {
        width: 100%;
        max-width: 280px;
        display: block;
        margin: 0 auto;
    }

    .font-size-65 {
        font-size: 1.8rem;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="row">

        <!-- Main Content -->
        <div class="col-12 animate__animated animate__fadeInDown">

            <!-- Welcome Section -->
            <div class="card-custom text-center mb-4" style="background-color: #2E3A87; color: white;">
                <h4 class="fw-bold mb-1">Welcome, {{ auth()->user()->FirstName ?? 'Admin' }}!</h4>
                <p class="mb-2">This is your admin control center. Review summaries, manage departments, and track recent leave requests.</p>
            </div>

            <!-- My Personal Leave Section (Admin as Employee) -->
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


            <!-- Summary Cards -->
            <div class="row text-center g-2 mb-4">
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-users"></i> Employees</h6>
                        <p>{{ $totalEmployees }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-male"></i> Male</h6>
                        <p>{{ $maleEmployees }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-female"></i> Female</h6>
                        <p>{{ $femaleEmployees }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-briefcase"></i> Positions</h6>
                        <p>{{ $totalPositions }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-layer-group"></i> Grades</h6>
                        <p>{{ $totalGrades }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-building"></i> Departments</h6>
                        <p>{{ $departments->count() }}</p>
                    </div>
                </div>
            </div>

            

            <!-- Department Overview Table -->
            <div class="card-custom mb-4">
                <div id="table-header">
                    <h5>Department Overview</h5>
                </div>
                <div class="table-responsive mt-3">
                    @if ($departments->isNotEmpty())
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Department Name</th>
                                    <th>Employees</th>
                                    <th>Supervisor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departments as $department)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $department->DepartmentName }}</td>
                                        <td>{{ $department->employees_count }}</td>
                                        <td>{{ $department->supervisor ? $department->supervisor->FirstName . ' ' . $department->supervisor->LastName : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info text-center m-0">No departments found.</div>
                    @endif
                </div>
            </div>

            <!-- Employees Currently on Leave (Global) -->
            <div class="card-custom mb-4">
                <div id="table-header" class="bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-plane-departure"></i> Employees Currently on Leave</h5>
                </div>
                <div class="table-responsive mt-3">
                    @if(isset($employeesOnLeave) && $employeesOnLeave->isNotEmpty())
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
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
                                        <td>{{ $onLeave->employee->department->DepartmentName ?? 'N/A' }}</td>
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
                            <i class="fas fa-check-circle text-success me-2"></i> No employees are currently on leave.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Leave Requests -->
            <div class="card-custom">
                <div id="table-header">
                    <h5>Recent Leave Requests</h5>
                </div>
                <div class="table-responsive mt-3">
                    @if ($recentLeaveRequests->isNotEmpty())
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Status</th>
                                    <th>Requested On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentLeaveRequests as $request)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                                        <td>{{ $request->leaveType->LeaveTypeName }}</td>
                                       <td>
                                            <span class="badge bg-{{ match($request->RequestStatus) { 'Approved' => 'success', 'Rejected' => 'danger', default => 'warning' } }}">
                                                {{ $request->RequestStatus }}
                                            </span>
                                       </td>
                                       <td>{{ $request->created_at->format('d-m-Y') }}</td>
                                       <td>
                                            @if($request->RequestStatus === 'Pending Admin Verification')
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <form action="{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="toggleAdminRejectForm('{{ $request->LeaveRequestID }}')">Reject</button>
                                                </div>
                                                <div id="adminRejectForm-{{ $request->LeaveRequestID }}" style="display:none;" class="mt-2">
                                                    <form action="{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}" method="POST">
                                                        @csrf
                                                        <textarea name="AdminRejectionReason" class="form-control form-control-sm mb-1" placeholder="Reason for rejection" required></textarea>
                                                        <button type="submit" class="btn btn-danger btn-sm w-100">Confirm</button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                       </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info text-center m-0">No recent leave requests found.</div>
                    @endif
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('leave_requests.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-list me-1"></i> View All Leave History
                    </a>
                </div>
            </div>

        </div> <!-- end col-12 -->
    </div> <!-- end row -->
</div> <!-- end dashboard-container -->

@section('scripts')
<script>
    function toggleAdminRejectForm(id) {
        const form = document.getElementById('adminRejectForm-' + id);
        if (form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    }

    // Prevent double form submissions
    document.addEventListener('DOMContentLoaded', function() {
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
