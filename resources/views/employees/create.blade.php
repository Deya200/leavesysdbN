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
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header-custom {
            background-color: #2E3A87;
            color: white;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control,
        .form-select {
            border-radius: 5px;
            font-size: 0.95rem;
        }

        .btn-primary {
            background-color: #2E3A87;
            border: none;
        }

        .btn-primary:hover {
            background-color: #1f2f75;
        }
    </style>
@endsection

@section('content')
    <div class="add-employee-container mt-4">

        <div class="card-custom">

            <!-- Header -->
            <div class="card-header-custom">
                <h5 class="mb-0">Add New Employee</h5>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">Dashboard</a>
            </div>

            <div class="card-body" style="background-color: #f8f9fa;">

                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Error Message -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('employees.store') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="EmployeeNumber" class="form-label">Employee Number</label>
                        <input type="text" id="EmployeeNumber" name="EmployeeNumber"
                            class="form-control @error('EmployeeNumber') is-invalid @enderror"
                            value="{{ old('EmployeeNumber') }}" required>
                        @error('EmployeeNumber')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="national_id" class="form-label">National ID</label>
                        <input type="text" id="national_id" name="national_id"
                            class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id') }}"
                            required>
                        @error('national_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="FirstName" class="form-label">First Name</label>
                        <input type="text" id="FirstName" name="FirstName"
                            class="form-control @error('FirstName') is-invalid @enderror" value="{{ old('FirstName') }}"
                            required>
                        @error('FirstName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="LastName" class="form-label">Last Name</label>
                        <input type="text" id="LastName" name="LastName"
                            class="form-control @error('LastName') is-invalid @enderror" value="{{ old('LastName') }}"
                            required>
                        @error('LastName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="HomeAddress" class="form-label">Home Address</label>
                        <input type="text" id="HomeAddress" name="HomeAddress"
                            class="form-control @error('HomeAddress') is-invalid @enderror" value="{{ old('HomeAddress') }}">
                        @error('HomeAddress')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="ResidentialAddress" class="form-label">Residential Address</label>
                        <input type="text" id="ResidentialAddress" name="ResidentialAddress"
                            class="form-control @error('ResidentialAddress') is-invalid @enderror" value="{{ old('ResidentialAddress') }}">
                        @error('ResidentialAddress')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="NextOfKin" class="form-label">Next of Kin</label>
                        <input type="text" id="NextOfKin" name="NextOfKin"
                            class="form-control @error('NextOfKin') is-invalid @enderror" value="{{ old('NextOfKin') }}">
                        @error('NextOfKin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        <div id="email-feedback" class="mt-1 small fw-bold"></div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="DateOfBirth" class="form-label">Date of Birth</label>
                        <input type="date" id="DateOfBirth" name="DateOfBirth"
                            class="form-control @error('DateOfBirth') is-invalid @enderror" value="{{ old('DateOfBirth') }}"
                            required>
                        @error('DateOfBirth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="AppointmentDate" class="form-label">Appointment Date</label>
                        <input type="date" id="AppointmentDate" name="AppointmentDate"
                            class="form-control @error('AppointmentDate') is-invalid @enderror" value="{{ old('AppointmentDate') }}">
                        @error('AppointmentDate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="Gender" class="form-label">Gender</label>
                        <select id="Gender" name="Gender" class="form-select @error('Gender') is-invalid @enderror"
                            required>
                            <option value="" disabled {{ old('Gender') ? '' : 'selected' }}>Select Gender</option>
                            <option value="Male" {{ old('Gender') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('Gender') === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('Gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="DepartmentID" class="form-label">Department</label>
                        <select id="DepartmentID" name="DepartmentID"
                            class="form-select @error('DepartmentID') is-invalid @enderror" required>
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

                    <div class="form-group mb-3">
                        <label for="GradeID" class="form-label">Grade</label>
                        <select id="GradeID" name="GradeID" class="form-select @error('GradeID') is-invalid @enderror"
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

                    <div class="form-group mb-3">
                        <label for="PositionID" class="form-label">Position</label>
                        <select id="PositionID" name="PositionID"
                            class="form-select @error('PositionID') is-invalid @enderror" required>
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

                    <div class="form-group mb-4">
                        <label for="role_id" class="form-label">Role</label>
                        <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror"
                            required>
                            <option value="" disabled selected>Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="DutyStation" class="form-label">Duty Station</label>
                            <input type="text" id="DutyStation" name="DutyStation"
                                class="form-control @error('DutyStation') is-invalid @enderror"
                                value="{{ old('DutyStation') }}" placeholder="Lilongwe HQ">
                            @error('DutyStation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="PensionNumber" class="form-label">Pension Number</label>
                            <input type="text" id="PensionNumber" name="PensionNumber"
                                class="form-control @error('PensionNumber') is-invalid @enderror"
                                value="{{ old('PensionNumber') }}" placeholder="PEN-001">
                            @error('PensionNumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="BankName" class="form-label">Bank Name</label>
                            <input type="text" id="BankName" name="BankName"
                                class="form-control @error('BankName') is-invalid @enderror"
                                value="{{ old('BankName') }}" placeholder="National Bank">
                            @error('BankName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="BankBranch" class="form-label">Bank Branch</label>
                            <input type="text" id="BankBranch" name="BankBranch"
                                class="form-control @error('BankBranch') is-invalid @enderror"
                                value="{{ old('BankBranch') }}" placeholder="Capital City">
                            @error('BankBranch')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="BankAccountNumber" class="form-label">Bank Account Number</label>
                            <input type="text" id="BankAccountNumber" name="BankAccountNumber"
                                class="form-control @error('BankAccountNumber') is-invalid @enderror"
                                value="{{ old('BankAccountNumber') }}" placeholder="00123456789">
                            @error('BankAccountNumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">Add Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const emailInput = document.getElementById('email');
            const emailFeedback = document.getElementById('email-feedback');
            let debounceTimer;

            emailInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                emailFeedback.className = 'mt-1 small fw-bold';
                emailFeedback.innerText = '';
                emailInput.classList.remove('is-invalid', 'is-valid');

                if (!email) return;

                if (!emailRegex.test(email)) {
                    emailFeedback.innerText = 'Invalid email format';
                    emailFeedback.classList.add('text-danger');
                    emailInput.classList.add('is-invalid');
                    return;
                }

                emailFeedback.innerText = 'Checking availability...';
                emailFeedback.classList.add('text-muted');

                debounceTimer = setTimeout(() => {
                    fetch(`/api/validate/email`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email: email })
                    })
                        .then(response => response.json())
                        .then(data => {
                            emailFeedback.classList.remove('text-muted');
                            if (data.available) {
                                emailFeedback.innerText = '✓ Email is available';
                                emailFeedback.className = 'mt-1 small fw-bold text-success';
                                emailInput.classList.add('is-valid');
                                emailInput.classList.remove('is-invalid');
                            } else {
                                emailFeedback.innerText = '⚠ Valid email format (already in use by another record)';
                                emailFeedback.className = 'mt-1 small fw-bold text-warning';
                                emailInput.classList.remove('is-invalid', 'is-valid');
                            }
                        })
                        .catch(error => {
                            console.error('Error validating email:', error);
                            emailFeedback.innerText = '';
                        });
                }, 500);
            });
        });
    </script>
@endsection
