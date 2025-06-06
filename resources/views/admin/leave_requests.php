@extends('layouts.app')

@section('title', 'Admin Leave Verification')

@section('content')
<div class="container mt-3">
    <h2 class="mb-4">Admin Leave Verification</h2>

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
                                    @if($request->RequestStatus === 'Approved') bg-success 
                                    @elseif($request->RequestStatus === 'Rejected by Admin') bg-danger 
                                    @elseif($request->RequestStatus === 'Pending Admin Verification') bg-primary 
                                    @else bg-warning text-dark @endif">
                                    {{ ucfirst($request->RequestStatus) }}
                                </span>
                            </td>
                            <td>
                                @if ($request->RequestStatus === 'Pending Admin Verification')
                                    <!-- Approve Action -->
                                    <form action="{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>

                                    <!-- Reject Action -->
                                    <button type="button" class="btn btn-sm btn-danger" onclick="toggleRejectForm('{{ $request->LeaveRequestID }}')">Reject</button>

                                    <!-- Hidden rejection form -->
                                    <form id="rejectForm-{{ $request->LeaveRequestID }}" action="{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}" method="POST" style="display:none; margin-top:5px;">
                                        @csrf
                                        <textarea name="RejectionReason" class="form-control" placeholder="Enter rejection reason" required></textarea>
                                        <button type="submit" class="btn btn-sm btn-danger mt-1">Confirm Rejection</button>
                                    </form>
                                @else
                                    <em>No actions available</em>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <h4>No leave requests pending admin verification.</h4>
        </div>
    @endif
</div>

<script>
    function toggleRejectForm(id) {
        var form = document.getElementById('rejectForm-' + id);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection
