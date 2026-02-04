@extends('layouts.app')

@section('title', 'Manage Employees')

@section('styles')
<style>
    /* Main Container */
    .manage-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    /* Cards */
    .card-custom {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e9ecef;
        padding: 0;
    }

    /* Header Card - Creamy White */
    .header-card {
        background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
        color: #2E3A87;
        padding: 24px 30px;
        border-bottom: 2px solid #e9ecef;
        border-radius: 12px 12px 0 0;
        position: relative;
        overflow: hidden;
    }

    .header-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    }

    /* Table Container Card */
    .table-card {
        padding: 25px;
        background-color: #f8fafc;
    }

    /* Table Styling */
    .table {
        background-color: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #e9ecef;
    }

    /* Table Header */
    .table thead tr {
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        color: white;
        font-weight: 600;
        height: 56px;
    }

    .table thead th {
        padding: 16px;
        border: none;
        font-size: 0.95rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Table Body */
    .table tbody tr {
        background-color: #ffffff;
        color: #333;
        transition: all 0.3s ease;
        border-bottom: 1px solid #f1f3f5;
    }

    .table tbody tr:hover {
        background-color: #f8faff !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(46, 58, 135, 0.1);
    }

    .table tbody tr:last-child {
        border-bottom: none;
    }

    .table td {
        padding: 16px;
        vertical-align: middle;
        text-align: center;
        border: none;
        font-size: 0.95rem;
    }

    /* Zebra Striping */
    .table tbody tr:nth-child(even) {
        background-color: #f9fafc;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        border: none;
        padding: 10px 20px;
        font-size: 0.95rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(46, 58, 135, 0.2);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(46, 58, 135, 0.3);
        background: linear-gradient(135deg, #26327A 0%, #3D4DC7 100%);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-edit:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(46, 58, 135, 0.3);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        color: white;
    }

    /* Search Bar */
    .input-group {
        max-width: 400px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #d1d9e6;
        padding: 10px 15px;
        font-size: 0.95rem;
        transition: all 0.3s;
        background-color: white;
    }

    .form-control:focus {
        border-color: #2E3A87;
        box-shadow: 0 0 0 3px rgba(46, 58, 135, 0.1);
        outline: none;
    }

    .input-group-text {
        background-color: white;
        border: 1px solid #d1d9e6;
        border-left: none;
        color: #2E3A87;
        transition: all 0.3s ease;
    }

    .input-group-text:hover {
        background-color: #f1f3ff;
    }

    /* Pagination Styling */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        border-color: #2E3A87;
        color: white;
    }
    
    .page-link {
        color: #2E3A87;
        border: 1px solid #dee2e6;
        padding: 8px 16px;
        margin: 0 3px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .page-link:hover {
        color: #2E3A87;
        background-color: #f8faff;
        border-color: #2E3A87;
        transform: translateY(-1px);
    }
    
    .page-item.disabled .page-link {
        color: #adb5bd;
        background-color: #f8f9fa;
    }

    /* Employee ID Badge */
    .employee-id {
        background-color: #f0f2ff;
        color: #2E3A87;
        padding: 4px 12px;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
    }

    /* Alert Messages */
    .alert {
        border-radius: 8px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .alert-success {
        background-color: #e8f5e9;
        color: #2e7d32;
        border-left: 4px solid #4caf50;
    }

    .alert-info {
        background-color: #e3f2fd;
        color: #1565c0;
        border-left: 4px solid #2196f3;
    }

    /* Role Badges */
    .badge-role {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }

    .badge-admin {
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        color: white;
    }

    .badge-employee {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .manage-container {
            padding: 15px;
        }
        
        .header-card {
            padding: 20px;
        }
        
        .table-card {
            padding: 15px;
        }
        
        .table th, .table td {
            padding: 12px 8px;
            font-size: 0.9rem;
        }
        
        .btn-sm {
            padding: 6px 10px;
            font-size: 0.8rem;
        }
        
        .input-group {
            max-width: 100%;
        }
        
        .page-link {
            padding: 6px 12px;
            margin: 0 2px;
        }
    }

    @media (max-width: 576px) {
        .table-responsive {
            border-radius: 8px;
        }
        
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 5px !important;
        }
        
        .btn-sm {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="manage-container mt-4">

    <!-- Page Header with Search and Add Button -->
    <div class="card-custom mb-4">
        <div class="header-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <!-- Title -->
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <i class="fas fa-users fa-lg me-3" style="color: #2E3A87;"></i>
                    <h5 class="mb-0" style="font-weight: 600; color: #2E3A87;">Employee List</h5>
                </div>
                
                <!-- Search and Add Button Container -->
                <div class="d-flex flex-column flex-md-row gap-3 align-items-start align-items-md-center">
                    <!-- Search Bar -->
                    <div class="flex-grow-1" style="min-width: 250px;">
                        <div class="input-group">
                            <input 
                                type="text" 
                                name="search" 
                                id="employeeSearch" 
                                class="form-control" 
                                placeholder="Search employees..."
                            >
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Add New Employee Button -->
                    <a href="{{ route('employees.create') }}" 
                       class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Add Employee</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Employee Table -->
    <div class="card-custom">
        <div class="table-card">
            @if ($employees->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle">
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
                                <tr class="employee-row">
                                    <td class="fw-medium">{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <span class="employee-id">
                                            {{ $employee->EmployeeNumber }}
                                        </span>
                                    </td>
                                    <td>{{ $employee->FirstName }}</td>
                                    <td>{{ $employee->LastName }}</td>
                                    <td>{{ $employee->Gender }}</td>
                                    <td>{{ $employee->department->DepartmentName ?? 'N/A' }}</td>
                                    <td>{{ $employee->grade->GradeName ?? 'N/A' }}</td>
                                    <td>{{ $employee->position->PositionName ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $roleClass = 'badge-employee';
                                            if($employee->role_id == 1) $roleClass = 'badge-admin';
                                        @endphp
                                        <span class="badge-role {{ $roleClass }}">
                                            {{ $employee->role->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('employees.edit', $employee->EmployeeNumber) }}" 
                                               class="btn btn-edit" 
                                               title="Edit Employee">
                                               <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('employees.destroy', $employee->EmployeeNumber) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Delete Employee">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $employees->onEachSide(1)->links() }}
                </div>
            @else
                <div class="alert alert-info text-center m-0">
                    <i class="fas fa-user-slash me-2"></i>
                    No employees found. Use the "Add New Employee" button to create one.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Search/Filter Feature
        const searchInput = document.getElementById('employeeSearch');
        const employeeRows = document.querySelectorAll('.employee-row');

        if (searchInput && employeeRows.length > 0) {
            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.trim().toLowerCase();
                employeeRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? 'table-row' : 'none';
                });
            });
        }
    });
</script>
@endsection