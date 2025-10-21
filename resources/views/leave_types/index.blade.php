@extends('layouts.app')

@section('title', 'Leave Types')

@section('styles')
<style>
    .leave-types-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    .card-custom {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
    }

    .table {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .table thead {
        background-color: #2E3A87;
        color: #ffffff;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
        padding: 12px;
        border: none;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .btn-edit {
        background-color: #2E3A87;
        color: white;
        font-size: 0.8rem;
    }

    .btn-edit:hover {
        background-color: #1f2f75;
    }

    .btn-sm {
        font-size: 0.8rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')
<div class="leave-types-container">

    <!-- Page Header -->
    <div class="card-custom text-center mb-4" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Leave Types</h4>
        <p class="mb-2">Manage different types of leave available to employees.</p>
    </div>

    <!-- Flash Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Add New Button -->
    <div class="text-end mb-3">
        <a href="{{ route('leave_types.create') }}" class="btn btn-success">Add New Leave Type</a>
    </div>

    <!-- Leave Types Table -->
    <div class="card-custom">
        @if ($leaveTypes->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Paid</th>
                            <th>Gender</th>
                            <th>Deducts from Annual</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveTypes as $leaveType)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $leaveType->LeaveTypeName }}</td>
                                <td>
                                    <span class="badge {{ $leaveType->IsPaidLeave ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $leaveType->IsPaidLeave ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>{{ $leaveType->GenderApplicable }}</td>
                                <td>
                                    <span class="badge {{ $leaveType->deductsFromAnnual() ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $leaveType->deductsFromAnnual() ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('leave_types.edit', $leaveType->LeaveTypeID) }}" class="btn btn-sm btn-edit">Edit</a>
                                        <form action="{{ route('leave_types.destroy', $leaveType->LeaveTypeID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this leave type?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info text-center m-0">
                <h5>No leave types found.</h5>
                <p>Add a new leave type to get started.</p>
            </div>
        @endif
    </div>
</div>
@endsection
