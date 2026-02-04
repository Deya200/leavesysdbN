@extends('layouts.app')

@section('title', 'Leave Types')

@section('styles')
<style>
    .leave-types-container {
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
    .table thead th:nth-child(6) {  /* Actions column */
        text-align: center;
    }
    
    .table thead th:nth-child(2) {  /* Name column */
        text-align: left;
        padding-left: 20px;
    }
    
    .table thead th:nth-child(3),  /* Paid column */
    .table thead th:nth-child(4),  /* Gender column */
    .table thead th:nth-child(5) {  /* Deducts column */
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
    .table td:nth-child(6) {  /* Actions column */
        text-align: center;
    }
    
    .table td:nth-child(2) {  /* Name column */
        text-align: left;
        padding-left: 20px;
    }
    
    .table td:nth-child(3),  /* Paid column */
    .table td:nth-child(4),  /* Gender column */
    .table td:nth-child(5) {  /* Deducts column */
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

    /* Leave Type Badge */
    .leave-type-badge {
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

    /* Status Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }

    .badge-yes {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .badge-no {
        background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
        color: white;
    }

    .badge-gender {
        background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        color: #212529;
        font-weight: 600;
    }

    /* Gender Icons */
    .gender-male {
        color: #4A5BD9;
    }

    .gender-female {
        color: #e83e8c;
    }

    .gender-both {
        color: #28a745;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .leave-types-container {
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
        
        .badge {
            padding: 4px 10px;
            font-size: 0.8rem;
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
        
        .leave-type-badge {
            padding: 4px 10px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="leave-types-container mt-4">

    <!-- Main Card -->
    <div class="card-custom mb-4">
        <!-- Header -->
        <div class="header-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <!-- Title -->
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <i class="fas fa-calendar-alt fa-lg me-3" style="color: #2E3A87;"></i>
                    <h5 class="mb-0" style="font-weight: 600; color: #2E3A87;">Leave Types Management</h5>
                </div>
                
                <!-- Add New Leave Type Button -->
                <a href="{{ route('leave_types.create') }}" 
                   class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Add Leave Type</span>
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

        <!-- Leave Types Table -->
        <div class="table-card">
            @if ($leaveTypes->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Paid</th>
                                <th>Gender</th>
                                <th>Deducts from Annual</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaveTypes as $leaveType)
                                <tr>
                                    <td class="fw-medium">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="leave-type-badge">
                                            {{ $leaveType->LeaveTypeName }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($leaveType->IsPaidLeave)
                                            <span class="badge badge-yes">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Yes
                                            </span>
                                        @else
                                            <span class="badge badge-no">
                                                <i class="fas fa-times-circle me-1"></i>
                                                No
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $genderIcon = 'fas fa-user';
                                            $genderClass = 'gender-both';
                                            if($leaveType->GenderApplicable === 'Male') {
                                                $genderIcon = 'fas fa-male';
                                                $genderClass = 'gender-male';
                                            } elseif($leaveType->GenderApplicable === 'Female') {
                                                $genderIcon = 'fas fa-female';
                                                $genderClass = 'gender-female';
                                            }
                                        @endphp
                                        <span class="badge badge-gender">
                                            <i class="{{ $genderIcon }} me-1 {{ $genderClass }}"></i>
                                            {{ $leaveType->GenderApplicable }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $deducts = $leaveType->deductsFromAnnual();
                                        @endphp
                                        @if($deducts)
                                            <span class="badge badge-yes">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Yes
                                            </span>
                                        @else
                                            <span class="badge badge-no">
                                                <i class="fas fa-times-circle me-1"></i>
                                                No
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('leave_types.edit', $leaveType->LeaveTypeID) }}" 
                                               class="btn btn-edit" 
                                               title="Edit Leave Type">
                                               <i class="fas fa-edit me-1"></i>
                                               Edit
                                            </a>
                                            <form action="{{ route('leave_types.destroy', $leaveType->LeaveTypeID) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this leave type?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger" 
                                                        title="Delete Leave Type">
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
                        <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">No leave types found</h5>
                    <p class="text-muted mb-4">Start by adding your first leave type to the system</p>
                    <a href="{{ route('leave_types.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Add First Leave Type
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced delete confirmation
        const deleteButtons = document.querySelectorAll('form button[type="submit"].btn-danger');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const leaveTypeName = this.closest('tr').querySelector('.leave-type-badge').textContent;
                
                if (!confirm(`Are you sure you want to delete the leave type "${leaveTypeName}"? This action cannot be undone.`)) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        });
    });
</script>
@endsection