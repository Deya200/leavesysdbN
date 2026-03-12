@extends('layouts.app')
@section('page_title', 'Leave Requests')
@section('title', 'Supervisor Dashboard')

@section('styles')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

@endsection


@section('content')
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
        </div>
    </div>
    </div>
    <!-- Summary Cards -->
    <div class="row g-4 mb-5">
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
                        </div>
                    </div>
                    <div class="progress bg-black bg-opacity-10" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: 100%; opacity: 0.8;"></div>
                    </div>
                </div>
            </div>
        </div>

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
                        </div>
                    </div>
                    @php $approvedPercent = ($totalCount > 0) ? ($approvedCount / $totalCount) * 100 : 0; @endphp
                    <div class="progress bg-black bg-opacity-10" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $approvedPercent }}%; opacity: 0.8;"></div>
                    </div>
                </div>
            </div>
        </div>

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
                        </div>
                    </div>
                    @php $rejectedPercent = ($totalCount > 0) ? ($rejectedCount / $totalCount) * 100 : 0; @endphp
                    <div class="progress bg-black bg-opacity-10" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $rejectedPercent }}%; opacity: 0.8;"></div>
                    </div>
                </div>
            </div>
        </div>

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
                        </div>
                    </div>
                    @php $pendingPercent = ($totalCount > 0) ? ($pendingCount / $totalCount) * 100 : 0; @endphp
                    <div class="progress bg-black bg-opacity-10" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $pendingPercent }}%; opacity: 0.8;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters for Admin/Supervisor -->
    <form method="GET" action="{{ route('leave_requests.index') }}" class="mb-4">

        <div class="input-group">

         <input type="text" name="search" class="form-control" placeholder="Search Name..." value="{{ request('search') }}">
         <button type="submit" class="btn" style="background-color:rgb(2, 43, 114);" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Search">
          <i class="fas fa-search" style="color:white"></i>
         </button>

        </div>

        <div class="row">

            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Filter by Status</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Pending Supervisor Approval">Pending Supervisor Approval</option>
                    <option value="Pending Admin Verification">Pending Admin Verification</option>
                </select>
            </div>

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
                </button>
            </div>

        </div>

    </form>


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

                                @php
                                    $canAdminAction = strcasecmp($request->RequestStatus, 'Pending Admin Verification') === 0;
                                    $canSupAction = strcasecmp($request->RequestStatus, 'Pending Supervisor Approval') === 0;
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
                        <textarea name="note" id="actionNote" class="form-control" rows="3" placeholder="Enter details here..."></textarea>
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

        // Prevent double form submissions
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
