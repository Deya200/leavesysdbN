@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="container mt-3">
    <h2 class="mb-4">Leave Requests</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                                    <form action="{{ route('leave_requests.supervisor.approve', $request->LeaveRequestID) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve (Supervisor)</button>
                                    </form>

                                    <!-- Supervisor Rejection - Button triggers textarea -->
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showRejectionForm('{{ $request->LeaveRequestID }}')">Reject (Supervisor)</button>

                                    <!-- Hidden rejection form -->
                                    <form id="rejectForm-{{ $request->LeaveRequestID }}" action="{{ route('leave_requests.supervisor.reject', $request->LeaveRequestID) }}" method="POST" style="display:none;">
                                        @csrf
                                        <textarea name="RejectionReason" placeholder="Enter rejection reason" required></textarea>
                                        <button type="submit" class="btn btn-sm btn-danger">Confirm Rejection</button>
                                    </form>
                                @elseif ($request->RequestStatus === 'Pending Admin Verification')
                                    <!-- Admin Actions -->
                                    <form action="{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve (Admin)</button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-danger" onclick="showRejectionForm('{{ $request->LeaveRequestID }}')">Reject (Admin)</button>

                                    <form id="rejectForm-{{ $request->LeaveRequestID }}" action="{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}" method="POST" style="display:none;">
                                        @csrf
                                        <textarea name="RejectionReason" placeholder="Enter rejection reason" required></textarea>
                                        <button type="submit" class="btn btn-sm btn-danger">Confirm Rejection</button>
                                    </form>
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

<!-- JavaScript for Showing Textarea on Reject -->
<script>
    function showRejectionForm(leaveRequestID) {
        document.getElementById('rejectForm-' + leaveRequestID).style.display = 'block';
    }
</script>

@endsection
