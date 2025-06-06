@extends('layouts.app')

@section('title', 'Supervisor Dashboard')

@section('styles')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

@endsection


@section('content')
<div class="container mt-3 animate__animated animate__fadeInDown">
   <div class="row text-center">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
              <img src="{{ asset('welcomedash.svg') }}" alt="Welcome Illustration" class="illustration">

              <div class="flex justify-between items-center">
              <div class="animate__animated animate__fadeInDown text-center mt-5">
                  <p class="text-2xl font-bold font-size-65 wtext" style="color: black;">
                    Welcome, {{ $employee->FirstName ?? 'Employee' }}!</p>
                  <p class="text-gray-600 wtext2">I hope you are having an amazing day!</p>
              </div>
              </div>
    </div>
    </div>
    <!-- Summary Cards for Admin -->
    <div class="row mb-4" style="padding-top: 10px;">

    @can('update', $leaveRequest)
    <a href="{{ route('leave_requests.edit', $leaveRequest) }}" class="btn btn-primary">Edit</a>
@endcan

@can('supervisorApprove', $leaveRequest)
    <form action="{{ route('leave_requests.supervisor.approve', $leaveRequest) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Approve</button>
    </form>
@endcan


        <div class="col-md-3">
            <div class="card shadow ">
                <div class="card-body text-center" style="background-color: #5169C4; color: white;">
                    <h5>Total Requests</h5>
                    <h3>{{ $leaveRequests->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-success">
                <div class="card-body text-center" style="background-color: #5169C4; color: white;">
                    <h5>Approved Requests</h5>
                    <h3 style="color: #A3E4A7;">{{ $leaveRequests->where('RequestStatus', 'Approved')->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-danger">
                <div class="card-body text-center" style="background-color: #5169C4; color: white;">
                    <h5 >Rejected Requests</h5>
                    <h3 class="text-danger">{{ $leaveRequests->where('RequestStatus', 'Rejected')->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-warning">
                <div class="card-body text-center" style="background-color: #5169C4; color: white;">
                    <h5>Pending Requests</h5>
                    <h3 class="text-warning">{{ $leaveRequests->where('RequestStatus', 'Pending Admin Verification')->count() }}</h3>
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
                                  <i class="{{ $request->RequestStatus === 'Approved' ? 'fas fa-check-circle' :
                                 ($request->RequestStatus === 'Rejected' ? 'fas fa-times-circle' :
                                 ($request->RequestStatus === 'Admin ' ? 'fas fa-tools' : 'fas fa-clock')) }}"></i>
                                {{ ucfirst($request->RequestStatus) }}
                            </span>
                        </td>
                        <td style="border: none;">

                                @if ($request->RequestStatus === 'Pending Supervisor Approval')
                                    <!-- Supervisor Approval -->
                                    <form action="{{ route('leave_requests.supervisor.approve', $request->LeaveRequestID) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Supervisor Approve">
                                        <i class="fas fa-check-circle"></i> <i class="fas fa-user-tie"></i>
                                        </button>
                                    </form>

                                    <!-- Supervisor Rejection - Button triggers textarea -->
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showRejectionForm('{{ $request->LeaveRequestID }}')" data-bs-toggle="tooltip" data-bs-placement="top" title="Supervisor reject">
                                    <i class="fas fa-times-circle"></i> <i class="fas fa-user-tie"></i>
                                    </button>

                                    <!-- Hidden rejection form -->
                                    <form id="rejectForm-{{ $request->LeaveRequestID }}" action="{{ route('leave_requests.supervisor.reject', $request->LeaveRequestID) }}" method="POST" style="display:none;">
                                        @csrf
                                        <textarea name="RejectionReason" placeholder="Enter rejection reason" required></textarea>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times-circle"></i> Confirm
                                        </button>
                                    </form>
                                @elseif ($request->RequestStatus === 'Pending Admin Verification')
                                    <!-- Admin Actions -->
                                    <form action="{{ route('leave_requests.admin.approve', $request->LeaveRequestID) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Admin Approve">
                                        <i class="fas fa-check-circle"></i><i class="fas fa-user-shield"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-danger" onclick="showRejectionForm('{{ $request->LeaveRequestID }}')" data-bs-toggle="tooltip" data-bs-placement="top" title="Admin Reject">
                                    <i class="fas fa-times-circle"></i> <i class="fas fa-user-shield"></i>
                                    </button>

                                    <form id="rejectForm-{{ $request->LeaveRequestID }}" action="{{ route('leave_requests.admin.reject', $request->LeaveRequestID) }}" method="POST" style="display:none;">
                                        @csrf
                                        <textarea name="RejectionReason" placeholder="Enter rejection reason" required></textarea>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times-circle"></i> Confirm
                                        </button>
                                    </form>
                                @endif

                        </td>
                    </tr>


@endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript for Showing Textarea on Reject -->
<script>
    function showRejectionForm(leaveRequestID) {
        document.getElementById('rejectForm-' + leaveRequestID).style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function () {
     var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
     var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
     });
    });
</script>

@endsection
