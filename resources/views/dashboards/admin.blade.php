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
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            padding: 14px;
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

        /* Compact stat cards */
        .stat-card {
            padding: 12px;
            border-radius: 12px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.12);
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
        }

        .stat-label {
            font-size: 0.75rem;
            letter-spacing: 0.04em;
        }

        /* Compact action buttons */
        .small-action-btn {
            padding: 4px 8px;
            font-size: 0.78rem;
            border-radius: 6px;
        }

        .table td .actions-inline {
            display: flex;
            gap: 6px;
            align-items: center;
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

        <!-- Modern Welcome Banner -->
        <div class="card p-0 overflow-hidden mb-4 border-0 shadow-lg" style="border-radius: 1.5rem;">
            <div class="p-5 text-white position-relative"
                style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
                <div class="position-relative z-1">
                    <h2 class="fw-bold mb-2">Welcome Back, {{ auth()->user()->FirstName }}!</h2>
                    <p class="opacity-75 mb-4 fs-5">You have full control over the leave management system. Review, approve,
                        and manage with ease.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('leave_types.index') }}" class="btn btn-light px-4 py-2 fw-semibold text-primary">
                            <i class="fas fa-cog me-2"></i>Configure Leave Types
                        </a>
                        <a href="{{ route('leave.report.pdf') }}" class="btn btn-outline-light px-4 py-2 fw-semibold">
                            <i class="fas fa-file-pdf me-2"></i>Generate Report
                        </a>
                    </div>
                </div>
                <!-- Decorative circle -->
                <div class="position-absolute top-50 end-0 translate-middle-y opacity-10"
                    style="margin-right: -5%; pointer-events: none;">
                    <i class="fas fa-shield-alt" style="font-size: 15rem;"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-3"
                    style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white bg-opacity-20 p-3 me-3">
                            <i class="fas fa-users fs-4"></i>
                        </div>
                        <div>
                            <small class="opacity-75 text-uppercase fw-bold" style="font-size: 0.7rem;">Total
                                Employees</small>
                            <h3 class="fw-bold mb-0">{{ \App\Models\Employee::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-3"
                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white bg-opacity-20 p-3 me-3">
                            <i class="fas fa-calendar-check fs-4"></i>
                        </div>
                        <div>
                            <small class="opacity-75 text-uppercase fw-bold" style="font-size: 0.7rem;">Active
                                Leaves</small>
                            <h3 class="fw-bold mb-0">
                                {{ \App\Models\LeaveRequest::where('RequestStatus', 'Approved')->where('is_active', true)->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-3"
                    style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white bg-opacity-20 p-3 me-3">
                            <i class="fas fa-clock fs-4"></i>
                        </div>
                        <div>
                            <small class="opacity-75 text-uppercase fw-bold" style="font-size: 0.7rem;">Pending
                                Approvals</small>
                            <h3 class="fw-bold mb-0">{{ $leaveRequests->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-3"
                    style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white bg-opacity-20 p-3 me-3">
                            <i class="fas fa-building fs-4"></i>
                        </div>
                        <div>
                            <small class="opacity-75 text-uppercase fw-bold" style="font-size: 0.7rem;">Departments</small>
                            <h3 class="fw-bold mb-0">{{ \App\Models\Department::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
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
                                                <button type="button" class="btn btn-sm btn-info text-white"
                                                    onclick="fetchAndShowLeaveModal('{{ route('leave_requests.show', $request->LeaveRequestID) }}')">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
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
                                                <button type="button" class="btn btn-sm btn-info text-white"
                                                    onclick="fetchAndShowLeaveModal('{{ route('leave_requests.show', $request->LeaveRequestID) }}')">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
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
                        <textarea name="note" id="actionNote" class="form-control" rows="3"
                            placeholder="Enter details here..."></textarea>
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

        // Prevent double form submissions
        document.addEventListener('DOMContentLoaded', function () {
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
    </script>
@endsection