@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header text-white d-flex justify-content-between align-items-center bg-primary">
                    <h5 class="mb-0">Edit Employee</h5>
                    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-light">Back to Employees</a>
                </div>
                <div class="card-body bg-light">

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Edit Employee Form -->
                    <form method="POST" action="{{ route('employees.update', $employee->EmployeeNumber) }}">
                        @csrf
                        @method('PUT')

                        <!-- Employee Number -->
                        <div class="form-group mb-3">
                            <label for="EmployeeNumber" class="form-label">Employee Number</label>
                            <input type="text" id="EmployeeNumber" name="EmployeeNumber" class="form-control" value="{{ $employee->EmployeeNumber }}" readonly>
                        </div>

                        <!-- First Name -->
                        <div class="form-group mb-3">
                            <label for="FirstName" class="form-label">First Name</label>
                            <input type="text" id="FirstName" name="FirstName" class="form-control @error('FirstName') is-invalid @enderror" value="{{ $employee->FirstName }}" required>
                            @error('FirstName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="form-group mb-3">
                            <label for="LastName" class="form-label">Last Name</label>
                            <input type="text" id="LastName" name="LastName" class="form-control @error('LastName') is-invalid @enderror" value="{{ $employee->LastName }}" required>
                            @error('LastName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="form-group mb-3">
                            <label for="Gender" class="form-label">Gender</label>
                            <select id="Gender" name="Gender" class="form-select @error('Gender') is-invalid @enderror" required>
                                <option value="Male" {{ $employee->Gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $employee->Gender == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('Gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="form-group mb-3">
                            <label for="DateOfBirth" class="form-label">Date of Birth</label>
                            <input type="date" id="DateOfBirth" name="DateOfBirth" class="form-control @error('DateOfBirth') is-invalid @enderror" value="{{ $employee->DateOfBirth }}" required>
                            @error('DateOfBirth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="form-group mb-3">
                            <label for="DepartmentID" class="form-label">Department</label>
                            <select id="DepartmentID" name="DepartmentID" class="form-select @error('DepartmentID') is-invalid @enderror" required>
                                @foreach($departments as $department)
                                    <option value="{{ $department->DepartmentID }}" {{ $employee->DepartmentID == $department->DepartmentID ? 'selected' : '' }}>
                                        {{ $department->DepartmentName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('DepartmentID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Grade -->
                        <div class="form-group mb-3">
                            <label for="GradeID" class="form-label">Grade</label>
                            <select id="GradeID" name="GradeID" class="form-select @error('GradeID') is-invalid @enderror" required>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->GradeID }}" {{ $employee->GradeID == $grade->GradeID ? 'selected' : '' }}>
                                        {{ $grade->GradeName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('GradeID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div class="form-group mb-3">
                            <label for="PositionID" class="form-label">Position</label>
                            <select id="PositionID" name="PositionID" class="form-select @error('PositionID') is-invalid @enderror" required>
                                @foreach($positions as $position)
                                    <option value="{{ $position->PositionID }}" {{ $employee->PositionID == $position->PositionID ? 'selected' : '' }}>
                                        {{ $position->PositionName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('PositionID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">Update Employee</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
