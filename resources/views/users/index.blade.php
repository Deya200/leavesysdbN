@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <h1><i class="fas fa-users me-2"></i>User Management</h1>
        <p>Manage user roles and status</p>
    </div>

    @if($users->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->EmployeeNumber }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge 
                                @if($user->role_id == 1) bg-primary
                                @elseif($user->role_id == 2) bg-info
                                @else bg-secondary @endif">
                                {{ $user->role->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $user->active ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $user->employee->department->DepartmentName ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Role Management -->
                                <form action="{{ route('users.updateRole', $user->EmployeeNumber) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="role_id" onchange="this.form.submit()" class="form-select form-select-sm">
                                        <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Admin</option>
                                        <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>Supervisor</option>
                                        <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>Employee</option>
                                    </select>
                                </form>

                                <!-- Status Toggle -->
                                <form action="{{ route('users.toggleStatus', $user->EmployeeNumber) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm {{ $user->active ? 'btn-danger' : 'btn-success' }}">
                                        <i class="fas {{ $user->active ? 'fa-user-slash' : 'fa-user-check' }} me-1"></i>
                                        {{ $user->active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>No users found.
        </div>
    @endif
</div>
@endsection
