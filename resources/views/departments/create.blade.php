@extends('layouts.dashboard')

@section('title', 'Add New Department')

@section('styles')
<style>
    .department-container {
        max-width: 1200px;
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

    /* Card Body */
    .card-body {
        background-color: #f8fafc;
        padding: 30px;
    }

    /* Sidebar Card Header */
    .sidebar-header {
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        color: white;
        padding: 20px;
        font-size: 1.1rem;
        font-weight: 600;
        border-bottom: none;
    }

    /* Form Styles */
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

    .form-hint {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 5px;
        font-style: italic;
    }

    /* Buttons */
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

    .btn-outline-primary {
        border-color: #2E3A87;
        color: #2E3A87;
        background: transparent;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        color: white;
        border-color: #2E3A87;
        transform: translateY(-1px);
    }

    .btn-outline-secondary {
        border-color: #d1d9e6;
        color: #6c757d;
        background: transparent;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #6c757d;
        transform: translateY(-1px);
    }

    /* Alert Messages */
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

    /* Sidebar Quick Links */
    .quick-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .quick-links .btn {
        text-align: left;
        padding: 12px 20px;
        font-size: 0.95rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .quick-links .btn i {
        width: 20px;
        text-align: center;
        margin-right: 10px;
    }

    @media (max-width: 768px) {
        .department-container {
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
        
        .quick-links .btn {
            padding: 10px 15px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="department-container mt-4">

    <!-- Main Card -->
    <div class="card-custom mb-4">
        <!-- Header -->
        <div class="card-header-custom">
            <h5>Add New Department</h5>
            <a href="{{ route('departments.index') }}" class="btn-back">
                ← Back to Departments
            </a>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Form Section -->
                <div class="col-lg-8">
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
                    <div class="form-section">
                        <h6 class="mb-4 text-primary" style="color: #2E3A87;">Department Information</h6>
                        
                        <form method="POST" action="{{ route('departments.store') }}">
                            @csrf

                            <!-- Department Name -->
                            <div class="form-group">
                                <label for="DepartmentName" class="form-label">Department Name</label>
                                <input type="text" name="DepartmentName" id="DepartmentName"
                                       class="form-control @error('DepartmentName') is-invalid @enderror"
                                       value="{{ old('DepartmentName') }}" 
                                       placeholder="e.g., IT Department, Human Resources, Finance"
                                       required
                                       autofocus>
                                @error('DepartmentName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">Enter the official name of the new department</div>
                            </div>

                            <!-- Supervisor Dropdown -->
                            <div class="form-group">
                                <label for="SupervisorID" class="form-label">Supervisor (Optional)</label>
                                <select name="SupervisorID" id="SupervisorID"
                                        class="form-select @error('SupervisorID') is-invalid @enderror">
                                    <option value="" selected>Select a Supervisor (Optional)</option>
                                    @foreach ($supervisors as $supervisor)
                                        <option value="{{ $supervisor->EmployeeNumber }}"
                                                {{ old('SupervisorID') == $supervisor->EmployeeNumber ? 'selected' : '' }}>
                                            {{ $supervisor->FirstName }} {{ $supervisor->LastName }}
                                            @if($supervisor->EmployeeNumber)
                                                ({{ $supervisor->EmployeeNumber }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('SupervisorID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">You can assign a supervisor now or later from the edit page</div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-plus me-2"></i>
                                    Create Department
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar Section -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card-custom h-100">
                        <div class="sidebar-header">
                            <i class="fas fa-link me-2"></i>
                            Quick Links
                        </div>
                        <div class="card-body">
                            <div class="quick-links">
                                <a href="{{ route('departments.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-building"></i>
                                    View All Departments
                                </a>
                                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-users"></i>
                                    Manage Employees
                                </a>
                                <a href="{{ route('grades.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-chart-line"></i>
                                    Manage Grades
                                </a>
                                <a href="{{ route('positions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-briefcase"></i>
                                    Manage Positions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Focus on department name field
        document.getElementById('DepartmentName').focus();
        
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
        
        // Real-time validation for department name
        const departmentNameInput = document.getElementById('DepartmentName');
        departmentNameInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                this.classList.remove('is-invalid');
            }
        });
    });
</script>
@endsection