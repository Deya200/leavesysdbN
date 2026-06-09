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
                <form method="POST" action="{{ route('employees.store') }}" id="createEmployeeForm">
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

                    <div class="form-group mb-3">
                        <label for="employment_type" class="form-label">Employment Type</label>
                        <select id="employment_type" name="employment_type" class="form-select @error('employment_type') is-invalid @enderror">
                            <option value="Permanent" {{ old('employment_type', 'Permanent') === 'Permanent' ? 'selected' : '' }}>Permanent</option>
                            <option value="Temporary" {{ old('employment_type') === 'Temporary' ? 'selected' : '' }}>Temporary</option>
                            <option value="Locum" {{ old('employment_type') === 'Locum' ? 'selected' : '' }}>Locum</option>
                            <option value="Contract" {{ old('employment_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                        </select>
                        @error('employment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="is_locum" class="form-label">Is Locum Staff</label>
                        <div class="form-check">
                            <input type="checkbox" id="is_locum" name="is_locum" value="1" 
                                class="form-check-input @error('is_locum') is-invalid @enderror" 
                                {{ old('is_locum') ? 'checked' : '' }}>
                            <label for="is_locum" class="form-check-label">
                                Check if this employee is dedicated locum staff
                            </label>
                        </div>
                        @error('is_locum')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3" id="contract_dates" style="display: none;">
                        <label for="contract_start_date" class="form-label">Contract Start Date</label>
                        <input type="date" id="contract_start_date" name="contract_start_date"
                            class="form-control @error('contract_start_date') is-invalid @enderror" 
                            value="{{ old('contract_start_date') }}">
                        @error('contract_start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3" id="contract_end_dates" style="display: none;">
                        <label for="contract_end_date" class="form-label">Contract End Date</label>
                        <input type="date" id="contract_end_date" name="contract_end_date"
                            class="form-control @error('contract_end_date') is-invalid @enderror" 
                            value="{{ old('contract_end_date') }}">
                        @error('contract_end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-primary w-100" onclick="openConfirmModal('create', '{{ old('FirstName') }} {{ old('LastName') }}')">
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
        document.addEventListener('DOMContentLoaded', function () {
            // Employment type change handler
            const employmentTypeSelect = document.getElementById('employment_type');
            const isLocumCheckbox = document.getElementById('is_locum');
            const contractDatesDiv = document.getElementById('contract_dates');
            const contractEndDatesDiv = document.getElementById('contract_end_dates');

            function toggleContractDates() {
                const employmentType = employmentTypeSelect.value;
                const isLocum = isLocumCheckbox.checked;

                if (employmentType === 'Temporary' || employmentType === 'Locum' || employmentType === 'Contract' || isLocum) {
                    contractDatesDiv.style.display = 'block';
                    contractEndDatesDiv.style.display = 'block';
                } else {
                    contractDatesDiv.style.display = 'none';
                    contractEndDatesDiv.style.display = 'none';
                }
            }

            employmentTypeSelect.addEventListener('change', toggleContractDates);
            isLocumCheckbox.addEventListener('change', toggleContractDates);

            // Initial check
            toggleContractDates();

            const emailInput = document.getElementById('email');
            const emailFeedback = document.getElementById('email-feedback');
            let debounceTimer;

            emailInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                emailFeedback.className = 'mt-1 small fw-bold'; // Reset classes
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

                            if (!data.is_valid) {
                                emailFeedback.innerText = `✖ ${data.verification_reason}`;
                                emailFeedback.className = 'mt-1 small fw-bold text-danger';
                                emailInput.classList.add('is-invalid');
                                emailInput.classList.remove('is-valid');
                                return;
                            }

                            if (!data.available) {
                                emailFeedback.innerText = '⚠ This email is already in use by another record.';
                                emailFeedback.className = 'mt-1 small fw-bold text-warning';
                                emailInput.classList.remove('is-valid');
                                emailInput.classList.add('is-invalid');
                                return;
                            }

                            let statusText = '✓ Email is available and looks deliverable.';
                            let statusClass = 'mt-1 small fw-bold text-success';

                            if (data.smtp_ok === false) {
                                statusText = '⚠ Email domain exists but SMTP recipient check failed.';
                                statusClass = 'mt-1 small fw-bold text-warning';
                            } else if (data.smtp_ok === null) {
                                statusText = '⚠ Email domain exists; could not verify mailbox conclusively.';
                                statusClass = 'mt-1 small fw-bold text-info';

            emailInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                emailFeedback.className = 'mt-1 small fw-bold'; // Reset classes
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

                            if (!data.is_valid) {
                                emailFeedback.innerText = `✖ ${data.verification_reason}`;
                                emailFeedback.className = 'mt-1 small fw-bold text-danger';
                                emailInput.classList.add('is-invalid');
                                emailInput.classList.remove('is-valid');
                                return;
                            }

                            if (!data.available) {
                                emailFeedback.innerText = '⚠ This email is already in use by another record.';
                                emailFeedback.className = 'mt-1 small fw-bold text-warning';
                                emailInput.classList.remove('is-valid');
                                emailInput.classList.add('is-invalid');
                                return;
                            }

                            let statusText = '✓ Email is available and looks deliverable.';
                            let statusClass = 'mt-1 small fw-bold text-success';

                            if (data.smtp_ok === false) {
                                statusText = '⚠ Email domain exists but SMTP recipient check failed.';
                                statusClass = 'mt-1 small fw-bold text-warning';
                            } else if (data.smtp_ok === null) {
                                statusText = '⚠ Email domain exists; could not verify mailbox conclusively.';
                                statusClass = 'mt-1 small fw-bold text-info';
                            }

                            emailFeedback.innerText = statusText;
                            emailFeedback.className = statusClass;
                            emailInput.classList.add('is-valid');
                            emailInput.classList.remove('is-invalid');
                        })
                        .catch(error => {
                            console.error('Error validating email:', error);
                            emailFeedback.innerText = 'Unable to verify email at the moment.';
                            emailFeedback.className = 'mt-1 small fw-bold text-warning';
                        });
                }, 500);
            });
        });
    </script>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Employee Creation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to create this employee?</p>
                    <p class="mb-0" style="color: #666; font-size: 0.95rem;"><strong id="employeeName"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitEmployeeForm()">Confirm & Create</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openConfirmModal(action, employeeName) {
            const firstName = document.getElementById('FirstName').value;
            const lastName = document.getElementById('LastName').value;
            document.getElementById('employeeName').innerText = (firstName + ' ' + lastName).trim() || 'New Employee';
            
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        }

        function submitEmployeeForm() {
            document.getElementById('createEmployeeForm').submit();
        }
    </script>
@endsection