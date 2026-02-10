@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Add New User</h2>

    <div class="card shadow">
        <div class="card-body">
            <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                New users will receive an email with a link to set their password. No manual password entry is required.
            </div>

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Enter full name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="email@example.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Employee Number -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Employee Number (Optional)</label>
                    <input type="text" name="EmployeeNumber" class="form-control @error('EmployeeNumber') is-invalid @enderror" value="{{ old('EmployeeNumber') }}" placeholder="EMP001">
                    <small class="text-muted">Associate this user account with an existing employee record.</small>
                    @error('EmployeeNumber')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="mb-3">
                    <label class="form-label fw-bold">System Role</label>
                    <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                        <option value="" disabled selected>Select a role</option>
                        @foreach(\App\Models\Role::all() as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="text-end mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-user-plus me-2"></i>Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
