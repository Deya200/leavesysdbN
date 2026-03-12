@extends('layouts.app')
@section('page_title', 'Employees')
@section('title', 'Manage Employees')

@section('styles')
    <style>
        /* Main Container */
        .manage-container {
            /* Layout handled globally */
        }

        /* Cards */
        .card-custom {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            background-color: rgba(255, 255, 255, 0.1);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table th,
        .table td {
            padding: 12px;
            vertical-align: middle;
            text-align: center;
            border: none;
            word-break: normal; /* Do not cut words */
        }

        /* Specific Cell Refinements */
        .col-emp-num, .col-grade {
            white-space: nowrap; /* Single line */
        }

        .col-position {
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: normal;
            line-height: 1.4;
            min-width: 150px; /* Suggest more width for position */
            max-width: 250px;
            text-align: center;
            margin: 0 auto;
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

            .table th,
            .table td {
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
                <!-- Search and Bulk Action Container -->
                <div class="d-flex flex-column flex-md-row gap-3">
                    <!-- Bulk Actions -->
                    <div id="bulkActions" style="display: none;" class="animate__animated animate__fadeIn">
                        <form action="{{ route('admin.employees.bulkSendInvitations') }}" method="POST" id="bulkInviteForm">
                            @csrf
                            <input type="hidden" name="scope" value="selected">
                            <button type="button" onclick="submitBulkInvite()" class="btn btn-warning btn-sm">
                                <i class="fas fa-paper-plane me-1"></i> Send Selected Invites
                            </button>
                        </form>
                    </div>

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('employees.index') }}" class="flex-grow-1"
                        style="min-width: 250px;">
                        <div class="input-group">
                            <input type="text" name="search" id="employeeSearch" class="form-control"
                                placeholder="Search employees..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Add New Employee Button -->
                    <a href="{{ route('employees.create') }}" class="btn btn-primary"
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
                                <th><input type="checkbox" id="selectAll"></th>
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
                                    <td><input type="checkbox" class="employee-checkbox" name="selected_employees[]"
                                            value="{{ $employee->EmployeeNumber }}"></td>
                                    <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                                    <td class="col-emp-num">{{ $employee->EmployeeNumber }}</td>
                                    <td>{{ $employee->FirstName }}</td>
                                    <td>{{ $employee->LastName }}</td>
                                    <td>{{ $employee->Gender }}</td>
                                    <td>{{ $employee->department->DepartmentName ?? 'N/A' }}</td>
                                    <td class="col-grade">{{ $employee->grade->GradeName ?? 'N/A' }}</td>
                                    <td style="width: 200px;">
                                        <div class="col-position">
                                            {{ $employee->position->PositionName ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>{{ $employee->role->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <!-- Invitation -->
                                            @if($employee->email)
                                                <form action="{{ route('admin.employees.sendInvitation', $employee->EmployeeNumber) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-info text-white"
                                                        title="Send Invitation">
                                                        <i class="fas fa-envelope-open-text"></i>
                                                    </button>
                                                </form>
                                            @endif



                                            <a href="{{ route('employees.edit', $employee->EmployeeNumber) }}" class="btn btn-sm"
                                                style="background-color: #2E3A87; color: white;" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('employees.destroy', $employee->EmployeeNumber) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
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
            // Bulk Selection Logic
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.employee-checkbox');
            const bulkActions = document.getElementById('bulkActions');

            function updateBulkHeader() {
                const anyChecked = Array.from(checkboxes).some(cb =\u003e cb.checked);
                bulkActions.style.display = anyChecked ? 'block' : 'none';
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb =\u003e cb.checked = selectAll.checked);
                    updateBulkHeader();
                });
            }

            checkboxes.forEach(cb =\u003e {
                cb.addEventListener('change', updateBulkHeader);
            });
        });



        function submitBulkInvite() {
            const selected = Array.from(document.querySelectorAll('.employee-checkbox:checked')).map(cb =\u003e cb.value);
            if (selected.length === 0) return;

            const form = document.getElementById('bulkInviteForm');
            // Clear existing hidden inputs
            form.querySelectorAll('input[name="employee_numbers[]"]').forEach(el =\u003e el.remove());

            selected.forEach(num =\u003e {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'employee_numbers[]';
                input.value = num;
                form.appendChild(input);
            });

            form.submit();
        }
    </script>
@endsection