@extends('layouts.app')
@section('page_title', request()->routeIs('admin.verification') ? 'Verification' : 'Admin Dashboard')
@section('title', 'Admin Leave Verification')

@section('styles')
    <style>
        .admin-container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .card-custom {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            padding: 14px;
        }

        .table {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #2E3A87;
            color: #ffffff;
        }

        .table tbody tr {
            background-color: #ffffff;
            color: #000000;
        }

        .table th,
        .table td {
            padding: 8px 12px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .badge {
            font-size: 13px;
            padding: 6px 10px;
            border-radius: 12px;
        }

        /* Compact stat cards */
        .stat-card {
            padding: 12px;
            border-radius: 12px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.12);
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
        }

        .stat-label {
            font-size: 0.75rem;
            letter-spacing: 0.04em;
        }

        /* Compact action buttons */
        .small-action-btn {
            padding: 4px 8px;
            font-size: 0.78rem;
            border-radius: 6px;
        }

        .table td .actions-inline {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .form-control {
            font-size: 0.9rem;
        }

        .btn-sm {
            font-size: 0.8rem;
        }

        .text-muted {
            color: #777 !important;
        }
    </style>
@endsection

@section('content')
    <div class="admin-container">

        <!-- Modern Welcome Banner -->
        <div class="card p-0 overflow-hidden mb-4 border-0 shadow-lg" style="border-radius: 1.5rem;">
            <div class="p-5 text-white position-relative"
                style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
                <div class="position-relative z-1">
                    <h2 class="fw-bold mb-2">Welcome Back, {{ auth()->user()->FirstName }}!</h2>
                    <p class="opacity-75 mb-4 fs-5">You have full control over the leave management system. Review, approve,
                        and manage with ease.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('leave_types.index') }}" class="btn btn-light px-4 py-2 fw-semibold text-primary">
                            <i class="fas fa-cog me-2"></i>Configure Leave Types
                        </a>
                        <a href="{{ route('leave.report.pdf') }}" class="btn btn-outline-light px-4 py-2 fw-semibold">
                            <i class="fas fa-file-pdf me-2"></i>Generate Report
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-3"
                    style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <div class="me-3 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(255,255,255,0.18);border-radius:10px;">
                            <i class="fas fa-users fs-4"></i>
                        </div>
                        <div>
                            <small class="opacity-75 text-uppercase fw-bold" style="font-size: 0.7rem;">Total
                                Employees</small>
                            <h3 class="fw-bold mb-0">{{ \App\Models\Employee::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-3"
                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <div class="me-3 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(255,255,255,0.18);border-radius:10px;">
                            <i class="fas fa-calendar-check fs-4"></i>
                        </div>
                        <div>
                            <small class="opacity-75 text-uppercase fw-bold" style="font-size: 0.7rem;">Active
                                Leaves</small>
                            <h3 class="fw-bold mb-0">
                                {{ \App\Models\LeaveRequest::where('RequestStatus', 'Approved')->where('is_active', true)->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-3"
                    style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <div class="me-3 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(255,255,255,0.18);border-radius:10px;">
                            <i class="fas fa-clock fs-4"></i>
                        </div>
                        <div>
                            <small class="opacity-75 text-uppercase fw-bold" style="font-size: 0.7rem;">Pending
                                Approvals</small>
                            <h3 class="fw-bold mb-0">{{ $leaveRequests->where('RequestStatus', 'Pending Admin Verification')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-3"
                    style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <div class="me-3 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(255,255,255,0.18);border-radius:10px;">
                            <i class="fas fa-building fs-4"></i>
                        </div>
                        <div>
                            <small class="opacity-75 text-uppercase fw-bold" style="font-size: 0.7rem;">Departments</small>
                            <h3 class="fw-bold mb-0">{{ \App\Models\Department::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 1rem;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark"><i class="fas fa-money-bill-wave text-success me-2"></i>Institution Locum Spend</h5>
                            <p class="mb-0 text-muted">Current month total for all departments.</p>
                        </div>
                        <span class="badge bg-success align-self-start">This month</span>
                    </div>
                    <h2 class="fw-bold mb-3">{{ $formattedLocumSpendThisMonth }}</h2>
                    <p class="mb-0 text-muted">{{ $totalLocumSessionsThisMonth }} locum session{{ $totalLocumSessionsThisMonth === 1 ? '' : 's' }} recorded.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 1rem;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>Locum Reporting</h5>
                            <p class="mb-0 text-muted">Review every locum session across the institution.</p>
                        </div>
                        <span class="badge bg-primary align-self-start">All departments</span>
                    </div>
                    <p class="mb-4 text-dark">Navigate to the full locum monthly report to see all sessions, earnings, and department-level details.</p>
                    <a href="{{ route('locum.report') }}" class="btn btn-primary px-4 py-2">View Locum Report</a>
                </div>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="row g-4 mb-4">
            <!-- Left: Verification Activity Trend -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 1rem;">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-chart-line text-primary me-2"></i> Monthly Verification Activity</h5>
                    <div style="height: 250px;">
                        <canvas id="monthlyVerificationChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- Right: Status Breakdown Doughnut -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 1rem;">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-chart-pie text-warning me-2"></i> All-Time Approvals</h5>
                    <div style="height: 250px; display: flex; justify-content: center;">
                        <canvas id="statusBreakdownChart"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <div class="row">
                            <div class="col-6 border-end">
                                <small class="text-uppercase text-muted fw-bold">Approval Rate</small>
                                <h4 class="mb-0 text-success fw-bold">{{ $approvalRate }}%</h4>
                            </div>
                            <div class="col-6">
                                <small class="text-uppercase text-muted fw-bold">Avg Duration</small>
                                <h4 class="mb-0 text-info fw-bold">{{ $avgDuration }}<span class="fs-6 fw-normal text-muted"> d</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Department Leave Balances -->
            <div class="col-12">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 1rem;">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-building text-info me-2"></i> Average Remaining Leave Balance by Department</h5>
                    <div style="height: 250px;">
                        <canvas id="deptBalanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Section -->
        <div class="card p-0 border-0 shadow-sm mb-4" style="border-radius: 1rem; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list-alt text-primary me-2"></i> Processing Queue</h5>
            </div>
            <div class="card-body p-0">
            @if ($leaveRequests->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Days</th>
                                <th>Reason</th> <!-- ✅ New column -->
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaveRequests as $request)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                                    <td>{{ $request->leaveType->LeaveTypeName }}</td>
                                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</td>
                                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</td>
                                    <td>{{ $request->TotalDays }} <small class="text-muted">days</small></td>
                                    <td style="max-width: 300px; min-width: 200px;">
                                        <div class="text-truncate text-wrap" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $request->Reason }}">
                                            {{ $request->Reason ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge
                                                                            @if($request->RequestStatus === 'Approved') bg-success
                                                                            @elseif($request->RequestStatus === 'Rejected by Admin' || $request->RequestStatus === 'Rejected') bg-danger
                                                                            @elseif($request->RequestStatus === 'Pending Admin Verification') bg-primary
                                                                            @else bg-warning text-dark @endif">
                                            {{ ucfirst($request->RequestStatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $canAdminAction = strcasecmp($request->RequestStatus, 'Pending Admin Verification') === 0;
                                            $canSupAction = strcasecmp($request->RequestStatus, 'Pending Supervisor Approval') === 0 && auth()->id() === $request->employee->SupervisorID;
                                        @endphp

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
                                                @if ($canAdminAction)
                                                    <li>
                                                        <a href="{{ route('leave_requests.admin.approve.form', $request->LeaveRequestID) }}" class="dropdown-item text-success">
                                                            <i class="fas fa-check-circle me-2"></i> Approve
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('leave_requests.admin.reject.form', $request->LeaveRequestID) }}" class="dropdown-item text-danger">
                                                            <i class="fas fa-times-circle me-2"></i> Reject
                                                        </a>
                                                    </li>
                                                @elseif ($canSupAction)
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
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3 pb-3">
                    <a href="{{ route('leave.report.pdf') }}" class="btn btn-outline-secondary shadow-sm btn-sm">
                        📄 Download Leave Report (PDF)
                    </a>
                </div>
            @else
                <div class="alert alert-info border-0 rounded-0 text-center m-0 py-5">
                    <i class="fas fa-check-circle fs-1 text-success mb-3"></i>
                    <h5>No leave requests in the queue.</h5>
                    <p class="text-muted mb-0">All non-archived requests managed!</p>
                </div>
            @endif
            </div>
        </div>
    </div>

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
                        <textarea name="note" id="actionNote" class="form-control" rows="3"
                            placeholder="Enter details here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ── Monthly Verification Trend ──
            const ctxMonthlyVerification = document.getElementById('monthlyVerificationChart').getContext('2d');
            new Chart(ctxMonthlyVerification, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyLabels) !!},
                    datasets: [{
                        label: 'Processed Requests',
                        data: {!! json_encode($monthlyVerified) !!},
                        backgroundColor: '#6366f1',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });

            // ── Status Breakdown Doughnut ──
            const ctxStatus = document.getElementById('statusBreakdownChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Approved', 'Rejected', 'Pending'],
                    datasets: [{
                        data: [
                            {{ $statusBreakdown['Approved'] }},
                            {{ $statusBreakdown['Rejected'] }},
                            {{ $statusBreakdown['Pending'] }}
                        ],
                        backgroundColor: ['#10b981', '#ef4444', '#f59e0b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // ── Department Balance Bar Chart ──
            const deptBalances = {!! json_encode($deptBalanceStats) !!};
            const ctxDeptBalance = document.getElementById('deptBalanceChart').getContext('2d');
            new Chart(ctxDeptBalance, {
                type: 'bar',
                data: {
                    labels: deptBalances.map(d => d.name),
                    datasets: [{
                        label: 'Avg Remaining Days',
                        data: deptBalances.map(d => d.avg),
                        backgroundColor: '#0ea5e9',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        });

        function openConfirmModal(type, url, title, fieldName = null) {
            const form = document.getElementById('actionForm');
            const titleEl = document.getElementById('modalTitle');
            const messageEl = document.getElementById('modalMessage');
            const noteEl = document.getElementById('actionNote');
            const submitBtn = document.getElementById('submitBtn');
            const noteContainer = document.getElementById('noteContainer');

            form.action = url;
            titleEl.innerText = title;

            // Reset note field
            noteEl.value = '';
            noteEl.required = (type === 'reject');

            // Determine field name (for controller validation)
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

        // Prevent double form submissions
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function (e) {
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