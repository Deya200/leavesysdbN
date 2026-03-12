@extends('layouts.app')
@section('page_title', 'Supervisor Dashboard')

@section('title', 'Supervisor Dashboard')

@section('styles')
    <style>
        /* Use layout's main padding — remove manual left offset to match other pages */
        .dashboard-container {
            margin: 0;
            /* let <main> provide outer spacing */
            padding: 0;
            /* main already applies page padding */
            min-height: 100vh;
            width: 100%;
            background-color: transparent;
        }

        /* Keep document body as-is (global layout handles overflow/padding) */
        body {
            overflow-x: hidden;
        }

        /* Ensure inner content sections don't add unexpected left offsets */
        .main-content,
        #main-content,
        .content-wrapper {
            padding-left: 0;
            margin-left: 0;
        }

        /* Responsive: spacing provided by main; add small inner padding for very small screens */
        @media (max-width: 576px) {
            .dashboard-container {
                padding: 0.75rem;
            }
        }

        .card-custom {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            padding: 10px 12px;
            background: white;
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

        .table thead {
            background-color: rgb(235, 236, 240);
            color: #d4d6ddff;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: center;
            vertical-align: middle;
            border: none;
        }

        .hover-up:hover {
            transform: translateY(-3px);
            transition: transform 0.3s ease;
        }

        .badge {
            font-size: 13px;
            padding: 4px 10px;
            border-radius: 12px;
        }

        /* Management card compact styles */
        .management-card .card-body {
            padding: 12px;
        }

        .management-icon {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .count-badge {
            font-size: 0.75rem;
            padding: 4px 7px;
            border-radius: 999px;
        }

        .small-action-btn {
            padding: 4px 8px;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        .badge-approved {
            background-color: #28a745;
            color: white;
        }

        .badge-rejected {
            background-color: #dc3545;
            color: white;
        }

        .badge-pending {
            background-color: #2E3A87;
            color: white;
        }

        .btn-outline-primary,
        .btn-outline-secondary {
            font-size: 0.85rem;
            padding: 6px 16px;
            border-radius: 6px;
        }

        .hidden-row {
            display: none;
        }

        /* Employees table styling */
        #employeeTable thead th {
            white-space: nowrap;
            padding: 12px 10px;
            font-weight: 600;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        #employeeTable tbody td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        #employeeTable_wrapper {
            overflow-x: auto;
        }

        /* Ensure employee table section is visible */
        .employee-table-container {
            width: 100%;
            overflow-x: auto;
            margin-left: -12px;
            margin-right: -12px;
            padding-left: 12px;
            padding-right: 12px;
        }

        .employee-table-container table {
            min-width: 800px;
        }

        /* Remove any potential gaps from the first card */
        .dashboard-container>.card:first-child {
            margin-top: 0;
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container">
        <!-- Your existing content remains exactly the same -->
        <!-- Welcome Section -->
        <div class="card border-0 shadow-sm mb-4 overflow-hidden"
            style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
            <div class="card-body p-4 text-center text-white">
                <h3 class="fw-bold mb-1">Welcome back, {{ auth()->user()->FirstName ?? 'Supervisor' }}!</h3>
                <p class="mb-0 text-white-50">You have {{ $pendingSupervisorRequests }} pending requests that need your
                    attention.</p>
            </div>
        </div>

        <!-- Management Actions -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <a href="{{ route('leave-appeals.index') }}" class="text-decoration-none">
                    <div
                        class="card management-card h-100 border-0 shadow-sm hover-up border-start border-4 border-warning">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <div class="management-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="fas fa-gavel"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center">
                                    <h6 class="fw-bold text-dark mb-0">Manage Appeals</h6>
                                    @if(isset($pendingAppeals) && $pendingAppeals > 0)
                                        <span
                                            class="badge bg-warning text-dark ms-auto count-badge">{{ $pendingAppeals }}</span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-0">Review and verify rejected leave appeals from your team.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('leave-extensions.index') }}" class="text-decoration-none">
                    <div class="card management-card h-100 border-0 shadow-sm hover-up border-start border-4 border-info">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <div class="management-icon bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center">
                                    <h6 class="fw-bold text-dark mb-0">Manage Extensions</h6>
                                    @if(isset($pendingExtensions) && $pendingExtensions > 0)
                                        <span
                                            class="badge bg-info text-dark ms-auto count-badge">{{ $pendingExtensions }}</span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-0">Approve or reject requests to extend existing leaves.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('leave-cancellations.index') }}" class="text-decoration-none">
                    <div class="card management-card h-100 border-0 shadow-sm hover-up border-start border-4 border-danger">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <div class="management-icon bg-danger bg-opacity-10 text-danger">
                                    <i class="fas fa-ban"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center">
                                    <h6 class="fw-bold text-dark mb-0">Cancellations</h6>
                                    @if(isset($pendingCancellations) && $pendingCancellations > 0)
                                        <span
                                            class="badge bg-danger text-white ms-auto count-badge">{{ $pendingCancellations }}</span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-0">Review and process leave cancellation requests.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="row g-3 mb-5">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-muted small text-uppercase fw-bold mb-2">Team Members</h6>
                        <h2 class="fw-bold text-primary mb-0">{{ $totalEmployees }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 bg-warning bg-opacity-10">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-muted small text-uppercase fw-bold mb-2">Currently on Leave</h6>
                        <h2 class="fw-bold text-warning mb-0">{{ $employeesOnLeave->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 bg-success bg-opacity-10">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-muted small text-uppercase fw-bold mb-2">My Balance</h6>
                        <h2 class="fw-bold text-success mb-0">{{ $personalLeaveBalance }} <small
                                class="text-muted fw-normal fs-6">days</small></h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
                    <div class="card-body p-4 text-center text-white">
                        <h6 class="text-white text-opacity-75 small text-uppercase fw-bold mb-2">Pending Action</h6>
                        <h2 class="fw-bold text-white mb-0">{{ $pendingSupervisorRequests }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Analytics Section -->
        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 1rem;">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-chart-area text-primary me-2"></i> Team Leave Trends</h5>
                    <div style="height: 250px;">
                        <canvas id="teamTrendChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 1rem;">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-chart-pie text-warning me-2"></i> Team Approvals</h5>
                    <div style="height: 250px; display: flex; justify-content: center;">
                        <canvas id="teamStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 1rem;">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-users text-info me-2"></i> Team Member Leave Balances</h5>
                    <div style="height: 200px;">
                        <canvas id="teamBalanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Team Activity -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-header bg-transparent py-3 d-flex align-items-center">
                <i class="fas fa-users text-primary me-2"></i>
                <h5 class="mb-0 fw-bold">Team Leave Status</h5>
            </div>
            <div class="table-responsive">
                @if(isset($employeesOnLeave) && $employeesOnLeave->isNotEmpty())
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Employee</th>
                                <th>Leave Type</th>
                                <th>Due Back</th>
                                <th class="pe-4 text-center">Action History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employeesOnLeave as $onLeave)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $onLeave->employee->FirstName }}
                                            {{ $onLeave->employee->LastName }}</div>
                                        <small class="text-muted">{{ $onLeave->EmployeeNumber }}</small>
                                    </td>
                                    <td><span
                                            class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">{{ $onLeave->leaveType->LeaveTypeName }}</span>
                                    </td>
                                    <td>
                                        <div class="text-danger fw-bold small">
                                            {{ \Carbon\Carbon::parse($onLeave->EndDate)->addDay()->format('M d, Y') }}
                                        </div>
                                        <small class="text-muted">Ends:
                                            {{ \Carbon\Carbon::parse($onLeave->EndDate)->format('M d') }}</small>
                                    </td>
                                    <td class="pe-4 text-center">
                                        <a href="#" class="btn btn-sm btn-link text-decoration-none">View Record</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-5 text-center bg-white">
                        <i class="fas fa-check-circle text-success fs-2 mb-3 d-block"></i>
                        <p class="text-muted mb-0">All team members are present and accounted for.</p>
                    </div>
                @endif
            </div>
        </div>

    <!-- Active Leave Requests -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold d-flex align-items-center">
                <i class="fas fa-clipboard-list text-primary me-2"></i> Pending Team Requests
            </h5>
            <div>
                <a href="{{ route('leave.report.pdf') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="leaveRequestsTable">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Type</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th class="pe-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaveRequests as $index => $request)
                        @php
                            $isActionNeeded = $request->RequestStatus === 'Pending Supervisor Approval';
                        @endphp
                        <tr class="{{ $index >= 5 ? 'hidden-row' : '' }}">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</div>
                                <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                            </td>
                            <td><span class="small">{{ $request->leaveType->LeaveTypeName }}</span></td>
                            <td>
                                <div class="small fw-bold">{{ $request->StartDate }} to {{ $request->EndDate }}</div>
                                <div class="text-primary small">({{ $request->TotalDays }} days)</div>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 {{ $isActionNeeded ? 'bg-warning text-dark' : 'bg-light text-muted border' }}">
                                    {{ $request->RequestStatus }}
                                </span>
                            </td>
                            <td class="pe-4 text-center">
                                @if ($isActionNeeded)
                                    <div class="d-flex gap-2 justify-content-center">
                                        <form action="{{ route('leave_requests.supervisor.approve', $request->LeaveRequestID) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">Approve</button>
                                        </form>
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="toggleRejectForm('{{ $request->LeaveRequestID }}')">Reject</button>
                                    </div>
                                    <div id="rejectForm-{{ $request->LeaveRequestID }}" style="display:none;" class="mt-3 text-start">
                                        <form action="{{ route('leave_requests.supervisor.reject', $request->LeaveRequestID) }}" method="POST" class="bg-light p-3 rounded-3 border">
                                            @csrf
                                            <label class="small fw-bold mb-1">Rejection Reason</label>
                                            <textarea name="SupervisorRejectionReason" class="form-control form-control-sm mb-2" rows="2" placeholder="Explain why..." required></textarea>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-danger btn-sm px-3">Confirm</button>
                                                <button type="button" class="btn btn-light btn-sm px-3" onclick="toggleRejectForm('{{ $request->LeaveRequestID }}')">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <i class="fas fa-info-circle text-muted opacity-50" title="{{ $request->Reason }}"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($leaveRequests->count() > 5)
                <div class="p-3 text-center border-top bg-light bg-opacity-50">
                    <button class="btn btn-sm btn-link text-decoration-none fw-bold" onclick="toggleLeaveTable()" id="leaveToggleButton">Show All Requests</button>
                    <button class="btn btn-sm btn-link text-decoration-none fw-bold" onclick="toggleLeaveTable()" id="leaveLessButton" style="display: none;">Show Less</button>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

        <!-- PDF Report Button -->
        <div class="text-center mt-3">
            <a href="{{ route('leave.report.pdf') }}" class="btn btn-outline-secondary shadow-sm btn-sm">
                📄 Download Leave Report (PDF)
            </a>
        </div>

        <div class="text-center mt-3">
            <button class="btn btn-outline-primary shadow-sm btn-sm" onclick="toggleLeaveTable()" id="leaveToggleButton">See
                More</button>
            <button class="btn btn-outline-secondary shadow-sm btn-sm" onclick="toggleLeaveTable()" id="leaveLessButton"
                style="display: none;">See Less</button>
        </div>

        <!-- Employees Under Supervision -->
        <div class="card card-custom mb-5">
            <div class="d-flex justify-content-between align-items-center mt-3 mb-3 px-3 flex-wrap gap-3">
                <h5 class="fw-bold mb-0">Employees You Supervise</h5>
                <div class="search-input-wrapper position-relative" style="min-width: 250px;">
                    <i class="fas fa-search position-absolute text-muted" style="top: 50%; left: 15px; transform: translateY(-50%);"></i>
                    <input type="text" id="employeeSearchInput" class="form-control form-control-sm border bg-light rounded-pill ps-5 py-2" placeholder="Search employees..." onkeyup="searchTable('employeeSearchInput', 'employeeTable')">
                </div>
            </div>
            @if ($employeesUnderSupervisor->count() > 0)
                <div class="employee-table-container">
                    <table class="table table-bordered align-middle text-dark bg-white" id="employeeTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 140px;">Employee No.</th>
                                <th style="width: 150px;">Name</th>
                                <th style="width: 140px;">Department</th>
                                <th style="width: 180px;">Position</th>
                                <th style="width: 100px;">Annual Leave</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employeesUnderSupervisor as $index => $employee)
                                <tr class="hover-up {{ $index >= 5 ? 'hidden-row' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold text-primary">{{ $employee->EmployeeNumber }}</td>
                                    <td>{{ $employee->FirstName }} {{ $employee->LastName }}</td>
                                    <td>{{ $employee->department->DepartmentName ?? 'N/A' }}</td>
                                    <td>{{ $employee->position->PositionName ?? 'N/A' }}</td>
                                    <td class="text-center">{{ optional($employee->grade)->AnnualLeaveDays ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-primary shadow-sm btn-sm" onclick="toggleEmployeeTable()"
                            id="toggleButton">See More</button>
                        <button class="btn btn-outline-secondary shadow-sm btn-sm" onclick="toggleEmployeeTable()"
                            id="toggleLessButton" style="display: none;">See Less</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ── Team Trend Chart ──
            const ctxTeamTrend = document.getElementById('teamTrendChart').getContext('2d');
            new Chart(ctxTeamTrend, {
                type: 'line',
                data: {
                    labels: {!! json_encode($teamMonthlyLabels) !!},
                    datasets: [
                        {
                            label: 'Approved',
                            data: {!! json_encode($teamMonthlyApproved) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Rejected',
                            data: {!! json_encode($teamMonthlyRejected) !!},
                            borderColor: '#ef4444',
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });

            // ── Team Status Doughnut ──
            const ctxTeamStatus = document.getElementById('teamStatusChart').getContext('2d');
            new Chart(ctxTeamStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Approved', 'Rejected', 'Pending'],
                    datasets: [{
                        data: [
                            {{ $teamStatusBreakdown['Approved'] }},
                            {{ $teamStatusBreakdown['Rejected'] }},
                            {{ $teamStatusBreakdown['Pending'] }}
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

            // ── Team Balance Bar Chart ──
            const ctxTeamBalance = document.getElementById('teamBalanceChart').getContext('2d');
            new Chart(ctxTeamBalance, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($teamBalanceLabels) !!},
                    datasets: [{
                        label: 'Remaining Days',
                        data: {!! json_encode($teamBalanceData) !!},
                        backgroundColor: '#6366f1',
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

        function toggleRejectForm(id) {
            const form = document.getElementById('rejectForm-' + id);
            if (form) {
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
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

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.hidden-row').forEach(row => {
                row.style.display = 'none';
            });

            // Prevent double form submissions
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
            const rows = table.querySelectorAll('tbody tr:not(.empty-state-row)');
            let visibleCount = 0;

            rows.forEach((row) => {
                const text = row.textContent || row.innerText;
                
                if (filter === '') {
                    // Restore original visibility based on whether it has hidden-row
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