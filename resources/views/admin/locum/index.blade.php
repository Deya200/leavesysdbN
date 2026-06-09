@extends('layouts.app')

@section('title', 'Admin Locum Management')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-user-md text-primary me-2"></i>
                    Admin Locum Management
                </h2>
                <a href="{{ route('locum.report') }}" class="btn btn-primary">
                    <i class="fas fa-file-invoice-dollar me-2"></i>
                    View Monthly Report
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Sessions</h6>
                    <h3 class="fw-bold text-primary mb-0">{{ $totalSessions }}</h3>
                    <small class="text-muted">This month across all departments</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x text-success mb-2"></i>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Employees with Locum</h6>
                    <h3 class="fw-bold text-success mb-0">{{ $employeesWithLocum->count() }}</h3>
                    <small class="text-muted">Active this month</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-warning bg-opacity-10">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-wave fa-2x text-warning mb-2"></i>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Earnings</h6>
                    <h3 class="fw-bold text-warning mb-0">MWK {{ number_format($totalEarnings, 2) }}</h3>
                    <small class="text-muted">This month</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Locum Sessions Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0">
                <i class="fas fa-list text-primary me-2"></i>
                Current Month Locum Sessions
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Sessions</th>
                            <th>Total Hours</th>
                            <th>Total Earnings</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employeesWithLocum as $employee)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                            {{ substr($employee->FirstName, 0, 1) }}{{ substr($employee->LastName, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $employee->FirstName }} {{ $employee->LastName }}</h6>
                                            <small class="text-muted">{{ $employee->EmployeeNumber }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->department->DepartmentName ?? 'N/A' }}</td>
                                <td>{{ $employee->position->PositionName ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $employee->locumSessions->count() }}</span>
                                </td>
                                <td>{{ number_format($employee->locumSessions->sum('hours_worked'), 2) }} hrs</td>
                                <td>
                                    <strong class="text-success">
                                        MWK {{ number_format($employee->locumSessions->sum(function ($session) {
                                            return $session->total_earnings ?? ($session->hours_worked * ($session->hourly_rate ?? 2000));
                                        }), 2) }}
                                    </strong>
                                </td>
                                <td>
                                    <a href="{{ route('admin.locum.employee-sessions', $employee) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No locum sessions found for this month.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection