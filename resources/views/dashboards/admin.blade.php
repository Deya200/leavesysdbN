@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    .admin-container {
        max-width: 100%;
        margin: 0;
        padding: 20px;
        background: #f8f9fa;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 8px;
    }

    .page-header p {
        color: #6c757d;
        font-size: 16px;
        margin-bottom: 0;
    }

    /* Summary Cards Container */
    .summary-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    /* Card Styling */
    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #dee2e6;
    }

    /* Card Header */
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .card-title {
        font-size: 14px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* Card Content */
    .card-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-count {
        font-size: 32px;
        font-weight: 700;
        color: #212529;
        line-height: 1;
        margin-bottom: 8px;
    }

    .card-subtitle {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    /* Progress Bar */
    .card-progress {
        margin-top: auto;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 6px;
    }

    .progress-bar-container {
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease;
    }

    /* Card Colors */
    .card-total .card-icon {
        background: rgba(109, 40, 217, 0.1);
        color: #6d28d9;
    }

    .card-total::before {
        background: #6d28d9;
    }

    .card-pending-admin .card-icon {
        background: rgba(61, 81, 159, 0.1);
        color: #3D519F;
    }

    .card-pending-admin::before {
        background: #3D519F;
    }

    .card-approved .card-icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .card-approved::before {
        background: #10b981;
    }

    .card-rejected .card-icon {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .card-rejected::before {
        background: #ef4444;
    }

    /* Badge Styling */
    .stats-badge {
        background: #f8f9fa;
        color: #6c757d;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Tab Styling */
    .nav-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 25px;
        justify-content: center;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 24px;
        border-radius: 8px 8px 0 0;
        margin: 0 5px;
        background: transparent;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        color: #3D519F;
        background: rgba(61, 81, 159, 0.05);
    }

    .nav-tabs .nav-link.active {
        color: #3D519F;
        background: white;
        border-bottom: 3px solid #3D519F;
        font-weight: 600;
    }

    /* Table Container */
    .table-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 300px;
    }

    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
        margin: 0 auto;
        max-width: 95%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
    }

    /* Table Styling */
    .table {
        margin: 0 auto;
        background: white;
        width: 100%;
        max-width: 1200px;
    }

    .table thead {
        background: linear-gradient(135deg, #2E3A87 0%, #3D519F 100%);
        color: white;
    }

    .table thead th {
        border: none;
        padding: 16px;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
        vertical-align: middle;
    }

    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
        text-align: center;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Badge Styling */
    .badge {
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        display: inline-block;
        margin: 0 auto;
    }

    .badge.bg-success {
        background: rgba(25, 135, 84, 0.1) !important;
        color: #198754 !important;
        border: 1px solid rgba(25, 135, 84, 0.2);
    }

    .badge.bg-danger {
        background: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .badge.bg-warning {
        background: rgba(255, 193, 7, 0.1) !important;
        color: #ffc107 !important;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }

    .badge.bg-primary {
        background: rgba(61, 81, 159, 0.1) !important;
        color: #3D519F !important;
        border: 1px solid rgba(61, 81, 159, 0.2);
    }

    .badge.bg-light {
        background: #f8f9fa !important;
        color: #212529 !important;
        border: 1px solid #dee2e6;
    }

    /* Button Styling */
    .btn-sm {
        padding: 6px 12px;
        font-size: 14px;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-success {
        background: #198754;
        border-color: #198754;
    }

    .btn-success:hover {
        background: #157347;
        border-color: #146c43;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.2);
    }

    .btn-danger {
        background: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background: #bb2d3b;
        border-color: #b02a37;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
    }

    /* Alert Styling */
    .alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        text-align: center;
    }

    .alert-success {
        background: rgba(25, 135, 84, 0.1);
        border-left: 4px solid #198754;
        color: #198754;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border-left: 4px solid #dc3545;
        color: #dc3545;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #6c757d;
        opacity: 0.5;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        color: #6c757d;
        font-weight: 500;
    }

    /* Form Styling */
    textarea.form-control {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px;
        font-size: 14px;
        resize: vertical;
        min-height: 80px;
        text-align: center;
    }

    textarea.form-control:focus {
        border-color: #3D519F;
        box-shadow: 0 0 0 0.2rem rgba(61, 81, 159, 0.25);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .summary-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .admin-container {
            padding: 15px;
        }
        
        .summary-cards-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .page-header h1 {
            font-size: 24px;
        }
        
        .card-count {
            font-size: 28px;
        }
        
        .table-responsive {
            max-width: 100%;
        }
        
        .table thead th,
        .table tbody td {
            padding: 12px 8px;
            font-size: 14px;
        }
        
        .nav-tabs .nav-link {
            padding: 10px 15px;
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 20px;
        }
        
        .card-count {
            font-size: 24px;
        }
        
        .card-title {
            font-size: 13px;
        }
        
        .nav-tabs {
            flex-direction: column;
            align-items: center;
        }
        
        .nav-tabs .nav-link {
            margin: 5px 0;
            width: 90%;
            text-align: center;
        }
    }

    /* Animation */
    .summary-card {
        animation: fadeInUp 0.5s ease-out;
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
<!-- Page Title Header -->
<div class="page-header">
    <h1>
        <i class="fas fa-file-alt me-2" style="color: #3D519F;"></i>Leave Requests Management
    </h1>
    <p>Review, approve, or reject employee leave applications</p>
</div>

<div class="admin-container">
    <!-- System Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-check-circle me-2"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="summary-cards-grid">
        <!-- Total Leave Requests -->
        <div class="summary-card card-total">
            <div class="card-header">
                <span class="card-title">Total Requests</span>
                <div class="card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div class="card-content">
                <div class="card-count">{{ $totalRequests }}</div>
                <p class="card-subtitle">All leave requests in system</p>
                <div class="card-progress">
                    <div class="progress-info">
                        <span>Overall</span>
                        <span>100%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-fill" style="width: 100%; background-color: #6d28d9;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Admin Approval -->
        <div class="summary-card card-pending-admin">
            <div class="card-header">
                <span class="card-title">Pending Admin</span>
                <div class="card-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
            <div class="card-content">
                <div class="card-count">{{ $pendingAdminCount }}</div>
                <p class="card-subtitle">Awaiting your decision</p>
                <div class="card-progress">
                    <div class="progress-info">
                        <span>Progress</span>
                        <span>{{ $totalRequests > 0 ? number_format(($pendingAdminCount / $totalRequests) * 100, 1) : 0 }}%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-fill" style="width: {{ $totalRequests > 0 ? ($pendingAdminCount / $totalRequests) * 100 : 0 }}%; background-color: #3D519F;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Requests -->
        <div class="summary-card card-approved">
            <div class="card-header">
                <span class="card-title">Approved</span>
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="card-content">
                <div class="card-count">{{ $approvedCount }}</div>
                <p class="card-subtitle">Fully approved requests</p>
                <div class="card-progress">
                    <div class="progress-info">
                        <span>Progress</span>
                        <span>{{ $totalRequests > 0 ? number_format(($approvedCount / $totalRequests) * 100, 1) : 0 }}%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-fill" style="width: {{ $totalRequests > 0 ? ($approvedCount / $totalRequests) * 100 : 0 }}%; background-color: #10b981;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Requests -->
        <div class="summary-card card-rejected">
            <div class="card-header">
                <span class="card-title">Rejected</span>
                <div class="card-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="card-content">
                <div class="card-count">{{ $rejectedCount }}</div>
                <p class="card-subtitle">Rejected by either level</p>
                <div class="card-progress">
                    <div class="progress-info">
                        <span>Progress</span>
                        <span>{{ $totalRequests > 0 ? number_format(($rejectedCount / $totalRequests) * 100, 1) : 0 }}%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-fill" style="width: {{ $totalRequests > 0 ? ($rejectedCount / $totalRequests) * 100 : 0 }}%; background-color: #ef4444;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Tab Content -->
    <div class="tab-content" id="adminTabsContent">
        <!-- Leave Requests Tab -->
        <div class="tab-pane fade show active" id="requests" role="tabpanel">
            <div class="tab-card">
                <div class="card-body">
                    @if ($leaveRequests->isNotEmpty())
                        <div class="table-container">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee</th>
                                            <th>Leave Type</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Total Days</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveRequests as $request)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</strong>
                                                </td>
                                                <td>{{ $request->leaveType->LeaveTypeName }}</td>
                                                <td>{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-light">{{ $request->TotalDays }} days</span>
                                                </td>
                                                <td>{{ $request->Reason ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge
                                                        @if($request->RequestStatus === 'Approved') bg-success
                                                        @elseif($request->RequestStatus === 'Rejected by Admin' || $request->RequestStatus === 'Rejected') bg-danger
                                                        @elseif($request->RequestStatus === 'Pending Admin Verification') bg-primary
                                                        @elseif($request->RequestStatus === 'Pending Supervisor Approval') bg-warning text-dark
                                                        @else bg-secondary @endif">
                                                        {{ ucfirst($request->RequestStatus) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($request->RequestStatus === 'Pending Admin Verification')
                                                        <div class="d-flex flex-column align-items-center gap-2">
                                                            <form action="{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}" method="POST" class="w-100">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                                    <i class="fas fa-check me-1"></i> Approve
                                                                </button>
                                                            </form>
                                                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="toggleRejectForm('{{ $request->LeaveRequestID }}')">
                                                                <i class="fas fa-times me-1"></i> Reject
                                                            </button>
                                                            <form id="rejectForm-{{ $request->LeaveRequestID }}"
                                                                  action="{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}"
                                                                  method="POST"
                                                                  style="display:none; width: 100%;">
                                                                @csrf
                                                                <textarea name="RejectionReason" class="form-control mb-2" 
                                                                          placeholder="Enter rejection reason" 
                                                                          rows="2" required></textarea>
                                                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                                                    <i class="fas fa-paper-plane me-1"></i> Confirm
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif($request->RequestStatus === 'Pending Supervisor Approval')
                                                        <span class="text-muted small">Awaiting supervisor approval</span>
                                                    @else
                                                        <span class="text-muted small">No actions available</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h5>No leave requests pending admin verification.</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Management Tab -->
        <div class="tab-pane fade" id="users" role="tabpanel">
            <div class="tab-card">
                <div class="card-body">
                    @if($users->isNotEmpty())
                        <div class="table-container">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Department</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->EmployeeNumber }}</td>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($user->role_id == 1) bg-primary
                                                    @elseif($user->role_id == 2) bg-info
                                                    @else bg-secondary @endif">
                                                    {{ $user->role->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $user->active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $user->active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $user->department->DepartmentName ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex flex-column align-items-center gap-2">
                                                    <!-- Role Management -->
                                                    <form action="{{ route('admin.users.updateRole', $user->EmployeeNumber) }}" method="POST" class="w-100">
                                                        @csrf
                                                        <select name="role_id" onchange="this.form.submit()" class="form-select form-select-sm">
                                                            <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Admin</option>
                                                            <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>Supervisor</option>
                                                            <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>Employee</option>
                                                        </select>
                                                    </form>

                                                    <!-- Status Toggle -->
                                                    <form action="{{ route('admin.users.toggleStatus', $user->EmployeeNumber) }}" method="POST" class="w-100">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $user->active ? 'btn-danger' : 'btn-success' }} w-100">
                                                            <i class="fas {{ $user->active ? 'fa-user-slash' : 'fa-user-check' }} me-1"></i>
                                                            {{ $user->active ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <h5>No users found.</h5>
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
    function toggleRejectForm(requestId) {
        const form = document.getElementById(`rejectForm-${requestId}`);
        form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Tab persistence
        const activeTab = localStorage.getItem('adminActiveTab');
        if (activeTab) {
            const tab = document.querySelector(`[data-bs-target="${activeTab}"]`);
            if (tab) {
                new bootstrap.Tab(tab).show();
            }
        }

        // Save active tab on change
        document.querySelectorAll('#adminTabs button').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                localStorage.setItem('adminActiveTab', e.target.getAttribute('data-bs-target'));
            });
        });

        // Center all table content
        function centerTableContent() {
            const tableCells = document.querySelectorAll('.table td, .table th');
            tableCells.forEach(cell => {
                cell.style.textAlign = 'center';
                cell.style.verticalAlign = 'middle';
            });
        }

        centerTableContent();
        window.addEventListener('resize', centerTableContent);
    });
</script>
@endsection