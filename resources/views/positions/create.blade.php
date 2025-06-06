@extends('layouts.app')

@section('title', 'Add Position')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Add Position</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('positions.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="PositionName" class="form-label">Position Name</label>
            <input type="text" id="PositionName" name="PositionName" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="GradeID" class="form-label">Grade</label>
            <select id="GradeID" name="GradeID" class="form-select" required>
                @foreach($grades as $grade)
                    <option value="{{ $grade->GradeID }}">{{ $grade->GradeName }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Position</button>
    </form>
</div>
@endsection
