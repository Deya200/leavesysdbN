@extends('layouts.dashboard')

@section('title', 'Add New Department')

@section('content')
<div class="container-fluid">
 

    <div class="row">
        <!-- Form Section -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    Create Department
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('departments.store') }}">
                        @csrf

                        <!-- Department Name -->
                        <div class="form-group mb-3">
                            <label for="DepartmentName" class="form-label">Department Name</label>
                            <input type="text" name="DepartmentName" id="DepartmentName"
                                   class="form-control @error('DepartmentName') is-invalid @enderror"
                                   value="{{ old('DepartmentName') }}" required placeholder="Enter department name">
                            @error('DepartmentName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Supervisor Dropdown -->
                        <div class="form-group mb-3">
                            <label for="SupervisorID" class="form-label">Supervisor</label>
                            <select name="SupervisorID" id="SupervisorID"
                                    class="form-select @error('SupervisorID') is-invalid @enderror">
                                <option value="" selected>Select Supervisor (Optional)</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->EmployeeNumber }}"
                                            {{ old('SupervisorID') == $employee->EmployeeNumber ? 'selected' : '' }}>
                                        {{ $employee->FirstName }} {{ $employee->LastName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('SupervisorID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success">Create Department</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Section -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    Quick Links
                </div>
                <div class="card-body">
                    <a href="{{ route('departments.index') }}" class="btn btn-info btn-block mb-2">View All Departments</a>
                    <a href="{{ route('employees.index') }}" class="btn btn-warning btn-block mb-2">Manage Employees</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
