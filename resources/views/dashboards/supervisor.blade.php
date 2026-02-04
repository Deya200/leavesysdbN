@extends('layouts.app')

@section('title', 'Supervisor Dashboard')

@section('styles')
<style>
/* === MATCHING DEPARTMENTS PAGE THEME === */
.dashboard-container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* Card Styles - Matching Departments */
.card-custom {
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 1px solid #e9ecef;
    padding: 0;
    margin-bottom: 25px;
}

/* Creamy White Header - Matching Departments */
.header-card {
    background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
    color: #2E3A87;
    padding: 24px 30px;
    border-bottom: 2px solid #e9ecef;
    border-radius: 12px 12px 0 0;
    position: relative;
    overflow: hidden;
}

.header-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
}

/* Welcome Banner - Matching Departments */
.welcome-banner {
    background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
    border-radius: 12px;
    padding: 25px;
    color: #2E3A87;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
    position: relative;
    overflow: hidden;
}

.welcome-banner:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
}

.welcome-banner h4 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.welcome-banner p {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 0;
}

/* Summary Cards Grid - Matching Departments */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.summary-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border-color: #dee2e6;
}

.summary-icon {
    font-size: 24px;
    margin-bottom: 10px;
    height: 50px;
    width: 50px;
    line-height: 50px;
    border-radius: 10px;
    margin: 0 auto 15px;
    background: rgba(46, 58, 135, 0.1);
    color: #2E3A87;
}

