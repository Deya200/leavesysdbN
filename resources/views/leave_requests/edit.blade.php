@extends('layouts.app')

@section('title', 'Edit Leave Request')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Leave Request
                    </h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('leave_requests.update', $leaveRequest) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="LeaveTypeID" class="form-label fw-bold">Leave Type</label>
                            <select id="LeaveTypeID" name="LeaveTypeID"
                                    class="form-select @error('LeaveTypeID') is-invalid @enderror" required>
                                @foreach($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->LeaveTypeID }}"
                                        {{ $leaveRequest->LeaveTypeID == $leaveType->LeaveTypeID ? 'selected' : '' }}>
                                        {{ $leaveType->LeaveTypeName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('LeaveTypeID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="StartDate" class="form-label fw-bold">Start Date</label>
                                <input type="date" id="StartDate" name="StartDate"
                                       class="form-control @error('StartDate') is-invalid @enderror"
                                       value="{{ old('StartDate', $leaveRequest->StartDate->format('Y-m-d')) }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       required>
                                @error('StartDate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="EndDate" class="form-label fw-bold">End Date</label>
                                <input type="date" id="EndDate" name="EndDate"
                                       class="form-control @error('EndDate') is-invalid @enderror"
                                       value="{{ old('EndDate', $leaveRequest->EndDate->format('Y-m-d')) }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       required>
                                @error('EndDate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="Reason" class="form-label fw-bold">Reason</label>
                            <textarea id="Reason" name="Reason"
                                      class="form-control @error('Reason') is-invalid @enderror"
                                      rows="4" required>{{ old('Reason', $leaveRequest->Reason) }}</textarea>
                            @error('Reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('leave_requests.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn text-white"
                                    style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                                <i class="fas fa-save me-2"></i>Update Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Date validation script
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('StartDate');
        const endDateInput = document.getElementById('EndDate');

        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
        });
    });
</script>
@endsection
