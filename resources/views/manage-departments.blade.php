@extends('layouts.app')

@section('title', 'Manage Departments')

@section('content')
<div class="container mt-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Departments</h2>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Department List -->
    <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Department List</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Department ID</th>
                        <th>Department Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td>{{ $dept->DepartmentID }}</td>
                            <td>{{ $dept->DepartmentName }}</td>
                            <td>
                                <!-- Edit Button -->
                                <a href="{{ route('departments.edit', $dept->DepartmentID) }}" class="btn btn-sm btn-warning">Edit</a>

                                <!-- Delete Button -->
                                <form action="{{ route('departments.destroy', $dept->DepartmentID) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No departments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Department Form -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add a New Department</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('departments.store') }}">
                @csrf

                <!-- Department Name -->
                <div class="form-group mb-3">
                    <label for="DepartmentName" class="form-label">Department Name:</label>
                    <input type="text" id="DepartmentName" name="DepartmentName" class="form-control" placeholder="Enter department name" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success w-100">Add Department</button>
            </form>
        </div>
    </div>
</div>
@endsection
