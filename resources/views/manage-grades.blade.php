@extends('layouts.app')

@section('title', 'Manage Grades')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Grades</h2>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Add Grade Form -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add New Grade</h5>
        </div>
        <div class="card-body bg-light">
            <form method="POST" action="{{ route('grades.store') }}">
                @csrf

                <!-- Grade Name -->
                <div class="form-group mb-3">
                    <label for="GradeName" class="form-label">Grade Name</label>
                    <input type="text" id="GradeName" name="GradeName" class="form-control @error('GradeName') is-invalid @enderror" placeholder="Enter grade name" required>
                    @error('GradeName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Annual Leave Days -->
                <div class="form-group mb-3">
                    <label for="AnnualLeaveDays" class="form-label">Annual Leave Days</label>
                    <input type="number" id="AnnualLeaveDays" name="AnnualLeaveDays" class="form-control @error('AnnualLeaveDays') is-invalid @enderror" placeholder="Enter annual leave days" required>
                    @error('AnnualLeaveDays')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Add Grade</button>
            </form>
        </div>
    </div>

    <!-- Existing Grades -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Existing Grades</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @forelse($grades as $grade)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $grade->GradeName }} - {{ $grade->AnnualLeaveDays }} days
                    </li>
                @empty
                    <li class="list-group-item text-center">No grades found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
