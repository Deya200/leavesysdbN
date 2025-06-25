@extends('layouts.app')

@section('title', 'Add Position')

@section('styles')
<style>
    .position-form-container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
    }

    .card-custom {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 25px 30px;
    }

    .card-header {
        background-color: #2E3A87;
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 20px 30px;
        text-align: center;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-control, .form-select {
        border-radius: 5px;
        font-size: 0.95rem;
    }

    .btn-primary {
        background-color: #2E3A87;
        border: none;
    }

    .btn-primary:hover {
        background-color: #1f2f75;
    }
</style>
@endsection

@section('content')
<div class="position-form-container mt-5">

    <!-- Header -->
    <div class="card-header mb-0">
        <h4 class="fw-bold mb-1">Add New Position</h4>
        <p class="mb-0">Fill in the details</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form -->
    <div class="card-custom">
        <form action="{{ route('positions.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="PositionName" class="form-label">Position Name</label>
                <input type="text" id="PositionName" name="PositionName" class="form-control" required placeholder="Position name">
            </div>

            <div class="form-group mb-4">
                <label for="GradeID" class="form-label">Grade</label>
                <select id="GradeID" name="GradeID" class="form-select" required>
                    <option value="" disabled selected>Select a grade</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->GradeID }}">{{ $grade->GradeName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">Add Position</button>
            </div>
        </form>
    </div>
</div>
@endsection
