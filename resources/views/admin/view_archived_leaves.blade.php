@extends('layouts.app')

@section('title', 'View Archived Leave Requests')

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

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .alert-info {
        background-color: #e3f2fd;
        border-color: #90caf9;
    }

    .stats-card {
        border-radius: 10px;
        padding: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-number {
        font-size: 24px;
        font-weight: bold;
    }

    .stat-label {
        font-size: 13px;
        opacity: 0.9;
    }

    .badge-archived {
        background-color: #9c27b0;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark">
                <i class="fas fa-history me-2 text-purple"></i> Archived Leave Requests
            </h2>
            <p class="text-muted small">View and restore previously archived leave requests</p>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Archived Leaves:</strong> These are leave requests that have already been archived. You can restore any archived request by clicking the restore button.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <div class="stat-number">{{ $archivedLeaves->total() }}</div>
                <div class="stat-label">Total Archived Leaves</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-number">{{ $archivedLeaves->where('RequestStatus', 'Approved')->count() }}</div>
                <div class="stat-label">Approved & Archived</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stat-number">{{ $archivedLeaves->where('RequestStatus', 'Rejected')->count() }}</div>
                <div class="stat-label">Rejected & Archived</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="stat-number">{{ $archivedLeaves->pluck('employee.EmployeeNumber')->unique()->count() }}</div>
                <div class="stat-label">Unique Employees</div>
            </div>
        </div>
    </div>

    <!-- Year Selector Card -->
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

    <!-- Archived Leaves Table -->
    @if($archivedLeaves->isNotEmpty())
        <div class="card archive-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Employee</th>
                            <th>Leave Type</th>
                            <th>Status</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Archived Date</th>
                            <th class="text-center pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archivedLeaves as $leave)
                            @php
                                $badgeClass = match($leave->RequestStatus) {
                                    'Approved' => 'bg-success-subtle text-success border border-success-subtle',
                                    'Rejected', 'Rejected by Admin' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    default => 'bg-secondary-subtle text-secondary'
                                };
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $leave->employee->FirstName }} {{ $leave->employee->LastName }}</div>
                                    <small class="text-muted">{{ $leave->employee->EmployeeNumber }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $leave->leaveType->LeaveTypeName ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 {{ $badgeClass }}">
                                        {{ $leave->RequestStatus }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ \Carbon\Carbon::parse($leave->StartDate)->format('M d, Y') }}</div>
                                    <div class="text-muted small">to {{ \Carbon\Carbon::parse($leave->EndDate)->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-archived">{{ $leave->TotalDays }} days</span>
                                </td>
                                <td>
                                    <div class="small">{{ $leave->archived_at ? \Carbon\Carbon::parse($leave->archived_at)->format('M d, Y') : 'N/A' }}</div>
                                    <small class="text-muted">{{ $leave->archived_at ? $leave->archived_at->diffForHumans() : '' }}</small>
                                </td>
                                <td class="text-center pe-4">
                                    <form action="{{ route('leave_requests.restore', $leave->LeaveRequestID) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3" title="Restore this archived leave request">
                                            <i class="fas fa-undo me-1"></i> Restore
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($archivedLeaves->hasPages())
                <div class="card-footer bg-light">
                    {{ $archivedLeaves->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.5; display: block; margin-bottom: 10px;"></i>
            <p class="fw-bold mb-1">No Archived Leaves Found</p>
            <p class="text-muted small">There are no archived leave requests to display.</p>
        </div>
    @endif
</div>

<script>
    function filterByYear() {
        const year = document.getElementById('yearSelect').value;
        const url = new URL(window.location);
        if (year) {
            url.searchParams.set('year', year);
        } else {
            url.searchParams.delete('year');
        }
        window.location = url.toString();
    }

    function clearFilter() {
        const url = new URL(window.location);
        url.searchParams.delete('year');
        window.location = url.toString();
    }

    // Allow Enter key to filter
    document.getElementById('yearSelect').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            filterByYear();
        }
    });
</script>
@endsection
