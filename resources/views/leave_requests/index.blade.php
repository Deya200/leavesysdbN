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
<<<<<<< HEAD
<div class="lr-page">
    <div class="dashboard-header animate-fade-in">
        <div class="header-title">
            <h1><i class="fas fa-chart-line me-3" style="color: var(--info);"></i>Supervisor Dashboard</h1>
            <p>Manage and oversee leave requests from your team</p>
        </div>
        <div class="header-badge">
            <i class="fas fa-calendar-alt"></i>
            {{ now()->format('l, F j, Y') }}
=======
<div class="container mt-3 animate__animated animate__fadeInDown">
   
   <!-- Archived Requests Notice (for employees) -->
   @if(auth()->check() && auth()->user()->role_id === 3)
   <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Archived Requests:</strong> Previous years' leave requests have been archived for record-keeping. Only current year requests are displayed here.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
   @elseif(auth()->check() && auth()->user()->role_id === 1)
   <div class="alert alert-warning alert-dismissible fade show mb-3 shadow-sm border-0" role="alert" style="background-color: #fef3c7; border-left: 4px solid #f59e0b !important;">
        <div class="d-flex justify-content-between align-items-center pe-5">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-20 p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="fas fa-archive text-warning"></i>
                </div>
                <div>
                    <strong class="text-dark">Admin View:</strong> 
                    <span class="text-muted small">You are viewing all leave requests including archived ones.</span>
                </div>
            </div>
            <a href="{{ route('leave_requests.archive_manager') }}" class="btn btn-sm btn-success px-3 shadow-sm">
                <i class="fas fa-archive me-1"></i> Manage Archive
            </a>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="top: 50%; transform: translateY(-50%); right: 15px; opacity: 0.7;"></button>
    </div>
   @endif

   <div class="row text-center">
    <!-- Welcome Section -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 animate__animated animate__fadeIn" style="background: linear-gradient(to right, #ffffff, #f8fafc);">
        <div class="card-body p-4 p-lg-5 text-center">
            <h3 class="fw-bold mb-2" style="color: #1e293b; letter-spacing: -0.5px;">Welcome back, {{ $employee->FirstName ?? 'Workflow' }}!</h3>
            <p class="text-slate-500 mb-0" style="font-size: 1.1rem;">I hope you are having an amazing day!</p>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
        </div>
    </div>

    <div class="row g-4 mb-5">
<<<<<<< HEAD
        <div class="col-xl-3 col-md-6">
            <div class="summary-card animate-fade-in" style="animation-delay: 0.1s;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="summary-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                            <i class="fas fa-file-alt"></i>
=======
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden h-100 animate__animated animate__fadeInUp">
                <div class="card-body p-4" style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);">
                    <div class="d-flex align-items-center justify-content-between mb-3 text-white">
                        <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-20 rounded-3 shadow-inner" style="width: 56px; height: 56px;">
                            <i class="fas fa-file-alt fa-lg"></i>
                        </div>
                        <div class="text-end">
                            <h6 class="text-white text-opacity-80 mb-1 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.1em;">Total Requests</h6>
                            <h2 class="mb-0 fw-bold" style="font-size: 1.85rem;">{{ $totalCount ?? $leaveRequests->total() }}</h2>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                        </div>
                        <span class="summary-label">Total</span>
                    </div>
<<<<<<< HEAD
                    <div class="summary-value">{{ $totalCount ?? $leaveRequests->total() }}</div>
                    <div class="summary-trend">
                        <i class="fas fa-arrow-up text-success"></i>
                        <span>All requests</span>
                    </div>
                    <div class="summary-progress">
                        <div class="summary-progress-bar" style="width: 100%; background: linear-gradient(135deg, #3498db, #2980b9);"></div>
=======
                    <div class="progress bg-black bg-opacity-10" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: 100%; opacity: 0.8;"></div>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                    </div>
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <div class="col-xl-3 col-md-6">
            <div class="summary-card animate-fade-in" style="animation-delay: 0.2s;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="summary-icon" style="background: linear-gradient(135deg, #27ae60, #219a52);">
                            <i class="fas fa-check-circle"></i>
=======
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <div class="card-body p-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="d-flex align-items-center justify-content-between mb-3 text-white">
                        <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-20 rounded-3 shadow-inner" style="width: 56px; height: 56px;">
                            <i class="fas fa-check-double fa-lg"></i>
                        </div>
                        <div class="text-end">
                            <h6 class="text-white text-opacity-80 mb-1 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.1em;">Approved</h6>
                            <h2 class="mb-0 fw-bold" style="font-size: 1.85rem;">{{ $approvedCount ?? 0 }}</h2>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                        </div>
                        <span class="summary-label">Approved</span>
                    </div>
<<<<<<< HEAD
                    <div class="summary-value">{{ $approvedCount ?? 0 }}</div>
                    <div class="summary-trend">
                        <i class="fas fa-percent text-muted"></i>
                        <span>{{ $totalCount > 0 ? round(($approvedCount / $totalCount) * 100) : 0 }}% of total</span>
                    </div>
                    <div class="summary-progress">
                        <div class="summary-progress-bar" style="width: {{ $totalCount > 0 ? ($approvedCount / $totalCount) * 100 : 0 }}%; background: linear-gradient(135deg, #27ae60, #219a52);"></div>
=======
                    @php $approvedPercent = ($totalCount > 0) ? ($approvedCount / $totalCount) * 100 : 0; @endphp
                    <div class="progress bg-black bg-opacity-10" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $approvedPercent }}%; opacity: 0.8;"></div>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                    </div>
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <div class="col-xl-3 col-md-6">
            <div class="summary-card animate-fade-in" style="animation-delay: 0.3s;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="summary-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                            <i class="fas fa-times-circle"></i>
=======
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <div class="card-body p-4" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                    <div class="d-flex align-items-center justify-content-between mb-3 text-white">
                        <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-20 rounded-3 shadow-inner" style="width: 56px; height: 56px;">
                            <i class="fas fa-ban fa-lg"></i>
                        </div>
                        <div class="text-end">
                            <h6 class="text-white text-opacity-80 mb-1 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.1em;">Rejected</h6>
                            <h2 class="mb-0 fw-bold" style="font-size: 1.85rem;">{{ $rejectedCount ?? 0 }}</h2>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                        </div>
                        <span class="summary-label">Rejected</span>
                    </div>
<<<<<<< HEAD
                    <div class="summary-value">{{ $rejectedCount ?? 0 }}</div>
                    <div class="summary-trend">
                        <i class="fas fa-percent text-muted"></i>
                        <span>{{ $totalCount > 0 ? round(($rejectedCount / $totalCount) * 100) : 0 }}% of total</span>
                    </div>
                    <div class="summary-progress">
                        <div class="summary-progress-bar" style="width: {{ $totalCount > 0 ? ($rejectedCount / $totalCount) * 100 : 0 }}%; background: linear-gradient(135deg, #e74c3c, #c0392b);"></div>
=======
                    @php $rejectedPercent = ($totalCount > 0) ? ($rejectedCount / $totalCount) * 100 : 0; @endphp
                    <div class="progress bg-black bg-opacity-10" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $rejectedPercent }}%; opacity: 0.8;"></div>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                    </div>
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <div class="col-xl-3 col-md-6">
            <div class="summary-card animate-fade-in" style="animation-delay: 0.4s;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="summary-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                            <i class="fas fa-clock"></i>
=======
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <div class="card-body p-4" style="background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);">
                    <div class="d-flex align-items-center justify-content-between mb-3 text-white">
                        <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-20 rounded-3 shadow-inner" style="width: 56px; height: 56px;">
                            <i class="fas fa-hourglass-half fa-lg"></i>
                        </div>
                        <div class="text-end">
                            <h6 class="text-white text-opacity-80 mb-1 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.1em;">Pending</h6>
                            <h2 class="mb-0 fw-bold" style="font-size: 1.85rem;">{{ $pendingCount ?? 0 }}</h2>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                        </div>
                        <span class="summary-label">Pending</span>
                    </div>
<<<<<<< HEAD
                    <div class="summary-value">{{ $pendingCount ?? 0 }}</div>
                    <div class="summary-trend">
                        <i class="fas fa-hourglass-half text-warning"></i>
                        <span>Awaiting action</span>
                    </div>
                    <div class="summary-progress">
                        <div class="summary-progress-bar" style="width: {{ $totalCount > 0 ? ($pendingCount / $totalCount) * 100 : 0 }}%; background: linear-gradient(135deg, #f39c12, #e67e22);"></div>
=======
                    @php $pendingPercent = ($totalCount > 0) ? ($pendingCount / $totalCount) * 100 : 0; @endphp
                    <div class="progress bg-black bg-opacity-10" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $pendingPercent }}%; opacity: 0.8;"></div>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
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
<<<<<<< HEAD
            <div class="col-lg-2 col-md-6">
                <button type="submit" class="btn lr-filter-btn w-100" data-bs-toggle="tooltip" title="Apply Filter">
                    <i class="fas fa-filter me-2"></i>Apply
=======

            @if(auth()->check() && auth()->user()->role_id === 1)
            <div class="col-md-4">
                <select name="archived" class="form-select">
                    <option value="">All Requests</option>
                    <option value="0" {{ request('archived') === '0' ? 'selected' : '' }}>Active Only</option>
                    <option value="1" {{ request('archived') === '1' ? 'selected' : '' }}>Archived Only</option>
                </select>
            </div>
            @endif

            <div class="col-md-4">
                <button type="submit" class="btn" style="background-color:rgb(2, 43, 114);"data-bs-toggle="tooltip" data-bs-placement="bottom" title="Apply Filter">
                    <i class="fas fa-filter" style="color:white" ></i>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                </button>
            </div>
        </div>
    </form>

<<<<<<< HEAD
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
                        <tr>
                            <td class="text-center-cell">
                                <span class="fw-semibold">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="employee-info">
                                    <div class="employee-avatar">
                                        {{ strtoupper(substr($request->employee->FirstName, 0, 1) . substr($request->employee->LastName, 0, 1)) }}
                                    </div>
                                    <div class="employee-name">
                                        {{ $request->employee->FirstName }} {{ $request->employee->LastName }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $request->leaveType->LeaveTypeName }}</span>
                            </td>
                            <td class="text-center-cell">{{ $request->StartDate }}</td>
                            <td class="text-center-cell">{{ $request->EndDate }}</td>
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
=======

