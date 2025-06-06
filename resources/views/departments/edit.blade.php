@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Edit Department</h2>

    <!-- Form -->
    <form method="POST" action="{{ route('departments.update', $department->DepartmentID) }}">
        @csrf
        @method('PUT')

        <!-- Department Name -->
        <div class="form-group mb-3">
            <label for="DepartmentName" class="form-label">Department Name</label>
            <input type="text" name="DepartmentName" id="DepartmentName" class="form-control" value="{{ old('DepartmentName', $department->DepartmentName) }}" required>
        </div>

        <!-- Supervisor Dropdown -->
        <div class="form-group mb-3">
            <label for="SupervisorID" class="form-label">Supervisor</label>
            <select name="SupervisorID" id="SupervisorID" class="form-select">
                <option value="" disabled>Select Supervisor</option>
                <!-- Dynamic options populated via JavaScript -->
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Department</button>
    </form>
</div>

<!-- JavaScript for Dynamic Filtering -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const supervisorDropdown = document.querySelector('#SupervisorID');
        const departmentID = "{{ $department->DepartmentID }}"; // Current department ID

        // Fetch employees for the department
        fetch(`/departments/${departmentID}/employees`)
            .then(response => response.json())
            .then(data => {
                supervisorDropdown.innerHTML = '<option value="" disabled>Select Supervisor</option>';

                data.forEach(employee => {
                    supervisorDropdown.innerHTML += `
                        <option value="${employee.EmployeeNumber}" ${employee.EmployeeNumber == "{{ old('SupervisorID', $department->SupervisorID) }}" ? 'selected' : ''}>
                            ${employee.FirstName} ${employee.LastName}
                        </option>
                    `;
                });
            })
            .catch(error => console.error('Error fetching employees:', error));
    });
</script>
@endsection
