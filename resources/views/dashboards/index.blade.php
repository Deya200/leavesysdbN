@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>



@endsection

@section('content')
<div class=" container-fluid mt-5">
    <div class="row">


        <!-- Main Content -->
        <div class="col-md-9 animate__animated animate__fadeInDown">
            <!-- Overview Cards -->
            <div class="row text-center">

             <!-- Welcome Section -->
             <div class="bg-white rounded-lg shadow p-6 mb-6">
              <img src="{{ asset('welcomedash.svg') }}" alt="Welcome Illustration" class="illustration">

              <div class="flex justify-between items-center">
              <div class="animate__animated animate__fadeInDown text-center mt-5 wtext">
              <p class="font-bold font-size-65" style="color: black;">
                Welcome, {{ auth()->user()->FirstName ?? 'Employee' }}!
            </p>
            

              </div>
              </div>

             </div>

                <!-- Total Employees -->
                <div class="col-md-4 mb-4">
                    <div class="card text-white" style="background-color: #5169C4">
                        <div class="card-body">
                            <h5>Total Employees</h5>
                            <p>{{ $totalEmployees }}</p>
                        </div>
                    </div>
                </div>
                <!-- Male Employees -->
                <div class="col-md-4 mb-4">
                    <div class="card text-white" style="background-color: #5169C4">
                        <div class="card-body">
                            <h5>Male Employees</h5>
                            <p>{{ $maleEmployees }}</p>
                        </div>
                    </div>
                </div>
                <!-- Female Employees -->
                <div class="col-md-4 mb-4">
                    <div class="card text-white" style="background-color: #5169C4">
                        <div class="card-body">
                            <h5>Female Employees</h5>
                            <p>{{ $femaleEmployees }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- More Overview Cards -->
            <div class="row text-center">
                <!-- Total Positions -->
                <div class="col-md-4 mb-4">
                    <div class="card text-white" style="background-color: #5169C4">
                        <div class="card-body">
                            <h5>Total Positions</h5>
                            <p>{{ $totalPositions }}</p>
                        </div>
                    </div>
                </div>
                <!-- Total Grades -->
                <div class="col-md-4 mb-4">
                    <div class="card  text-white" style="background-color: #5169C4">
                        <div class="card-body">
                            <h5>Total Grades</h5>
                            <p>{{ $totalGrades }}</p>
                        </div>
                    </div>
                </div>
                <!-- Total Departments -->
                <div class="col-md-4 mb-4">
                    <div class="card text-white" style="background-color: #5169C4">
                        <div class="card-body">
                            <h5>Total Departments</h5>
                            <p>{{ $departments->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Overview -->
            <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="text-white" id="table-header">
                <h5>Department Overview</h5>
            </div>
            <div class="card-body">
                @if ($departments->isNotEmpty())
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary text-center">
                            <tr>
                                <th id="main-table" style="border: none;">#</th>
                                <th id="main-table" style="border: none;">Department Name</th>
                                <th id="main-table" style="border: none;">Number of Employees</th>
                                <th id="main-table" style="border: none;">Supervisor</th> <!-- New Supervisor Column -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td style="border: none;">{{ $loop->iteration }}</td>
                                    <td style="border: none;">{{ $department->DepartmentName }}</td>
                                    <td style="border: none;">{{ $department->employees_count }}</td>
                                    <td style="border: none;">{{ $department->supervisor ? $department->supervisor->FirstName . ' ' . $department->supervisor->LastName : 'N/A' }}</td> <!-- Supervisor Name -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info text-center">No departments found.</div>
                @endif
            </div>
        </div>
    </div>
</div>

            <!-- Recent Leave Requests -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="text-white" id="table-header">
                            <h5>Recent Leave Requests</h5>
                        </div>
                        <div class="card-body">
                            @if ($recentLeaveRequests->isNotEmpty())
                                <table class="table table-bordered table-striped">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th id="main-table" style="border: none;">#</th>
                                            <th id="main-table" style="border: none;">Employee Name</th>
                                            <th id="main-table" style="border: none;">Leave Type</th>
                                            <th id="main-table" style="border: none;">Status</th>
                                            <th id="main-table" style="border: none;">Requested On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentLeaveRequests as $request)
                                            <tr>
                                                <td style="border: none;">{{ $loop->iteration }}</td>
                                                <td style="border: none;">{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                                                <td style="border: none;">{{ $request->leaveType->LeaveTypeName }}</td>
                                                <td style="border: none;">{{ ucfirst($request->Status) }}</td>
                                                <td style="border: none;">{{ $request->created_at->format('d-m-Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info text-center">No recent leave requests found.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
