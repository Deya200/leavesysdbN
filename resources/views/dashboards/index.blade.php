@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        .dashboard-container {
            /* max-width handled by app.blade.php */
        }

        .card-custom {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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

        .table th,
        .table td {
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
            <div class="col-12 animate__animated animate__fadeIn">

                <!-- Welcome Section -->
                <div class="card border-0 shadow-sm mb-4 overflow-hidden"
                    style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);">
                    <div class="card-body p-4 text-center text-white">
                        <h3 class="fw-bold mb-1">Welcome, {{ auth()->user()->FirstName ?? 'Admin' }}!</h3>
                        <p class="mb-0 text-white-50 opacity-75">Your administration control center and management
                            dashboard.</p>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h6 class="text-muted small text-uppercase fw-bold mb-3 d-flex align-items-center">
                                    <i class="fas fa-sitemap me-2 text-primary"></i> Organization
                                </h6>
                                <h2 class="fw-bold mb-0 text-dark">{{ $departments->count() }} <small
                                        class="text-muted fw-normal fs-6">Departments</small></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100"
                            style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                            <div class="card-body p-4 text-center">
                                <h6 class="text-white-50 small text-uppercase fw-bold mb-3">On Leave Today</h6>
                                <h2 class="fw-bold mb-0 text-white">{{ $employeesOnLeave->count() }} <small
                                        class="text-white-50 fw-normal fs-6">Employees</small></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4 text-end">
                                <h6
                                    class="text-muted small text-uppercase fw-bold mb-3 d-flex align-items-center justify-content-end">
                                    My Balance <i class="fas fa-calendar-check ms-2 text-primary"></i>
                                </h6>
                                <h2 class="fw-bold mb-0 text-dark">{{ $personalLeaveBalance }} <small
                                        class="text-muted fw-normal fs-6">days</small></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent py-3">
                                <h5 class="mb-0 fw-bold d-flex align-items-center">
                                    <i class="fas fa-chart-area text-primary me-2"></i> Leave Trends & Analytics
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded text-center">
                                            <small class="text-uppercase text-muted fw-bold">Approval Rate</small>
                                            <h3 class="mb-0 text-success fw-bold">{{ $approvalRate }}%</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded text-center">
                                            <small class="text-uppercase text-muted fw-bold">Avg Leave Duration</small>
                                            <h3 class="mb-0 text-info fw-bold">{{ $avgDuration }} <small class="text-muted fw-normal fs-6">days</small></h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded text-center">
                                            <small class="text-uppercase text-muted fw-bold">Total Requests</small>
                                            <h3 class="mb-0 text-primary fw-bold">{{ $statusBreakdown['Approved'] + $statusBreakdown['Rejected'] + $statusBreakdown['Pending'] }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded text-center">
                                            <small class="text-uppercase text-muted fw-bold">Pending Actions</small>
                                            <h3 class="mb-0 text-warning fw-bold">{{ $statusBreakdown['Pending'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col-lg-8">
                                        <h6 class="text-center text-muted mb-3">Monthly Leave Requests (Last 12 Months)</h6>
                                        <canvas id="monthlyTrendChart" height="100"></canvas>
                                    </div>
                                    <div class="col-lg-4">
                                        <h6 class="text-center text-muted mb-3">Leave Type Distribution</h6>
                                        <canvas id="leaveTypeChart"></canvas>
                                    </div>
                                </div>
                                <div class="row g-4 mt-2">
                                    <div class="col-lg-8">
                                        <h6 class="text-center text-muted mb-3">Department Leave Usage (Total Days)</h6>
                                        <canvas id="deptUsageChart" height="100"></canvas>
                                    </div>
                                    <div class="col-lg-4">
                                        <h6 class="text-center text-muted mb-3">Requests by Gender</h6>
                                        <canvas id="genderPieChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Left Column: Reports & Activity -->
                    <div class="col-lg-8">
                        <!-- Employees Currently on Leave -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div
                                class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h5 class="mb-0 fw-bold d-flex align-items-center">
                                    <i class="fas fa-plane-departure text-warning me-2"></i> Live Leave Status
                                </h5>
                                <div class="search-input-wrapper position-relative" style="min-width: 250px;">
                                    <i class="fas fa-search position-absolute text-muted"
                                        style="top: 50%; left: 15px; transform: translateY(-50%);"></i>
                                    <input type="text" id="liveLeaveSearch"
                                        class="form-control form-control-sm border bg-light rounded-pill ps-5 py-2"
                                        placeholder="Search employees..."
                                        onkeyup="searchTable('liveLeaveSearch', 'liveLeaveTable')">
                                </div>
                            </div>
                            <div class="table-responsive">
                                @if(isset($employeesOnLeave) && $employeesOnLeave->isNotEmpty())
                                    <table class="table table-hover align-middle mb-0" id="liveLeaveTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">Employee</th>
                                                <th>Type</th>
                                                <th>Due Back</th>
                                                <th>Balance</th>
                                                <th class="pe-4">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employeesOnLeave as $onLeave)
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                                                style="width: 38px; height: 38px; font-weight: 700;">
                                                                {{ substr($onLeave->employee->FirstName, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark">{{ $onLeave->employee->FirstName }}
                                                                    {{ $onLeave->employee->LastName }}
                                                                </div>
                                                                <small
                                                                    class="text-muted">{{ $onLeave->employee->department->DepartmentName ?? 'Global' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span
                                                            class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">{{ $onLeave->leaveType->LeaveTypeName }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="text-danger fw-bold small">
                                                            {{ \Carbon\Carbon::parse($onLeave->EndDate)->addDay()->format('M d, Y') }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="fw-bold text-dark">{{ $onLeave->employee->leave_days_remaining }}</span>
                                                        <small class="text-muted">days</small>
                                                    </td>
                                                    <td class="pe-4">
                                                        <button type="button" class="btn btn-sm btn-light border"
                                                            onclick="fetchAndShowLeaveModal('{{ route('leave_requests.show', $onLeave->LeaveRequestID) }}')">
                                                            <i class="fas fa-eye text-primary"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="p-5 text-center alert-light">
                                        <i class="fas fa-check-circle text-success fs-2 mb-3 d-block"></i>
                                        <p class="text-muted mb-0">Full team coverage today. No employees on leave.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="card border-0 shadow-sm">
                            <div
                                class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-history text-primary me-2"></i>
                                    <h5 class="mb-0 fw-bold">Recent Leave Activity</h5>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="search-input-wrapper position-relative" style="min-width: 200px;">
                                        <i class="fas fa-search position-absolute text-muted"
                                            style="top: 50%; left: 15px; transform: translateY(-50%);"></i>
                                        <input type="text" id="recentActivitySearch"
                                            class="form-control form-control-sm border bg-light rounded-pill ps-5 py-2"
                                            placeholder="Search activity..."
                                            onkeyup="searchTable('recentActivitySearch', 'recentLeaveTable')">
                                    </div>
                                    <a href="{{ route('leave_requests.index') }}"
                                        class="btn btn-sm btn-link text-decoration-none">View Full History</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                @if ($recentLeaveRequests->isNotEmpty())
                                    <table class="table table-hover align-middle mb-0" id="recentLeaveTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">Employee</th>
                                                <th>Leave Type</th>
                                                <th>Status</th>
                                                <th class="pe-4 text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentLeaveRequests as $request)
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="fw-bold text-dark">{{ $request->employee->FirstName }}
                                                            {{ $request->employee->LastName }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                                    </td>
                                                    <td><span class="small">{{ $request->leaveType->LeaveTypeName }}</span></td>
                                                    <td>
                                                        @php
                                                            $statusClass = match ($request->RequestStatus) {
                                                                'Approved' => 'bg-success',
                                                                'Rejected', 'Rejected by Admin' => 'bg-danger',
                                                                default => 'bg-warning text-dark'
                                                            };
                                                        @endphp
                                                        <span class="badge rounded-pill px-3 {{ $statusClass }}">
                                                            {{ $request->RequestStatus }}
                                                        </span>
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        <div class="d-flex justify-content-end gap-1">
                                                            <button type="button" class="btn btn-sm btn-light border"
                                                                onclick="fetchAndShowLeaveModal('{{ route('leave_requests.show', $request->LeaveRequestID) }}')">
                                                                <i class="fas fa-eye text-primary"></i>
                                                            </button>
                                                            @if($request->RequestStatus === 'Pending Admin Verification')
                                                                <a href="{{ route('admin.verification') }}"
                                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">Verify</a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="p-4 text-center">
                                        <p class="text-muted mb-0 italic">Silence is golden. No recent requests.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Quick Stats & Management -->
                    <div class="col-lg-4">
                        <!-- Department Overview Summary -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent py-3">
                                <h5 class="mb-0 fw-bold d-flex align-items-center">
                                    <i class="fas fa-chart-bar text-info me-2"></i> Department Pulse
                                </h5>
                            </div>
                            <div class="p-0">
                                <ul class="list-group list-group-flush">
                                    @foreach ($departments->take(6) as $department)
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-0">
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ $department->DepartmentName }}</div>
                                                <small
                                                    class="text-muted">{{ $department->supervisor ? $department->supervisor->FirstName : 'No Sup' }}</small>
                                            </div>
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                                                {{ $department->employees_count }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                                @if($departments->count() > 6)
                                    <div class="p-3 text-center border-top">
                                        <a href="{{ route('departments.index') }}" class="small fw-bold text-decoration-none">+
                                            {{ $departments->count() - 6 }} More Departments</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Personal Recent Requests Overlay -->
                        <div class="card border-0 shadow-sm" style="background: #f8fafc;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3">My Recent Leave Request</h6>
                                @if($personalRecentRequests->isNotEmpty())
                                    @php $lastReq = $personalRecentRequests->first(); @endphp
                                    <div class="p-3 rounded-3 bg-white border border-light">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span
                                                class="small fw-bold text-dark">{{ $lastReq->leaveType->LeaveTypeName }}</span>
                                            <span class="badge {{ $statusClass }} py-1">{{ $lastReq->RequestStatus }}</span>
                                        </div>
                                        <div class="small text-muted mb-0">
                                            {{ \Carbon\Carbon::parse($lastReq->StartDate)->format('M d') }} -
                                            {{ \Carbon\Carbon::parse($lastReq->EndDate)->format('M d') }}
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted small italic mb-0">You haven't requested any leave recently.</p>
                                @endif
                                <a href="{{ route('leave_requests.create') }}"
                                    class="btn btn-primary w-100 mt-4 rounded-3 py-2">
                                    <i class="fas fa-plus me-1"></i> New Leave Request
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('leave_requests._view_modal')
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ── Monthly Trend Chart ──
            const ctxMonthly = document.getElementById('monthlyTrendChart').getContext('2d');
            new Chart(ctxMonthly, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyLabels) !!},
                    datasets: [
                        {
                            label: 'Approved',
                            data: {!! json_encode($monthlyApproved) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Rejected',
                            data: {!! json_encode($monthlyRejected) !!},
                            borderColor: '#ef4444',
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });

            // ── Leave Type Doughnut Chart ──
            const leaveStats = {!! json_encode($leaveTypeStats) !!};
            const ctxType = document.getElementById('leaveTypeChart').getContext('2d');
            new Chart(ctxType, {
                type: 'doughnut',
                data: {
                    labels: leaveStats.map(s => s.name),
                    datasets: [{
                        data: leaveStats.map(s => s.count),
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#64748b']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // ── Department Leave Usage Bar Chart ──
            const deptStats = {!! json_encode($deptLeaveStats) !!};
            const ctxDept = document.getElementById('deptUsageChart').getContext('2d');
            new Chart(ctxDept, {
                type: 'bar',
                data: {
                    labels: deptStats.map(s => s.name),
                    datasets: [{
                        label: 'Total Leave Days',
                        data: deptStats.map(s => s.days),
                        backgroundColor: '#6366f1',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // ── Gender Pie Chart ──
            const ctxGender = document.getElementById('genderPieChart').getContext('2d');
            new Chart(ctxGender, {
                type: 'pie',
                data: {
                    labels: ['Male', 'Female'],
                    datasets: [{
                        data: [{{ $maleRequests }}, {{ $femaleRequests }}],
                        backgroundColor: ['#0ea5e9', '#ec4899']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });
    </script>
    <script>
        function toggleAdminRejectForm(id) {
            const row = document.getElementById('adminRejectFormRow-' + id);
            if (row) {
                row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
            }
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

        function searchTable(inputId, tableId) {
            const input = document.getElementById(inputId);
            const filter = input.value.toLowerCase();
            const table = document.getElementById(tableId);
            if (!table) return;
            const rows = table.querySelectorAll('tbody tr:not(.empty-state-row)');
            let visibleCount = 0;

            rows.forEach((row) => {
                const text = row.textContent || row.innerText;

                if (filter === '') {
                    row.style.display = row.classList.contains('hidden-row') ? 'none' : '';
                    if (!row.classList.contains('hidden-row')) visibleCount++;
                } else {
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            // Handle empty state
            let emptyRow = table.querySelector('.empty-state-row');
            if (visibleCount === 0) {
                if (!emptyRow) {
                    const tbody = table.querySelector('tbody');
                    const colCount = table.querySelectorAll('thead th').length;
                    emptyRow = document.createElement('tr');
                    emptyRow.className = 'empty-state-row';
                    emptyRow.innerHTML = `
                            <td colspan="${colCount}" class="text-center py-5 text-muted">
                                <div class="py-3">
                                    <i class="fas fa-search fa-2x mb-2 text-light"></i>
                                    <p class="mb-0">No matches found for "${filter}"</p>
                                </div>
                            </td>
                        `;
                    tbody.appendChild(emptyRow);
                } else {
                    emptyRow.querySelector('p').innerText = `No matches found for "${filter}"`;
                    emptyRow.style.display = '';
                }
            } else if (emptyRow) {
                emptyRow.style.display = 'none';
            }
        }
    </script>
@endsection