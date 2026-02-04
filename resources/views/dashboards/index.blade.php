@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    /* Main dashboard styles */
    .dashboard-content {
        width: 100% !important;
        max-width: 100% !important;
        padding: 20px !important;
        background: #f8f9fa !important;
        min-height: calc(100vh - var(--header-height) - 70px);
        overflow-x: visible !important;
    }
    
    /* FIXED: Ensure content expands properly when sidebar opens */
    body.sidebar-open .dashboard-content {
        width: calc(100vw - var(--sidebar-width)) !important;
        max-width: calc(100vw - var(--sidebar-width)) !important;
        padding: 20px !important;
        overflow-x: auto !important;
    }
    
    /* Welcome banner */
    .welcome-banner {
        background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
        border-radius: 12px;
        padding: 20px;
        color: #2E3A87;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        width: 100% !important;
        max-width: 100% !important;
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
    
    .welcome-message h1 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 6px;
        color: #2E3A87;
    }
    
    .welcome-message p {
        font-size: 15px;
        opacity: 0.8;
        margin-bottom: 0;
        color: #6c757d;
    }
    
    .current-date {
        text-align: right;
    }
    
    .date-day {
        font-size: 18px;
        font-weight: 600;
        color: #2E3A87;
    }
    
    .date-full {
        font-size: 13px;
        opacity: 0.9;
        color: #6c757d;
    }
    
    /* Stats cards - UPDATED to smaller size */
    .stats-cards {
        margin-bottom: 20px;
        width: 100% !important;
    }
    
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 16px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
        height: 100%;
        width: 100% !important;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border-color: #dee2e6;
    }
    
    .stats-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .stats-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    
    .stats-badge {
        background: #f8f9fa;
        color: #6c757d;
        padding: 3px 8px;
        border-radius: 16px;
        font-size: 11px;
        font-weight: 500;
    }
    
    .stats-content {
        margin-bottom: 12px;
    }
    
    .stats-value {
        font-size: 24px;
        font-weight: 700;
        color: #2E3A87;
        line-height: 1;
        margin-bottom: 4px;
    }
    
    .stats-label {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }
    
    .stats-progress {
        margin-top: 6px;
    }
    
    .stats-progress .progress {
        border-radius: 3px;
        overflow: hidden;
        height: 3px !important;
    }
    
    /* Dashboard cards */
    .dashboard-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        margin-bottom: 20px;
        overflow: hidden;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .dashboard-card .card-header {
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 16px 20px;
    }
    
    .dashboard-card .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #2E3A87;
        margin: 0;
    }
    
    .dashboard-card .card-body {
        padding: 16px;
        width: 100% !important;
    }
    
    .dashboard-card .card-footer {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 12px 20px;
    }
    
    /* Department table */
    .department-index {
        width: 32px;
        height: 32px;
        background: #f0f2ff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #2E3A87;
        font-size: 13px;
    }
    
    .department-icon {
        width: 36px;
        height: 36px;
        background: rgba(46, 58, 135, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2E3A87;
        font-size: 16px;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 12px;
    }
    
    /* Table styles */
    .table {
        margin-bottom: 0;
        width: 100% !important;
        max-width: 100% !important;
        font-size: 14px;
    }
    
    .table-responsive {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: auto !important;
    }
    
    body.sidebar-open .table-responsive {
        max-width: calc(100vw - var(--sidebar-width) - 80px) !important;
    }
    
    .table thead th {
        border-top: none;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        color: #6c757d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 12px;
        white-space: nowrap;
        background-color: #f8f9fa;
    }
    
    .table tbody td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
        font-size: 14px;
    }
    
    .table tbody tr:hover {
        background-color: #f8faff;
    }
    
    .table tbody tr.table-active {
        background-color: rgba(46, 58, 135, 0.05);
    }
    
    /* Recent activity */
    .request-item {
        padding: 12px;
        border-radius: 8px;
        background: #f8f9fa;
        width: 100% !important;
        margin-bottom: 8px;
    }
    
    .request-item:hover {
        background: #e9ecef;
    }
    
    .request-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 12px;
    }
    
    /* Quick stats grid */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        width: 100% !important;
    }
    
    .stat-item {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        width: 100% !important;
    }
    
    .stat-label {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 6px;
        font-weight: 500;
    }
    
    .stat-value {
        font-size: 20px;
        font-weight: 700;
        line-height: 1;
        color: #2E3A87;
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 30px 16px;
    }
    
    .empty-state i {
        opacity: 0.5;
        font-size: 2rem;
    }
    
    .empty-state p {
        color: #6c757d;
        margin-top: 8px;
        font-size: 14px;
    }
    
    /* Buttons */
    .btn-outline-primary {
        border: 1px solid #2E3A87;
        color: #2E3A87;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 13px;
    }
    
    .btn-outline-primary:hover {
        background: #2E3A87;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(46, 58, 135, 0.2);
    }
    
    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
    }
    
    /* Card actions */
    .card-actions {
        display: flex;
        gap: 8px;
    }
    
    /* Responsive adjustments */
    @media (min-width: 992px) {
        body.sidebar-open .dashboard-content {
            width: calc(100vw - var(--sidebar-width)) !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
        }
        
        body.sidebar-open .welcome-banner,
        body.sidebar-open .dashboard-card,
        body.sidebar-open .stats-cards {
            max-width: calc(100vw - var(--sidebar-width) - 40px) !important;
        }
    }
    
    @media (max-width: 768px) {
        .dashboard-content {
            padding: 15px !important;
        }
        
        .welcome-banner {
            padding: 16px;
            text-align: center;
        }
        
        .current-date {
            text-align: center;
            margin-top: 12px;
        }
        
        .stats-value {
            font-size: 20px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .table-responsive {
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
    }
    
    @media (max-width: 576px) {
        .welcome-message h1 {
            font-size: 20px;
        }
        
        .stats-card {
            padding: 12px;
        }
        
        .stats-value {
            font-size: 18px;
        }
        
        .stats-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }
        
        .dashboard-card .card-header,
        .dashboard-card .card-body,
        .dashboard-card .card-footer {
            padding: 12px;
        }
        
        .table thead th,
        .table tbody td {
            padding: 10px 8px;
            font-size: 13px;
        }
        
        .department-index {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
        
        .department-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
    }
    
    /* Loading animations */
    .animate-stats {
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-content">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="welcome-message">
                    <h1>Welcome back, {{ auth()->user()->FirstName ?? 'Admin' }}! 👋</h1>
                    <p>Here's what's happening with your organization today.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="current-date">
                    <div class="date-day">{{ date('l') }}</div>
                    <div class="date-full">{{ date('F j, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - UPDATED with smaller, reasonable sizing -->
    <div class="row stats-cards mb-4">
        @php
            $cards = [
                ['Employees', $totalEmployees ?? 0, 'users', '#2E3A87', '+12%'],
                ['Male', $maleEmployees ?? 0, 'male', '#4A5BD9', '41%'],
                ['Female', $femaleEmployees ?? 0, 'female', '#e83e8c', '59%'],
                ['Positions', $totalPositions ?? 0, 'briefcase', '#28a745', 'Active'],
                ['Grades', $totalGrades ?? 0, 'layer-group', '#fd7e14', 'Levels'],
                ['Departments', $departments->count() ?? 0, 'building', '#8b5cf6', 'Units'],
            ];
        @endphp

        @foreach ($cards as [$label, $value, $icon, $color, $badge])
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="stats-card animate-stats" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="stats-card-header">
                        <div class="stats-icon" style="background: {{ $color }}20; color: {{ $color }};">
                            <i class="fas fa-{{ $icon }}"></i>
                        </div>
                        <div class="stats-badge">{{ $badge }}</div>
                    </div>
                    <div class="stats-content">
                        <div class="stats-value">{{ number_format($value) }}</div>
                        <div class="stats-label">{{ $label }}</div>
                    </div>
                    <div class="stats-progress">
                        <div class="progress" style="height: 3px;">
                            <div class="progress-bar" style="width: {{ $loop->index % 2 == 0 ? '75%' : '60%' }}; background: {{ $color }};"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Dashboard Content Grid -->
    <div class="row dashboard-row">
        <!-- Department Overview -->
        <div class="col-lg-8 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-building me-2" style="color: #2E3A87;"></i>
                            Department Overview
                        </h5>
                        <div class="card-actions">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> View All
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (isset($departments) && $departments->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Department</th>
                                        <th class="text-center">Employees</th>
                                        <th>Supervisor</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments->take(5) as $department)
                                        
                                            <td>
                                                <div class="department-index">{{ $loop->iteration }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="department-icon me-3">
                                                        @php
                                                            $icons = ['cogs', 'headset', 'stethoscope', 'flask', 'pills', 'x-ray'];
                                                            $iconIndex = $loop->index % count($icons);
                                                        @endphp
                                                        <i class="fas fa-{{ $icons[$iconIndex] }}"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold" style="color: #2E3A87;">{{ $department->DepartmentName ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $department->Description ?? 'No description' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge" style="background: rgba(46, 58, 135, 0.1); color: #2E3A87;">
                                                    {{ $department->employees_count ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                @if (isset($department->supervisor) && $department->supervisor)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-2">
                                                            {{ substr($department->supervisor->FirstName ?? '', 0, 1) }}{{ substr($department->supervisor->LastName ?? '', 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium" style="color: #2E3A87;">{{ $department->supervisor->FirstName ?? '' }} {{ $department->supervisor->LastName ?? '' }}</div>
                                                            <small class="text-muted">Supervisor</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                                                    <i class="fas fa-circle fa-xs me-1"></i> Active
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-building text-muted mb-3"></i>
                            <p class="text-muted mb-0">No departments found.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('departments.index') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-eye me-2"></i> View All Departments
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Stats -->
        <div class="col-lg-4 mb-4">
            <!-- Recent Leave Requests -->
            <div class="dashboard-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2" style="color: #fd7e14;"></i>
                        Pending Approvals
                    </h5>
                </div>
                <div class="card-body">
                    @if (isset($recentLeaveRequests) && $recentLeaveRequests->isNotEmpty())
                        <div class="requests-list">
                            @foreach ($recentLeaveRequests->take(4) as $request)
                                <div class="request-item">
                                    <div class="d-flex align-items-start">
                                        <div class="request-avatar me-3">
                                            {{ substr($request->employee->FirstName ?? '', 0, 1) }}{{ substr($request->employee->LastName ?? '', 0, 1) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1" style="color: #2E3A87; font-size: 14px;">{{ $request->employee->FirstName ?? '' }} {{ $request->employee->LastName ?? '' }}</h6>
                                                    <p class="text-muted small mb-1">
                                                        {{ $request->leaveType->LeaveTypeName ?? 'N/A' }} · 
                                                        {{ isset($request->created_at) ? $request->created_at->format('M d') : 'N/A' }}
                                                    </p>
                                                </div>
                                                @php
                                                    $status = $request->RequestStatus ?? 'pending';
                                                    $statusClass = $status == 'approved' ? 'success' : 
                                                                  ($status == 'rejected' ? 'danger' : 'warning');
                                                    $statusColors = [
                                                        'success' => ['bg' => '#28a745', 'text' => '#28a745'],
                                                        'danger' => ['bg' => '#dc3545', 'text' => '#dc3545'],
                                                        'warning' => ['bg' => '#ffc107', 'text' => '#ffc107']
                                                    ];
                                                @endphp
                                                <span class="badge" style="background: rgba({{ hexdec(substr($statusColors[$statusClass]['bg'], 1, 2)) }}, {{ hexdec(substr($statusColors[$statusClass]['bg'], 3, 2)) }}, {{ hexdec(substr($statusColors[$statusClass]['bg'], 5, 2)) }}, 0.1); color: {{ $statusColors[$statusClass]['text'] }};">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </div>
                                            <div class="progress" style="height: 3px;">
                                                @php
                                                    $progress = $status == 'approved' ? 100 : 
                                                                ($status == 'rejected' ? 100 : 60);
                                                @endphp
                                                <div class="progress-bar" 
                                                     style="width: {{ $progress }}%; background: {{ $statusColors[$statusClass]['bg'] }};"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-inbox text-muted mb-3"></i>
                            <p class="text-muted mb-0">No pending requests.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2" style="color: #0dcaf0;"></i>
                        Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-label">Today's Leaves</div>
                            <div class="stat-value">{{ $todayLeaves ?? '0' }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">This Week</div>
                            <div class="stat-value">{{ $weekLeaves ?? '0' }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">This Month</div>
                            <div class="stat-value">{{ $monthLeaves ?? '0' }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Approval Rate</div>
                            <div class="stat-value">{{ $approvalRate ?? '0' }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Admin Dashboard Loaded');
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Add click effects to department rows
        const tableRows = document.querySelectorAll('tbody tr[style*="cursor: pointer"]');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8faff';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
        
        // Adjust dashboard content when sidebar opens/closes
        const adjustDashboardWidth = function() {
            const dashboardContent = document.querySelector('.dashboard-content');
            const isDesktop = window.innerWidth >= 992;
            const sidebarOpen = document.body.classList.contains('sidebar-open');
            
            if (dashboardContent) {
                if (isDesktop && sidebarOpen) {
                    const sidebarWidth = 320;
                    dashboardContent.style.width = `calc(100vw - ${sidebarWidth}px)`;
                    dashboardContent.style.maxWidth = `calc(100vw - ${sidebarWidth}px)`;
                } else {
                    dashboardContent.style.width = '100%';
                    dashboardContent.style.maxWidth = '100%';
                }
            }
        };
        
        // Listen for sidebar events
        const sidebar = document.getElementById('mainSidebar');
        if (sidebar) {
            sidebar.addEventListener('show.bs.offcanvas', adjustDashboardWidth);
            sidebar.addEventListener('hide.bs.offcanvas', adjustDashboardWidth);
            
            // Also adjust on window resize
            window.addEventListener('resize', adjustDashboardWidth);
            
            // Initial adjustment
            setTimeout(adjustDashboardWidth, 100);
        }
        
        // Refresh dashboard data every 60 seconds
        setInterval(() => {
            console.log('Refreshing dashboard data...');
            // Add AJAX call here to refresh data without page reload
        }, 60000);
    });
</script>
@endsection