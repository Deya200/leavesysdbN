@extends('layouts.app')

@section('title', 'Manage Employees')

@section('header')

    <!-- Search Form -->
    <form action="{{ route('employees.index') }}" method="GET" class="mb-4" style="width:75%;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Department Name or Supervisor Name" value="{{ $search ?? '' }}" >
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

@endsection

@section('content')
<div class="container mt-5">

    <!-- Employees Table -->
    @if ($employees->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-primary text-center">
                    <tr>
                        <th id="main-table" style="border: none;">No</th>
                        <th id="main-table" style="border: none;">Employee Number</th>
                        <th id="main-table" style="border: none;">First Name</th>
                        <th id="main-table" style="border: none;">Last Name</th>
                        <th id="main-table" style="border: none;">Gender</th>
                        <th id="main-table" style="border: none;">Department</th>
                        <th id="main-table" style="border: none;">Grade</th>
                        <th id="main-table" style="border: none;">Position</th>
                        <th id="main-table" style="border: none;">Role</th> <!-- NEW Role Column -->
                        <th id="main-table" style="border: none;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td style="border: none;">{{ $loop->iteration }}</td>
                            <td style="border: none;">{{ $employee->EmployeeNumber }}</td>
                            <td style="border: none;">{{ $employee->FirstName }}</td>
                            <td style="border: none;">{{ $employee->LastName }}</td>
                            <td style="border: none;">{{ $employee->Gender }}</td>
                            <td style="border: none;">{{ $employee->department->DepartmentName ?? 'N/A' }}</td>
                            <td style="border: none;">{{ $employee->grade->GradeName ?? 'N/A' }}</td>
                            <td style="border: none;">{{ $employee->position->PositionName ?? 'N/A' }}</td>
                            <td style="border: none;">
                                {{ $employee->role_id == 1 ? 'Admin' : 'Employee' }}
                            </td> <!-- NEW: Display Role -->
                            <td style="border: none;">
                                <div class="d-flex gap-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('employees.edit', $employee->EmployeeNumber) }}" class="btn btn-warning btn-sm">Edit</a>

                                    <!-- Delete Form -->
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
        </div>
    @else
        <div class="alert alert-info text-center">
            No employees found. Click the "Add New Employee" button above to add one.
        </div>
    @endif
</div>
@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('tr.clickable-row');

        rows.forEach(row => {
            row.addEventListener('mouseover', function () {
                // Remove the 'hover-up' class from all rows
                rows.forEach(r => r.classList.remove('hover-up'));

                // Add the 'hover-up' class to the clicked row
                this.classList.add('hover-up');
            });
        });
    });
</script>

@endsection
