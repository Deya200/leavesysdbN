@extends('layouts.app')
@section('page_title', 'Dashboard')

@section('title', 'Employee Dashboard')

@section('styles')
    <style>
        .dashboard-container {
            max-width: 1400px;
            margin: auto;
            padding: 24px;
        }

        .card-custom {
            background: white;
            border-radius: var(--radius-xl);
            border: 1px solid var(--color-slate-100);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .card-custom:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        /* Leave Type Cards Styling */
        .leave-type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 2rem;
        }

        @media (max-width: 1200px) {
            .leave-type-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .leave-type-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .leave-type-grid {
                grid-template-columns: 1fr;
            }
        }

        .leave-type-card {
            border: 1px solid var(--color-slate-100);
            border-radius: var(--radius-lg);
            padding: 20px;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: var(--shadow-sm);
        }

        .leave-type-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
            border-color: var(--color-primary-light);
        }

        .leave-type-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .leave-type-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .leave-type-title {
            flex-grow: 1;
            margin-left: 12px;
        }

        .leave-type-title h6 {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            color: #1a1a1a;
            word-break: break-word;
        }

        .leave-type-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 6px;
            white-space: nowrap;
            display: inline-block;
            margin-top: 2px;
        }

        .leave-type-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin: 12px 0;
            padding: 10px 0;
            border-top: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            font-size: 11px;
            color: var(--color-slate-500);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--color-slate-900);
        }

        .stat-unit {
            font-size: 10px;
            color: #6c757d;
            font-weight: normal;
        }

        .progress-container {
            margin-top: 12px;
        }

        .progress-label {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
        }

        .progress-bar-custom {
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar-custom .bar {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .bar-success {
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
        }

        .bar-warning {
            background: linear-gradient(90deg, #ffc107 0%, #ff6c00 100%);
        }

        .bar-danger {
            background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
        }

        .bar-unlimited {
            background: linear-gradient(90deg, #17a2b8 0%, #138496 100%);
        }

        .status-badge {
            font-size: 13px;
            padding: 4px 10px;
            border-radius: 12px;
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
            background-color: var(--color-slate-50);
            color: var(--color-slate-700);
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 16px 12px;
            color: var(--color-slate-600);
        }

        .hover-up:hover {
            background-color: var(--color-slate-50) !important;
            transition: background-color 0.2s ease;
        }

        /* Icon colors for different leave types */
        .icon-annual {
            background-color: #cfe9ff;
            color: #0c63e4;
        }

        .icon-sick {
            background-color: #f8d7da;
            color: #dc3545;
        }

        .icon-paternity {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .icon-maternity {
            background-color: #f8cecc;
            color: #d5176b;
        }

        .icon-study {
            background-color: #fff3cd;
            color: #856404;
        }

        .icon-unpaid {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .icon-default {
            background-color: #d6d8db;
            color: #383d41;
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container px-4 py-4">

        <div class="row mb-5">
                <div class="col-lg-8">
                    <!-- Leave Balances Section -->
                    <div class="section-header d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-slate-800 mb-0">Leave Balances</h5>
                    </div>
                    <div class="leave-type-grid">
                        @foreach($dashboardData as $data)
                            @php
                                $type = $data['type'];
                                $iconClass = match ($type->LeaveTypeName) {
                                    'Annual Leave' => 'icon-annual',
                                    'Sick Leave' => 'icon-sick',
                                    'Study Leave' => 'icon-study',
                                    'Paternity Leave' => 'icon-paternity',
                                    'Maternity Leave' => 'icon-maternity',
                                    'Unpaid Leave' => 'icon-unpaid',
                                    default => 'icon-default',
                                };
                                $icon = match ($type->LeaveTypeName) {
                                    'Annual Leave' => 'fa-calendar-check',
                                    'Sick Leave' => 'fa-briefcase-medical',
                                    'Study Leave' => 'fa-graduation-cap',
                                    'Paternity Leave' => 'fa-baby',
                                    'Maternity Leave' => 'fa-breast-feeding',
                                    'Unpaid Leave' => 'fa-hand-holding-usd',
                                    default => 'fa-file-alt',
                                };

                                $percentage = 0;
                                if (!$data['isUnlimited'] && $data['total'] > 0) {
                                    $percentage = ($data['taken'] / $data['total']) * 100;
                                }

                                $barClass = 'bar-success';
                                if ($percentage > 70)
                                    $barClass = 'bar-warning';
                                if ($percentage > 90)
                                    $barClass = 'bar-danger';
                            @endphp
                            <div class="leave-type-card">
                                <div class="leave-type-header">
                                    <div class="leave-type-icon {{ $iconClass }}">
                                        <i class="fas {{ $icon }}"></i>
                                    </div>
                                    <div class="leave-type-title">
                                        <h6>{{ $type->LeaveTypeName }}</h6>
                                        @if($type->IsPaidLeave)
                                            <span class="leave-type-badge bg-success bg-opacity-10 text-success fw-bold">PAID</span>
                                        @else
                                            <span class="leave-type-badge bg-secondary bg-opacity-10 text-secondary fw-bold">UNPAID</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="leave-type-stats">
                                    <div class="stat-item">
                                        <div class="stat-label">Taken</div>
                                        <div class="stat-value">{{ $data['taken'] }} <span class="stat-unit">days</span></div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-label">Remaining</div>
                                        <div class="stat-value">
                                            {{ $data['isUnlimited'] ? '∞' : $data['remaining'] }}
                                            <span class="stat-unit">days</span>
                                        </div>
                                    </div>
                                </div>
                                @if(!$data['isUnlimited'])
                                    <div class="progress-container">
                                        <div class="progress-label">
                                            <span>Utilization</span>
                                            <span>{{ round($percentage) }}%</span>
                                        </div>
                                        <div class="progress-bar-custom">
                                            <div class="bar {{ $barClass }}" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <div class="progress-container">
                                        <div class="progress-label">
                                            <span>No Limit Apply</span>
                                        </div>
                                        <div class="progress-bar-custom">
                                            <div class="bar bar-unlimited" style="width: 100%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Actions & Recent Activity -->
                    <div class="section-header d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-slate-800 mb-0">Quick Actions</h5>
                    </div>
                    
                    <!-- Locum Booking Card -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-gradient-primary text-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-user-md fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">Locum Booking</h6>
                                    <p class="mb-0 small opacity-75">Sign in/out for locum shifts</p>
                                </div>
                                <a href="{{ route('locum.index') }}" class="btn btn-light btn-sm rounded-pill px-3">
                                    <i class="fas fa-arrow-right me-1"></i> Go
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Notifications -->
                    <div class="section-header d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-slate-800 mb-0">Recent Activity</h5>
                        <a href="{{ route('notifications') }}" class="text-primary small text-decoration-none fw-bold">See all</a>
                    </div>
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-0">
                            @forelse($recentNotifications as $notification)
                                <div class="d-flex align-items-start p-3 border-bottom border-light hov-up transition-all">
                                    <div class="rounded-circle {{ $notification->Status === 'Unread' ? 'bg-primary' : 'bg-slate-200' }} mt-2 me-3" style="width: 8px; height: 8px; flex-shrink: 0;"></div>
                                    <div class="flex-grow-1">
                                        <p class="mb-1 text-slate-700" style="font-size: 0.85rem; line-height: 1.4;">{{ $notification->Message }}</p>
                                        <span class="text-slate-400" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-5 text-center text-slate-400">
                                    <i class="fas fa-bell-slash fa-2x mb-2 text-slate-100"></i>
                                    <p class="small mb-0">No new alerts</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Worksy Recent Requests Table -->
            <div class="section-header d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <h5 class="fw-bold text-slate-800 mb-0">My Leave Applications</h5>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="search-input-wrapper position-relative">
                        <i class="fas fa-search position-absolute text-slate-400" style="top: 50%; left: 15px; transform: translateY(-50%);"></i>
                        <input type="text" id="tableSearchInput" class="form-control form-control-sm border-light shadow-sm rounded-pill ps-5 py-2" placeholder="Search requests..." onkeyup="searchTable()">
                    </div>
                    <div class="filter-controls d-flex gap-2">
                        <button class="btn btn-sm btn-light border-light rounded-pill px-3 active-filter" onclick="filterTable('all')">All</button>
                        <button class="btn btn-sm btn-light border-light rounded-pill px-3" onclick="filterTable('approved')">Approved</button>
                        <button class="btn btn-sm btn-light border-light rounded-pill px-3" onclick="filterTable('pending')">Pending</button>
                        <button class="btn btn-sm btn-light border-light rounded-pill px-3" onclick="filterTable('rejected')">Rejected</button>
                    </div>
                </div>
            </div>

            <div class="modern-table-card shadow-sm border border-light rounded-4 overflow-hidden bg-white mb-5">
                <div class="table-responsive">
                    <table class="table-modern w-100" id="leaveTable">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="ps-4 py-3 text-uppercase small fw-bold text-slate-500 letter-spacing-1">Date</th>
                                <th class="py-3 text-uppercase small fw-bold text-slate-500 letter-spacing-1">Leave Type</th>
                                <th class="py-3 text-uppercase small fw-bold text-slate-500 letter-spacing-1">Reasons</th>
                                <th class="py-3 text-uppercase small fw-bold text-slate-500 letter-spacing-1">Attachments</th>
                                <th class="text-end pe-4 py-3 text-uppercase small fw-bold text-slate-500 letter-spacing-1">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveRequests as $request)
                                <tr class="hover-up border-bottom border-light transition-all leave-row" data-status="{{ strtolower($request->RequestStatus) }}">
                                    <td class="ps-4 py-4 fw-medium text-slate-700">{{ $request->StartDate->format('d M Y') }}</td>
                                    <td class="py-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="fas fa-calendar-alt text-primary small"></i>
                                            </div>
                                            <span class="fw-semibold text-slate-800">{{ $request->leaveType->LeaveTypeName }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-slate-500 small d-block"
                                            style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $request->Reason }}
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        @if($request->Attachment)
                                            <a href="{{ asset('storage/' . $request->Attachment) }}" target="_blank"
                                                class="text-primary text-decoration-none small fw-bold hover-underline">
                                                <i class="fas fa-paperclip me-1"></i> View doc
                                            </a>
                                        @else
                                            <span class="text-slate-300 small">None</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 py-4">
                                        @php
                                            $statusClass = match ($request->RequestStatus) {
                                                'Approved' => 'bg-success bg-opacity-10 text-success',
                                                'Rejected' => 'bg-danger bg-opacity-10 text-danger',
                                                default => 'bg-warning bg-opacity-10 text-warning',
                                            };
                                            $displayStatus = match ($request->RequestStatus) {
                                                'Pending Supervisor Approval' => 'P-SUPERVISOR',
                                                'Pending Admin Verification' => 'P-ADMIN',
                                                default => $request->RequestStatus,
                                            };
                                        @endphp
                                        <span class="badge rounded-pill px-3 py-2 fw-bold {{ $statusClass }}"
                                            style="font-size: 0.7rem; letter-spacing: 0.02em;">
                                            {{ strtoupper($displayStatus) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center py-5 text-slate-400">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x mb-3 text-slate-200"></i>
                                            <p class="mb-0 fw-medium">No leave requests found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
@endsection

@section('scripts')
    <script>
        function filterTable(status) {
            const rows = document.querySelectorAll('.leave-row');
            let visibleCount = 0;

            // Update active state of buttons
            const buttons = document.querySelectorAll('.filter-controls button');
            buttons.forEach(btn => btn.classList.remove('active-filter', 'btn-primary'));
            buttons.forEach(btn => btn.classList.add('btn-light'));

            event.currentTarget.classList.remove('btn-light');
            event.currentTarget.classList.add('btn-primary', 'active-filter');

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                if (status === 'all') {
                    row.style.display = '';
                    visibleCount++;
                } else if (status === 'pending') {
                    // Include all pending sub-statuses
                    if (rowStatus.includes('pending')) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                } else if (rowStatus === status) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle empty state
            let emptyRow = document.getElementById('emptyRow');
            if (visibleCount === 0) {
                if (!emptyRow) {
                    const tbody = document.querySelector('#leaveTable tbody');
                    emptyRow = document.createElement('tr');
                    emptyRow.id = 'emptyRow';
                    emptyRow.innerHTML = `
                        <td colspan="5" class="text-center py-5 text-slate-400">
                            <div class="py-4">
                                <i class="fas fa-search fa-3x mb-3 text-slate-200"></i>
                                <p class="mb-0 fw-medium">No matches found for "${status}"</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(emptyRow);
                } else {
                    emptyRow.style.display = '';
                }
            } else if (emptyRow) {
                emptyRow.style.display = 'none';
            }
        }

        function searchTable() {
            const input = document.getElementById("tableSearchInput");
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll('.leave-row');
            let visibleCount = 0;

            rows.forEach(row => {
                // Assuming the search applies to the whole row text
                const text = row.textContent || row.innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });

            // Handle empty state
            let emptyRow = document.getElementById('emptyRow');
            if (visibleCount === 0) {
                if (!emptyRow) {
                    const tbody = document.querySelector('#leaveTable tbody');
                    emptyRow = document.createElement('tr');
                    emptyRow.id = 'emptyRow';
                    emptyRow.innerHTML = `
                        <td colspan="5" class="text-center py-5 text-slate-400">
                            <div class="py-4">
                                <i class="fas fa-search fa-3x mb-3 text-slate-200"></i>
                                <p class="mb-0 fw-medium">No matches found for "${filter}"</p>
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
    <style>
        .active-filter {
            background-color: var(--color-primary) !important;
            color: white !important;
            border-color: var(--color-primary) !important;
        }
    </style>
@endsection