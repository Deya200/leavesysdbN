@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="border: 2px solidrgb(96, 173, 255); box-shadow: 0px 4px 8px rgba(0,0,0,0.2);">
                    <div class="card-header text-white" style="background-color: #2E3A87;">
                        <h5 class="mb-0">Edit Employee</h5>
                    </div>
                    <div class="card-body" style="background-color: #f8f9fa;">

                        <form method="POST" action="{{ route('employees.update', $employee->EmployeeNumber) }}" id="editEmployeeForm">
                            @csrf
                            @method('PUT')

                            <!-- Employee Number (Read-Only) -->
                            <div class="mb-3">
                                <label for="EmployeeNumber" class="form-label">Employee Number</label>
                                <input type="text" name="EmployeeNumber" id="EmployeeNumber" class="form-control"
                                    value="{{ $employee->EmployeeNumber }}" readonly>
                            </div>

                            <!-- National ID -->
                            <div class="mb-3">
                                <label for="national_id" class="form-label">National ID *</label>
                                <input type="text" name="national_id" id="national_id"
                                    class="form-control @error('national_id') is-invalid @enderror"
                                    value="{{ old('national_id', $employee->national_id) }}" required>
                                @error('national_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                             <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $employee->email) }}" required>
                                <div id="email-feedback" class="mt-1 small fw-bold"></div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- First Name -->
                            <div class="mb-3">
                                <label for="FirstName" class="form-label">First Name</label>
                                <input type="text" name="FirstName" id="FirstName"
                                    class="form-control @error('FirstName') is-invalid @enderror"
                                    value="{{ old('FirstName', $employee->FirstName) }}" required>
                                @error('FirstName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="mb-3">
                                <label for="LastName" class="form-label">Last Name</label>
                                <input type="text" name="LastName" id="LastName"
                                    class="form-control @error('LastName') is-invalid @enderror"
                                    value="{{ old('LastName', $employee->LastName) }}" required>
                                @error('LastName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="mb-3">
                                <label for="DateOfBirth" class="form-label">Date of Birth</label>
                                <input type="date" name="DateOfBirth" id="DateOfBirth"
                                    class="form-control @error('DateOfBirth') is-invalid @enderror"
                                    value="{{ old('DateOfBirth', $employee->DateOfBirth) }}" required>
                                @error('DateOfBirth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="mb-3">
                                <label for="Gender" class="form-label">Gender</label>
                                <select id="Gender" name="Gender" class="form-select @error('Gender') is-invalid @enderror"
                                    required>
                                    <option value="Male" {{ old('Gender', $employee->Gender) === 'Male' ? 'selected' : '' }}>
                                        Male</option>
                                    <option value="Female" {{ old('Gender', $employee->Gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('Gender', $employee->Gender) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('Gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Department -->
                            <div class="mb-3">
                                <label for="DepartmentID" class="form-label">Department</label>
                                <select id="DepartmentID" name="DepartmentID"
                                    class="form-select @error('DepartmentID') is-invalid @enderror" required>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->DepartmentID }}" {{ old('DepartmentID', $employee->DepartmentID) == $department->DepartmentID ? 'selected' : '' }}>
                                            {{ $department->DepartmentName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('DepartmentID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Grade -->
                            <div class="mb-3">
                                <label for="GradeID" class="form-label">Grade</label>
                                <select id="GradeID" name="GradeID"
                                    class="form-select @error('GradeID') is-invalid @enderror" required>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->GradeID }}" {{ old('GradeID', $employee->GradeID) == $grade->GradeID ? 'selected' : '' }}>
                                            {{ $grade->GradeName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('GradeID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Position -->
                            <div class="mb-3">
                                <label for="PositionID" class="form-label">Position</label>
                                <select id="PositionID" name="PositionID"
                                    class="form-select @error('PositionID') is-invalid @enderror" required>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->PositionID }}" {{ old('PositionID', $employee->PositionID) == $position->PositionID ? 'selected' : '' }}>
                                            {{ $position->PositionName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('PositionID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role Selection -->
                            <div class="mb-3">
                                <label for="role_id" class="form-label">Role</label>
                                <select id="role_id" name="role_id"
                                    class="form-select @error('role_id') is-invalid @enderror" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id', $employee->role_id) == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Employment Type -->
                            <div class="mb-3">
                                <label for="employment_type" class="form-label">Employment Type</label>
                                <select id="employment_type" name="employment_type" class="form-select @error('employment_type') is-invalid @enderror">
                                    <option value="Permanent" {{ old('employment_type', $employee->employment_type ?? 'Permanent') === 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                    <option value="Temporary" {{ old('employment_type', $employee->employment_type) === 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                    <option value="Locum" {{ old('employment_type', $employee->employment_type) === 'Locum' ? 'selected' : '' }}>Locum</option>
                                    <option value="Contract" {{ old('employment_type', $employee->employment_type) === 'Contract' ? 'selected' : '' }}>Contract</option>
                                </select>
                                @error('employment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Is Locum -->
                            <div class="mb-3">
                                <label for="is_locum" class="form-label">Is Locum Staff</label>
                                <div class="form-check">
                                    <input type="checkbox" id="is_locum" name="is_locum" value="1" 
                                        class="form-check-input @error('is_locum') is-invalid @enderror" 
                                        {{ old('is_locum', $employee->is_locum) ? 'checked' : '' }}>
                                    <label for="is_locum" class="form-check-label">
                                        Check if this employee is dedicated locum staff
                                    </label>
                                </div>
                                @error('is_locum')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contract Start Date -->
                            <div class="mb-3" id="contract_dates" style="display: none;">
                                <label for="contract_start_date" class="form-label">Contract Start Date</label>
                                <input type="date" id="contract_start_date" name="contract_start_date"
                                    class="form-control @error('contract_start_date') is-invalid @enderror" 
                                    value="{{ old('contract_start_date', $employee->contract_start_date) }}">
                                @error('contract_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contract End Date -->
                            <div class="mb-3" id="contract_end_dates" style="display: none;">
                                <label for="contract_end_date" class="form-label">Contract End Date</label>
                                <input type="date" id="contract_end_date" name="contract_end_date"
                                    class="form-control @error('contract_end_date') is-invalid @enderror" 
                                    value="{{ old('contract_end_date', $employee->contract_end_date) }}">
                                @error('contract_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="button" class="btn text-white w-100" style="background-color: #2E3A87;" onclick="openConfirmModal('update', '{{ $employee->FirstName }} {{ $employee->LastName }}')">
                                Update Employee
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Employee Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to update this employee?</p>
                <p class="mb-0" style="color: #666; font-size: 0.95rem;"><strong id="employeeName">{{ $employee->FirstName }} {{ $employee->LastName }}</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitEmployeeForm()">Confirm & Update</button>
            </div>
        </div>
    </div>
</div>

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
    });

    function openConfirmModal(action, employeeName) {
        document.getElementById('employeeName').innerText = employeeName || 'Employee';
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
    }

    function submitEmployeeForm() {
        document.getElementById('editEmployeeForm').submit();
    }
</script>
@endsection