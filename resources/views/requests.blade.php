@extends('layouts.app')

@section('title', 'Leave Request Form')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Leave Request Form</h2>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Leave Request Form -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Request Leave</h5>
        </div>
        <div class="card-body bg-light">
            <form method="POST" action="{{ route('leave_requests.store') }}">
                @csrf

                <!-- Employee Number (Hidden Field) -->
                <input type="hidden" name="EmployeeNumber" value="{{ auth()->user()->EmployeeNumber }}">

                <!-- Leave Type -->
                <div class="form-group mb-3">
                    <label for="LeaveTypeID" class="form-label">Leave Type</label>
                    <select id="LeaveTypeID" name="LeaveTypeID" class="form-select @error('LeaveTypeID') is-invalid @enderror" required>
                        <option value="" disabled selected>Select Leave Type</option>
                        @foreach ($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->LeaveTypeID }}">{{ $leaveType->LeaveTypeName }}</option>
                        @endforeach
                    </select>
                    @error('LeaveTypeID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Start Date -->
                <div class="form-group mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input id="start_date" type="date" name="StartDate" class="form-control @error('StartDate') is-invalid @enderror" required>
                    @error('StartDate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- End Date -->
                <div class="form-group mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input id="end_date" type="date" name="EndDate" class="form-control @error('EndDate') is-invalid @enderror" required>
                    @error('EndDate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Reason -->
                <div class="form-group mb-3">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required></textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Submit Request</button>
            </form>
        </div>
    </div>
</div>
@endsection