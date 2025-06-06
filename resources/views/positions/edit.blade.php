@extends('layouts.app')

@section('title', 'Edit Position')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Edit Position</h2>

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
        <div class="form-group mb-3">
            <label for="PositionName" class="form-label">Position Name</label>
            <input type="text" id="PositionName" name="PositionName" class="form-control" value="{{ $position->PositionName }}" required>
        </div>

        <!-- Grade Dropdown -->
        <div class="form-group mb-3">
            <label for="GradeID" class="form-label">Grade</label>
            <select id="GradeID" name="GradeID" class="form-select" required>
                @foreach($grades as $grade)
                    <option value="{{ $grade->GradeID }}" {{ $grade->GradeID == $position->GradeID ? 'selected' : '' }}>
                        {{ $grade->GradeName }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Position</button>
    </form>
</div>
@endsection
