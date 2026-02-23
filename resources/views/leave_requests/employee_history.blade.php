@extends('layouts.app')

@section('title', 'My Leave Request History')

@section('styles')
<style>
    .history-card {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .badge-custom {
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 12px;
    }

    .search-box {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .stats-card {
        border-radius: 10px;
        padding: 12px;
        background: #f8f9fa;
        border-left: 4px solid;
    }

    .stats-card.approved {
        border-left-color: #28a745;
    }

    .stats-card.rejected {
        border-left-color: #dc3545;
    }

    .stats-card.pending {
        border-left-color: #ffc107;
    }

    .stats-number {
        font-size: 20px;
        font-weight: bold;
        color: #333;
    }

    .stats-label {
        font-size: 12px;
        color: #666;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark">
                <i class="fas fa-history me-2 text-primary"></i> Leave Request History
            </h2>
            <p class="text-muted small">View and manage all your leave requests</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('leave_requests.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus me-2"></i> New Request
            </a>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Your Leave History:</strong> View and manage all your leave requests. You have the option to appeal rejections, extend approved leaves, or cancel future requests.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        @php
            $approved = $leaveRequests->where('RequestStatus', 'Approved')->count();
            $rejected = $leaveRequests->whereIn('RequestStatus', ['Rejected', 'Rejected by Admin'])->count();
            $pending = $leaveRequests->whereIn('RequestStatus', ['Pending Supervisor Approval', 'Pending Admin Verification'])->count();
        @endphp
        <div class="col-md-3 mb-2">
            <div class="stats-card approved">
                <div class="stats-number text-success">{{ $approved }}</div>
                <div class="stats-label">Approved</div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card rejected">
                <div class="stats-number text-danger">{{ $rejected }}</div>
                <div class="stats-label">Rejected</div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card pending">
                <div class="stats-number text-warning">{{ $pending }}</div>
                <div class="stats-label">Pending</div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card">
                <div class="stats-number">{{ $leaveRequests->count() }}</div>
                <div class="stats-label">Total Requests</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card history-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('leave_requests.employee_history') }}" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control search-box" placeholder="Search by leave type or status..." value="{{ $search }}">
                </div>
                <div class="col-md-6">
                    <select name="status" class="form-select search-box">
                        <option value="">All Status</option>
                        <option value="Approved" {{ $status === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ $status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Rejected by Admin" {{ $status === 'Rejected by Admin' ? 'selected' : '' }}>Rejected by Admin</option>
                        <option value="Pending Supervisor Approval" {{ $status === 'Pending Supervisor Approval' ? 'selected' : '' }}>Pending Supervisor</option>
                        <option value="Pending Admin Verification" {{ $status === 'Pending Admin Verification' ? 'selected' : '' }}>Pending Admin</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <a href="{{ route('leave_requests.employee_history') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Leave Requests Table -->
    @if($leaveRequests->isNotEmpty())
        <div class="card history-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Leave Type</th>
                            <th>Status</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Notes</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $request)
                            @php
                                $statusNormalized = strtolower(str_replace(' ', '_', $request->RequestStatus));
                                $isRejected = in_array($request->RequestStatus, ['Rejected', 'Rejected by Admin']);
                                $rejectReason = $request->RejectionReason ?? $request->SupervisorRejectionReason ?? null;
                                
                                $badgeClass = match($request->RequestStatus) {
                                    'Approved' => 'bg-success-subtle text-success',
                                    'Rejected', 'Rejected by Admin' => 'bg-danger-subtle text-danger',
                                    'Pending Supervisor Approval' => 'bg-warning-subtle text-warning-emphasis',
                                    'Pending Admin Verification' => 'bg-info-subtle text-info-emphasis',
                                    default => 'bg-secondary-subtle text-secondary'
                                };
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-primary-subtle text-primary badge-custom">
                                        {{ $request->leaveType->LeaveTypeName ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill badge-custom {{ $badgeClass }}">
                                        {{ $request->RequestStatus }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</div>
                                    <div class="text-muted small">to {{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $request->TotalDays }} days</span>
                                </td>
                                <td>
                                    <div class="small text-wrap" style="max-width: 250px;">
                                        @if($request->SupervisorApprovalNote)
                                            <div class="mb-1"><strong>Sup.:</strong> {{ $request->SupervisorApprovalNote }}</div>
                                        @endif
                                        @if($request->AdminApprovalNote)
                                            <div class="mb-1"><strong>Admin:</strong> {{ $request->AdminApprovalNote }}</div>
                                        @endif
                                        @if($isRejected)
                                            <div class="text-danger"><strong>Reason:</strong> {{ $rejectReason ?? 'No reason provided' }}</div>
                                        @endif
                                        @if(!$request->SupervisorApprovalNote && !$request->AdminApprovalNote && !$isRejected)
                                            <em class="text-muted">Awaiting review...</em>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                                        @if($request->canBeAppealed())
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#appealModal" 
                                                data-id="{{ $request->LeaveRequestID }}" data-reason="{{ $rejectReason }}" title="Appeal this rejection">
                                                <i class="fas fa-redo"></i> Appeal
                                            </button>
                                        @endif

                                        @if($request->canBeExtended())
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#extendModal" 
                                                data-id="{{ $request->LeaveRequestID }}" data-end="{{ $request->EndDate }}" title="Extend this leave">
                                                <i class="fas fa-plus"></i> Extend
                                            </button>
                                        @endif

                                        @if($request->canBeCancelled())
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal" 
                                                data-id="{{ $request->LeaveRequestID }}" title="Cancel this request">
                                                <i class="fas fa-trash"></i> Cancel
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($leaveRequests->hasPages())
                <div class="card-footer bg-light">
                    {{ $leaveRequests->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.5; display: block; margin-bottom: 10px;"></i>
            <p class="fw-bold mb-1">No Leave Requests Found</p>
            <p class="text-muted small mb-3">You haven't submitted any leave requests yet.</p>
            <a href="{{ route('leave_requests.create') }}" class="btn btn-primary rounded-pill">
                <i class="fas fa-plus me-2"></i> Create Your First Request
            </a>
        </div>
    @endif
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var appealModal = document.getElementById('appealModal');
        appealModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var reason = button.getAttribute('data-reason');
            
            var form = document.getElementById('appealForm');
            form.action = '/leave-requests/' + id + '/appeal';
            
            document.getElementById('modalRejectReason').textContent = reason || 'No reason provided';
        });

        var extendModal = document.getElementById('extendModal');
        extendModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var endDate = button.getAttribute('data-end');
            
            var form = document.getElementById('extendForm');
            form.action = '/leave-requests/' + id + '/extend';
            
            document.getElementById('modalCurrentEnd').textContent = endDate;
        });

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
@endsection
