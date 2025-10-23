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
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 20px;
        transition: box-shadow 0.3s ease;
    }

    /* Welcome Card */
    .card-custom[style*="background-color: #2E3A87"] {
        background: linear-gradient(135deg, #2E3A87 0%, #6a1b9a 100%);
    }
    
    .card-custom[style*="background-color: #2E3A87"]:hover {
        box-shadow: 0 4px 20px rgba(46, 58, 135, 0.3);
    }

    /* Table Styling */
    .table {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        overflow: hidden;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    /* Table Header - Purple */
    .table thead tr {
        background: linear-gradient(135deg, #6a1b9a 0%, #4a148c 100%);
        color: white;
        font-weight: 500;
    }

    /* Sortable Header Links */
    .table thead th a {
        color: white;
        text-decoration: none;
        display: block;
        padding: 12px;
        transition: all 0.2s ease;
    }
    
    .table thead th a:hover {
        background-color: rgba(255,255,255,0.1);
        border-radius: 4px;
    }

    /* Table Body */
    .table tbody tr {
        background-color: #ffffff;
        color: #000000;
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table th, .table td {
        padding: 12px;
        vertical-align: middle;
        text-align: center;
        border: none;
    }

    /* Zebra Striping for Better Readability */
    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Buttons */
    .btn-sm {
        font-size: 0.8rem;
        transition: all 0.2s ease;
        padding: 0.35rem 0.75rem;
    }

    .btn[style*="background-color: #2E3A87"] {
        background-color: #6a1b9a;
        border-color: #6a1b9a;
    }
    
    .btn[style*="background-color: #2E3A87"]:hover {
        background-color: #4a148c !important;
        transform: scale(1.03);
    }

    .btn-danger:hover {
        background-color: #c62828 !important;
        transform: scale(1.03);
    }

    /* Search Bar */
    .input-group-text {
        transition: all 0.3s ease;
    }

    .input-group-text:hover {
        background-color: #f1f3ff !important;
    }

    /* Pagination Styling */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    
    .page-item.active .page-link {
        background-color: #6a1b9a;
        border-color: #6a1b9a;
    }
    
    .page-link {
        color: #6a1b9a;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        margin: 0 2px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .page-link:hover {
        color: #4a148c;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .page-item.disabled .page-link {
        color: #6c757d;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            border-radius: 8px;
        }
        
        .table th, .table td {
            padding: 8px;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="manage-container">

<!-- Page Header with Search and Add Button -->
    <div class="card-custom mb-4 p-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <!-- Title -->
            <h5 class="mb-3 mb-md-0" style="font-weight: 600; color: #2E3A87;">Employee List</h5>
            
            <!-- Search and Add Button Container -->
            <div class="d-flex flex-column flex-md-row gap-3">
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
                   class="btn btn-primary" 
                   style="background-color: #6a1b9a; border-color: #6a1b9a; white-space: nowrap;">
                    <i class="fas fa-plus me-1"></i> Add Employee
                </a>
            </div>
        </div>
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
                            <tr class="employee-row">
                                <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                                <td>{{ $employee->EmployeeNumber }}</td>
                                <td>{{ $employee->FirstName }}</td>
                                <td>{{ $employee->LastName }}</td>
                                <td>{{ $employee->Gender }}</td>
                                <td>{{ $employee->department->DepartmentName ?? 'N/A' }}</td>
                                <td>{{ $employee->grade->GradeName ?? 'N/A' }}</td>
                                <td>{{ $employee->position->PositionName ?? 'N/A' }}</td>
                                <td>{{ $employee->role->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('employees.edit', $employee->EmployeeNumber) }}" 
                                           class="btn btn-sm" 
                                           style="background-color: #2E3A87; color: white;">
                                           <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('employees.destroy', $employee->EmployeeNumber) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $employees->onEachSide(1)->links() }}
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
