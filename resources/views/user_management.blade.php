@extends('layouts.app')

@section('title', 'User Management')

@section('content')
@php
    // Fetch all users with their employee details
    $users = \App\Models\User::with('employee')->orderBy('created_at', 'desc')->get();
@endphp

@section('styles')
<style>
    .manage-container {
        /* max-width handled globally */
    }

    .card-custom {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 20px;
        transition: box-shadow 0.3s ease;
    }

    .table {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        overflow: hidden;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead tr {
        background: linear-gradient(135deg, #6a1b9a 0%, #4a148c 100%);
        color: white;
    }

    .table th, .table td {
        padding: 12px;
        vertical-align: middle;
        text-align: center;
        border: none;
    }

    .hover-up:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }
</style>
@endsection

@section('content')
@php
    $users = \App\Models\User::with('employee')->orderBy('created_at', 'desc')->get();
@endphp

<div class="manage-container py-4">
    <!-- Header Card -->
    <div class="card-custom mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-primary mb-0">User Management</h4>
            <a href="{{ route('users.create') }}" class="btn btn-primary" style="background-color: #6a1b9a; border-color: #6a1b9a;">
                <i class="fas fa-plus me-1"></i> Add New User
            </a>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="card-custom">
        @if ($users->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Employee No.</th>
                            <th>Joined</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="hover-up">
                                <td class="fw-bold">{{ $user->name }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ ucfirst($user->role->name ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td><code class="text-primary">{{ $user->EmployeeNumber ?? 'N/A' }}</code></td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
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
            <div class="alert alert-info text-center m-0">No users found.</div>
        @endif
    </div>
</div>
@endsection
