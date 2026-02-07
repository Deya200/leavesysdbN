@extends('layouts.app')

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
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 20px;
    }

    .table {
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .table thead {
        background-color: #2E3A87;
        color: #ffffff;
    }

    .table tbody tr {
        background-color: #ffffff;
        color: #000000;
    }

    .table th, .table td {
        padding: 12px;
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

    <!-- Welcome Section -->
    <div class="card-custom text-center mb-4" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Welcome, {{ auth()->user()->FirstName ?? 'Administrator' }}!</h4>
        <p class="mb-2">You can review, approve, or reject leave requests submitted by employees.</p>
    </div>



    <div class="card-custom">
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
                                <td>{{ $request->StartDate }}</td>
                                <td>{{ $request->EndDate }}</td>
                                <td>{{ $request->TotalDays }}</td>
                                <td>{{ $request->Reason ?? 'N/A' }}</td> <!-- ✅ New cell -->
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

                                    @if ($canAdminAction)
                                        <div class="d-flex flex-column gap-2">
                                            <button type="button" class="btn btn-sm btn-success" 
                                                onclick="openConfirmModal('approve', '{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}', 'Admin Approval')">
                                                Approve
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="openConfirmModal('reject', '{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}', 'Admin Rejection')">
                                                Reject
                                            </button>
                                        </div>
                                    @elseif ($canSupAction)
                                        <div class="d-flex flex-column gap-2">
                                            <button type="button" class="btn btn-sm btn-success" 
                                                onclick="openConfirmModal('approve', '{{ route('leave_requests.supervisor.approve', $request->LeaveRequestID) }}', 'Supervisor Approval', 'SupervisorApprovalNote')">
                                                Sup. Approve
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="openConfirmModal('reject', '{{ route('leave_requests.supervisor.reject', $request->LeaveRequestID) }}', 'Supervisor Rejection', 'SupervisorRejectionReason')">
                                                Sup. Reject
                                            </button>
                                        </div>
                                    @else
                                        <em class="text-muted">No actions available</em>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-3">
            <a href="{{ route('leave.report.pdf') }}" class="btn btn-outline-secondary shadow-sm btn-sm">
                📄 Download Leave Report (PDF)
            </a>
        </div>
        @else
            <div class="alert alert-info text-center m-0">
                <h5>No leave requests pending admin verification.</h5>
            </div>
        @endif
    </div>
</div>
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
</script>
@endsection
