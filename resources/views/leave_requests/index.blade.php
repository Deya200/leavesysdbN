@extends('layouts.app')
@section('page_title', 'Leave Requests')
@section('title', 'Supervisor Dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    :root {
        --primary: #2c3e50;
        --primary-dark: #1a252f;
        --success: #27ae60;
        --success-light: #e8f5e9;
        --danger: #e74c3c;
        --danger-light: #fbe9e7;
        --warning: #f39c12;
        --warning-light: #fff3e0;
        --info: #3498db;
        --info-light: #e3f2fd;
        --gray-light: #f8f9fa;
        --gray-medium: #ecf0f1;
        --text-primary: #2c3e50;
        --text-secondary: #7f8c8d;
        --border-color: #e9ecef;
        --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 6px 12px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .lr-page {
        padding: 2rem 1.5rem;
        max-width: 1600px;
        margin: 0 auto;
    }

    .dashboard-header {
        margin-bottom: 2rem;
        background: white;
        border-radius: 24px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-title h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .header-title p {
        color: var(--text-secondary);
        margin: 0.25rem 0 0 0;
        font-size: 0.95rem;
    }

    .header-badge {
        background: var(--gray-light);
        padding: 0.75rem 1.5rem;
        border-radius: 40px;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid var(--border-color);
    }

    .header-badge i {
        color: var(--info);
        margin-right: 0.5rem;
    }

    .summary-card {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        transition: var(--transition);
        height: 100%;
        position: relative;
        background: white;
        box-shadow: var(--shadow-sm);
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .summary-card .card-body {
        padding: 1.75rem;
    }

    .summary-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        transition: var(--transition);
    }

    .summary-card:hover .summary-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .summary-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--text-secondary);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .summary-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .summary-trend {
        font-size: 0.85rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.5rem;
    }

    .summary-progress {
        margin-top: 1.25rem;
        height: 6px;
        border-radius: 10px;
        background: var(--gray-medium);
        overflow: hidden;
    }

    .summary-progress-bar {
        height: 100%;
        border-radius: 10px;
        transition: width 1s ease-in-out;
    }

    .lr-filter-form {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        transition: var(--transition);
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--info);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        outline: none;
    }

    .input-group .btn {
        border-radius: 12px;
        padding: 0.65rem 1.25rem;
    }

    .lr-search-btn, .lr-filter-btn {
        background: var(--primary);
        color: white;
        border: none;
        font-weight: 500;
        transition: var(--transition);
    }

    .lr-search-btn:hover, .lr-filter-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
        color: white;
    }

    .lr-table-shell {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .lr-table-scroll {
        overflow: auto;
        max-height: 70vh;
        scrollbar-width: thin;
        scrollbar-color: var(--text-secondary) var(--gray-medium);
    }

    .lr-table-scroll::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .lr-table-scroll::-webkit-scrollbar-track {
        background: var(--gray-medium);
        border-radius: 10px;
    }

    .lr-table-scroll::-webkit-scrollbar-thumb {
        background: var(--text-secondary);
        border-radius: 10px;
    }

    .lr-table {
        margin-bottom: 0;
        min-width: 1200px;
    }

    .lr-table thead th {
        position: sticky;
        top: 0;
        background: #fafbfc;
        color: var(--text-secondary);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1.25rem 1rem;
        border-bottom: 2px solid var(--border-color);
        white-space: nowrap;
    }

    .lr-table tbody td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .lr-table tbody tr:hover td {
        background: #f8faff;
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--info-light), #d4e6f1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--info);
        font-weight: 600;
        font-size: 1rem;
    }

    .employee-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    .lr-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 40px;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
        border: 1px solid rgba(0, 0, 0, 0.05);
        background: white;
    }

    .lr-badge i {
        font-size: 0.9rem;
    }

    .lr-badge-approved {
        background: var(--success-light);
        color: #1e7e34;
    }

    .lr-badge-rejected {
        background: var(--danger-light);
        color: #c0392b;
    }

    .lr-badge-pending-admin {
        background: var(--info-light);
        color: #2980b9;
    }

    .lr-badge-pending {
        background: var(--warning-light);
        color: #b45f06;
    }

    .action-stack {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 160px;
    }

    .btn-action {
        border: none;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: var(--transition);
        width: 100%;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .btn-action i {
        font-size: 0.9rem;
    }

    .btn-approve {
        background: var(--success-light);
        color: #1e7e34;
    }

    .btn-approve:hover {
        background: #c8e6c9;
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
        color: #1e7e34;
    }

    .btn-reject {
        background: var(--danger-light);
        color: #c0392b;
    }

    .btn-reject:hover {
        background: #ffcdd2;
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
        color: #c0392b;
    }

    .no-actions {
        color: var(--text-secondary);
        font-size: 0.85rem;
        font-style: italic;
        text-align: center;
        padding: 0.5rem;
    }

    .text-center-cell {
        text-align: center;
    }

    .modal-content {
        border-radius: 24px;
        border: none;
        box-shadow: var(--shadow-lg);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border-radius: 24px 24px 0 0;
        padding: 1.25rem 1.5rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 1.25rem 1.5rem;
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .lr-page {
            padding: 1rem;
        }

        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .summary-card .card-body {
            padding: 1.25rem;
        }

        .summary-value {
            font-size: 1.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="lr-page">
    <!-- Archived Requests Notice -->
    @if(auth()->check() && auth()->user()->role_id === 3)
        <div class="alert alert-info alert-dismissible fade show mb-4 border-0 shadow-sm animate-fade-in" role="alert" style="border-radius: 16px; background-color: #e3f2fd; border-left: 5px solid #3498db !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-3 fs-4 text-primary"></i>
                <div>
                    <strong>Archived Requests:</strong> Previous years' leave requests have been archived for record-keeping. Only current year requests are displayed here.
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(auth()->check() && auth()->user()->role_id === 1)
        <div class="alert alert-warning alert-dismissible fade show mb-4 border-0 shadow-sm animate-fade-in" role="alert" style="border-radius: 16px; background-color: #fff8e1; border-left: 5px solid #ffc107 !important;">
            <div class="d-flex justify-content-between align-items-center pe-5 w-100">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-20 p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="fas fa-archive text-warning fs-5"></i>
                    </div>
                    <div>
                        <strong class="text-dark">Admin View:</strong> 
                        <span class="text-muted small">You are viewing all leave requests including archived ones.</span>
                    </div>
                </div>
                <a href="{{ route('leave_requests.archive_manager') }}" class="btn btn-sm btn-success px-4 rounded-pill shadow-sm">
                    <i class="fas fa-archive me-2"></i> Manage Archive
                </a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="dashboard-header animate-fade-in">
        <div class="header-title">
            <h1><i class="fas fa-chart-line me-3" style="color: var(--info);"></i>Supervisor Dashboard</h1>
            <p>Manage and oversee leave requests from your team</p>
        </div>
        <div class="header-badge">
            <i class="fas fa-calendar-alt"></i>
            {{ now()->format('l, F j, Y') }}
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="summary-card animate-fade-in" style="animation-delay: 0.1s;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="summary-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="summary-label">Total</span>
                    </div>
                    <div class="summary-value">{{ $totalCount ?? $leaveRequests->total() }}</div>
                    <div class="summary-trend">
                        <i class="fas fa-arrow-up text-success"></i>
                        <span>All requests</span>
                    </div>
                    <div class="summary-progress">
                        <div class="summary-progress-bar" style="width: 100%; background: linear-gradient(135deg, #3498db, #2980b9);"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card animate-fade-in" style="animation-delay: 0.2s;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="summary-icon" style="background: linear-gradient(135deg, #27ae60, #219a52);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="summary-label">Approved</span>
                    </div>
                    <div class="summary-value">{{ $approvedCount ?? 0 }}</div>
                    <div class="summary-trend">
                        <i class="fas fa-percent text-muted"></i>
                        <span>{{ $totalCount > 0 ? round(($approvedCount / $totalCount) * 100) : 0 }}% of total</span>
                    </div>
                    <div class="summary-progress">
                        <div class="summary-progress-bar" style="width: {{ $totalCount > 0 ? ($approvedCount / $totalCount) * 100 : 0 }}%; background: linear-gradient(135deg, #27ae60, #219a52);"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card animate-fade-in" style="animation-delay: 0.3s;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="summary-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <span class="summary-label">Rejected</span>
                    </div>
                    <div class="summary-value">{{ $rejectedCount ?? 0 }}</div>
                    <div class="summary-trend">
                        <i class="fas fa-percent text-muted"></i>
                        <span>{{ $totalCount > 0 ? round(($rejectedCount / $totalCount) * 100) : 0 }}% of total</span>
                    </div>
                    <div class="summary-progress">
                        <div class="summary-progress-bar" style="width: {{ $totalCount > 0 ? ($rejectedCount / $totalCount) * 100 : 0 }}%; background: linear-gradient(135deg, #e74c3c, #c0392b);"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="summary-card animate-fade-in" style="animation-delay: 0.4s;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="summary-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span class="summary-label">Pending</span>
                    </div>
                    <div class="summary-value">{{ $pendingCount ?? 0 }}</div>
                    <div class="summary-trend">
                        <i class="fas fa-hourglass-half text-warning"></i>
                        <span>Awaiting action</span>
                    </div>
                    <div class="summary-progress">
                        <div class="summary-progress-bar" style="width: {{ $totalCount > 0 ? ($pendingCount / $totalCount) * 100 : 0 }}%; background: linear-gradient(135deg, #f39c12, #e67e22);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('leave_requests.index') }}" class="lr-filter-form animate-fade-in" style="animation-delay: 0.5s;">
        <div class="row g-3 align-items-end">
            <div class="col-lg-7">
                <label for="lr-search" class="form-label">
                    <i class="fas fa-search me-2"></i>Search Employee
                </label>
                <div class="input-group">
                    <input id="lr-search" type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                    <button type="submit" class="btn lr-search-btn" data-bs-toggle="tooltip" title="Search">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <label for="lr-status" class="form-label">
                    <i class="fas fa-filter me-2"></i>Status Filter
                </label>
                <select id="lr-status" name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Pending Supervisor Approval">Pending Supervisor Approval</option>
                    <option value="Pending Admin Verification">Pending Admin Verification</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <!-- Archived Filter (Admin Only) -->
                @if(auth()->check() && auth()->user()->role_id === 1)
                    <div class="mb-3">
                        <select name="archived" class="form-select">
                            <option value="">All (Active & Archived)</option>
                            <option value="0" {{ request('archived') === '0' ? 'selected' : '' }}>Active Only</option>
                            <option value="1" {{ request('archived') === '1' ? 'selected' : '' }}>Archived Only</option>
                        </select>
                    </div>
                @endif
                <button type="submit" class="btn lr-filter-btn w-100" data-bs-toggle="tooltip" title="Apply Filter">
                    <i class="fas fa-filter me-2"></i>Apply
                </button>
            </div>
        </div>
    </form>

    <div class="lr-table-shell animate-fade-in" style="animation-delay: 0.6s;">
        <div class="lr-table-scroll">
            <table class="table lr-table">
                <thead>
                    <tr>
                        <th class="text-center-cell">#</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th class="text-center-cell">Start Date</th>
                        <th class="text-center-cell">End Date</th>
                        <th class="text-center-cell">Days</th>
                        <th class="text-center-cell">Status</th>
                        <th class="text-center-cell">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaveRequests as $request)
                        <tr style="{{ $request->is_archived ? 'opacity: 0.7; background-color: #f5f5f5;' : '' }}">
                            <td class="text-center-cell">
                                <span class="fw-semibold">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="employee-info">
                                    <div class="employee-avatar">
                                        {{ strtoupper(substr($request->employee->FirstName, 0, 1) . substr($request->employee->LastName, 0, 1)) }}
                                    </div>
                                    <div class="employee-name d-flex flex-column">
                                        {{ $request->employee->FirstName }} {{ $request->employee->LastName }}
                                        @if($request->is_archived)
                                            <span class="badge bg-secondary mt-1" style="font-size: 0.65rem; width: fit-content;">
                                                <i class="fas fa-archive me-1"></i> Archived
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $request->leaveType->LeaveTypeName }}</span>
                            </td>
                            <td class="text-center-cell text-nowrap">{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</td>
                            <td class="text-center-cell text-nowrap">{{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</td>
                            <td class="text-center-cell">
                                <span class="badge bg-secondary bg-opacity-10 text-dark">{{ $request->TotalDays }}d</span>
                            </td>
                            <td class="text-center-cell">
                                <span class="lr-badge
                                    {{ $request->RequestStatus === 'Approved' ? 'lr-badge-approved' :
                                     ($request->RequestStatus === 'Rejected' ? 'lr-badge-rejected' :
                                     ($request->RequestStatus === 'Pending Admin Verification' ? 'lr-badge-pending-admin' : 'lr-badge-pending')) }}">
                                    <i class="{{ $request->RequestStatus === 'Approved' ? 'fas fa-check-circle' :
                                             ($request->RequestStatus === 'Rejected' ? 'fas fa-times-circle' :
                                             ($request->RequestStatus === 'Admin ' ? 'fas fa-tools' : 'fas fa-clock')) }}"></i>
                                    {{ ucfirst($request->RequestStatus) }}
                                </span>
                            </td>
                            <td class="text-center-cell">
                                @php
                                    $canAdminAction = strcasecmp($request->RequestStatus, 'Pending Admin Verification') === 0;
                                    $canSupAction = strcasecmp($request->RequestStatus, 'Pending Supervisor Approval') === 0;
                                @endphp

                                @if ($canSupAction)
                                    <div class="action-stack">
                                        <button type="button" class="btn-action btn-approve"
                                            onclick="openConfirmModal('approve', '{{ route('leave_requests.supervisor.approve', $request->LeaveRequestID) }}', 'Supervisor Approval', 'SupervisorApprovalNote')">
                                            <i class="fas fa-check-circle"></i> Approve
                                        </button>
                                        <button type="button" class="btn-action btn-reject"
                                            onclick="openConfirmModal('reject', '{{ route('leave_requests.supervisor.reject', $request->LeaveRequestID) }}', 'Supervisor Rejection', 'SupervisorRejectionReason')">
                                            <i class="fas fa-times-circle"></i> Reject
                                        </button>
                                    </div>
                                @elseif ($canAdminAction)
                                    <div class="action-stack">
                                        <button type="button" class="btn-action btn-approve"
                                            onclick="openConfirmModal('approve', '{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}', 'Admin Approval', 'AdminApprovalNote')">
                                            <i class="fas fa-check-circle"></i> Admin Approve
                                        </button>
                                        <button type="button" class="btn-action btn-reject"
                                            onclick="openConfirmModal('reject', '{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}', 'Admin Rejection', 'AdminRejectionReason')">
                                            <i class="fas fa-times-circle"></i> Admin Reject
                                        </button>
                                    </div>
                                @else
                                    <div class="no-actions">
                                        <i class="fas fa-lock me-1"></i>
                                        No actions available
                                    </div>
                                @endif

                                <!-- Admin Archive/Restore Actions -->
                                @if (auth()->user()->role_id === 1)
                                    <div class="mt-2 pt-2 border-top">
                                        @if ($request->is_archived)
                                            <form action="{{ route('leave_requests.restore', $request->LeaveRequestID) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning w-100" onclick="return confirm('Restore this archived request?')">
                                                    <i class="fas fa-undo me-1"></i> Restore
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('leave_requests.archive', $request->LeaveRequestID) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary w-100" onclick="return confirm('Archive this request?')">
                                                    <i class="fas fa-archive me-1"></i> Archive
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if(method_exists($leaveRequests, 'links'))
        <div class="mt-4">
            {{ $leaveRequests->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Modals -->
    <div class="modal fade" id="actionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="actionForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Confirm Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="modalMessage">Are you sure you want to perform this action?</p>
                        <div id="noteContainer">
                            <label for="actionNote" class="form-label">Note/Reason:</label>
                            <textarea name="note" id="actionNote" class="form-control" rows="4" placeholder="Enter details here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Include View Leave Modal -->
    @include('leave_requests._view_modal')
</div>
@endsection

@section('scripts')
<script>
    function openConfirmModal(type, url, title, fieldName = null) {
        const form = document.getElementById('actionForm');
        const titleEl = document.getElementById('modalTitle');
        const messageEl = document.getElementById('modalMessage');
        const noteEl = document.getElementById('actionNote');
        const submitBtn = document.getElementById('submitBtn');

        form.action = url;
        titleEl.innerText = title;

        noteEl.value = '';
        noteEl.required = (type === 'reject');

        let defaultField = '';
        if (type === 'approve') {
            defaultField = url.includes('admin') ? 'AdminApprovalNote' : 'SupervisorApprovalNote';
            messageEl.innerText = 'Please provide an optional approval note.';
            submitBtn.className = 'btn btn-success';
            submitBtn.innerText = 'Confirm Approval';
        } else {
            defaultField = url.includes('admin') ? 'AdminRejectionReason' : 'SupervisorRejectionReason';
            messageEl.innerText = 'Please provide a mandatory rejection reason.';
            submitBtn.className = 'btn btn-danger';
            submitBtn.innerText = 'Confirm Rejection';
        }

        noteEl.name = fieldName || defaultField;
        noteEl.placeholder = type === 'reject' ? 'Rejection reason is required...' : 'Optional approval notes...';

        const modal = new bootstrap.Modal(document.getElementById('actionModal'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

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