@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .dashboard-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    .card-custom {
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        background-color: white;
        transition: transform 0.2s ease-in-out;
        padding: 12px 18px;
    }

    .card-custom:hover {
        transform: scale(1.01);
    }

    .summary-card h6 {
        font-size: 13px;
        margin-bottom: 4px;
        color: #333;
    }

    .summary-card p {
        font-size: 18px;
        font-weight: bold;
        margin: 0;
        color: #2E3A87;
    }

    #table-header {
        background-color: #2E3A87;
        padding: 12px 20px;
        border-radius: 10px 10px 0 0;
        color: white;
    }

    .table thead {
        background-color: rgb(235, 236, 240);
        color: #2E3A87;
    }

    .table th, .table td {
        text-align: center;
        padding: 12px;
        vertical-align: middle;
        border: none;
    }

    .alert-info {
        margin: 0;
        font-size: 14px;
    }

    .illustration {
        width: 100%;
        max-width: 280px;
        display: block;
        margin: 0 auto;
    }

    .font-size-65 {
        font-size: 1.8rem;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="row">

        <!-- Main Content -->
        <div class="col-12 animate__animated animate__fadeInDown">

            <!-- Welcome Section -->
<div class="card-custom text-center mb-4" style="background-color: #2E3A87; color: white;">
    <h4 class="fw-bold mb-1">Welcome, {{ auth()->user()->FirstName ?? 'Admin' }}!</h4>
    <p class="mb-2">This is your admin control center. Review summaries, manage departments, and track recent leave requests.</p>
</div>


            <!-- Summary Cards -->
            <div class="row text-center g-2 mb-4">
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-users"></i> Employees</h6>
                        <p>{{ $totalEmployees }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-male"></i> Male</h6>
                        <p>{{ $maleEmployees }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-female"></i> Female</h6>
                        <p>{{ $femaleEmployees }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-briefcase"></i> Positions</h6>
                        <p>{{ $totalPositions }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-layer-group"></i> Grades</h6>
                        <p>{{ $totalGrades }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-2 summary-card">
                    <div class="card-custom">
                        <h6><i class="fas fa-building"></i> Departments</h6>
                        <p>{{ $departments->count() }}</p>
                    </div>
                </div>
            </div>

            

            <!-- Department Overview Table -->
            <div class="card-custom mb-4">
                <div id="table-header">
                    <h5>Department Overview</h5>
                </div>
                <div class="table-responsive mt-3">
                    @if ($departments->isNotEmpty())
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Department Name</th>
                                    <th>Employees</th>
                                    <th>Supervisor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departments as $department)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $department->DepartmentName }}</td>
                                        <td>{{ $department->employees_count }}</td>
                                        <td>{{ $department->supervisor ? $department->supervisor->FirstName . ' ' . $department->supervisor->LastName : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info text-center m-0">No departments found.</div>
                    @endif
                </div>
            </div>

            <!-- Recent Leave Requests -->
            <div class="card-custom">
                <div id="table-header">
                    <h5>Recent Leave Requests</h5>
                </div>
                <div class="table-responsive mt-3">
                    @if ($recentLeaveRequests->isNotEmpty())
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Status</th>
                                    <th>Requested On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentLeaveRequests as $request)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                                        <td>{{ $request->leaveType->LeaveTypeName }}</td>
                                       <td>{{ $request->RequestStatus }}</td>
                                       <td>{{ $request->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info text-center m-0">No recent leave requests found.</div>
                    @endif
                </div>
            </div>

        </div> <!-- end col-12 -->
    </div> <!-- end row -->
</div> <!-- end dashboard-container -->
@endsection
