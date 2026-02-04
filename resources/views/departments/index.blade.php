@extends('layouts.app')

@section('title', 'Departments')

@section('styles')
<style>
    .departments-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    /* Card Styles */
    .card-custom {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e9ecef;
        padding: 0;
    }

    /* Creamy White Header */
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

    /* Column Alignment */
    .table thead th:nth-child(1),  /* # column */
    .table thead th:nth-child(4) {  /* Actions column */
        text-align: center;
    }
    
    .table thead th:nth-child(2) {  /* Department Name column */
        text-align: left;
        padding-left: 20px;
    }
    
    .table thead th:nth-child(3) {  /* Supervisor column */
        text-align: center;
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
        border: none;
        font-size: 0.95rem;
    }

    /* Column Alignment for Table Body */
    .table td:nth-child(1),  /* # column */
    .table td:nth-child(4) {  /* Actions column */
        text-align: center;
    }
    
    .table td:nth-child(2) {  /* Department Name column */
        text-align: left;
        padding-left: 20px;
    }
    
    .table td:nth-child(3) {  /* Supervisor column */
        text-align: center;
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

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(40, 167, 69, 0.2);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        background: linear-gradient(135deg, #218838 0%, #1e9c7a 100%);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(220, 53, 69, 0.2);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
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

    /* Department Badge - Aligned Left */
    .dept-badge {
        background-color: #f0f2ff;
        color: #2E3A87;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-block;
        border-left: 4px solid #4A5BD9;
        text-align: left;
        width: auto;
    }

    /* Supervisor Badge */
    .supervisor-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }

    /* No Supervisor Badge */
    .no-supervisor-badge {
        background-color: #f8f9fa;
        color: #6c757d;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
        border: 1px solid #dee2e6;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .departments-container {
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
        
        .table td:nth-child(2) {
            padding-left: 15px;
        }
        
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 5px !important;
        }
        
        .btn-sm {
            width: 100%;
        }
        
        .input-group {
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .table-responsive {
            border-radius: 8px;
        }
        
        .btn {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
        
        .table td:nth-child(2) {
            padding-left: 12px;
        }
        
        .dept-badge {
            padding: 4px 10px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="departments-container mt-4">

    <!-- Main Card -->
    <div class="card-custom mb-4">
        <!-- Header -->
        <div class="header-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <!-- Title -->
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <i class="fas fa-building fa-lg me-3" style="color: #2E3A87;"></i>
                    <h5 class="mb-0" style="font-weight: 600; color: #2E3A87;">Department Management</h5>
                </div>
                
                <!-- Search and Add Button Container -->
                <div class="d-flex flex-column flex-md-row gap-3 align-items-start align-items-md-center">
                    <!-- Search Bar -->
                    <div class="flex-grow-1" style="min-width: 250px;">
                        <div class="input-group">
                            <input 
                                type="text" 
                                id="departmentSearch" 
                                class="form-control" 
                                placeholder="Search departments or supervisors..."
                            >
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Add New Department Button -->
                    <a href="{{ route('departments.create') }}" 
                       class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Add Department</span>
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

        <!-- Departments Table -->
        <div class="table-card">
            @if ($departments->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department Name</th>
                                <th>Supervisor</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr class="department-row">
                                    <td class="fw-medium">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="dept-badge">
                                            {{ $department->DepartmentName }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($department->SupervisorID && $department->supervisor)
                                            <span class="supervisor-badge">
                                                <i class="fas fa-user-tie me-1"></i>
                                                {{ $department->supervisor->FirstName }} {{ $department->supervisor->LastName }}
                                            </span>
                                        @else
                                            <span class="no-supervisor-badge">
                                                <i class="fas fa-user-slash me-1"></i>
                                                Not Assigned
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('departments.edit', $department->DepartmentID) }}" 
                                               class="btn btn-success" 
                                               title="Edit Department">
                                               <i class="fas fa-edit me-1"></i>
                                               Edit
                                            </a>
                                            <form action="{{ route('departments.destroy', $department->DepartmentID) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this department?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger" 
                                                        title="Delete Department">
                                                    <i class="fas fa-trash-alt me-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-building fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">No departments found</h5>
                    <p class="text-muted mb-4">Start by adding your first department to the system</p>
                    <a href="{{ route('departments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Add First Department
                    </a>
                </div>
            @endif
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
            let visibleCount = 0;
            
            departmentRows.forEach(row => {
                const departmentName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const supervisorName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                if (
                    departmentName.includes(searchTerm) ||
                    supervisorName.includes(searchTerm)
                ) {
                    row.style.display = 'table-row';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show no results message if needed
            const noResults = document.getElementById('noResultsMessage');
            if (visibleCount === 0 && searchTerm !== '') {
                if (!noResults) {
                    const tableBody = document.querySelector('tbody');
                    const messageRow = document.createElement('tr');
                    messageRow.id = 'noResultsMessage';
                    messageRow.innerHTML = `
                        <td colspan="4" class="text-center py-4">
                            <i class="fas fa-search fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No departments found matching "${searchTerm}"</p>
                        </td>
                    `;
                    tableBody.appendChild(messageRow);
                }
            } else if (noResults) {
                noResults.remove();
            }
        });

        // Add loading animation
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                searchInput.classList.remove('search-loading');
            }, 300);
        });
    }

    // Enhanced delete confirmation
    const deleteButtons = document.querySelectorAll('form button[type="submit"].btn-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const departmentName = this.closest('tr').querySelector('.dept-badge').textContent;
            
            if (!confirm(`Are you sure you want to delete the department "${departmentName}"? This action cannot be undone.`)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
});
</script>
@endsection