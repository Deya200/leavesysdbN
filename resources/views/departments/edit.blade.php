@extends('layouts.app')

@section('title', 'Edit Department')

@section('styles')
<style>
    .dashboard-container {
        max-width: 800px;
        margin: auto;
        padding: 20px;
    }

    .card-custom {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
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
<div class="dashboard-container">
    <div class="card-custom">
       <h4 class="fw-bold text-center mb-4" style="color: black;">Edit Department</h4>


        <form method="POST" action="{{ route('departments.update', $department->DepartmentID) }}">
            @csrf
            @method('PUT')

            <!-- Department Name -->
            <div class="mb-3">
                <label for="DepartmentName" class="form-label">Department Name</label>
                <input type="text" name="DepartmentName" id="DepartmentName"
                       class="form-control @error('DepartmentName') is-invalid @enderror"
                       value="{{ old('DepartmentName', $department->DepartmentName) }}" required>
                @error('DepartmentName')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Supervisor Dropdown -->
            <div class="mb-3">
                <label for="SupervisorID" class="form-label">Supervisor</label>
                <select name="SupervisorID" id="SupervisorID" class="form-select">
                    <option value="" disabled selected>Select Supervisor</option>
                    <!-- Populated via JS -->
                </select>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary w-100">Update Department</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const supervisorDropdown = document.querySelector('#SupervisorID');
        const departmentID = "{{ $department->DepartmentID }}";

        fetch(`/departments/${departmentID}/employees`)
            .then(response => response.json())
            .then(data => {
                supervisorDropdown.innerHTML = '<option value="" disabled>Select Supervisor</option>';

                data.forEach(employee => {
                    const selected = employee.EmployeeNumber == "{{ old('SupervisorID', $department->SupervisorID) }}" ? 'selected' : '';
                    supervisorDropdown.innerHTML += `
                        <option value="${employee.EmployeeNumber}" ${selected}>
                            ${employee.FirstName} ${employee.LastName}
                        </option>
                    `;
                });
            })
            .catch(error => console.error('Error fetching employees:', error));
    });
</script>
@endsection
