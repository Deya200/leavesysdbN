@extends('layouts.app')

@section('title', 'User Management')

@section('content')
@php
    // Fetch all users with their employee details
    $users = \App\Models\User::with('employee')->orderBy('created_at', 'desc')->get();
@endphp

<div class="container mt-5">
    <h2 class="text-center mb-4">User Management</h2>

    <div class="row mb-3">
        <div class="col-md-12 text-end">
            <a href="{{ route('users.create') }}" class="btn btn-success btn-lg shadow">Add New User</a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h5>Registered Users</h5>
        </div>
        <div class="card-body">
            @if ($users->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Full Name</th>
                                <th>Gender</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Employee Number</th>
                                <th>Registration Date</th>
                                <th>Account Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ optional($user->employee)->gender ?? 'Not Assigned' }}</td> <!-- ✅ Improved gender retrieval -->
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->EmployeeNumber ?? 'Not Available' }}</td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->is_active ? 'Active' : 'Disabled' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a> <!-- ✅ Edit Button -->

                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                        </form>

                                        <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}">
                                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">No users found.</div>
            @endif
        </div>
    </div>
</div>
@endsection
