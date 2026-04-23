@extends('layouts.app')

@section('title', 'Archive Leave Requests')

@section('styles')
<style>
    .archive-card {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead {
        background: linear-gradient(135deg, #6a1b9a 0%, #4a148c 100%);
        color: white;
    }

    .table tbody tr {
        border-bottom: 1px solid #e0e0e0;
    }

    .table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .year-selector {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .select-icon {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .alert-info {
        background-color: #e3f2fd;
        border-color: #1976d2;
        color: #1565c0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold">
                <i class="fas fa-archive me-2 text-primary"></i> Archive Leave Requests
            </h1>
            <p class="text-muted">Annually archive completed leave requests and return days to employees</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('leave_requests.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Requests
            </a>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Archive Leave Requests:</strong> All approved leave requests are listed below. Select and archive them anytime. Approved annual leaves will automatically return used days to employees.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Year Selector Card (Optional) -->
    <div class="card archive-card mb-4">
        <div class="card-body">
            <div class="year-selector">
                <label for="yearSelect" class="form-label fw-bold mb-0">Filter by Year (Optional):</label>
                <select id="yearSelect" class="form-select" style="max-width: 150px;">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-primary" onclick="filterByYear()">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
                @if($selectedYear)
                    <button class="btn btn-secondary" onclick="clearFilter()">
                        <i class="fas fa-times me-2"></i> Clear Filter
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Stats -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card archive-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">Total Requests ({{ $selectedYear }})</h6>
                            <h3 class="fw-bold text-primary">{{ $leaveRequests->total() }}</h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">Annual Leaves</h6>
                            <h3 class="fw-bold text-success">
                                {{ $leaveRequests->pluck('leaveType')->filter(fn($lt) => $lt->deductsFromAnnual())->count() }}
                            </h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">Days to Return</h6>
                            <h3 class="fw-bold text-warning">
                                {{ $leaveRequests
                                    ->filter(fn($lr) => $lr->RequestStatus === 'Approved' && $lr->leaveType->deductsFromAnnual())
                                    ->sum('TotalDays') ?? 0 }}
                            </h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">Employees Affected</h6>
                            <h3 class="fw-bold text-info">
                                {{ $leaveRequests->pluck('EmployeeNumber')->unique()->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Form -->
    <form action="{{ route('leave_requests.bulk_archive') }}" method="POST" id="archiveForm">
        @csrf

        <!-- Bulk Actions -->
        <div class="card archive-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <label class="form-check-label">
                            <input type="checkbox" id="selectAllCheckbox" class="form-check-input" onchange="toggleAllCheckboxes()">
                            <strong class="ms-2">Select All Requests</strong>
                        </label>
                        <span class="text-muted ms-3" id="selectedCount">(0 selected)</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-success btn-lg me-2" style="min-width: 200px;" onclick="return confirm('Archive ' + document.querySelectorAll('input[name=leave_request_ids[]]:checked').length + ' leave request(s)? Days will be returned to employees.')">
                            <i class="fas fa-archive me-2"></i> Archive Selected
                        </button>
                        <button type="reset" class="btn btn-secondary btn-lg" style="min-width: 150px;">
                            <i class="fas fa-times me-2"></i> Clear Selection
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Requests Table -->
        @if($leaveRequests->count() > 0)
            <div class="card archive-card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">
                                    <input type="checkbox" id="headerCheckbox" class="form-check-input" onchange="toggleAllCheckboxes()">
                                </th>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Dates</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Days to Return</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveRequests as $request)
                                @php
                                    $daysToReturn = ($request->RequestStatus === 'Approved' && $request->leaveType->deductsFromAnnual()) 
                                        ? $request->TotalDays 
                                        : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="leave_request_ids[]" value="{{ $request->LeaveRequestID }}" 
                                            class="form-check-input requestCheckbox" onchange="updateSelectedCount()">
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</div>
                                        <small class="text-muted">{{ $request->employee->EmployeeNumber }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $request->leaveType->LeaveTypeName }}</span>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $request->StartDate->format('M d') }} - {{ $request->EndDate->format('M d, Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $request->TotalDays }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($request->RequestStatus === 'Approved')
                                                bg-success
                                            @elseif($request->RequestStatus === 'Rejected')
                                                bg-danger
                                            @else
                                                bg-warning text-dark
                                            @endif">
                                            {{ $request->RequestStatus }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($daysToReturn > 0)
                                            <span class="badge bg-success">+{{ $daysToReturn }} days</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $leaveRequests->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="card archive-card text-center py-5">
                <i class="fas fa-inbox text-muted fs-1 mb-3"></i>
                <h5 class="text-muted">No Leave Requests to Archive</h5>
                <p class="text-muted">All leave requests for {{ $selectedYear }} have been archived or are still pending.</p>
            </div>
        @endif
    </form>

    <!-- Help Section -->
    <div class="card archive-card mt-5 bg-light">
        <div class="card-body">
            <h5 class="fw-bold mb-3">
                <i class="fas fa-question-circle me-2"></i> How This Works
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary">Annual Leaves</h6>
                    <ul class="small">
                        <li>When you archive <strong>Approved</strong> annual leaves, used days are returned to the employee</li>
                        <li>When you archive <strong>Rejected</strong> annual leaves, no days are affected</li>
                        <li>Used days can be tracked per employee</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary">Other Leave Types</h6>
                    <ul class="small">
                        <li>Archiving does not affect days for configurable leave types</li>
                        <li>Records are preserved for audit purposes</li>
                        <li>Archived requests are hidden from employee dashboards</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function filterByYear() {
        const year = document.getElementById('yearSelect').value;
        window.location.href = `{{ route('leave_requests.archive_manager') }}?year=${year}`;
    }

    function toggleAllCheckboxes() {
        const isChecked = document.getElementById('selectAllCheckbox').checked;
        document.querySelectorAll('input[name="leave_request_ids[]"]').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('input[name="leave_request_ids[]"]:checked').length;
        document.getElementById('selectedCount').textContent = `(${selectedCount} selected)`;
    }
</script>
@endsection

@endsection
