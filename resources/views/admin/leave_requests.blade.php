@extends('layouts.app')
@section('page_title', 'Leave Requests')

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
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .table {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #2E3A87;
            color: #ffffff;
        }

        .table tbody tr {
            background-color: #ffffff;
            color: #000000;
        }

        .table th,
        .table td {
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
                                                    @if($request->RequestStatus === 'Approved') bg-success
                                                    @elseif($request->RequestStatus === 'Rejected by Admin') bg-danger
                                                    @elseif($request->RequestStatus === 'Pending Admin Verification') bg-primary
                                                    @else bg-warning text-dark @endif">
                                            {{ ucfirst($request->RequestStatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($request->RequestStatus === 'Pending Admin Verification')
                                            <button type="button" class="btn btn-sm btn-success mb-1"
                                                onclick="openConfirmModal('approve', '{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}', 'Admin Approval', 'AdminApprovalNote')">
                                                Approve
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger mb-1"
                                                onclick="openConfirmModal('reject', '{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}', 'Admin Rejection', 'AdminRejectionReason')">
                                                Reject
                                            </button>
                                        @else
                                            <em class="text-muted">No actions available</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center m-0">
                    <h5>No leave requests pending admin verification.</h5>
                </div>
            @endif
        </div>
    </div>

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

            if (type === 'approve') {
                messageEl.innerText = 'Please provide an optional approval note.';
                submitBtn.className = 'btn btn-success';
                submitBtn.innerText = 'Confirm Approval';
            } else {
                messageEl.innerText = 'Please provide a mandatory rejection reason.';
                submitBtn.className = 'btn btn-danger';
                submitBtn.innerText = 'Confirm Rejection';
            }

            noteEl.name = fieldName || (type === 'approve' ? 'AdminApprovalNote' : 'AdminRejectionReason');
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