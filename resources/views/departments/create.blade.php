@extends('layouts.dashboard')

@section('title', 'Add New Department')

@section('styles')
<style>
    .department-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    .card-custom {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 25px;
    }

    .card-header-custom {
        background-color: #2E3A87;
        color: white;
        font-size: 1.25rem;
        font-weight: 600;
        padding: 16px 25px;
        border-radius: 10px 10px 0 0;
    }

    .form-label {
        font-weight: 600;
    }

    .form-control, .form-select {
        font-size: 0.95rem;
        border-radius: 5px;
    }

    .btn-primary {
        background-color: #2E3A87;
        border: none;
    }

    .btn-primary:hover {
        background-color: #1f2f75;
    }

    .btn-block {
        width: 100%;
    }

    .text-white {
        color: #ffffff !important;
    }
</style>
@endsection

@section('content')
<div class="department-container">

    <!-- Welcome Card -->
    <div class="card-custom text-center mb-4" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Add New Department</h4>
        <p class="mb-2">Create and assign departments with optional supervisors.</p>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-md-8">
            <div class="card-custom mb-4">
                <div class="card-header-custom">Create Department</div>
                <div class="card-body">

                    <form method="POST" action="{{ route('departments.store') }}">
                        @csrf

                        <!-- Department Name -->
                        <div class="form-group mb-3">
                            <label for="DepartmentName" class="form-label" style="color: black;">Department Name</label>
                            <input type="text" name="DepartmentName" id="DepartmentName"
                                   class="form-control @error('DepartmentName') is-invalid @enderror"
                                   value="{{ old('DepartmentName') }}" required placeholder="e.g., IT Department">
                            @error('DepartmentName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Supervisor Dropdown -->
                        <div class="form-group mb-4">
                            <label for="SupervisorID" class="form-label" style="color: black;">Supervisor</label>
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
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">Create Department</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- Sidebar Section -->
        <div class="col-md-4">
            <div class="card-custom">
                <div class="card-header-custom" style="background-color: #6c757d;">Quick Links</div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('departments.index') }}" class="btn btn-outline-primary btn-block">View All Departments</a>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-block">Manage Employees</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
