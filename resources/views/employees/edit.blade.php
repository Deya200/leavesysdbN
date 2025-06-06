@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border: 2px solid #007bff; box-shadow: 0px 4px 8px rgba(0,0,0,0.2);">
                <div class="card-header text-white" style="background-color: #007bff;">
                    <h5 class="mb-0">Edit Employee</h5>
                </div>
                <div class="card-body" style="background-color: #f8f9fa;">
                    
                    <form method="POST" action="{{ route('employees.update', $employee->EmployeeNumber) }}">
                        @csrf
                        @method('PUT')

                        <!-- Employee Number (Read-Only) -->
                        <div class="mb-3">
                            <label for="EmployeeNumber" class="form-label">Employee Number</label>
                            <input type="text" name="EmployeeNumber" id="EmployeeNumber" class="form-control" value="{{ $employee->EmployeeNumber }}" readonly>
                        </div>

                        <!-- First Name -->
                        <div class="mb-3">
                            <label for="FirstName" class="form-label">First Name</label>
                            <input type="text" name="FirstName" id="FirstName" class="form-control @error('FirstName') is-invalid @enderror" value="{{ old('FirstName', $employee->FirstName) }}" required>
                            @error('FirstName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="LastName" class="form-label">Last Name</label>
                            <input type="text" name="LastName" id="LastName" class="form-control @error('LastName') is-invalid @enderror" value="{{ old('LastName', $employee->LastName) }}" required>
                            @error('LastName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="mb-3">
                            <label for="DateOfBirth" class="form-label">Date of Birth</label>
                            <input type="date" name="DateOfBirth" id="DateOfBirth" class="form-control @error('DateOfBirth') is-invalid @enderror" value="{{ old('DateOfBirth', $employee->DateOfBirth) }}" required>
                            @error('DateOfBirth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="mb-3">
                            <label for="Gender" class="form-label">Gender</label>
                            <select id="Gender" name="Gender" class="form-select @error('Gender') is-invalid @enderror" required>
                                <option value="Male" {{ old('Gender', $employee->Gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('Gender', $employee->Gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('Gender', $employee->Gender) === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('Gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="mb-3">
                            <label for="DepartmentID" class="form-label">Department</label>
                            <select id="DepartmentID" name="DepartmentID" class="form-select @error('DepartmentID') is-invalid @enderror" required>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->DepartmentID }}" {{ old('DepartmentID', $employee->DepartmentID) == $department->DepartmentID ? 'selected' : '' }}>
                                        {{ $department->DepartmentName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('DepartmentID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Grade -->
                        <div class="mb-3">
                            <label for="GradeID" class="form-label">Grade</label>
                            <select id="GradeID" name="GradeID" class="form-select @error('GradeID') is-invalid @enderror" required>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->GradeID }}" {{ old('GradeID', $employee->GradeID) == $grade->GradeID ? 'selected' : '' }}>
                                        {{ $grade->GradeName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('GradeID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div class="mb-3">
                            <label for="PositionID" class="form-label">Position</label>
                            <select id="PositionID" name="PositionID" class="form-select @error('PositionID') is-invalid @enderror" required>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->PositionID }}" {{ old('PositionID', $employee->PositionID) == $position->PositionID ? 'selected' : '' }}>
                                        {{ $position->PositionName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('PositionID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role</label>
                            <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                <option value="2" {{ old('role_id', $employee->role_id) == 2 ? 'selected' : '' }}>Employee</option>
                                <option value="1" {{ old('role_id', $employee->role_id) == 1 ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn text-white w-100" style="background-color: #007bff;">Update Employee</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
