@extends('layouts.app')

@section('title', 'Edit Position')

@section('styles')
<style>
    .dashboard-container {
        max-width: 700px;
        margin: auto;
        padding: 20px;
    }

    .card-custom {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
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
<div class="dashboard-container">
    <div class="card-custom">
        <h4 class="fw-bold text-center mb-4" style="color: black;">Edit Position</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('positions.update', $position->PositionID) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Position Name -->
            <div class="mb-3">
                <label for="PositionName" class="form-label">Position Name</label>
                <input type="text" id="PositionName" name="PositionName"
                       class="form-control @error('PositionName') is-invalid @enderror"
                       value="{{ old('PositionName', $position->PositionName) }}" required>
                @error('PositionName')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Grade Dropdown -->
            <div class="mb-4">
                <label for="GradeID" class="form-label">Grade</label>
                <select id="GradeID" name="GradeID"
                        class="form-select @error('GradeID') is-invalid @enderror" required>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->GradeID }}"
                            {{ $grade->GradeID == old('GradeID', $position->GradeID) ? 'selected' : '' }}>
                            {{ $grade->GradeName }}
                        </option>
                    @endforeach
                </select>
                @error('GradeID')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100">Update Position</button>
            </div>
        </form>
    </div>
</div>
@endsection