<!-- Leave Requests Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th id="main-table" style="border: none;">#</th>
                    <th id="main-table" style="border: none;">Employee</th>
                    <th id="main-table" style="border: none;">Leave Type</th>
                    <th id="main-table" style="border: none;">Start Date</th>
                    <th id="main-table" style="border: none;">End Date</th>
                    <th id="main-table" style="border: none;">Total Days</th>
                    <th id="main-table" style="border: none;">Status</th>
                    <th id="main-table" style="border: none;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaveRequests as $request)
                    <tr style="{{ $request->is_archived ? 'opacity: 0.7; background-color: #f5f5f5;' : '' }}">
                        <td style="border: none;">{{ $loop->iteration }}</td>
                        <td style="border: none;">
                            <div>
                                {{ $request->employee->FirstName }} {{ $request->employee->LastName }}
                                @if($request->is_archived)
                                    <br><small class="badge bg-secondary">
                                        <i class="fas fa-archive me-1"></i> Archived
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td style="border: none;">{{ $request->leaveType->LeaveTypeName }}</td>
                        <td style="border: none;" class="text-nowrap">{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</td>
                        <td style="border: none;" class="text-nowrap">{{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</td>
                        <td style="border: none;">{{ $request->TotalDays }} days</td>
                        <td style="border: none;">
                            <span class="badge
                                {{ $request->RequestStatus === 'Approved' ? 'bg-success' :
                                 ($request->RequestStatus === 'Rejected' ? 'bg-danger' :
                                 ($request->RequestStatus === 'Pending Admin Verification' ? 'bg-primary' : 'bg-warning text-dark')) }}">
                                  <i class="{{ $request->RequestStatus === 'Approved' ? 'fas fa-check-circle' :
                                 ($request->RequestStatus === 'Rejected' ? 'fas fa-times-circle' :
                                 ($request->RequestStatus === 'Admin ' ? 'fas fa-tools' : 'fas fa-clock')) }}"></i>
                                {{ ucfirst($request->RequestStatus) }}
                            </span>
                        </td>
                        <td style="border: none;">

>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                                @php
                                    $canAdminAction = strcasecmp($request->RequestStatus, 'Pending Admin Verification') === 0;
                                    $canSupAction = strcasecmp($request->RequestStatus, 'Pending Supervisor Approval') === 0;
                                @endphp

<<<<<<< HEAD
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
=======
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle py-1 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v me-1"></i> Actions
                                    </button>
                                    <ul class="dropdown-menu shadow-sm">
                                        <li>
                                            <button class="dropdown-item" type="button" onclick="fetchAndShowLeaveModal('{{ route('leave_requests.show', $request->LeaveRequestID) }}')">
                                                <i class="fas fa-eye text-info me-2"></i> View Details
                                            </button>
                                        </li>
                                        
                                        @if ($canSupAction)
                                            <li>
                                                <button class="dropdown-item text-success" type="button" onclick="openConfirmModal('approve', '{{ route('leave_requests.supervisor.approve', $request->LeaveRequestID) }}', 'Supervisor Approval', 'SupervisorApprovalNote')">
                                                    <i class="fas fa-check-circle me-2"></i> Sup. Approve
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger" type="button" onclick="openConfirmModal('reject', '{{ route('leave_requests.supervisor.reject', $request->LeaveRequestID) }}', 'Supervisor Rejection', 'SupervisorRejectionReason')">
                                                    <i class="fas fa-times-circle me-2"></i> Sup. Reject
                                                </button>
                                            </li>
                                        @elseif ($canAdminAction)
                                            <li>
                                                <button class="dropdown-item text-success" type="button" onclick="openConfirmModal('approve', '{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}', 'Admin Approval', 'AdminApprovalNote')">
                                                    <i class="fas fa-check-circle me-2"></i> Admin Approve
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger" type="button" onclick="openConfirmModal('reject', '{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}', 'Admin Rejection', 'AdminRejectionReason')">
                                                    <i class="fas fa-times-circle me-2"></i> Admin Reject
                                                </button>
                                            </li>
                                        @endif

                                        @if (auth()->user()->role_id === 1)
                                            <li><hr class="dropdown-divider"></li>
                                            @if ($request->is_archived)
                                                <li>
                                                    <form action="{{ route('leave_requests.restore', $request->LeaveRequestID) }}" method="POST" class="m-0 p-0">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-warning" onclick="return confirm('Restore this archived request?')">
                                                            <i class="fas fa-undo me-2"></i> Restore
                                                        </button>
                                                    </form>
                                                </li>
                                            @else
                                                <li>
                                                    <form action="{{ route('leave_requests.archive', $request->LeaveRequestID) }}" method="POST" class="m-0 p-0">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-secondary" onclick="return confirm('Archive this request?')">
                                                            <i class="fas fa-archive me-2"></i> Archive
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>

                        </td>
                    </tr>


@endforeach
            </tbody>
        </table>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
    </div>

<<<<<<< HEAD
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
=======
<!-- Include View Leave Modal -->
@include('leave_requests._view_modal')

@endsection

<!-- Action Modals -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
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
                        <textarea name="note" id="actionNote" class="form-control" rows="3" placeholder="Enter details here..."></textarea>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                    </div>
                </div>
            </form>
        </div>
    </div>
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
