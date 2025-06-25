@extends('layouts.app')

@section('title', 'Manage Employees')

@section('styles')
<style>
    .manage-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
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
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table thead {
        background-color: #2E3A87;
        color: white;
    }

    .table tbody tr {
        background-color: #ffffff;
        color: #000000;
    }

    .table th, .table td {
        padding: 12px;
        vertical-align: middle;
        text-align: center;
        border: none;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .btn-sm {
        font-size: 0.8rem;
    }

    .input-group .form-control {
        font-size: 0.9rem;
    }

    .hover-up:hover {
        transform: translateY(-3px);
        transition: transform 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="manage-container">

    <!-- Welcome Section -->
    <div class="card-custom text-center mb-4" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Welcome, {{ auth()->user()->FirstName ?? 'Admin' }}!</h4>
        <p class="mb-2">Here you can manage employees, update details, and assign roles.</p>
    </div>

    <!-- Search Form -->
    <div class="text-center mb-4">
        <form action="{{ route('employees.index') }}" method="GET" style="max-width: 600px; margin: auto;">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by Department or Supervisor Name" value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>

    <!-- Employee Table -->
    <div class="card-custom">
        @if ($employees->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Employee Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>Department</th>
                            <th>Grade</th>
                            <th>Position</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr class="hover-up employee-row" @if($loop->index >= 10) style="display: none;" @endif>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->EmployeeNumber }}</td>
                                <td>{{ $employee->FirstName }}</td>
                                <td>{{ $employee->LastName }}</td>
                                <td>{{ $employee->Gender }}</td>
                                <td>{{ $employee->department->DepartmentName ?? 'N/A' }}</td>
                                <td>{{ $employee->grade->GradeName ?? 'N/A' }}</td>
                                <td>{{ $employee->position->PositionName ?? 'N/A' }}</td>
                                <td>{{ $employee->role_id == 1 ? 'Admin' : 'Employee' }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('employees.edit', $employee->EmployeeNumber) }}" class="btn btn-sm" style="background-color: #2E3A87; color: white;">Edit</a>
                                        <form action="{{ route('employees.destroy', $employee->EmployeeNumber) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-center mt-3">
                    <button id="seeMoreBtn" class="btn btn-outline-primary btn-sm">See More</button>
                    <button id="seeLessBtn" class="btn btn-outline-secondary btn-sm" style="display: none;">See Less</button>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center m-0">
                No employees found. Use the "Add New Employee" button to create one.
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Hover-up effect
        const rows = document.querySelectorAll('tr.hover-up');
        rows.forEach(row => {
            row.addEventListener('mouseover', function () {
                rows.forEach(r => r.classList.remove('hover-up'));
                this.classList.add('hover-up');
            });
        });

        // See More / See Less functionality
        const allRows = document.querySelectorAll('.employee-row');
        const seeMoreBtn = document.getElementById('seeMoreBtn');
        const seeLessBtn = document.getElementById('seeLessBtn');

        if (seeMoreBtn && seeLessBtn) {
            // Initially show first 10, hide others
            allRows.forEach((row, index) => {
                row.style.display = index < 10 ? 'table-row' : 'none';
            });

            seeMoreBtn.addEventListener('click', () => {
                allRows.forEach(row => row.style.display = 'table-row');
                seeMoreBtn.style.display = 'none';
                seeLessBtn.style.display = 'inline-block';
            });

            seeLessBtn.addEventListener('click', () => {
                allRows.forEach((row, index) => {
                    row.style.display = index < 10 ? 'table-row' : 'none';
                });
                seeMoreBtn.style.display = 'inline-block';
                seeLessBtn.style.display = 'none';
            });
        }
    });
</script>
@endsection
