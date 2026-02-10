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
            <a href="{{ route('leave_types.index') }}" class="btn btn-primary">Manage Leave Types</a>
            <a href="{{ route('departments.index') }}" class="btn btn-info">Manage Departments</a>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="mb-3 d-flex align-items-center">
        <form id="bulkActionForm" action="{{ route('admin.employees.bulkSendInvitations') }}" method="POST" class="d-inline-flex align-items-center">
            @csrf
            <select name="scope" class="form-select form-select-sm me-2" style="width: auto;">
                <option value="selected">Selected Employees</option>
                <option value="all">All Employees with Email</option>
            </select>
            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Send invitations to selected scope?')">Bulk Send Invitations</button>
        </form>
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
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>Employee Number</th>
                        <th>Name</th>
                        <th>Department/Position</th>
                        <th>Auth Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>
                                <input type="checkbox" name="employee_numbers[]" value="{{ $employee->EmployeeNumber }}" class="employee-checkbox" form="bulkActionForm">
                            </td>
                            <td>{{ $employee->EmployeeNumber }}</td>
                            <td>{{ $employee->FirstName }} {{ $employee->LastName }}<br>
                                <small class="text-muted">{{ $employee->email }}</small>
                            </td>
                            <td>{{ $employee->department->DepartmentName }}<br>
                                <small class="text-muted">{{ $employee->position->PositionName }}</small>
                            </td>
                            <td>
                                @if($employee->last_password_reset_at)
                                    <span class="badge bg-success">Invited</span>
                                @else
                                    <span class="badge bg-secondary">Not Invited</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('employees.edit', $employee->EmployeeNumber) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                    
                                    @if($employee->email)
                                        <form action="{{ route('admin.employees.sendInvitation', $employee->EmployeeNumber) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Send Invitation Email">Invite</button>
                                        </form>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#passwordModal{{ str_replace('-', '', $employee->EmployeeNumber) }}">
                                        Pass
                                    </button>

                                    <form action="{{ route('employees.destroy', $employee->EmployeeNumber) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this employee?')">Del</button>
                                    </form>
                                </div>

                                <!-- Manual Password Modal -->
                                <div class="modal fade" id="passwordModal{{ str_replace('-', '', $employee->EmployeeNumber) }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.employees.manualSetPassword', $employee->EmployeeNumber) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Set Password: {{ $employee->FirstName }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">New Password</label>
                                                        <input type="password" name="password" class="form-control" required minlength="8">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Confirm Password</label>
                                                        <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Password</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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

<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.employee-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
