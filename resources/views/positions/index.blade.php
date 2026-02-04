@extends('layouts.app')

@section('title', 'Manage Positions')

@section('styles')
<style>
    .positions-container {
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
    
    .table thead th:nth-child(2) {  /* Position Name column */
        text-align: left;
        padding-left: 20px;
    }
    
    .table thead th:nth-child(3) {  /* Grade column */
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
    
    .table td:nth-child(2) {  /* Position Name column */
        text-align: left;
        padding-left: 20px;
    }
    
    .table td:nth-child(3) {  /* Grade column */
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

    .btn-edit {
        background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        font-weight: 600;
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
        padding: 8px 16px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        color: white;
    }

    /* Search Bar */
    .input-group {
        max-width: 600px;
        margin: 0 auto;
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

    /* Position Badge */
    .position-badge {
        background-color: #f0f2ff;
        color: #2E3A87;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-block;
        border-left: 4px solid #4A5BD9;
        text-align: left;
    }

    /* Grade Badge */
    .grade-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }

    /* See More/Less Buttons */
    .see-more-btn {
        margin-top: 20px;
    }

    .btn-outline-primary {
        border-color: #2E3A87;
        color: #2E3A87;
    }

    .btn-outline-primary:hover {
        background-color: #2E3A87;
        color: white;
        border-color: #2E3A87;
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
        border-color: #6c757d;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .positions-container {
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
        
        .position-badge {
            padding: 4px 10px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="positions-container mt-4">

    <!-- Main Card -->
    <div class="card-custom mb-4">
        <!-- Header -->
        <div class="header-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <!-- Title -->
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <i class="fas fa-briefcase fa-lg me-3" style="color: #2E3A87;"></i>
                    <h5 class="mb-0" style="font-weight: 600; color: #2E3A87;">Position Management</h5>
                </div>
                
                <!-- Add New Position Button -->
                <a href="{{ route('positions.create') }}" 
                   class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Add Position</span>
                </a>
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

        <!-- Search Form -->
        <div class="mx-3 mt-3">
            <form action="{{ route('positions.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search positions by name or grade..." 
                           value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Positions Table -->
        <div class="table-card">
            @if ($positions->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Position Name</th>
                                <th>Grade</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="positionsTableBody">
                            @foreach ($positions as $position)
                                <tr class="position-row" @if($loop->index >= 10) style="display: none;" @endif>
                                    <td class="fw-medium">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="position-badge">
                                            {{ $position->PositionName }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($position->grade)
                                            <span class="grade-badge">
                                                <i class="fas fa-chart-line me-1"></i>
                                                {{ $position->grade->GradeName }}
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Not Assigned
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('positions.edit', $position->PositionID) }}" 
                                               class="btn btn-edit" 
                                               title="Edit Position">
                                               <i class="fas fa-edit me-1"></i>
                                               Edit
                                            </a>
                                            <form action="{{ route('positions.destroy', $position->PositionID) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this position?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger" 
                                                        title="Delete Position">
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

                <!-- See More/Less Buttons -->
                @if($positions->count() > 10)
                <div class="text-center see-more-btn">
                    <button id="seeMoreBtn" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-chevron-down me-1"></i>
                        See More
                    </button>
                    <button id="seeLessBtn" class="btn btn-outline-secondary btn-sm" style="display: none;">
                        <i class="fas fa-chevron-up me-1"></i>
                        See Less
                    </button>
                </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-briefcase fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">No positions found</h5>
                    <p class="text-muted mb-4">
                        @if($search)
                            No results found for "{{ $search }}"
                        @else
                            Start by adding your first position to the system
                        @endif
                    </p>
                    <a href="{{ route('positions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Add First Position
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
        const rows = document.querySelectorAll('.position-row');
        const seeMoreBtn = document.getElementById('seeMoreBtn');
        const seeLessBtn = document.getElementById('seeLessBtn');

        if (seeMoreBtn && seeLessBtn) {
            rows.forEach((row, index) => {
                row.style.display = index < 10 ? 'table-row' : 'none';
            });

            seeMoreBtn.addEventListener('click', () => {
                rows.forEach(row => row.style.display = 'table-row');
                seeMoreBtn.style.display = 'none';
                seeLessBtn.style.display = 'inline-block';
            });

            seeLessBtn.addEventListener('click', () => {
                rows.forEach((row, index) => {
                    row.style.display = index < 10 ? 'table-row' : 'none';
                });
                seeMoreBtn.style.display = 'inline-block';
                seeLessBtn.style.display = 'none';
            });
        }

        // Enhanced delete confirmation
        const deleteButtons = document.querySelectorAll('form button[type="submit"].btn-danger');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const positionName = this.closest('tr').querySelector('.position-badge').textContent;
                
                if (!confirm(`Are you sure you want to delete the position "${positionName}"? This action cannot be undone.`)) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        });
    });
</script>
@endsection