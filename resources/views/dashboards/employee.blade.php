@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('styles')
<style>
    .dashboard-container {
        max-width: 1200px;
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

    <div class="row g-4 mb-5">
        @foreach($dashboardData as $data)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-up overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-3 bg-primary bg-opacity-10 p-3 text-primary">
                                <i class="fas fa-calendar-check fa-lg"></i>
                            </div>
                            <div class="text-end">
                                <h6 class="text-muted small text-uppercase fw-bold mb-1">{{ $data['type']->LeaveTypeName }}</h6>
                                @if($data['isUnlimited'])
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Unlimited</span>
                                @else
                                    <span class="h4 fw-bold text-dark mb-0">{{ $data['remaining'] }}</span>
                                    <small class="text-muted fw-normal ms-1">days left</small>
                                @endif
                            </div>
                        </div>

                        <hr class="my-3 opacity-10">

                        <div class="row g-0">
                            <div class="col-6 border-end py-1">
                                <div class="text-muted small mb-1">Leave Taken</div>
                                <div class="fw-bold text-dark">{{ $data['taken'] }} <small class="text-muted fw-normal">days</small></div>
                            </div>
                            <div class="col-6 ps-3 py-1">
                                <div class="text-muted small mb-1">Total Limit</div>
                                <div class="fw-bold text-dark">
                                    @if($data['isUnlimited'])
                                        Unlimited
                                    @else
                                        {{ $data['total'] }} <small class="text-muted fw-normal">days</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(!$data['isUnlimited'])
                        @php
                            $usagePercent = min(100, ($data['total'] > 0 ? ($data['taken'] / $data['total']) * 100 : 0));
                            $barColor = $usagePercent > 80 ? 'bg-danger' : ($usagePercent > 50 ? 'bg-warning' : 'bg-success');
                        @endphp
                        <div class="progress rounded-0" style="height: 4px;">
                            <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $usagePercent }}%"></div>
                        </div>
                    @else
                        <div class="bg-success" style="height: 4px; opacity: 0.1;"></div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Request History Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
            <i class="fas fa-history me-2 text-primary"></i> Recent Requests
        </h5>
        <a href="{{ route('leave.report.employee.pdf') }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-file-pdf me-1"></i> Generate Report
        </a>
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
