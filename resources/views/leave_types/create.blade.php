@extends('layouts.app')

@section('title', 'Create Leave Type')

@section('styles')
<style>
    .create-leave-type-container {
        max-width: 800px;
        margin: auto;
        padding: 20px;
    }

    .card-custom {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e9ecef;
        padding: 0;
    }

    /* Creamy White Header */
    .card-header-custom {
        background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
        color: #2E3A87;
        padding: 24px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #e9ecef;
        position: relative;
        overflow: hidden;
    }

    .card-header-custom:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    }

    .card-header-custom h5 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #2E3A87;
    }

    .card-header-custom h5:before {
        content: "＋";
        font-size: 1.8rem;
        font-weight: 300;
        color: #4A5BD9;
    }

    .btn-back {
        background-color: #2E3A87;
        color: white;
        border: 1px solid #2E3A87;
        padding: 8px 16px;
        font-size: 0.9rem;
        border-radius: 6px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background-color: #26327A;
        border-color: #26327A;
        transform: translateY(-1px);
        color: white;
        box-shadow: 0 2px 8px rgba(46, 58, 135, 0.2);
    }

    .card-body {
        background-color: #f8fafc;
        padding: 30px;
    }

    .form-label {
        font-weight: 600;
        color: #2E3A87;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #d1d9e6;
        padding: 10px 15px;
        font-size: 0.95rem;
        transition: all 0.3s;
        background-color: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2E3A87;
        box-shadow: 0 0 0 3px rgba(46, 58, 135, 0.1);
        outline: none;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        border: none;
        padding: 12px 30px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(46, 58, 135, 0.2);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(46, 58, 135, 0.3);
        background: linear-gradient(135deg, #26327A 0%, #3D4DC7 100%);
        color: white;
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-outline-secondary {
        border-color: #d1d9e6;
        color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #6c757d;
    }

    .alert {
        border-radius: 8px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .alert-success {
        background-color: #e8f5e9;
        color: #2e7d32;
        border-left: 4px solid #4caf50;
    }

    .alert-danger {
        background-color: #ffebee;
        color: #c62828;
        border-left: 4px solid #f44336;
    }

    .invalid-feedback {
        color: #f44336;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    .is-invalid {
        border-color: #f44336;
    }

    .is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.1);
    }

    .form-section {
        background: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .form-hint {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 5px;
        font-style: italic;
    }

    /* Status Indicators */
    .info-note {
        background-color: #e3f2fd;
        border-left: 4px solid #2196f3;
        padding: 15px;
        border-radius: 6px;
        margin-top: 20px;
        font-size: 0.9rem;
    }

    .info-note i {
        color: #2196f3;
        margin-right: 10px;
    }

    .gender-icon {
        margin-right: 8px;
    }

    .gender-male {
        color: #4A5BD9;
    }

    .gender-female {
        color: #e83e8c;
    }

    .gender-both {
        color: #28a745;
    }

    @media (max-width: 768px) {
        .create-leave-type-container {
            padding: 15px;
        }
        
        .card-header-custom {
            padding: 20px;
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .form-section {
            padding: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="create-leave-type-container mt-4">

    <div class="card-custom">

        <!-- Header -->
        <div class="card-header-custom">
            <h5>Create New Leave Type</h5>
            <a href="{{ route('leave_types.index') }}" class="btn-back">
                ← Back to Leave Types
            </a>
        </div>

        <div class="card-body">

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Message -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('leave_types.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="form-section">
                    <h6 class="mb-4 text-primary" style="color: #2E3A87;">Leave Type Information</h6>
                    
                    <!-- Leave Type Name -->
                    <div class="form-group">
                        <label for="LeaveTypeName" class="form-label">Leave Type Name</label>
                        <input type="text" id="LeaveTypeName" name="LeaveTypeName"
                               class="form-control @error('LeaveTypeName') is-invalid @enderror"
                               value="{{ old('LeaveTypeName') }}" 
                               placeholder="e.g., Annual Leave, Sick Leave, Maternity Leave"
                               required
                               autofocus>
                        @error('LeaveTypeName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Enter the official name of the leave type</div>
                    </div>

                    <!-- Is Paid Leave -->
                    <div class="form-group">
                        <label for="IsPaidLeave" class="form-label">Is Paid Leave</label>
                        <select id="IsPaidLeave" name="IsPaidLeave"
                                class="form-select @error('IsPaidLeave') is-invalid @enderror" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="1" {{ old('IsPaidLeave') === '1' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Yes - Employee gets paid during leave
                            </option>
                            <option value="0" {{ old('IsPaidLeave') === '0' ? 'selected' : '' }}>
                                <i class="fas fa-times-circle text-secondary me-2"></i>
                                No - Leave without pay
                            </option>
                        </select>
                        @error('IsPaidLeave')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Select whether this leave type is paid or unpaid</div>
                    </div>

                    <!-- Gender Applicable -->
                    <div class="form-group">
                        <label for="GenderApplicable" class="form-label">Gender Applicable</label>
                        <select id="GenderApplicable" name="GenderApplicable"
                                class="form-select @error('GenderApplicable') is-invalid @enderror" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="Male" {{ old('GenderApplicable') === 'Male' ? 'selected' : '' }}>
                                <i class="fas fa-male gender-icon gender-male"></i>
                                Male Only
                            </option>
                            <option value="Female" {{ old('GenderApplicable') === 'Female' ? 'selected' : '' }}>
                                <i class="fas fa-female gender-icon gender-female"></i>
                                Female Only
                            </option>
                            <option value="Both" {{ old('GenderApplicable') === 'Both' ? 'selected' : '' }}>
                                <i class="fas fa-users gender-icon gender-both"></i>
                                Both Genders
                            </option>
                        </select>
                        @error('GenderApplicable')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Select which gender(s) can apply for this leave type</div>
                    </div>

                    <!-- Duration Period -->
                    <div class="form-group">
                        <label for="DurationPeriod" class="form-label">Duration Period (Days)</label>
                        <input type="number" id="DurationPeriod" name="DurationPeriod"
                               class="form-control @error('DurationPeriod') is-invalid @enderror"
                               value="{{ old('DurationPeriod') }}" 
                               min="1" 
                               max="365"
                               placeholder="e.g., 30"
                               required>
                        @error('DurationPeriod')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Enter the maximum duration allowed for this leave type in days</div>
                    </div>

                    <!-- Information Note -->
                    <div class="info-note">
                        <i class="fas fa-info-circle"></i>
                        <strong>Important:</strong> Leave types that are <strong>paid</strong> and applicable to <strong>both genders</strong> 
                        will automatically deduct from the annual leave balance. This setting cannot be changed after creation.
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-plus me-2"></i>
                        Create Leave Type
                    </button>
                    
                    <a href="{{ route('leave_types.index') }}" class="btn btn-outline-secondary px-5 ms-2">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Focus on leave type name field
        document.getElementById('LeaveTypeName').focus();
        
        // Form validation feedback
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                }
            });
            
            // Validate Duration Period
            const durationField = document.getElementById('DurationPeriod');
            if (durationField.value) {
                const duration = parseInt(durationField.value);
                if (duration < 1 || duration > 365) {
                    isValid = false;
                    durationField.classList.add('is-invalid');
                    if (!durationField.nextElementSibling.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Duration must be between 1 and 365 days';
                        durationField.parentNode.appendChild(errorDiv);
                    }
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        // Real-time validation for leave type name
        const leaveTypeNameInput = document.getElementById('LeaveTypeName');
        leaveTypeNameInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                this.classList.remove('is-invalid');
            }
        });
        
        // Real-time validation for duration period
        const durationInput = document.getElementById('DurationPeriod');
        durationInput.addEventListener('input', function() {
            const duration = parseInt(this.value);
            if (!isNaN(duration) && duration >= 1 && duration <= 365) {
                this.classList.remove('is-invalid');
                // Remove any existing custom error message
                const errorDiv = this.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv.remove();
                }
            }
        });
        
        // Update visual indicators when selections change
        const paidLeaveSelect = document.getElementById('IsPaidLeave');
        const genderSelect = document.getElementById('GenderApplicable');
        
        // Show/hide annual leave deduction info based on selections
        function updateAnnualDeductionInfo() {
            const isPaid = paidLeaveSelect.value === '1';
            const gender = genderSelect.value;
            const deductsFromAnnual = isPaid && gender === 'Both';
            
            const infoNote = document.querySelector('.info-note');
            if (infoNote) {
                if (deductsFromAnnual) {
                    infoNote.style.backgroundColor = '#e8f5e9';
                    infoNote.style.borderLeftColor = '#4caf50';
                    infoNote.innerHTML = `
                        <i class="fas fa-exclamation-circle text-success"></i>
                        <strong>Note:</strong> This leave type <strong>WILL deduct</strong> from annual leave balance 
                        because it's a paid leave applicable to both genders.
                    `;
                } else {
                    infoNote.style.backgroundColor = '#e3f2fd';
                    infoNote.style.borderLeftColor = '#2196f3';
                    infoNote.innerHTML = `
                        <i class="fas fa-info-circle"></i>
                        <strong>Important:</strong> Leave types that are <strong>paid</strong> and applicable to <strong>both genders</strong> 
                        will automatically deduct from the annual leave balance. This setting cannot be changed after creation.
                    `;
                }
            }
        }
        
        paidLeaveSelect.addEventListener('change', updateAnnualDeductionInfo);
        genderSelect.addEventListener('change', updateAnnualDeductionInfo);
    });
</script>
@endsection