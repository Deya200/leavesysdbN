@extends('layouts.app')

@section('title', 'Edit Leave Type')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Edit Leave Type</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
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
            <label for="LeaveTypeName" class="form-label">Leave Type Name:</label>
            <input type="text" id="LeaveTypeName" name="LeaveTypeName" value="{{ $leaveType->LeaveTypeName }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="IsPaidLeave" class="form-label">Is Paid Leave:</label>
            <select id="IsPaidLeave" name="IsPaidLeave" class="form-select" required>
                <option value="1" {{ $leaveType->IsPaidLeave ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$leaveType->IsPaidLeave ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="GenderApplicable" class="form-label">Gender Applicable:</label>
            <select id="GenderApplicable" name="GenderApplicable" class="form-select" required>
                <option value="Male" {{ $leaveType->GenderApplicable === 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ $leaveType->GenderApplicable === 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Both" {{ $leaveType->GenderApplicable === 'Both' ? 'selected' : '' }}>