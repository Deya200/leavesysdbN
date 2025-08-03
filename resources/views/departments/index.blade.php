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
        <a href="{{ route('departments.create') }}" class="btn btn-success flex-shrink-0">
            Add New Department
        </a>
    </div>
</div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

   
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
                                    <a href="{{ route('departments.edit', $department->DepartmentID) }}" class="btn btn-success">Edit</a>
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
</div>
@endsection

@section('scripts')
<script>
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
