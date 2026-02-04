@extends('layouts.app')

@section('title', 'Create Leave Request')

@section('styles')
<style>
/* === MATCHING DEPARTMENTS PAGE THEME === */
.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* Card Styles - Matching Departments */
.card-custom {
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 1px solid #e9ecef;
    padding: 0;
    margin-bottom: 25px;
}

/* Creamy White Header - Matching Departments */
.header-card {
    background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
    color: #2E3A87;
    padding: 24px 30px;
    border-bottom: 2px solid #e9ecef;
    border-radius: 12px 12px 0 0;
    position: relative;
    overflow: hidden;
}

.header-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
}

/* Steps Progress - Matching Departments Theme */
.steps-container {
    background: #ffffff;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    padding: 20px 0;
}

.step {
    text-align: center;
    position: relative;
    flex: 1;
    z-index: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f0f2ff;
    color: #2E3A87;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: 600;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.step.active .step-circle {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    color: white;
    border-color: #2E3A87;
    box-shadow: 0 4px 15px rgba(46, 58, 135, 0.3);
}

.step-label {
    font-size: 14px;
    font-weight: 600;
    color: #6c757d;
    transition: all 0.3s ease;
}

.step.active .step-label {
    color: #2E3A87;
}

.step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 20px;
    left: 60%;
    width: 80%;
    height: 2px;
    background: #e9ecef;
    z-index: -1;
}

.step.active::after {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
}

/* Form Styling - Matching Departments */
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e9ecef;
    padding: 12px 15px;
    font-size: 14px;
    transition: all 0.3s ease;
    background-color: #ffffff;
}

.form-control:focus, .form-select:focus {
    border-color: #2E3A87;
    box-shadow: 0 0 0 3px rgba(46, 58, 135, 0.1);
    outline: none;
}

.form-label {
    font-weight: 600;
    color: #2E3A87;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-floating > .form-control {
    height: 120px;
    min-height: 120px;
}

.form-floating > label {
    color: #6c757d;
    padding: 1rem 0.75rem;
}

/* Leave Type Buttons - Matching Departments */
.leave-type-container {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    margin-bottom: 25px;
}

.leave-type-label {
    font-size: 16px;
    font-weight: 600;
    color: #2E3A87;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.leave-type-label i {
    color: #2E3A87;
}

.leave-type-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.custom-leave-type-btn {
    padding: 20px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    background: #ffffff;
    color: #6c757d;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    height: 100%;
}

.custom-leave-type-btn:hover {
    border-color: #2E3A87;
    background: #f8faff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 58, 135, 0.1);
}

.custom-leave-type-btn i {
    font-size: 24px;
    margin-bottom: 5px;
}

.btn-check:checked + .custom-leave-type-btn {
    border-color: #2E3A87;
    background: linear-gradient(135deg, rgba(46, 58, 135, 0.1) 0%, rgba(74, 91, 217, 0.1) 100%);
    color: #2E3A87;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(46, 58, 135, 0.15);
}

.btn-check:focus + .custom-leave-type-btn {
    box-shadow: 0 0 0 3px rgba(46, 58, 135, 0.2);
}

/* Date Inputs Styling */
.date-input-container {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    margin-bottom: 25px;
}

.input-group {
    border-radius: 8px;
    overflow: hidden;
}

.input-group-text {
    background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
    border: 1px solid #e9ecef;
    color: #2E3A87;
    font-weight: 600;
    min-width: 45px;
    justify-content: center;
}

.input-group .form-control {
    border-left: none;
}

/* Reason Textarea Container */
.reason-container {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    margin-bottom: 25px;
}

/* Buttons - Matching Departments */
.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 12px 24px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 8px rgba(46, 58, 135, 0.2);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(46, 58, 135, 0.3);
    background: linear-gradient(135deg, #26327A 0%, #3D4DC7 100%);
    color: white;
}

.btn-outline-secondary {
    border: 1px solid #e9ecef;
    color: #6c757d;
    background: #ffffff;
}

