@extends('layouts.app')

@section('title', 'Manage Positions')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Positions</h2>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Add Position Form -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add New Position</h5>
        </div>
        <div class="card-body bg-light">
            <form method="POST" action="{{ route('positions.store') }}">
                @csrf

                <!-- Position Name -->
                <div class="form-group mb-3">
                    <label for="PositionName" class="form-label">Position Name</label>
                    <input type="text" id="PositionName" name="PositionName" class="form-control @error('PositionName') is-invalid @enderror" placeholder="Enter position name" required>
                    @error('PositionName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Grade -->
                <div class="form-group mb-3">
                    <label for="GradeID" class="form-label">Grade</label>
                    <select id="GradeID" name="GradeID" class="form-select @error('GradeID') is-invalid @enderror" required>
                        <option value="" disabled selected>Select Grade</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->GradeID }}">{{ $grade->GradeName }}</option>
                        @endforeach
                    </select>
                    @error('GradeID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Add Position</button>
            </form>
        </div>
    </div>

    <!-- Position List -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Existing Positions</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @forelse($positions as $position)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $position->PositionName }} <span class="badge bg-secondary">Grade: {{ $position->grade->GradeName ?? 'N/A' }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-center">No positions found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
