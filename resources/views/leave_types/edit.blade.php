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
                    @if($leaveType->isStatutory())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            This leave type is mandatory under Malawi Employment Act settings and cannot be edited.
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leave_types.update', $leaveType->LeaveTypeID) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="LeaveTypeName" class="form-label fw-bold">Leave Type Name</label>
                            <input type="text" class="form-control" id="LeaveTypeName" name="LeaveTypeName" 
                                   value="{{ old('LeaveTypeName', $leaveType->LeaveTypeName) }}" required {{ $leaveType->isStatutory() ? 'disabled' : '' }}>
                        </div>

                        <div class="mb-4">
                            <label for="IsPaidLeave" class="form-label fw-bold">Is Paid Leave</label>
                            <select id="IsPaidLeave" name="IsPaidLeave" class="form-select" required {{ $leaveType->isStatutory() ? 'disabled' : '' }}>
                                <option value="1" {{ old('IsPaidLeave', $leaveType->IsPaidLeave) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('IsPaidLeave', $leaveType->IsPaidLeave) ? '' : 'selected' }}>No</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="GenderApplicable" class="form-label fw-bold">Gender Applicable</label>
                            <select id="GenderApplicable" name="GenderApplicable" class="form-select" required {{ $leaveType->isStatutory() ? 'disabled' : '' }}>
                                <option value="Male" {{ old('GenderApplicable', $leaveType->GenderApplicable) === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('GenderApplicable', $leaveType->GenderApplicable) === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Both" {{ old('GenderApplicable', $leaveType->GenderApplicable) === 'Both' ? 'selected' : '' }}>Both</option>
                            </select>
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
                                       value="{{ old('MaxLeaveDays', $leaveType->MaxLeaveDays) }}" min="0" {{ $leaveType->isStatutory() ? 'disabled' : '' }}>
                                <div class="form-text text-muted">
                                    Leave blank to allow <strong>Unlimited</strong> days (no maximum limit).
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('leave_types.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            @if(!$leaveType->isStatutory())
                                <button type="submit" class="btn btn-primary" style="background: #1e3c72; border: none;">
                                    Update Leave Type
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
