@extends('layouts.app')

@section('title', 'Edit Leave Type')

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
        <h4 class="fw-bold text-center mb-4" style="color: black;">Edit Leave Type</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>There were some errors with your input:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('leave_types.update', $leaveType->LeaveTypeID) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="LeaveTypeName" class="form-label">Leave Type Name</label>
                <input type="text" id="LeaveTypeName" name="LeaveTypeName"
                       value="{{ old('LeaveTypeName', $leaveType->LeaveTypeName) }}"
                       class="form-control @error('LeaveTypeName') is-invalid @enderror" required>
                @error('LeaveTypeName')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="IsPaidLeave" class="form-label">Is Paid Leave</label>
                <select id="IsPaidLeave" name="IsPaidLeave"
                        class="form-select @error('IsPaidLeave') is-invalid @enderror" required>
                    <option value="1" {{ $leaveType->IsPaidLeave ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$leaveType->IsPaidLeave ? 'selected' : '' }}>No</option>
                </select>
                @error('IsPaidLeave')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="GenderApplicable" class="form-label">Gender Applicable</label>
                <select id="GenderApplicable" name="GenderApplicable"
                        class="form-select @error('GenderApplicable') is-invalid @enderror" required>
                    <option value="Male" {{ $leaveType->GenderApplicable === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $leaveType->GenderApplicable === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Both" {{ $leaveType->GenderApplicable === 'Both' ? 'selected' : '' }}>Both</option>
                </select>
                @error('GenderApplicable')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary w-100">Update Leave Type</button>
            </div>
        </form>
    </div>
</div>
@endsection
