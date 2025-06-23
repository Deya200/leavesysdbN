@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4" style="color: black;">Supervisor Dashboard</h2>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm text-center p-3">
                <h5><i class="fas fa-users"></i> Total Employees Under Supervision</h5>
                <strong class="fs-3">{{ $totalEmployees }}</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm text-center p-3">
                <h5><i class="fas fa-female"></i> Total Female Employees</h5>
                <strong class="fs-3">{{ $totalFemaleEmployees }}</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm text-center p-3">
                <h5><i class="fas fa-male"></i> Total Male Employees</h5>
                <strong class="fs-3">{{ $totalMaleEmployees }}</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm text-center p-3">
                <h5><i class="fas fa-user-clock"></i> Employees Currently on Leave</h5>
                <strong class="fs-3">{{ $employeesOnLeave }}</strong>
            </div>
        </div>
    </div>

    <!-- Pending Requests Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-danger shadow-sm text-center p-3">
                <h5><i class="fas fa-hourglass-half"></i> Pending Supervisor Approval</h5>
                <strong class="fs-3">{{ $pendingSupervisorRequests }}</strong>
            </div>
        </div>
        @if ($totalEmployees > 0)
            <div class="col-md-6">
                <div class="card text-white bg-dark shadow-sm text-center p-3">
                    <h5><i class="fas fa-user-check"></i> Pending Admin Approval</h5>
                    <strong class="fs-3">{{ $pendingAdminRequests }}</strong>
                </div>
            </div>
        @endif
    </div>

    <!-- Leave Requests Table -->
    <div class="card shadow-sm p-3 mb-4">
        <h4 class="fw-bold text-center">Leave Requests</h4>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-bordered" id="leaveRequestsTable">
                <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaveRequests as $index => $request)
                        <tr class="{{ $index >= 5 ? 'hidden-row' : '' }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                            <td>{{ $request->leaveType->LeaveTypeName }}</td>
                            <td>{{ $request->StartDate }}</td>
                            <td>{{ $request->EndDate }}</td>
                            <td>{{ $request->TotalDays }}</td>
                            <td>{{ $request->Reason }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $request->RequestStatus === 'Approved' ? 'success' : ($request->RequestStatus === 'Rejected' ? 'danger' : ($request->RequestStatus === 'Pending Admin Verification' ? 'primary' : 'warning text-dark')) }}">
                                    {{ ucfirst($request->RequestStatus) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($request->RequestStatus === 'Pending Supervisor Approval')
                                    <!-- Buttons Row: Approve and Reject side by side -->
                                    <div class="d-flex flex-row align-items-center gap-2">
                                        <!-- Approve Button -->
                                        <form action="{{ route('leave_requests.supervisor.approve', $request->LeaveRequestID) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm shadow-sm">Approve</button>
                                        </form>

                                        <!-- Reject Button (toggles the form) -->
                                        <button type="button" class="btn btn-danger btn-sm shadow-sm" onclick="toggleRejectForm('{{ $request->LeaveRequestID }}')">Reject</button>
                                    </div>
                                    <!-- Hidden Rejection Form (appears below the buttons when toggled) -->
                                    <div id="rejectForm-{{ $request->LeaveRequestID }}" style="display:none; margin-top:5px; width:100%;">
                                        <form action="{{ route('leave_requests.supervisor.reject', $request->LeaveRequestID) }}" method="POST">
                                            @csrf
                                            <textarea name="RejectionReason" class="form-control form-control-sm mt-1" placeholder="Enter reason" required></textarea>
                                            <button type="submit" class="btn btn-sm btn-danger mt-1 w-100">Confirm Rejection</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- See More / See Less Buttons -->
        <div class="text-center mt-3">
            <button class="btn btn-outline-primary shadow-sm" onclick="toggleLeaveTable()" id="leaveToggleButton">See More</button>
            <button class="btn btn-outline-secondary shadow-sm" onclick="toggleLeaveTable()" id="leaveLessButton" style="display: none;">See Less</button>
        </div>
    </div>

    <!-- Employees Under Supervision -->
    <div class="card shadow-sm p-3">
        <h4 class="fw-bold text-center">Employees Under Your Supervision</h4>
        @if ($employeesUnderSupervisor->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered" id="employeeTable">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Employee Number</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Total Annual Leave Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employeesUnderSupervisor as $index => $employee)
                            <tr class="{{ $index >= 5 ? 'hidden-row' : '' }}">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $employee->EmployeeNumber }}</td>
                                <td>{{ $employee->FirstName }} {{ $employee->LastName }}</td>
                                <td>{{ $employee->department->DepartmentName ?? 'N/A' }}</td>
                                <td>{{ $employee->position->PositionName ?? 'N/A' }}</td>
                                <td class="text-center">{{ optional($employee->grade)->AnnualLeaveDays ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- See More / See Less Buttons -->
            <div class="text-center mt-3">
                <button class="btn btn-outline-primary shadow-sm" onclick="toggleEmployeeTable()" id="toggleButton">See More</button>
                <button class="btn btn-outline-secondary shadow-sm" onclick="toggleEmployeeTable()" id="toggleLessButton" style="display: none;">See Less</button>
            </div>
        @endif
    </div>

    <script>
        function toggleRejectForm(id) {
            const form = document.getElementById('rejectForm-' + id);
            if (form) {
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }
        }

        function toggleLeaveTable() {
            let hiddenRows = document.querySelectorAll('#leaveRequestsTable .hidden-row');
            let moreButton = document.getElementById('leaveToggleButton');
            let lessButton = document.getElementById('leaveLessButton');

            hiddenRows.forEach(row => {
                row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
            });

            if (moreButton.style.display !== 'none') {
                moreButton.style.display = 'none';
                lessButton.style.display = 'inline-block';
            } else {
                moreButton.style.display = 'inline-block';
                lessButton.style.display = 'none';
            }
        }

        function toggleEmployeeTable() {
            let hiddenRows = document.querySelectorAll('#employeeTable .hidden-row');
            let moreButton = document.getElementById('toggleButton');
            let lessButton = document.getElementById('toggleLessButton');

            hiddenRows.forEach(row => {
                row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
            });

            if (moreButton.style.display !== 'none') {
                moreButton.style.display = 'none';
                lessButton.style.display = 'inline-block';
            } else {
                moreButton.style.display = 'inline-block';
                lessButton.style.display = 'none';
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.hidden-row').forEach(row => {
                row.style.display = 'none';
            });
        });
    </script>
</div>
@endsection
