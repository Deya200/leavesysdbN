@extends('layouts.app')

@section('title', 'Edit Grade')

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

    .form-control {
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
        <h4 class="fw-bold text-center mb-4" style="color: black;">Edit Grade</h4>

        <form action="{{ route('grades.update', $grade->GradeID) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="GradeName" class="form-label">Grade Name</label>
                <input type="text" id="GradeName" name="GradeName"
                       placeholder="e.g. Senior Staff or Grade B"
                       value="{{ old('GradeName', $grade->GradeName) }}"
                       class="form-control @error('GradeName') is-invalid @enderror" required>
                @error('GradeName')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="AnnualLeaveDays" class="form-label">Annual Leave Days</label>
                <input type="number" id="AnnualLeaveDays" name="AnnualLeaveDays"
                       placeholder="Enter number of days, e.g. 18"
                       min="0"
                       value="{{ old('AnnualLeaveDays', $grade->AnnualLeaveDays) }}"
                       class="form-control @error('AnnualLeaveDays') is-invalid @enderror" required>
                @error('AnnualLeaveDays')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100">Update Grade</button>
            </div>
        </form>
    </div>
</div>
@endsection
