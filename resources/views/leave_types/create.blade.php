@extends('layouts.app')

@section('title', 'Create Leave Type')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Create Leave Type</h2>

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

    <!-- Create Leave Type Form -->
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="text-center mb-0">Leave Type Information</h5>
        </div>
        <div class="card-body bg-light">
            <form method="POST" action="{{ route('leave-types.store') }}" class="needs-validation" novalidate>
                @csrf

                <!-- Leave Type Name -->
                <div class="mb-3">
                    <label for="LeaveTypeName" class="form-label">Leave Type Name</label>
                    <input type="text" id="LeaveTypeName" name="LeaveTypeName" class="form-control @error('LeaveTypeName') is-invalid @enderror" required>
                    @error('LeaveTypeName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Is Paid Leave -->
                <div class="mb-3">
                    <label for="IsPaidLeave" class="form-label">Is Paid Leave</label>
                    <select id="IsPaidLeave" name="IsPaidLeave" class="form-select @error('IsPaidLeave') is-invalid @enderror" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    @error('IsPaidLeave')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Gender Applicable -->
                <div class="mb-3">
                    <label for="GenderApplicable" class="form-label">Gender Applicable</label>
                    <select id="GenderApplicable" name="GenderApplicable" class="form-select @error('GenderApplicable') is-invalid @enderror" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Both">Both</option>
                    </select>
                    @error('GenderApplicable')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Duration Period -->
                <div class="mb-3">
                    <label for="DurationPeriod" class="form-label">Duration Period (Days)</label>
                    <input type="number" id="DurationPeriod" name="DurationPeriod" class="form-control @error('DurationPeriod') is-invalid @enderror" min="1" required>
                    @error('DurationPeriod')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Create Leave Type</button>
            </form>
        </div>
    </div>
</div>
@endsection
