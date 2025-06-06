@extends('layouts.app')

@section('title', 'Manage Supervisors')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Supervisors</h2>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Add Supervisor Form -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add New Supervisor</h5>
        </div>
        <div class="card-body bg-light">
            <form method="POST" action="{{ route('supervisors.store') }}">
                @csrf

                <!-- First Name -->
                <div class="form-group mb-3">
                    <label for="FirstName" class="form-label">First Name</label>
                    <input type="text" id="FirstName" name="FirstName" class="form-control @error('FirstName') is-invalid @enderror" placeholder="Enter first name" required>
                    @error('FirstName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Last Name -->
                <div class="form-group mb-3">
                    <label for="LastName" class="form-label">Last Name</label>
                    <input type="text" id="LastName" name="LastName" class="form-control @error('LastName') is-invalid @enderror" placeholder="Enter last name" required>
                    @error('LastName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Add Supervisor</button>
            </form>
        </div>
    </div>

    <!-- Supervisor List -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Supervisor List</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @forelse($supervisors as $supervisor)
                    <li class="list-group-item">
                        {{ $supervisor->FirstName }} {{ $supervisor->LastName }}
                    </li>
                @empty
                    <li class="list-group-item text-center">No supervisors found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
