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
    $employee = auth()->user();

    $leaveRequests = \App\Models\LeaveRequest::where('EmployeeNumber', $employee->EmployeeNumber)
        ->orderBy('created_at', 'desc')
        ->get();

    $normalizeStatus = fn($s) => trim(strtolower((string) ($s ?? '')));

    $totalAssigned = optional($employee->grade)->AnnualLeaveDays ?? 0;
    $approvedLeaveDays = $leaveRequests
        ->filter(fn($r) => $normalizeStatus($r->RequestStatus) === 'approved')
        ->sum('TotalDays');
    $remainingDays = max(0, $totalAssigned - $approvedLeaveDays);

    $counts = [
        'approved' => $leaveRequests->filter(fn($r) => $normalizeStatus($r->RequestStatus) === 'approved')->count(),
        'rejected' => $leaveRequests->filter(fn($r) => in_array($normalizeStatus($r->RequestStatus), ['rejected', 'rejected by admin']))->count(),
        'pending_supervisor' => $leaveRequests->filter(fn($r) => $normalizeStatus($r->RequestStatus) === 'pending supervisor approval')->count(),
        'pending_admin' => $leaveRequests->filter(fn($r) => $normalizeStatus($r->RequestStatus) === 'pending admin verification')->count(),
    ];

    $priorityMap = [
        'pending supervisor approval' => 1,
        'pending admin verification' => 2,
        'rejected' => 3,
        'rejected by admin' => 3,
        'approved' => 4,
    ];

    $sortedLeaveRequests = $leaveRequests->sortBy(function ($request) use ($normalizeStatus, $priorityMap) {
        $statusKey = $normalizeStatus($request->RequestStatus);
        $priority = $priorityMap[$statusKey] ?? 5;
        $timePriority = -strtotime($request->created_at ?? now());
        return [$priority, $timePriority];
    })->values();
@endphp



<div class="dashboard-container">

    <div class="card card-custom mb-4 text-center" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Welcome, {{ $employee->FirstName ?? $employee->name ?? 'Employee' }}!</h4>
        <p class="mb-2">Here’s your leave overview.</p>
    </div>

    <div class="row g-3 mb-4">
        <!-- Approved -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle" style="width: 48px; height: 48px;">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </span>
                    </div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Approved</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $counts['approved'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle" style="width: 48px; height: 48px;">
                            <i class="fas fa-times-circle fa-lg"></i>
                        </span>
                    </div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Rejected</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $counts['rejected'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Pending Supervisor -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle" style="width: 48px; height: 48px;">
                            <i class="fas fa-user-clock fa-lg"></i>
                        </span>
                    </div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Supervisor</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $counts['pending_supervisor'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Pending Admin -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-circle" style="width: 48px; height: 48px;">
                            <i class="fas fa-hourglass-half fa-lg"></i>
                        </span>
                    </div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Admin</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $counts['pending_admin'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Annual Days -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 48px; height: 48px;">
                            <i class="fas fa-calendar-alt fa-lg"></i>
                        </span>
                    </div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Days</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalAssigned }}</h3>
                </div>
            </div>
        </div>

        <!-- Remaining -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); color: white;">
                <div class="card-body p-3 text-center">
                    <div class="mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-25 text-white rounded-circle" style="width: 48px; height: 48px;">
                            <i class="fas fa-calendar-check fa-lg"></i>
                        </span>
                    </div>
                    <h6 class="text-white-50 small text-uppercase fw-bold mb-1">Remaining</h6>
                    <h3 class="fw-bold text-white mb-0">{{ $remainingDays }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body table-responsive" style="background-color: #ffffff;">
            @if ($sortedLeaveRequests->isNotEmpty())
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
                        @foreach ($sortedLeaveRequests as $request)
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
