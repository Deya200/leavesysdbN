@extends('layouts.app')

@section('title', 'Add New Grade')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Add New Grade</h2>

    <form action="{{ route('grades.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="GradeName" class="form-label">Grade Name</label>
            <input type="text" id="GradeName" name="GradeName" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="AnnualLeaveDays" class="form-label">Annual Leave Days</label>
            <input type="number" id="AnnualLeaveDays" name="AnnualLeaveDays" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Grade</button>
    </form>
</div>
@endsection
