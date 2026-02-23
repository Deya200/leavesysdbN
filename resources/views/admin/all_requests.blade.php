@extends('layouts.app')

@section('title', 'All Leave Requests')

@section('styles')
<style>
    .admin-card {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .table thead {
        background: linear-gradient(135deg, #2E3A87 0%, #1a237e 100%);
        color: white;
    }

    .table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .badge-custom {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 12px;
    }

    .search-box {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .stats-card {
        border-radius: 10px;
        padding: 15px;
        color: white;
        text-align: center;
    }

    .stats-number {
        font-size: 22px;
        font-weight: bold;
    }

    .stats-label {
        font-size: 12px;
        opacity: 0.9;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark">
                <i class="fas fa-file-invoice me-2"></i> All Leave Requests
            </h2>
            <p class="text-muted small">View and manage all leave requests in the system</p>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>System Overview:</strong> View all leave requests submitted by employees. Use filters to search by employee, leave type, or status.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-2">
            <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stats-number">{{ $allRequests->total() }}</div>
                <div class="stats-label">Total Requests</div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stats-number">{{ $allRequests->whereIn('RequestStatus', ['Pending Supervisor Approval', 'Pending Admin Verification'])->count() }}</div>
                <div class="stats-label">Pending Review</div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stats-number">{{ $allRequests->where('RequestStatus', 'Approved')->count() }}</div>
                <div class="stats-label">Approved</div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="stats-number">{{ $allRequests->pluck('EmployeeNumber')->unique()->count() }}</div>
                <div class="stats-label">Employees</div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card admin-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('leave_requests.admin_all') }}" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control search-box" placeholder="Search by employee name, number, or leave type..." value="{{ $search }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select search-box">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $status === request('status') ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="archived" class="form-select search-box">
                        <option value="active" {{ $archived === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="archived" {{ $archived === 'archived' ? 'selected' : '' }}>Archived Only</option>
                        <option value="" {{ $archived === '' ? 'selected' : '' }}>All Requests</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <a href="{{ route('leave_requests.admin_all') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Leave Requests Table -->
    @if($allRequests->isNotEmpty())
        <div class="card admin-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Employee</th>
                            <th>Leave Type</th>
                            <th>Status</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Submitted</th>
                            <th class="text-center pe-4">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allRequests as $request)
                            @php
                                $badgeClass = match($request->RequestStatus) {
                                    'Approved' => 'bg-success-subtle text-success',
                                    'Rejected', 'Rejected by Admin' => 'bg-danger-subtle text-danger',
                                    'Pending Supervisor Approval' => 'bg-warning-subtle text-warning-emphasis',
                                    'Pending Admin Verification' => 'bg-info-subtle text-info-emphasis',
                                    default => 'bg-secondary-subtle text-secondary'
                                };
                                
                                $archivedBadge = $request->is_archived ? '<span class="badge bg-secondary ms-1">Archived</span>' : '';
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</div>
                                    <small class="text-muted">{{ $request->employee->EmployeeNumber }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary badge-custom">
                                        {{ $request->leaveType->LeaveTypeName ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill badge-custom {{ $badgeClass }}">
                                        {{ $request->RequestStatus }}
                                    </span>
                                    {!! $archivedBadge !!}
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</div>
                                    <div class="text-muted small">to {{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-dark">{{ $request->TotalDays }} days</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $request->created_at->format('M d, Y') }}</small><br>
                                    <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ route('leave_requests.show', $request->LeaveRequestID) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="View request details">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($allRequests->hasPages())
                <div class="card-footer bg-light">
                    {{ $allRequests->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.5; display: block; margin-bottom: 10px;"></i>
            <p class="fw-bold mb-1">No Leave Requests Found</p>
            <p class="text-muted small">There are no leave requests matching your current filters.</p>
        </div>
    @endif
</div>
@endsection
