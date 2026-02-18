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

            

            <!-- Department Overview Table -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem; overflow: hidden;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-sitemap me-2"></i>Department Overview</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        @if ($departments->isNotEmpty())
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Department Name</th>
                                        <th>Employees</th>
                                        <th class="pe-4">Supervisor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $department)
                                        <tr>
                                            <td class="ps-4">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold text-dark">{{ $department->DepartmentName }}</td>
                                            <td>
                                                <span class="badge bg-light text-primary border px-3">
                                                    {{ $department->employees_count }} Members
                                                </span>
                                            </td>
                                            <td class="pe-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                                        <i class="fas fa-user-tie"></i>
                                                    </div>
                                                    <span class="small">{{ $department->supervisor ? $department->supervisor->FirstName . ' ' . $department->supervisor->LastName : 'N/A' }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-4 text-center">
                                <div class="mb-2"><i class="fas fa-info-circle text-muted fs-3"></i></div>
                                <p class="text-muted mb-0">No departments found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Employees Currently on Leave (Global) -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem; overflow: hidden;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-20 text-warning d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                        <i class="fas fa-plane-departure small"></i>
                    </div>
                    <h5 class="mb-0 fw-bold text-dark">Employees Currently on Leave</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        @if(isset($employeesOnLeave) && $employeesOnLeave->isNotEmpty())
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background-color: #f8fafc;">
                                    <tr>
                                        <th class="ps-4">Employee</th>
                                        <th>Department</th>
                                        <th>Leave Type</th>
                                        <th>Due Back</th>
                                        <th class="pe-4">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employeesOnLeave as $onLeave)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; font-weight: 600;">
                                                        {{ substr($onLeave->employee->FirstName, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark">{{ $onLeave->employee->FirstName }} {{ $onLeave->employee->LastName }}</div>
                                                        <small class="text-muted">{{ $onLeave->EmployeeNumber }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="small">{{ $onLeave->employee->department->DepartmentName ?? 'N/A' }}</td>
                                            <td><span class="badge bg-soft-secondary text-secondary border px-2 py-1" style="background-color: #f1f5f9;">{{ $onLeave->leaveType->LeaveTypeName }}</span></td>
                                            <td>
                                                <div class="text-danger fw-bold small">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ \Carbon\Carbon::parse($onLeave->EndDate)->addDay()->format('M d, Y') }}
                                                </div>
                                                <div class="text-muted" style="font-size: 0.7rem;">Ends: {{ \Carbon\Carbon::parse($onLeave->EndDate)->format('M d') }}</div>
                                            </td>
                                            <td class="pe-4">
                                                <div class="fw-bold text-primary">{{ $onLeave->employee->RemainingAnnualLeaveDays }} <small class="text-muted fw-normal">days</small></div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-4 text-center">
                                <div class="mb-2"><i class="fas fa-check-circle text-success fs-3"></i></div>
                                <p class="text-muted mb-0">No employees are currently on leave.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Leave Requests -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem; overflow: hidden;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                            <i class="fas fa-history small"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-dark">Recent Activity</h5>
                    </div>
                    <a href="{{ route('leave_requests.index') }}" class="btn btn-link btn-sm text-decoration-none fw-semibold">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        @if ($recentLeaveRequests->isNotEmpty())
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background-color: #f8fafc;">
                                    <tr>
                                        <th class="ps-4">Employee</th>
                                        <th>Leave Type</th>
                                        <th>Requested</th>
                                        <th>Status</th>
                                        <th class="pe-4 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentLeaveRequests as $request)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</div>
                                            </td>
                                            <td class="small">{{ $request->leaveType->LeaveTypeName }}</td>
                                            <td class="small text-muted">{{ $request->created_at->diffForHumans() }}</td>
                                            <td>
                                                <span class="badge rounded-pill px-3 py-1 bg-{{ match($request->RequestStatus) { 'Approved' => 'success', 'Rejected' => 'danger', default => 'warning text-dark' } }}">
                                                    {{ $request->RequestStatus }}
                                                </span>
                                            </td>
                                            <td class="pe-4 text-center">
                                                @if($request->RequestStatus === 'Pending Admin Verification')
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <form action="{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success px-3">Approve</button>
                                                        </form>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="toggleAdminRejectForm('{{ $request->LeaveRequestID }}')">Reject</button>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">No actions</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($request->RequestStatus === 'Pending Admin Verification')
                                            <tr id="adminRejectFormRow-{{ $request->LeaveRequestID }}" style="display:none;">
                                                <td colspan="5" class="bg-light p-3">
                                                    <div class="card border shadow-none" style="max-width: 500px; margin: auto;">
                                                        <div class="card-body">
                                                            <form action="{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}" method="POST">
                                                                @csrf
                                                                <label class="form-label small fw-bold">Rejection Reason</label>
                                                                <textarea name="AdminRejectionReason" class="form-control mb-2" rows="2" placeholder="Provide a reason for rejection..." required></textarea>
                                                                <div class="d-flex gap-2">
                                                                    <button type="submit" class="btn btn-danger btn-sm px-4">Confirm Rejection</button>
                                                                    <button type="button" class="btn btn-light btn-sm px-4" onclick="toggleAdminRejectForm('{{ $request->LeaveRequestID }}')">Cancel</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-4 text-center">
                                <p class="text-muted mb-0 fst-italic">No recent leave requests found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div> <!-- end col-12 -->
    </div> <!-- end row -->
</div> <!-- end dashboard-container -->

@section('scripts')
<script>
    function toggleAdminRejectForm(id) {
        const row = document.getElementById('adminRejectFormRow-' + id);
        if (row) {
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
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
