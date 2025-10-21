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

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                                    @if ($request->RequestStatus === 'Pending Admin Verification')
                                        <form action="{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success mb-1">Approve</button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger mb-1" onclick="toggleRejectForm('{{ $request->LeaveRequestID }}')">Reject</button>
                                        <form id="rejectForm-{{ $request->LeaveRequestID }}"
                                              action="{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}"
                                              method="POST"
                                              style="display:none; margin-top:5px;">
                                            @csrf
                                            <textarea name="RejectionReason" class="form-control mb-2" placeholder="Enter rejection reason" required></textarea>
                                            <button type="submit" class="btn btn-sm btn-danger w-100">Confirm Rejection</button>
                                        </form>
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
    function toggleRejectForm(id) {
        const form = document.getElementById('rejectForm-' + id);
        if (form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    }
</script>
@endsection
