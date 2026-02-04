@extends('layouts.app')

@section('title', 'Add Position')

@section('styles')
<style>
    .add-position-container {
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

    @media (max-width: 768px) {
        .add-position-container {
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
<div class="add-position-container mt-4">

    <div class="card-custom">

        <!-- Header -->
        <div class="card-header-custom">
            <h5>Add New Position</h5>
            <a href="{{ route('positions.index') }}" class="btn-back">
                ← Back to Positions
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
            <form action="{{ route('positions.store') }}" method="POST">
                @csrf

                <div class="form-section">
                    <h6 class="mb-4 text-primary" style="color: #2E3A87;">Position Information</h6>
                    
                    <!-- Position Name -->
                    <div class="form-group">
                        <label for="PositionName" class="form-label">Position Name</label>
                        <input type="text" id="PositionName" name="PositionName" 
                               class="form-control @error('PositionName') is-invalid @enderror" 
                               value="{{ old('PositionName') }}" 
                               placeholder="e.g., Software Engineer, Project Manager, HR Specialist"
                               required
                               autofocus>
                        @error('PositionName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Enter the official name of the new position</div>
                    </div>

                    <!-- Grade Dropdown -->
                    <div class="form-group">
                        <label for="GradeID" class="form-label">Grade</label>
                        <select id="GradeID" name="GradeID" 
                                class="form-select @error('GradeID') is-invalid @enderror" 
                                required>
                            <option value="" disabled selected>Select a grade</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->GradeID }}" 
                                        {{ old('GradeID') == $grade->GradeID ? 'selected' : '' }}>
                                    {{ $grade->GradeName }}
                                </option>
                            @endforeach
                        </select>
                        @error('GradeID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Select the appropriate grade level for this position</div>
                        
                        @if($grades->isEmpty())
                            <div class="alert alert-info mt-2">
                                <i class="fas fa-info-circle me-2"></i>
                                No grades available. Please add grades first in the Grade Management section.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-plus me-2"></i>
                        Add Position
                    </button>
                    
                    <a href="{{ route('positions.index') }}" class="btn btn-outline-secondary px-5 ms-2">
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
        // Focus on position name field
        document.getElementById('PositionName').focus();
        
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
        
        // Real-time validation for position name
        const positionNameInput = document.getElementById('PositionName');
        positionNameInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                this.classList.remove('is-invalid');
            }
        });
        
        // Real-time validation for grade selection
        const gradeSelect = document.getElementById('GradeID');
        gradeSelect.addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
            }
        });
    });
</script>
@endsection