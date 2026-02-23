@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Edit User</h2>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user->EmployeeNumber) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Employee Number (Read-only) -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Employee Number</label>
                        <input type="text" class="form-control" value="{{ $user->EmployeeNumber }}" readonly>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- First Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">First Name *</label>
                        <input type="text" name="FirstName" class="form-control @error('FirstName') is-invalid @enderror" value="{{ old('FirstName', $user->FirstName) }}" required>
                        @error('FirstName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Last Name *</label>
                        <input type="text" name="LastName" class="form-control @error('LastName') is-invalid @enderror" value="{{ old('LastName', $user->LastName) }}" required>
                        @error('LastName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Gender -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Gender *</label>
                        <select name="Gender" class="form-select @error('Gender') is-invalid @enderror" required>
                            <option value="Male" {{ old('Gender', $user->Gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('Gender', $user->Gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('Gender', $user->Gender) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('Gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Date of Birth *</label>
                        <input type="date" name="DateOfBirth" class="form-control @error('DateOfBirth') is-invalid @enderror" value="{{ old('DateOfBirth', $user->DateOfBirth) }}" required>
                        @error('DateOfBirth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Department -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Department *</label>
                        <select name="DepartmentID" class="form-select @error('DepartmentID') is-invalid @enderror" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->DepartmentID }}" {{ old('DepartmentID', $user->DepartmentID) == $department->DepartmentID ? 'selected' : '' }}>
                                    {{ $department->DepartmentName }}
                                </option>
                            @endforeach
                        </select>
                        @error('DepartmentID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Grade -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Grade *</label>
                        <select name="GradeID" class="form-select @error('GradeID') is-invalid @enderror" required>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->GradeID }}" {{ old('GradeID', $user->GradeID) == $grade->GradeID ? 'selected' : '' }}>
                                    {{ $grade->GradeName }}
                                </option>
                            @endforeach
                        </select>
                        @error('GradeID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Position -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Position *</label>
                        <select name="PositionID" class="form-select @error('PositionID') is-invalid @enderror" required>
                            @foreach($positions as $position)
                                <option value="{{ $position->PositionID }}" {{ old('PositionID', $user->PositionID) == $position->PositionID ? 'selected' : '' }}>
                                    {{ $position->PositionName }}
                                </option>
                            @endforeach
                        </select>
                        @error('PositionID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">System Role *</label>
                        <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-end mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i>Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