.btn-outline-secondary:hover {
    background: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

/* Alert Messages - Matching Departments */
.alert {
    border-radius: 8px;
    border: none;
    padding: 16px 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border-left: 4px solid #dc3545;
}

.alert i {
    margin-right: 10px;
}

/* Responsive Adjustments - Matching Departments */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    
    .steps {
        padding: 15px 0;
    }
    
    .step-circle {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .step:not(:last-child)::after {
        top: 17px;
    }
    
    .leave-type-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .custom-leave-type-btn {
        padding: 15px;
    }
    
    .date-input-container,
    .reason-container,
    .leave-type-container {
        padding: 20px;
    }
    
    .form-control, .form-select {
        padding: 10px 12px;
    }
}

@media (max-width: 576px) {
    .leave-type-grid {
        grid-template-columns: 1fr;
    }
    
    .steps {
        flex-direction: column;
        gap: 20px;
    }
    
    .step:not(:last-child)::after {
        display: none;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .d-md-flex {
        flex-direction: column;
    }
}

/* Loading Spinner */
.spinner-border {
    width: 1rem;
    height: 1rem;
    border-width: 0.2em;
}
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Main Card - Matching Departments Structure -->
    <div class="card-custom">
        <!-- Header -->
        <div class="header-card">
            <div class="d-flex align-items-center">
                <div class="header-icon me-3">
                    <i class="fas fa-umbrella-beach fa-2x" style="color: #2E3A87;"></i>
                </div>
                <div>
                    <h5 class="mb-1" style="font-weight: 700; color: #2E3A87;">Create Leave Request</h5>
                    <p class="mb-0" style="color: #6c757d; font-size: 14px;">Fill out the form to submit your time-off request</p>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="table-card" style="padding: 30px;">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong style="color: #dc3545;">There were some issues with your submission:</strong>
                    <ul class="mt-2 mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('leave_requests.review') }}" class="needs-validation" novalidate>
                @csrf

                <!-- Steps Progress -->
                <div class="steps-container">
                    <div class="steps mb-3">
                        <div class="step active">
                            <div class="step-circle">1</div>
                            <div class="step-label">Leave Details</div>
                        </div>
                        <div class="step">
                            <div class="step-circle">2</div>
                            <div class="step-label">Review Request</div>
                        </div>
                        <div class="step">
                            <div class="step-circle">3</div>
                            <div class="step-label">Submit Request</div>
                        </div>
                    </div>
                </div>

                <!-- Leave Type Selection -->
                <div class="leave-type-container">
                    <div class="leave-type-label">
                        <i class="fas fa-tags"></i>
                        Leave Type
                    </div>
                    <div class="leave-type-grid">
                        @foreach ($leaveTypes as $leaveType)
                            <div>
                                <input type="radio" class="btn-check" name="LeaveTypeID"
                                       id="type-{{ $leaveType->LeaveTypeID }}"
                                       value="{{ $leaveType->LeaveTypeID }}"
                                       autocomplete="off" required
                                       @checked(old('LeaveTypeID') == $leaveType->LeaveTypeID)>
                                <label class="custom-leave-type-btn"
                                       for="type-{{ $leaveType->LeaveTypeID }}">
                                    @php
                                        $icon = 'calendar-alt'; // Default icon
                                        $typeName = strtolower($leaveType->LeaveTypeName);
                                        
                                        if (str_contains($typeName, 'sick')) {
                                            $icon = 'head-side-cough';
                                        } elseif (str_contains($typeName, 'vacation') || str_contains($typeName, 'annual')) {
                                            $icon = 'umbrella-beach';
                                        } elseif (str_contains($typeName, 'maternity')) {
                                            $icon = 'baby';
                                        } elseif (str_contains($typeName, 'paternity')) {
                                            $icon = 'user-tie';
                                        } elseif (str_contains($typeName, 'emergency')) {
                                            $icon = 'exclamation-triangle';
                                        } elseif (str_contains($typeName, 'study')) {
                                            $icon = 'graduation-cap';
                                        }
                                    @endphp
                                    <i class="fas fa-{{ $icon }}" style="color: #2E3A87;"></i>
                                    <span>{{ $leaveType->LeaveTypeName }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Date Selection -->
                <div class="date-input-container">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="StartDate" class="form-label">
                                <i class="fas fa-calendar-day me-2"></i>
                                Start Date
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control" id="StartDate" name="StartDate"
                                       value="{{ old('StartDate') }}"
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                            @error('StartDate')
                                <div class="text-danger mt-2" style="font-size: 13px;">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="EndDate" class="form-label">
                                <i class="fas fa-calendar-week me-2"></i>
                                End Date
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control" id="EndDate" name="EndDate"
                                       value="{{ old('EndDate') }}"
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                            @error('EndDate')
                                <div class="text-danger mt-2" style="font-size: 13px;">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Reason for Leave -->
                <div class="reason-container">
                    <label for="Reason" class="form-label">
                        <i class="fas fa-comment-dots me-2"></i>
                        Reason for Leave
                    </label>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Provide details about your leave request" 
                                  id="Reason" name="Reason" style="height: 120px">{{ old('Reason') }}</textarea>
                        <label for="Reason">Provide details about your leave request...</label>
                    </div>
                    @error('Reason')
                        <div class="text-danger mt-2" style="font-size: 13px;">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <span class="submit-text">
                            <i class="fas fa-paper-plane me-2"></i>Review Request
                        </span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
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
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.querySelector('.submit-text').classList.add('d-none');
            btn.querySelector('.spinner-border').classList.remove('d-none');
            btn.disabled = true;
        }
    });

    // Set default min dates
    const today = new Date().toISOString().split('T')[0];
    if (!startDateInput.value) {
        startDateInput.value = today;
    }
    if (!endDateInput.value) {
        endDateInput.value = today;
    }
    startDateInput.min = today;
    endDateInput.min = startDateInput.value;

    // Add active state to selected leave type
    const leaveTypeButtons = document.querySelectorAll('.custom-leave-type-btn');
    leaveTypeButtons.forEach(button => {
        button.addEventListener('click', function() {
            leaveTypeButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
});
</script>
@endsection