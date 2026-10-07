@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="container mt-3">
    <h2 class="mb-4">Leave Requests</h2>



    @if ($leaveRequests->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover align-middle table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Days</th>
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
                            <td>
                                <span class="badge 
                                    {{ $request->RequestStatus === 'Approved' ? 'bg-success' : 
                                    ($request->RequestStatus === 'Rejected' ? 'bg-danger' : 
                                    ($request->RequestStatus === 'Pending Admin Verification' ? 'bg-primary' : 'bg-warning text-dark')) }}">
                                    {{ ucfirst($request->RequestStatus) }}
                                </span>
                            </td>
                            <td>
                                @if ($request->RequestStatus === 'Pending Supervisor Approval')
                                    <!-- Supervisor Approval -->
                                    <button type="button" class="btn btn-sm btn-success"
                                        onclick="openConfirmModal('approve', '{{ route('leave_requests.supervisor.approve', $request->LeaveRequestID) }}', 'Supervisor Approval', 'SupervisorApprovalNote')">
                                        Approve
                                    </button>

                                    <!-- Supervisor Rejection -->
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="openConfirmModal('reject', '{{ route('leave_requests.supervisor.reject', $request->LeaveRequestID) }}', 'Supervisor Rejection', 'SupervisorRejectionReason')">
                                        Reject
                                    </button>
                                @elseif ($request->RequestStatus === 'Pending Admin Verification')
                                    <!-- Admin Actions -->
                                    <button type="button" class="btn btn-sm btn-success"
                                        onclick="openConfirmModal('approve', '{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}', 'Admin Approval', 'AdminApprovalNote')">
                                        Approve (Admin)
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="openConfirmModal('reject', '{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}', 'Admin Rejection', 'AdminRejectionReason')">
                                        Reject (Admin)
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <h4>No leave requests found.</h4>
        </div>
    @endif
</div>

<!-- JavaScript for Showing Modal -->
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

    // Close modal on successful form submission
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success') || sessionStorage.getItem('modalClosed')) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('actionModal'));
            if (modal) {
                modal.hide();
            }
            sessionStorage.removeItem('modalClosed');
        }

        // Mark modal as closed when form is submitted
        document.getElementById('actionForm').addEventListener('submit', function() {
            sessionStorage.setItem('modalClosed', 'true');
        });
    });
</script>

<!-- Action Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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
                        <textarea name="note" id="actionNote" class="form-control" rows="4" placeholder="Enter details here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
