@extends('layouts.app')

@section('title', 'Edit Leave Type')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-white" style="background: #1e3c72;">
                    <h5 class="mb-0">Edit Leave Type: {{ $leaveType->LeaveTypeName }}</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leave-types.update', $leaveType->LeaveTypeID) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="LeaveTypeName" class="form-label fw-bold">Leave Type Name</label>
                            <input type="text" class="form-control" id="LeaveTypeName" name="LeaveTypeName" 
                                   value="{{ old('LeaveTypeName', $leaveType->LeaveTypeName) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="MaxLeaveDays" class="form-label fw-bold">Max Days Allowed (Per Year)</label>
                            @if($leaveType->deductsFromAnnual())
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Annual Leave</strong> limits are determined by Employee Grade and Carry-over policy. 
                                    This setting is ignored for Annual Leave.
                                </div>
                                <input type="number" class="form-control" id="MaxLeaveDays" name="MaxLeaveDays" 
                                       value="{{ old('MaxLeaveDays', $leaveType->MaxLeaveDays) }}" disabled>
                                <input type="hidden" name="MaxLeaveDays" value="{{ $leaveType->MaxLeaveDays }}">
                            @else
                                <input type="number" class="form-control" id="MaxLeaveDays" name="MaxLeaveDays" 
                                       value="{{ old('MaxLeaveDays', $leaveType->MaxLeaveDays) }}" min="0">
                                <div class="form-text text-muted">
                                    Leave blank to allow <strong>Unlimited</strong> days (no maximum limit).
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('leave-types.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" style="background: #1e3c72; border: none;">
                                Update Leave Type
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
