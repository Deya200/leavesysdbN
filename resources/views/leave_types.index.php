@extends('layouts.app')

@section('title', 'Leave Types')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Leave Types</h2>
        <a href="{{ route('leave_types.create') }}" class="btn btn-success">Add New Leave Type</a>
    </div>

    <!-- Success and Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Leave Types Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Leave Types List</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Leave Type ID</th>
                        <th>Name</th>
                        <th>Is Paid Leave</th>
                        <th>Gender Applicable</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaveTypes as $leaveType)
                        <tr>
                            <td>{{ $leaveType->LeaveTypeID }}</td>
                            <td>{{ $leaveType->LeaveTypeName }}</td>
                            <td>{{ $leaveType->IsPaidLeave ? 'Yes' : 'No' }}</td>
                            <td>{{ $leaveType->GenderApplicable }}</td>
                            <td>
                                <a href="{{ route('leave_types.edit', $leaveType->LeaveTypeID) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('leave-types.destroy', $leaveType->LeaveTypeID) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this leave type?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No leave types found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
