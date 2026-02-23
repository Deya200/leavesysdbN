@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('styles')
<style>
    .dashboard-container {
        max-width: 1400px;
        margin: auto;
        padding: 20px;
    }
    .card-custom {
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        padding: 10px 12px;
        background: white;
    }
    .card-custom:hover {
        transform: scale(1.01);
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
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 16px;
        background: #ffffff;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .leave-type-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        border-color: #1e3c72;
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
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .stat-value {
        font-size: 16px;
        font-weight: 700;
        color: #1e3c72;
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
        background-color:rgb(235, 236, 240);
        color: #333;
    }
    .table th, .table td {
        padding: 12px;
    }
    .hover-up:hover {
        transform: translateY(-3px);
        transition: transform 0.3s ease;
    }
    
    /* Icon colors for different leave types */
    .icon-annual { background-color: #cfe9ff; color: #0c63e4; }
    .icon-sick { background-color: #f8d7da; color: #dc3545; }
    .icon-paternity { background-color: #d1ecf1; color: #0c5460; }
    .icon-maternity { background-color: #f8cecc; color: #d5176b; }
    .icon-study { background-color: #fff3cd; color: #856404; }
    .icon-unpaid { background-color: #e2e3e5; color: #383d41; }
    .icon-default { background-color: #d6d8db; color: #383d41; }
</style>
@endsection

@section('content')





@php
    $normalizeStatus = fn($s) => trim(strtolower((string) ($s ?? '')));
@endphp

<div class="dashboard-container">

    <!-- Welcome Section -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
        <div class="card-body p-4 text-center text-white">
            <h3 class="fw-bold mb-1">Hello, {{ $employee->FirstName ?? 'Employee' }}!</h3>
            <p class="mb-0 text-white-50">Manage your leave requests and track your balances below.</p>
        </div>
    </div>

    <!-- Usage Statistics Section -->
    <h5 class="fw-bold mb-3 text-dark d-flex align-items-center">
        <i class="fas fa-chart-pie me-2 text-primary"></i> Leave Balances & Usage
    </h5>

    <div class="leave-type-grid">
        @foreach($dashboardData as $data)
            @php
                // Determine icon and color based on leave type
                $leaveTypeName = strtolower($data['type']->LeaveTypeName);
                if (str_contains($leaveTypeName, 'annual')) {
                    $iconClass = 'icon-annual';
                    $icon = 'fas fa-calendar-alt';
                } elseif (str_contains($leaveTypeName, 'sick')) {
                    $iconClass = 'icon-sick';
                    $icon = 'fas fa-pills';
                } elseif (str_contains($leaveTypeName, 'paternity')) {
                    $iconClass = 'icon-paternity';
                    $icon = 'fas fa-baby';
                } elseif (str_contains($leaveTypeName, 'maternity')) {
                    $iconClass = 'icon-maternity';
                    $icon = 'fas fa-heart';
                } elseif (str_contains($leaveTypeName, 'study')) {
                    $iconClass = 'icon-study';
                    $icon = 'fas fa-book';
                } elseif (str_contains($leaveTypeName, 'unpaid')) {
                    $iconClass = 'icon-unpaid';
                    $icon = 'fas fa-ban';
                } else {
                    $iconClass = 'icon-default';
                    $icon = 'fas fa-calendar-check';
                }
                
                // Calculate usage percentage
                $usagePercent = 0;
                $barClass = 'bar-success';
                if (!$data['isUnlimited'] && $data['total'] > 0) {
                    $usagePercent = min(100, ($data['taken'] / $data['total']) * 100);
                    if ($usagePercent > 80) {
                        $barClass = 'bar-danger';
                    } elseif ($usagePercent > 50) {
                        $barClass = 'bar-warning';
                    }
                } else {
                    $barClass = 'bar-unlimited';
                    $usagePercent = 100;
                }
            @endphp
            
            <div class="leave-type-card">
                <!-- Header with Icon and Badge -->
                <div class="leave-type-header">
                    <div class="leave-type-icon {{ $iconClass }}">
                        <i class="{{ $icon }}"></i>
                    </div>
                    <div class="leave-type-title">
                        <h6>{{ $data['type']->LeaveTypeName }}</h6>
                        @if($data['isUnlimited'])
                            <span class="leave-type-badge bg-success bg-opacity-10 text-success">Unlimited</span>
                        @else
                            @if($data['type']->IsPaidLeave)
                                <span class="leave-type-badge bg-info bg-opacity-10 text-info">Paid</span>
                            @else
                                <span class="leave-type-badge bg-secondary bg-opacity-10 text-secondary">Unpaid</span>
                            @endif
                        @endif
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="leave-type-stats">
                    <div class="stat-item">
                        <div class="stat-label">Remaining</div>
                        <div class="stat-value">
                            @if($data['isUnlimited'])
                                <span class="text-success">∞</span>
                            @else
                                {{ $data['remaining'] }}
                            @endif
                        </div>
                        @if(!$data['isUnlimited'])
                            <span class="stat-unit">days</span>
                        @endif
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Taken</div>
                        <div class="stat-value">{{ $data['taken'] }}</div>
                        <span class="stat-unit">days</span>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                @if(!$data['isUnlimited'])
                    <div class="progress-container">
                        <div class="progress-label">
                            <span>Usage</span>
                            <span>{{ round($usagePercent, 0) }}%</span>
                        </div>
                        <div class="progress-bar-custom">
                            <div class="bar {{ $barClass }}" style="width: {{ $usagePercent }}%"></div>
                        </div>
                    </div>
                @else
                    <div class="progress-container">
                        <div class="progress-label">
                            <span>Status</span>
                        </div>
                        <div class="progress-bar-custom">
                            <div class="bar bar-unlimited" style="width: 100%"></div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Request History Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
            <i class="fas fa-history me-2 text-primary"></i> Recent Requests
        </h5>
        <div class="d-flex gap-2">
            <a href="{{ route('leave_requests.employee_history') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="fas fa-list me-1"></i> View Full History
            </a>
            <a href="{{ route('leave.report.employee.pdf') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="fas fa-file-pdf me-1"></i> Generate Report
            </a>
        </div>
    </div>


    <div class="card card-custom">
        <div class="card-body table-responsive" style="background-color: #ffffff;">
            @if ($leaveRequests->isNotEmpty())
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Status</th>
                            <th>Dates</th>
                            <th>Notes/Comments</th>
                            <th>Quick Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveRequests as $request)
                            @php
                                $statusNormalized = $normalizeStatus($request->RequestStatus);
                                $isRejected = in_array($statusNormalized, ['rejected', 'rejected by admin']);
                                
                                $badgeClass = match($statusNormalized) {
                                    'approved' => 'bg-success-subtle text-success border border-success-subtle',
                                    'rejected', 'rejected by admin' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    'pending supervisor approval' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                    'pending admin verification' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                                    default => 'bg-secondary-subtle text-secondary'
                                };
                                
                                $statusIcon = match($statusNormalized) {
                                    'approved' => 'fas fa-check-circle',
                                    'rejected', 'rejected by admin' => 'fas fa-times-circle',
                                    'pending supervisor approval' => 'fas fa-user-clock',
                                    'pending admin verification' => 'fas fa-hourglass-half',
                                    default => 'fas fa-circle'
                                };

                                $rejectReason = $request->RejectionReason ?? $request->SupervisorRejectionReason ?? null;
                                $friendlyStatus = ucwords($statusNormalized);
                            @endphp
                            <tr class="hover-up">
                                <td>{{ optional($request->leaveType)->LeaveTypeName ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge rounded-pill fw-normal px-3 py-2 {{ $badgeClass }}">
                                        <i class="{{ $statusIcon }} me-1"></i> {{ $friendlyStatus }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ $request->StartDate ? \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') : 'N/A' }}</div>
                                    <div class="small text-muted">to {{ $request->EndDate ? \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') : 'N/A' }}</div>
                                    <div class="text-primary small">({{ $request->TotalDays }} days)</div>
                                </td>
                                <td>
                                    <div class="small text-wrap" style="max-width: 250px;">
                                        @if($request->SupervisorApprovalNote)
                                            <div class="mb-1"><strong>Sup. Note:</strong> {{ $request->SupervisorApprovalNote }}</div>
                                        @endif
                                        
                                        @if($request->AdminApprovalNote)
                                            <div class="mb-1"><strong>Admin Note:</strong> {{ $request->AdminApprovalNote }}</div>
                                        @endif

                                        @if($isRejected)
                                            <div class="text-danger">
                                                <strong>Reason:</strong> {{ $rejectReason ?? 'No reason provided' }}
                                            </div>
                                        @endif

                                        @if(!$request->SupervisorApprovalNote && !$request->AdminApprovalNote && !$isRejected)
                                            <em class="text-muted small">Awaiting review...</em>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2">
                                        @if($request->canBeAppealed())
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#appealModal" 
                                                data-id="{{ $request->LeaveRequestID }}" data-reason="{{ $rejectReason }}">
                                                Appeal
                                            </button>
                                        @endif

                                        @if($request->canBeExtended())
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#extendModal" 
                                                data-id="{{ $request->LeaveRequestID }}" data-end="{{ $request->EndDate }}">
                                                Extend
                                            </button>
                                        @endif

                                        @if($request->canBeCancelled())
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal" 
                                                data-id="{{ $request->LeaveRequestID }}">
                                                Cancel
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center">No leave requests found.</p>
            @endif
        </div>
    </div>

</div>

<!-- Appeal Modal -->
<div class="modal fade" id="appealModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="appealForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Appeal Leave Rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger"><strong>Original Rejection Reason:</strong> <span id="modalRejectReason"></span></p>
                    <div class="mb-3">
                        <label for="appealReason" class="form-label">Reason for Appeal</label>
                        <textarea class="form-control" id="appealReason" name="Reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Appeal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Extension Modal -->
<div class="modal fade" id="extendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="extendForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Leave Extension</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Current End Date: <strong id="modalCurrentEnd"></strong></p>
                    <div class="mb-3">
                        <label for="extensionDays" class="form-label">Additional Days Requesting</label>
                        <input type="number" class="form-control" id="extensionDays" name="ExtensionDays" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="extensionReason" class="form-label">Reason</label>
                        <textarea class="form-control" id="extensionReason" name="Reason" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Request Extension</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Cancellation Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="cancelForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this leave request? Refunds will be calculated based on remaining days.</p>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Reason for Cancellation</label>
                        <textarea class="form-control" id="cancelReason" name="Reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Appeal Modal
        var appealModal = document.getElementById('appealModal');
        appealModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var reason = button.getAttribute('data-reason');
            
            var form = document.getElementById('appealForm');
            form.action = '/leave-requests/' + id + '/appeal';
            
            document.getElementById('modalRejectReason').textContent = reason || 'No reason provided';
        });

        // Extension Modal
        var extendModal = document.getElementById('extendModal');
        extendModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var endDate = button.getAttribute('data-end');
            
            var form = document.getElementById('extendForm');
            form.action = '/leave-requests/' + id + '/extend';
            
            document.getElementById('modalCurrentEnd').textContent = endDate;
        });

        // Cancellation Modal
        var cancelModal = document.getElementById('cancelModal');
        cancelModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            
            var form = document.getElementById('cancelForm');
            form.action = '/leave-requests/' + id + '/cancel';
        });
    });
</script>
@endsection
