@extends('layouts.app')

@section('title', 'Manage Positions')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Manage Positions</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('positions.create') }}" class="btn btn-success">Add New Position</a>
    </div>

    @if ($positions->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Position Name</th>
                        <th>Grade</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($positions as $position)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $position->PositionName }}</td>
                            <td>{{ $position->grade->GradeName ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('positions.edit', $position->PositionID) }}" class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('positions.destroy', $position->PositionID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this position?');">
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
        <div class="alert alert-info text-center">No positions found. Click "Add New Position" above to add one.</div>
    @endif
</div>
@endsection
