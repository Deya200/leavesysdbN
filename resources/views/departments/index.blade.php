@extends('layouts.app')

@section('title', 'Departments')

@section('styles')
<style>
    .departments-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }
    .btn-edit {
    background-color: #2E3A87;
    color: white;
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 4px;
    border: none;
}
.btn-edit:hover {
    background-color:rgb(31, 117, 53);
    color: white;
}


    .card-custom {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 20px;
    }

    .table {
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(68, 9, 219, 0.1);
    }

    .table thead {
        background-color: #2E3A87;
        color: #ffffff;
    }

    .table tbody tr {
        background-color: #ffffff;
        color: #000000;
    }

    .table th, .table td {
        padding: 12px;
        text-align: center;
        vertical-align: middle;
        border: none;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .btn-sm {
        font-size: 0.8rem;
    }

    .form-control {
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="departments-container">

    <!-- Welcome Section -->
    <div class="card-custom text-center mb-4" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Welcome, {{ auth()->user()->FirstName ?? 'Admin' }}!</h4>
        <p class="mb-2">Here you can view, edit, and manage all departments and their supervisors.</p>
    </div>

   <!-- Search Bar & Add Button Row -->
<div class="row mb-4" style="max-width: 800px; margin: 0 auto;">
    <div class="col-12 d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <input 
                type="text"
                id="departmentSearch"
                class="form-control"
                placeholder="Search by Department or Supervisor"
            >
        </div>
        <button type="button" class="btn btn-success flex-shrink-0" data-bs-toggle="modal" data-bs-target="#departmentModal" onclick="openDepartmentModal()">
            Add New Department
        </button>
    </div>
</div>



   
    <!-- Departments Table -->
    <div class="card-custom">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Department Name</th>
                        <th>Supervisor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $department)
                        <tr class="department-row">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $department->DepartmentName }}</td>
                            <td>
                                @if ($department->SupervisorID)
                                    {{ $department->supervisor->FirstName ?? 'N/A' }} {{ $department->supervisor->LastName ?? '' }}
                                @else
                                    Not Assigned
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#departmentModal"
                                        onclick="openDepartmentModal('{{ route('departments.update', $department->DepartmentID) }}', '{{ addslashes($department->DepartmentName) }}', '{{ $department->SupervisorID }}')">Edit</button>
                                    <form action="{{ route('departments.destroy', $department->DepartmentID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No departments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="departmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="departmentForm" method="POST" action="{{ route('departments.store') }}" class="modal-content">
                @csrf
                <input type="hidden" id="departmentMethod" name="_method" value="POST">
                <div class="modal-header"><h5 class="modal-title" id="departmentModalTitle">Add Department</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label for="departmentName" class="form-label">Department Name</label><input type="text" id="departmentName" name="DepartmentName" class="form-control" required></div>
                    <div><label for="departmentSupervisor" class="form-label">Supervisor</label><select id="departmentSupervisor" name="SupervisorID" class="form-select"><option value="">Select Supervisor (Optional)</option>@foreach($employees as $employee)<option value="{{ $employee->EmployeeNumber }}">{{ $employee->FirstName }} {{ $employee->LastName }}</option>@endforeach</select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Save Department</button></div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openDepartmentModal(updateUrl = '', departmentName = '', supervisorId = '') {
    const form = document.getElementById('departmentForm');
    form.action = updateUrl || '{{ route('departments.store') }}';
    document.getElementById('departmentMethod').value = updateUrl ? 'PUT' : 'POST';
    document.getElementById('departmentModalTitle').textContent = updateUrl ? 'Edit Department' : 'Add Department';
    document.getElementById('departmentName').value = departmentName;
    document.getElementById('departmentSupervisor').value = supervisorId;
}

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('departmentSearch');
    const departmentRows = document.querySelectorAll('.department-row');

    if (searchInput && departmentRows.length > 0) {
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.trim().toLowerCase();

            departmentRows.forEach(row => {
                // Get department name and supervisor name from columns
                const departmentName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const supervisorName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                if (
                    departmentName.includes(searchTerm) ||
                    supervisorName.includes(searchTerm)
                ) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection
