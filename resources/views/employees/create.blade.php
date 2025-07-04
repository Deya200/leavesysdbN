@extends('layouts.app')

@section('title', 'Add Employee')

@section('styles')
<style>
    .add-employee-container {
        max-width: 800px;
        margin: auto;
        padding: 20px;
    }

    .card-custom {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .card-header-custom {
        background-color: #2E3A87;
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-control, .form-select {
        border-radius: 5px;
        font-size: 0.95rem;
    }

    .btn-primary {
        background-color: #2E3A87;
        border: none;
    }

    .btn-primary:hover {
        background-color: #1f2f75;
    }
</style>
@endsection

@section('content')
<div class="add-employee-container mt-4">

    <div class="card-custom">

        <!-- Header -->
        <div class="card-header-custom">
            <h5 class="mb-0">Add New Employee</h5>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">Dashboard</a>
        </div>

        <div class="card-body" style="background-color: #f8f9fa;">

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Message -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf

                <div class="form-group mb-3">
                    <label for="EmployeeNumber" class="form-label">Employee Number</label>
                    <input type="text" id="EmployeeNumber" name="EmployeeNumber" class="form-control @error('EmployeeNumber') is-invalid @enderror" value="{{ old('EmployeeNumber') }}" required>
                    @error('EmployeeNumber')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="FirstName" class="form-label">First Name</label>
                    <input type="text" id="FirstName" name="FirstName" class="form-control @error('FirstName') is-invalid @enderror" value="{{ old('FirstName') }}" required>
                    @error('FirstName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="LastName" class="form-label">Last Name</label>
                    <input type="text" id="LastName" name="LastName" class="form-control @error('LastName') is-invalid @enderror" value="{{ old('LastName') }}" required>
                    @error('LastName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="DateOfBirth" class="form-label">Date of Birth</label>
                    <input type="date" id="DateOfBirth" name="DateOfBirth" class="form-control @error('DateOfBirth') is-invalid @enderror" value="{{ old('DateOfBirth') }}" required>
                    @error('DateOfBirth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="Gender" class="form-label">Gender</label>
                    <select id="Gender" name="Gender" class="form-select @error('Gender') is-invalid @enderror" required>
                        <option value="" disabled {{ old('Gender') ? '' : 'selected' }}>Select Gender</option>
                        <option value="Male" {{ old('Gender') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('Gender') === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('Gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="DepartmentID" class="form-label">Department</label>
                    <select id="DepartmentID" name="DepartmentID" class="form-select @error('DepartmentID') is-invalid @enderror" required>
                        <option value="" disabled {{ old('DepartmentID') ? '' : 'selected' }}>Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->DepartmentID }}" {{ old('DepartmentID') == $department->DepartmentID ? 'selected' : '' }}>
                                {{ $department->DepartmentName }}
                            </option>
                        @endforeach
                    </select>
                    @error('DepartmentID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="GradeID" class="form-label">Grade</label>
                    <select id="GradeID" name="GradeID" class="form-select @error('GradeID') is-invalid @enderror" required>
                        <option value="" disabled {{ old('GradeID') ? '' : 'selected' }}>Select Grade</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->GradeID }}" {{ old('GradeID') == $grade->GradeID ? 'selected' : '' }}>
                                {{ $grade->GradeName }}
                            </option>
                        @endforeach
                    </select>
                    @error('GradeID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="PositionID" class="form-label">Position</label>
                    <select id="PositionID" name="PositionID" class="form-select @error('PositionID') is-invalid @enderror" required>
                        <option value="" disabled {{ old('PositionID') ? '' : 'selected' }}>Select Position</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->PositionID }}" {{ old('PositionID') == $position->PositionID ? 'selected' : '' }}>
                                {{ $position->PositionName }}
                            </option>
                        @endforeach
                    </select>
                    @error('PositionID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="role_id" class="form-label">Role</label>
                    <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                        <option value="2" selected>Employee</option>
                        <option value="1">Admin</option>
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">Add Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
