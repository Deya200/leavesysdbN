@extends('layouts.app')

@section('title', 'Departments')

@section('header')

    <!-- Search Form -->
    <form action="{{ route('departments.index') }}" method="GET" class="mb-4" style="width:75%;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Department Name or Supervisor Name" value="{{ $search ?? '' }}" >
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

@endsection

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Departments</h2>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Add Department Button -->
    <div class="mb-4">
    <a href="{{ route('departments.create') }}" class="btn btn-success">Add New Department</a>


    </div>

    <!-- Departments Table -->
    <table class="table table-hover">
        <thead>
            <tr>
                <th id="main-table" style="border: none;">#</th>
                <th id="main-table" style="border: none;">Department Name</th>
                <th id="main-table" style="border: none;">Supervisor</th>
                <th id="main-table" style="border: none;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($departments as $department)
                <tr>
                    <td style="border: none;">{{ $loop->iteration }}</td>
                    <td style="border: none;">{{ $department->DepartmentName }}</td>
                    <td style="border: none;">
                        @if ($department->SupervisorID)
                            {{ $department->supervisor->FirstName ?? 'N/A' }} {{ $department->supervisor->LastName ?? '' }}
                        @else
                            Not Assigned
                        @endif
                    </td>
                    <td style="border: none;">
                        <a href="{{ route('departments.edit', $department->DepartmentID) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('departments.destroy', $department->DepartmentID) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="border: none;">No departments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
