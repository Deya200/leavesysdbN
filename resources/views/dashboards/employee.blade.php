@extends('layouts.app')

@section('title', 'Employee Dashboard')

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

/* Employee Profile Section - Matching Departments Style */
.employee-profile {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.employee-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(46, 58, 135, 0.3);
}

.employee-details h2 {
    font-size: 24px;
    font-weight: 700;
    color: #2E3A87;
    margin-bottom: 5px;
}

.employee-details p {
    color: #6c757d;
    margin-bottom: 0;
    font-size: 14px;
}

/* Stats Grid - Matching Departments */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border-color: #dee2e6;
}

.stat-icon {
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

.stat-title {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 8px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #2E3A87;
    line-height: 1;
}

/* Leave Balance Progress Card - Matching Departments Style */
.leave-balance-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    color: #000000;
    margin-bottom: 25px;
    position: relative;
    overflow: hidden;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.leave-balance-card .card-header {
    background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
    border-bottom: 2px solid #e9ecef;
    padding: 18px 25px;
    border-radius: 12px 12px 0 0;
    position: relative;
    overflow: hidden;
}

.leave-balance-card .card-header:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
}

.leave-balance-card .card-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: #2E3A87;
    display: flex;
    align-items: center;
    gap: 10px;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 0 5px;
}

.progress-header h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: #2E3A87;
}

.progress-percentage {
    font-size: 28px;
    font-weight: 700;
    color: #2E3A87;
}

.leave-progress {
    height: 10px;
    border-radius: 5px;
    background: #e9ecef;
    overflow: hidden;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
}

.leave-progress .progress-bar {
    height: 100%;
    border-radius: 5px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    transition: width 1s ease;
}

.progress-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    text-align: center;
}

.progress-stat {
    padding: 15px;
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.progress-stat-label {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 5px;
    text-transform: uppercase;
    font-weight: 500;
}

.progress-stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #2E3A87;
}

/* Summary Cards - Matching Departments */
.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.summary-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border-color: #dee2e6;
}

.summary-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}

.summary-card-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #ffffff;
}

.summary-card-icon.blue {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
}

.summary-card-icon.green {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.summary-card-icon.orange {
    background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
}

.summary-card-icon.purple {
    background: linear-gradient(135deg, #6f42c1 0%, #8b5cf6 100%);
}

.summary-card-badge {
    background: rgba(46, 58, 135, 0.1);
    color: #2E3A87;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.summary-card-content h4 {
    font-size: 16px;
    font-weight: 600;
    color: #2E3A87;
    margin-bottom: 10px;
}

.summary-card-content p {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 15px;
    line-height: 1.5;
}

.summary-card-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
}

.summary-stat {
    text-align: center;
}

.summary-stat-label {
    font-size: 11px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.summary-stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #2E3A87;
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

/* Empty State - Matching Departments */
.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-state i {
    font-size: 48px;
    color: #dee2e6;
    margin-bottom: 15px;
}

.empty-state h4 {
    color: #6c757d;
    margin-bottom: 10px;
    font-weight: 600;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 0;
}

/* Buttons - Matching Departments */
.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 10px 20px;
    font-size: 0.95rem;
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

/* Supervisor Info Card */
.supervisor-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    margin-bottom: 25px;
}

.supervisor-card h3 {
    color: #2E3A87;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.supervisor-card h3 i {
    color: #2E3A87;
}

.supervisor-info {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #2E3A87;
}

.supervisor-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
}

.supervisor-details h4 {
    color: #2E3A87;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
}

.supervisor-details p {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 0;
}

.no-supervisor {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    text-align: center;
    color: #6c757d;
}

.no-supervisor i {
    font-size: 24px;
    margin-bottom: 10px;
    display: block;
}

/* Department Info Badge */
.dept-badge {
    background-color: #f0f2ff;
    color: #2E3A87;
    padding: 6px 14px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-block;
    border-left: 4px solid #4A5BD9;
}

