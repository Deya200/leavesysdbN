@extends('layouts.app')

@section('title', 'Edit Grade')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Edit Grade</h2>

    <form action="{{ route('grades.update', $grade->GradeID) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="GradeName" class="form-label">Grade Name</label>
            <input type="text" id="GradeName" name="GradeName" class="form-control" value="{{ $grade->GradeName }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="AnnualLeaveDays" class="form-label">Annual Leave Days</label>
            <input type="number" id="AnnualLeaveDays" name="AnnualLeaveDays" class="form-control" value="{{ $grade->AnnualLeaveDays }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Grade</button>
    </form>
</div>
@endsection
