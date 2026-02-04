@extends('layouts.app')

@section('title', 'Add Employee')

@section('styles')
<style>
    .add-employee-container {
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

    .btn-dashboard {
        background-color: #2E3A87;
        color: white;
        border: 1px solid #2E3A87;
        padding: 8px 16px;
        font-size: 0.9rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-dashboard:hover {
        background-color: #26327A;
        border-color: #26327A;
        transform: translateY(-1px);
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

    @media (max-width: 768px) {
        .add-employee-container {
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
<div class="add-employee-container mt-4">

    <div class="card-custom">

        <!-- Header -->
        <div class="card-header-custom">
            <h5>Add New Employee</h5>
            <a href="{{ route('dashboard') }}" class="btn btn-dashboard">
                ← Back to Dashboard
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
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf

                <div class="form-section">
                    <h6 class="mb-3 text-primary" style="color: #2E3A87;">Personal Information</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="EmployeeNumber" class="form-label">Employee Number</label>
                                <input type="text" id="EmployeeNumber" name="EmployeeNumber" 
                                       class="form-control @error('EmployeeNumber') is-invalid @enderror" 
                                       value="{{ old('EmployeeNumber') }}" 
                                       placeholder="Enter employee number"
                                       required>
                                @error('EmployeeNumber')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DateOfBirth" class="form-label">Date of Birth</label>
                                <input type="date" id="DateOfBirth" name="DateOfBirth" 
                                       class="form-control @error('DateOfBirth') is-invalid @enderror" 
                                       value="{{ old('DateOfBirth') }}" 
                                       required>
                                @error('DateOfBirth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="FirstName" class="form-label">First Name</label>
                                <input type="text" id="FirstName" name="FirstName" 
                                       class="form-control @error('FirstName') is-invalid @enderror" 
                                       value="{{ old('FirstName') }}" 
                                       placeholder="Enter first name"
                                       required>
                                @error('FirstName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="LastName" class="form-label">Last Name</label>
                                <input type="text" id="LastName" name="LastName" 
                                       class="form-control @error('LastName') is-invalid @enderror" 
                                       value="{{ old('LastName') }}" 
                                       placeholder="Enter last name"
                                       required>
                                @error('LastName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Gender" class="form-label">Gender</label>
                                <select id="Gender" name="Gender" 
                                        class="form-select @error('Gender') is-invalid @enderror" 
                                        required>
                                    <option value="" disabled {{ old('Gender') ? '' : 'selected' }}>Select Gender</option>
                                    <option value="Male" {{ old('Gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('Gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('Gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h6 class="mb-3 text-primary" style="color: #2E3A87;">Job Information</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="DepartmentID" class="form-label">Department</label>
                                <select id="DepartmentID" name="DepartmentID" 
                                        class="form-select @error('DepartmentID') is-invalid @enderror" 
                                        required>
                                    <option value="" disabled {{ old('DepartmentID') ? '' : 'selected' }}>Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->DepartmentID }}" {{ old('DepartmentID') == $department->DepartmentID ? 'selected' : '' }}>
                                            {{ $department->DepartmentName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('DepartmentID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="GradeID" class="form-label">Grade</label>
                                <select id="GradeID" name="GradeID" 
                                        class="form-select @error('GradeID') is-invalid @enderror" 
                                        required>
                                    <option value="" disabled {{ old('GradeID') ? '' : 'selected' }}>Select Grade</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->GradeID }}" {{ old('GradeID') == $grade->GradeID ? 'selected' : '' }}>
                                            {{ $grade->GradeName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('GradeID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="PositionID" class="form-label">Position</label>
                                <select id="PositionID" name="PositionID" 
                                        class="form-select @error('PositionID') is-invalid @enderror" 
                                        required>
                                    <option value="" disabled {{ old('PositionID') ? '' : 'selected' }}>Select Position</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->PositionID }}" {{ old('PositionID') == $position->PositionID ? 'selected' : '' }}>
                                            {{ $position->PositionName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('PositionID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h6 class="mb-3 text-primary" style="color: #2E3A87;">System Access</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role_id" class="form-label">Role</label>
                                <select id="role_id" name="role_id" 
                                        class="form-select @error('role_id') is-invalid @enderror" 
                                        required>
                                    <option value="2" {{ old('role_id', 2) == 2 ? 'selected' : '' }}>Employee</option>
                                    <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-user-plus me-2"></i>
                        Add Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add some interactivity
    document.addEventListener('DOMContentLoaded', function() {
        // Focus on first input field
        document.getElementById('EmployeeNumber').focus();
        
        // Add date picker constraints (optional)
        const dobInput = document.getElementById('DateOfBirth');
        const today = new Date().toISOString().split('T')[0];
        const minDate = new Date();
        minDate.setFullYear(minDate.getFullYear() - 70);
        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() - 18);
        
        dobInput.max = maxDate.toISOString().split('T')[0];
        dobInput.min = minDate.toISOString().split('T')[0];
        
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
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection