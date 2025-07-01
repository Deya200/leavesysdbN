@extends('layouts.app')

@section('title', 'Create Leave Request')

@section('styles')

 <style>
    .steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }
    .step {
        text-align: center;
        position: relative;
        flex: 1;
    }
    .step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 5px;
    }
    .step.active .step-circle {
        background: #3a7bd5;
        color: white;
    }
    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 15px;
        left: 60%;
        width: 80%;
        height: 2px;
        background: #e9ecef;
    }
    .step.active::after {
        background: #3a7bd5;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3a7bd5;
        box-shadow: 0 0 0 0.25rem rgba(58, 123, 213, 0.25);
        transition: all 0.3s ease;
    }
    .card {
        border-radius: 15px;
        border: none;
    }
    .btn-outline-primary:hover {
        background-color: rgba(58, 123, 213, 0.1);
    }

    .custom-leave-type-btn {
        color: #495057;
        border-color: rgb(162, 204, 236);
        background-color: white;
    }

    .custom-leave-type-btn:hover {
        color: #495057;
        border-color: rgb(162, 204, 236);
        background-color: rgba(162, 204, 236, 0.1);
    }

    .btn-check:checked + .custom-leave-type-btn,
    .btn-check:active + .custom-leave-type-btn {
        color: white;
        background-color: rgb(162, 204, 236);
        border-color: rgb(162, 204, 236);
    }

    .btn-check:focus + .custom-leave-type-btn {
        box-shadow: 0 0 0 0.25rem rgba(162, 204, 236, 0.25);
    }

 </style>

@endsection

@section('content')
<div class="container py-5" style="background: url('https://www.transparenttextures.com/patterns/clean-gray-paper.png');">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg overflow-hidden">
                <div class="card-header text-white" style="background:  #1e3c72;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-umbrella-beach fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Create Leave Request</h5>
                            <small class="opacity-80">Fill out the form to submit your time-off request</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>There were some issues with your submission:</strong>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

     <form method="POST" action="{{ route('leave_requests.review') }}" class="needs-validation" novalidate>
                        @csrf

                        <!--Steps-->
                        <div class="steps mb-4">
                            <div class="step active">
                                <div class="step-circle">1</div>
                                <div class="step-label">Details</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">2</div>
                                <div class="step-label">Review</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">3</div>
                                <div class="step-label">Submit</div>
                            </div>
                        </div>

                        <!-- Choosing LeaveTypes -->
                        <div class="mb-4">
    <label class="form-label fw-bold mb-3">Leave Type</label>
    <div class="row g-3">
        @foreach ($leaveTypes as $leaveType)
        <div class="col-md-4">
            <input type="radio" class="btn-check" name="LeaveTypeID"
                   id="type-{{ $leaveType->LeaveTypeID }}"
                   value="{{ $leaveType->LeaveTypeID }}"
                   autocomplete="off" required
                   @checked(old('LeaveTypeID') == $leaveType->LeaveTypeID)>
            <label class="btn custom-leave-type-btn w-100 py-3 d-flex flex-column"
                   for="type-{{ $leaveType->LeaveTypeID }}">
                <i class="fas
                   @if(str_contains($leaveType->LeaveTypeName, 'Sick')) fa-head-side-cough
                   @elseif(str_contains($leaveType->LeaveTypeName, 'Vacation')) fa-umbrella-beach
                   @elseif(str_contains($leaveType->LeaveTypeName, 'Maternity')) fa-baby
                   @else fa-calendar-alt
                   @endif
                   mb-2"></i>
                {{ $leaveType->LeaveTypeName }}
            </label>
        </div>
        @endforeach
    </div>
</div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar-day text-primary"></i>
                                    </span>
                                    <input type="date" class="form-control" id="StartDate" name="StartDate"
                                           value="{{ old('StartDate') }}"
                                           min="{{ date('Y-m-d') }}" required>
                                </div>
                                @error('StartDate')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar-week text-primary"></i>
                                    </span>
                                    <input type="date" class="form-control" id="EndDate" name="EndDate"
                                           value="{{ old('EndDate') }}"
                                           min="{{ date('Y-m-d') }}" required>
                                </div>
                                @error('EndDate')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-floating mb-4">
                            <textarea class="form-control" placeholder=" " id="Reason" name="Reason"
                                      style="height: 120px">{{ old('Reason') }}</textarea>
                            <label for="Reason">Reason for leave</label>
                            @error('Reason')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-md-2 px-4">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4 py-3 shadow-sm"
                                    style="background:  #1e3c72; border: none;">
                                <span class="submit-text">
                                    <i class="fas fa-paper-plane me-2"></i>Review
                                </span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection


@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date validation
        const startDateInput = document.getElementById('StartDate');
        const endDateInput = document.getElementById('EndDate');

        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
        });

        // Form submission loader
        document.querySelector('form').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.querySelector('.submit-text').classList.add('d-none');
            btn.querySelector('.spinner-border').classList.remove('d-none');
            btn.disabled = true;
        });
    });
</script>

@endsection
