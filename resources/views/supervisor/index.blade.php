@extends('layouts.app')

@section('title', 'Leave Requests')

@section('header')

    <!-- Search Form -->
    <form action="{{ route('departments.index') }}" method="GET" class="mb-4" style="width:75%;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Department Name or Supervisor Name" value="{{ $search ?? '' }}" >
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

@endsection

@section(section: 'content')
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
            <table class="table table-hover align-middle">
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
                        <tr>
                            <td style="border: none;">{{ $loop->iteration }}</td>
                            <td style="border: none;">{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                            <td style="border: none;">{{ $request->leaveType->LeaveTypeName }}</td>
                            <td style="border: none;">{{ $request->StartDate }}</td>
                            <td style="border: none;">{{ $request->EndDate }}</td>
                            <td style="border: none;">{{ $request->TotalDays }}</td>
                            <td style="border: none;">
                                <span class="badge
                                    {{ $request->RequestStatus === 'Approved' ? 'bg-success' :
                                    ($request->RequestStatus === 'Rejected' ? 'bg-danger' :
                                    ($request->RequestStatus === 'Pending Admin Verification' ? 'bg-primary' : 'bg-warning text-dark')) }}">
                                    {{ ucfirst($request->RequestStatus) }}
                                </span>
                            </td>
                            <td style="border: none;">
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
