@extends('layouts.app')

@section('title', 'Employee Locum Sessions')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ $employee->FirstName }} {{ $employee->LastName }}</h2>
            <p class="text-muted mb-0">Employee Number: {{ $employee->EmployeeNumber }} | Department: {{ $employee->department->DepartmentName ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('supervisor.locum.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Locum Management
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-uppercase text-muted mb-2">Total Sessions</h6>
                    <h3 class="fw-bold text-primary mb-0">{{ $summary['total_sessions'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-uppercase text-muted mb-2">Total Hours</h6>
                    <h3 class="fw-bold text-success mb-0">{{ number_format($summary['total_hours'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-warning bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-uppercase text-muted mb-2">Total Earnings</h6>
                    <h3 class="fw-bold text-warning mb-0">MWK {{ number_format($summary['total_earnings'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Locum Session History</h5>
        </div>
        <div class="card-body">
            @if($employeeSessions->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Sign In</th>
                                <th>Sign Out</th>
                                <th>Hours</th>
                                <th>Earnings</th>
                                <th>Shift</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employeeSessions as $session)
                                <tr>
                                    <td>{{ $session->session_date->format('M d, Y') }}</td>
                                    <td>{{ $session->sign_in_time ? $session->sign_in_time->format('H:i') : 'N/A' }}</td>
                                    <td>{{ $session->sign_out_time ? $session->sign_out_time->format('H:i') : 'N/A' }}</td>
                                    <td>{{ number_format($session->hours_worked, 2) }} hrs</td>
                                    <td class="fw-bold text-success">{{ $session->getFormattedEarnings() }}</td>
                                    @php $shiftTypeValue = $session->getShiftTypeValue(); @endphp
                                    <td>
                                        <span class="badge {{ $shiftTypeValue === 'day' ? 'bg-warning text-dark' : 'bg-dark' }}">
                                            {{ $session->getShiftType() }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No locum sessions recorded yet.</h5>
                    <p class="text-muted">This employee has not yet logged any locum sessions.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection