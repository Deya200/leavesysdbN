@extends('layouts.app')

@section('title', 'Employee List')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Employee List</h2>

    <!-- Navigation Buttons -->
    <div class="mb-4 d-flex justify-content-between">
        <a href="{{ route('dashboard') }}" class="btn btn-dark">Dashboard</a>
        <div>
            <a href="{{ route('employees.create') }}" class="btn btn-success">Add New Employee</a>
            <a href="{{ route('leave-types.index') }}" class="btn btn-primary">Manage Leave Types</a>
            <a href="{{ route('departments.index') }}" class="btn btn-info">Manage Departments</a>
        </div>
    </div>

    <!-- Employee Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Employees</h5>
        </div>
        <div class="card-body bg-light">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Employee Number</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Department</th>
                        <th>Grade</th>
                        <th>Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->EmployeeNumber }}</td>
                            <td>{{ $employee->FirstName }}</td>
                            <td>{{ $employee->LastName }}</td>
                            <td>{{ $employee->Gender }}</td>
                            <td>{{ $employee->DateOfBirth }}</td>
                            <td>{{ $employee->department->DepartmentName }}</td>
                            <td>{{ $employee->grade->GradeName }}</td>
                            <td>{{ $employee->position->PositionName }}</td>
                            <td>
                                <a href="{{ route('employees.edit', $employee->EmployeeNumber) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('employees.destroy', $employee->EmployeeNumber) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
