@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="logout-panel text-end mt-3">
    

</div>

        <!-- Left Sidebar -->
        <div class="col-md-2">
            <div>
                <div class="card-header bg-dark text-white">
                
                </div>
                <ul class="list-group list-group-flush">
                    <a class="nav-link btn btn-outline-primary text-black"  href="{{ route('employees.index') }}">Employees</a>
                    <a class="nav-link btn btn-outline-primary text-black"  href="{{ route('departments.index') }}">Departments</a>
                    <a class="nav-link btn btn-outline-primary text-black"  href="{{ route('leave-types.index') }}">Leave Types</a>
                    <a class="nav-link btn btn-outline-primary text-black" href="{{ route('admin.leave_requests') }}">Admin Verification</a>

                <a class="nav-link btn btn-outline-primary text-black"  href="{{ route('leave.requests.index') }}">Recent Leave Requests</a>
                </ul>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="col-md-9">
            <div class="row">
                <!-- Total Employees -->
                <div class="col-md-4">
                    <div class="card bg-primary text-white text-center shadow mb-4">
                        <div class="card-body">
                            <h5>Total Employees</h5>
                            <p class="h3">{{ $totalEmployees }}</p>
                        </div>
                    </div>
                </div>

                <!-- Employees by Gender -->
                <div class="col-md-8">
                    <div class="card bg-info text-white shadow mb-4">
                        <div class="card-body">
                            <h5 class="text-center">Employees by Gender</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1">
                                    <strong>Male Employees:</strong> {{ $maleEmployees }}
                                </li>
                                <li class="mb-1">
                                    <strong>Female Employees:</strong> {{ $femaleEmployees }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Employees by Department -->
                <div class="col-md-8">
                    <div class="card bg-info text-white shadow mb-4">
                        <div class="card-body">
                            <h5 class="text-center">Employees by Department</h5>
                            <ul class="list-unstyled mb-0">
                                @foreach($departments as $department)
                                    <li class="mb-1">
                                        <strong>{{ $department->DepartmentName ?? 'Unknown Department' }}:</strong> 
                                        {{ $department->employees_count }} employees
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Total Leave Requests -->
                <div class="col-md-12">
                    <div class="card bg-success text-white text-center shadow mb-4">
                        <div class="card-body">
                            <h5>Total Leave Requests</h5>
                            <p class="h3">{{ $totalLeaveRequests }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
