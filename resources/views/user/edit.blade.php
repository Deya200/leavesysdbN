@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Edit User</h2>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <!-- Role Selection -->
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        @foreach(\App\Models\Role::all() as $role)
                            <option value="{{ $role->name }}" {{ $user->role === $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Employee Number -->
                <div class="mb-3">
                    <label class="form-label">Employee Number</label>
                    <input type="text" name="EmployeeNumber" class="form-control" value="{{ $user->EmployeeNumber }}" readonly>
                </div>

                <!-- Gender (Retrieved from Employees Table) -->
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <input type="text" class="form-control" value="{{ optional($user->employee)->gender ?? 'Not Assigned' }}" readonly>
                </div>

                <!-- Account Status -->
                <div class="mb-3">
                    <label class="form-label">Account Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="text-end">
                    <button type="submit" class="btn btn-success">Update User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
