@extends('layouts.app')

@section('title', 'Create Leave Type')

@section('styles')
<style>
    .leave-type-container {
        max-width: 700px;
        margin: auto;
        padding: 20px;
    }

    .card-custom {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 25px;
    }

    .header-card {
        background-color: #2E3A87;
        color: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #000;
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
<div class="leave-type-container">

    <!-- Header -->
    <div class="header-card">
        <h4 class="fw-bold mb-1">Create New Leave Type</h4>
        <p class="mb-0">Define new leave policies for employees</p>
    </div>

    <!-- Error Message -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>There were some issues with your submission:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form -->
    <div class="card-custom">
        <form method="POST" action="{{ route('leave_types.store') }}" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
                <label for="LeaveTypeName" class="form-label">Leave Type Name</label>
                <input type="text" id="LeaveTypeName" name="LeaveTypeName"
                       class="form-control @error('LeaveTypeName') is-invalid @enderror"
                       value="{{ old('LeaveTypeName') }}" required>
                @error('LeaveTypeName')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="IsPaidLeave" class="form-label">Is Paid Leave</label>
                <select id="IsPaidLeave" name="IsPaidLeave"
                        class="form-select @error('IsPaidLeave') is-invalid @enderror" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
                @error('IsPaidLeave')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="GenderApplicable" class="form-label">Gender Applicable</label>
                <select id="GenderApplicable" name="GenderApplicable"
                        class="form-select @error('GenderApplicable') is-invalid @enderror" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Both">Both</option>
                </select>
                @error('GenderApplicable')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="DurationPeriod" class="form-label">Duration Period (Days)</label>
                <input type="number" id="DurationPeriod" name="DurationPeriod"
                       class="form-control @error('DurationPeriod') is-invalid @enderror"
                       value="{{ old('DurationPeriod') }}" min="1" required>
                @error('DurationPeriod')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100">Create Leave Type</button>
            </div>
        </form>
    </div>
</div>
@endsection