.summary-label {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 8px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-value {
    font-size: 24px;
    font-weight: 700;
    color: #2E3A87;
    line-height: 1;
}

/* Table Container Card - Matching Departments */
.table-card {
    padding: 25px;
    background-color: #f8fafc;
}

/* Table Styling - Matching Departments */
.table {
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid #e9ecef;
}

.table thead tr {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    color: white;
    font-weight: 600;
    height: 56px;
}

.table thead th {
    padding: 16px;
    border: none;
    font-size: 0.95rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody tr {
    background-color: #ffffff;
    color: #333;
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f5;
}

.table tbody tr:hover {
    background-color: #f8faff !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(46, 58, 135, 0.1);
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table td {
    padding: 16px;
    vertical-align: middle;
    border: none;
    font-size: 0.95rem;
}

/* Zebra Striping - Matching Departments */
.table tbody tr:nth-child(even) {
    background-color: #f9fafc;
}

/* Status Badges - Matching Departments Style */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    border-left: 4px solid;
}

.badge-approved {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border-left-color: #28a745;
}

.badge-rejected {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border-left-color: #dc3545;
}

.badge-pending {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border-left-color: #ffc107;
}

.badge-supervisor {
    background-color: rgba(46, 58, 135, 0.1);
    color: #2E3A87;
    border-left-color: #2E3A87;
}

.badge-admin {
    background-color: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border-left-color: #6c757d;
}

/* Buttons - Matching Departments */
.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 8px 16px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 8px rgba(46, 58, 135, 0.2);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(46, 58, 135, 0.3);
    background: linear-gradient(135deg, #26327A 0%, #3D4DC7 100%);
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    background: linear-gradient(135deg, #218838 0%, #1e9c7a 100%);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    color: white;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.2);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
    color: white;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: center;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
    min-width: 90px;
}

/* Reject Form Styling */
.reject-form {
    background: rgba(220, 53, 69, 0.05);
    border-radius: 8px;
    padding: 15px;
    margin-top: 10px;
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.reject-form textarea {
    border-radius: 6px;
    border: 1px solid #dee2e6;
    padding: 10px;
    font-size: 14px;
    resize: vertical;
    min-height: 80px;
    transition: all 0.3s ease;
}

.reject-form textarea:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    outline: none;
}

/* Hidden Rows */
.hidden-row {
    display: none;
}

/* Card Headers */
.card-header {
    background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
    border-bottom: 2px solid #e9ecef;
    padding: 18px 25px;
    border-radius: 12px 12px 0 0;
    position: relative;
    overflow: hidden;
}

.card-header:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: #2E3A87;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-state i {
    font-size: 48px;
    color: #dee2e6;
    margin-bottom: 15px;
}

.empty-state h5 {
    color: #6c757d;
    margin-bottom: 10px;
    font-weight: 600;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 0;
}

/* Responsive Adjustments - Matching Departments */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 15px;
    }
    
    .welcome-banner,
    .card-header {
        padding: 20px;
    }
    
    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .summary-card {
        padding: 15px;
    }
    
    .table-card {
        padding: 15px;
    }
    
    .table th, .table td {
        padding: 12px 8px;
        font-size: 0.9rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-sm {
        width: 100%;
        margin-bottom: 5px;
    }
}

@media (max-width: 576px) {
    .summary-grid {
        grid-template-columns: 1fr;
    }
    
    .table-responsive {
        border-radius: 8px;
        border: 1px solid #eef1f5;
    }
    
    .welcome-banner h4 {
        font-size: 20px;
    }
    
    .summary-value {
        font-size: 20px;
    }
}
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4>Welcome, {{ auth()->user()->FirstName ?? 'Supervisor' }}! 👋</h4>
                <p>Here's an overview of your team's leave activity and supervision responsibilities.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div style="color: #2E3A87; font-weight: 600;">
                    <div>{{ date('l') }}</div>
                    <div style="font-size: 14px; color: #6c757d;">{{ date('F j, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards Grid -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="summary-label">Total Supervised</div>
            <div class="summary-value">{{ $totalEmployees }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-female"></i>
            </div>
            <div class="summary-label">Female Employees</div>
            <div class="summary-value">{{ $totalFemaleEmployees }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-male"></i>
            </div>
            <div class="summary-label">Male Employees</div>
            <div class="summary-value">{{ $totalMaleEmployees }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="summary-label">Currently on Leave</div>
            <div class="summary-value">{{ $employeesOnLeave }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="summary-label">Pending Supervisor</div>
            <div class="summary-value">{{ $pendingSupervisorRequests }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="summary-label">Pending Admin</div>
            <div class="summary-value">{{ $pendingAdminRequests }}</div>
        </div>
    </div>

    <!-- Main Card - Matching Departments Structure -->
    <div class="card-custom">
        <!-- Leave Requests Header -->
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    <i class="fas fa-calendar-alt"></i>
                    Leave Requests Pending Approval
                </h5>
                <a href="{{ route('leave.report.pdf') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-file-pdf me-2"></i>Download Report
                </a>
            </div>
        </div>

        <!-- Leave Requests Content -->
        <div class="table-card">
            @if ($leaveRequests->count())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaveRequests as $index => $request)
                                <tr class="{{ $index >= 5 ? 'hidden-row' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                {{ substr($request->employee->FirstName ?? '', 0, 1) }}{{ substr($request->employee->LastName ?? '', 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="color: #2E3A87; font-weight: 600;">
                                                    {{ $request->employee->FirstName }} {{ $request->employee->LastName }}
                                                </div>
                                                <small class="text-muted">{{ $request->employee->EmployeeNumber }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: #2E3A87; font-weight: 500;">
                                            {{ $request->leaveType->LeaveTypeName }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge" style="background: rgba(46, 58, 135, 0.1); color: #2E3A87;">
                                            {{ $request->TotalDays }} days
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $status = strtolower($request->RequestStatus);
                                            $statusClass = '';
                                            if (str_contains($status, 'pending supervisor')) {
                                                $statusClass = 'badge-supervisor';
                                            } elseif (str_contains($status, 'pending admin')) {
                                                $statusClass = 'badge-admin';
                                            } elseif (str_contains($status, 'approved')) {
                                                $statusClass = 'badge-approved';
                                            } elseif (str_contains($status, 'rejected')) {
                                                $statusClass = 'badge-rejected';
                                            } else {
                                                $statusClass = 'badge-pending';
                                            }
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">
                                            <i class="fas
                                                @if(str_contains($status, 'approved')) fa-check
                                                @elseif(str_contains($status, 'rejected')) fa-times
                                                @elseif(str_contains($status, 'admin')) fa-user-shield
                                                @elseif(str_contains($status, 'supervisor')) fa-user-tie
                                                @else fa-clock
                                                @endif
                                                fa-xs">
                                            </i>
                                            {{ ucwords($request->RequestStatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($request->RequestStatus === 'Pending Supervisor Approval')
                                            <div class="action-buttons">
                                                <form action="{{ route('leave_requests.supervisor.approve', $request->LeaveRequestID) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check me-1"></i>Approve
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="toggleRejectForm('{{ $request->LeaveRequestID }}')">
                                                    <i class="fas fa-times me-1"></i>Reject
                                                </button>
                                            </div>
                                            <div id="rejectForm-{{ $request->LeaveRequestID }}" class="reject-form" style="display:none;">
                                                <form action="{{ route('leave_requests.supervisor.reject', $request->LeaveRequestID) }}" method="POST">
                                                    @csrf
                                                    <textarea name="RejectionReason" class="form-control form-control-sm mb-2" 
                                                              placeholder="Please provide a reason for rejection..." required></textarea>
                                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                                        <i class="fas fa-paper-plane me-1"></i>Confirm Rejection
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted" style="font-size: 12px;">
                                                Awaiting {{ str_contains($request->RequestStatus, 'Admin') ? 'Admin' : 'Next Step' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($leaveRequests->count() > 5)
                    <div class="text-center mt-3">
                        <button class="btn btn-primary btn-sm" onclick="toggleLeaveTable()" id="leaveToggleButton">
                            <i class="fas fa-chevron-down me-2"></i>Show More Requests
                        </button>
                        <button class="btn btn-secondary btn-sm" onclick="toggleLeaveTable()" id="leaveLessButton" style="display: none;">
                            <i class="fas fa-chevron-up me-2"></i>Show Less
                        </button>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>No Pending Requests</h5>
                    <p>There are no leave requests requiring your approval at this time.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Employees Under Supervision Card -->
    <div class="card-custom">
        <!-- Employees Header -->
        <div class="card-header">
            <h5 class="card-title">
                <i class="fas fa-user-friends"></i>
                Employees Under Your Supervision
            </h5>
        </div>

        <!-- Employees Content -->
        <div class="table-card">
            @if ($employeesUnderSupervisor->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle" id="employeeTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Annual Leave</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employeesUnderSupervisor as $index => $employee)
                                <tr class="{{ $index >= 5 ? 'hidden-row' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span style="color: #2E3A87; font-weight: 600;">
                                            {{ $employee->EmployeeNumber }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                {{ substr($employee->FirstName ?? '', 0, 1) }}{{ substr($employee->LastName ?? '', 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="color: #2E3A87; font-weight: 600;">
                                                    {{ $employee->FirstName }} {{ $employee->LastName }}
                                                </div>
                                                <small class="text-muted">{{ $employee->Gender }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: #2E3A87; font-weight: 500;">
                                            {{ $employee->department->DepartmentName ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $employee->position->PositionName ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge" style="background: rgba(40, 167, 69, 0.1); color: #28a745;">
                                            {{ optional($employee->grade)->AnnualLeaveDays ?? 'N/A' }} days
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($employeesUnderSupervisor->count() > 5)
                    <div class="text-center mt-3">
                        <button class="btn btn-primary btn-sm" onclick="toggleEmployeeTable()" id="toggleButton">
                            <i class="fas fa-chevron-down me-2"></i>Show More Employees
                        </button>
                        <button class="btn btn-secondary btn-sm" onclick="toggleEmployeeTable()" id="toggleLessButton" style="display: none;">
                            <i class="fas fa-chevron-up me-2"></i>Show Less
                        </button>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h5>No Employees Assigned</h5>
                    <p>There are no employees assigned under your supervision at this time.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize hidden rows
    document.querySelectorAll('.hidden-row').forEach(row => {
        row.style.display = 'none';
    });

    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8faff';
            this.style.transform = 'translateY(-1px)';
            this.style.boxShadow = '0 2px 8px rgba(46, 58, 135, 0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
});

function toggleRejectForm(id) {
    const form = document.getElementById('rejectForm-' + id);
    if (form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
        
        // Scroll to the form if opening
        if (form.style.display === 'block') {
            form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
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

// Auto-refresh dashboard every 60 seconds
setInterval(() => {
    console.log('Refreshing supervisor dashboard data...');
    // You can add AJAX refresh here if needed
    // window.location.reload();
}, 60000);
</script>
@endsection