/* Responsive Adjustments - Matching Departments */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 15px;
    }
    
    .header-card {
        padding: 20px;
    }
    
    .employee-profile {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .employee-details h2 {
        font-size: 20px;
    }
    
    .stats-grid,
    .summary-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stat-card,
    .summary-card {
        padding: 15px;
    }
    
    .progress-stats {
        grid-template-columns: 1fr;
    }
    
    .table-card {
        padding: 15px;
    }
    
    .table th, .table td {
        padding: 12px 8px;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .stats-grid,
    .summary-cards {
        grid-template-columns: 1fr;
    }
    
    .table-responsive {
        border-radius: 8px;
    }
    
    .leave-balance-card {
        padding: 20px;
    }
    
    .progress-stat,
    .summary-card {
        padding: 12px;
    }
    
    .progress-stat-value,
    .summary-stat-value {
        font-size: 20px;
    }
    
    .summary-card-stats {
        flex-direction: column;
        gap: 10px;
    }
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
    
    $progressPercentage = $totalAssigned > 0 ? ($remainingDays / $totalAssigned) * 100 : 0;
    $usedDays = $totalAssigned - $remainingDays;
    $employeeInitial = strtoupper(substr($employee->FirstName ?? $employee->name ?? 'E', 0, 1));
    $supervisorInitial = $employee->supervisor ? 
        strtoupper(substr($employee->supervisor->FirstName ?? '', 0, 1) . substr($employee->supervisor->LastName ?? '', 0, 1)) : '';
    
    // Calculate additional statistics
    $currentYear = date('Y');
    $yearlyLeaves = $leaveRequests->filter(function($request) use ($currentYear) {
        return date('Y', strtotime($request->created_at ?? now())) == $currentYear;
    });
    $monthlyLeaves = $leaveRequests->filter(function($request) {
        return date('m Y', strtotime($request->created_at ?? now())) == date('m Y');
    });
    
    $avgLeaveDays = $leaveRequests->isNotEmpty() ? 
        round($leaveRequests->avg('TotalDays') ?? 0, 1) : 0;
    
    $mostCommonType = $leaveRequests->isNotEmpty() ? 
        optional($leaveRequests->groupBy('leaveType.LeaveTypeName')->sortDesc()->keys()->first()) : 'None';
@endphp

<div class="dashboard-container">
    <!-- Main Card - Matching Departments Structure -->
    <div class="card-custom">
        <!-- Header -->
        <div class="header-card">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="employee-profile">
                        <div class="employee-avatar">
                            {{ $employeeInitial }}
                        </div>
                        <div class="employee-details">
                            <h2>{{ $employee->FirstName ?? 'Employee' }} {{ $employee->LastName ?? '' }}</h2>
                            <p class="mb-1">{{ $employee->EmployeeNumber ?? 'No Employee ID' }}</p>
                            <span class="dept-badge">
                                <i class="fas fa-building me-1"></i>
                                {{ $employee->department->DepartmentName ?? 'Unassigned Department' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Supervisor Info -->
                    <div class="supervisor-card">
                        <h3><i class="fas fa-user-tie"></i> My Supervisor</h3>
                        @if($employee->supervisor)
                            <div class="supervisor-info">
                                <div class="supervisor-avatar">
                                    {{ $supervisorInitial }}
                                </div>
                                <div class="supervisor-details">
                                    <h4>{{ $employee->supervisor->FirstName ?? '' }} {{ $employee->supervisor->LastName ?? '' }}</h4>
                                    <p><i class="fas fa-envelope me-1"></i> {{ $employee->supervisor->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        @else
                            <div class="no-supervisor">
                                <i class="fas fa-user-slash"></i>
                                <p class="mb-0">No supervisor assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="table-card">
            <!-- Leave Balance Card -->
            <div class="leave-balance-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            <i class="fas fa-chart-line"></i>
                            Leave Balance Overview
                        </h5>
                        <div class="summary-card-badge">
                            {{ date('Y') }} Progress
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="progress-header">
                        <h3>Leave Balance Progress</h3>
                        <div class="progress-percentage">{{ number_format($progressPercentage, 1) }}%</div>
                    </div>
                    
                    <div class="progress leave-progress mb-4">
                        <div class="progress-bar" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    
                    <div class="progress-stats">
                        <div class="progress-stat">
                            <div class="progress-stat-label">Total Days</div>
                            <div class="progress-stat-value">{{ $totalAssigned }}</div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-stat-label">Days Used</div>
                            <div class="progress-stat-value">{{ $usedDays }}</div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-stat-label">Days Remaining</div>
                            <div class="progress-stat-value">{{ $remainingDays }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="summary-cards">
                <!-- Yearly Summary -->
                <div class="summary-card">
                    <div class="summary-card-header">
                        <div class="summary-card-icon blue">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="summary-card-badge">
                            {{ $currentYear }}
                        </div>
                    </div>
                    <div class="summary-card-content">
                        <h4>Yearly Leave Summary</h4>
                        <p>Total leave requests submitted this year</p>
                        <div class="summary-card-stats">
                            <div class="summary-stat">
                                <div class="summary-stat-label">Total</div>
                                <div class="summary-stat-value">{{ $yearlyLeaves->count() }}</div>
                            </div>
                            <div class="summary-stat">
                                <div class="summary-stat-label">Approved</div>
                                <div class="summary-stat-value">{{ $yearlyLeaves->filter(fn($r) => $normalizeStatus($r->RequestStatus) === 'approved')->count() }}</div>
                            </div>
                            <div class="summary-stat">
                                <div class="summary-stat-label">Pending</div>
                                <div class="summary-stat-value">{{ $yearlyLeaves->filter(fn($r) => $normalizeStatus($r->RequestStatus) !== 'approved' && !in_array($normalizeStatus($r->RequestStatus), ['rejected', 'rejected by admin']))->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Summary -->
                <div class="summary-card">
                    <div class="summary-card-header">
                        <div class="summary-card-icon green">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="summary-card-badge">
                            {{ date('M Y') }}
                        </div>
                    </div>
                    <div class="summary-card-content">
                        <h4>Monthly Activity</h4>
                        <p>Leave requests for current month</p>
                        <div class="summary-card-stats">
                            <div class="summary-stat">
                                <div class="summary-stat-label">This Month</div>
                                <div class="summary-stat-value">{{ $monthlyLeaves->count() }}</div>
                            </div>
                            <div class="summary-stat">
                                <div class="summary-stat-label">Avg Days</div>
                                <div class="summary-stat-value">{{ $avgLeaveDays }}</div>
                            </div>
                            <div class="summary-stat">
                                <div class="summary-stat-label">Common</div>
                                <div class="summary-stat-value">{{ Str::limit($mostCommonType, 8) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="summary-card">
                    <div class="summary-card-header">
                        <div class="summary-card-icon orange">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="summary-card-badge">
                            Quick Stats
                        </div>
                    </div>
                    <div class="summary-card-content">
                        <h4>Performance Metrics</h4>
                        <p>Your leave request statistics and patterns</p>
                        <div class="summary-card-stats">
                            <div class="summary-stat">
                                <div class="summary-stat-label">Avg Time</div>
                                <div class="summary-stat-value">{{ $avgLeaveDays }}d</div>
                            </div>
                            <div class="summary-stat">
                                <div class="summary-stat-label">Approval %</div>
                                <div class="summary-stat-value">{{ $leaveRequests->isNotEmpty() ? round(($counts['approved'] / $leaveRequests->count()) * 100) : 0 }}%</div>
                            </div>
                            <div class="summary-stat">
                                <div class="summary-stat-label">Total Req</div>
                                <div class="summary-stat-value">{{ $leaveRequests->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-title">Approved</div>
                    <div class="stat-value">{{ $counts['approved'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-title">Rejected</div>
                    <div class="stat-value">{{ $counts['rejected'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                    <div class="stat-title">Pending Supervisor</div>
                    <div class="stat-value">{{ $counts['pending_supervisor'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
                    <div class="stat-title">Pending Admin</div>
                    <div class="stat-value">{{ $counts['pending_admin'] }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-title">Annual Days</div>
                    <div class="stat-value">{{ $totalAssigned }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-title">Days Left</div>
                    <div class="stat-value">{{ $remainingDays }}</div>
                </div>
            </div>

            <!-- Leave Requests Table -->
            <div class="mt-4">
                <h5 class="mb-3" style="color: #2E3A87; font-weight: 600;">
                    <i class="fas fa-history me-2"></i> Leave Requests History
                </h5>
                
                <div class="table-responsive">
                    @if ($sortedLeaveRequests->isNotEmpty())
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sortedLeaveRequests as $request)
                                    @php
                                        $statusNormalized = $normalizeStatus($request->RequestStatus);
                                        $isRejected = in_array($statusNormalized, ['rejected', 'rejected by admin']);
                                        $rejectReason = $request->RejectionReason ?? $request->RejectioReason ?? null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-umbrella-beach me-2" style="color: #2E3A87;"></i>
                                                <span>{{ optional($request->leaveType)->LeaveTypeName ?? 'General' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge {{ 
                                                $statusNormalized === 'approved' ? 'badge-approved' :
                                                ($isRejected ? 'badge-rejected' : 'badge-pending')
                                            }}">
                                                <i class="fas fa-{{ 
                                                    $statusNormalized === 'approved' ? 'check' :
                                                    ($isRejected ? 'times' : 'clock')
                                                }} fa-xs"></i>
                                                {{ ucwords(str_replace('_', ' ', $statusNormalized)) }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') ?? 'N/A' }}</td>
                                        <td>
                                            @if($rejectReason)
                                                <span class="text-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ Str::limit($rejectReason, 30) }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h4>No Leave Requests</h4>
                            <p>You haven't submitted any leave requests yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bar
    const progressBar = document.querySelector('.leave-progress .progress-bar');
    if (progressBar) {
        const width = progressBar.style.width;
        progressBar.style.width = '0';
        setTimeout(() => {
            progressBar.style.transition = 'width 1s ease-in-out';
            progressBar.style.width = width;
        }, 300);
    }

    // Add row hover effects (matching Departments page)
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

    // Add hover effects to summary cards
    const summaryCards = document.querySelectorAll('.summary-card');
    summaryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.12)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.08)';
        });
    });
});
</script>
@endsection