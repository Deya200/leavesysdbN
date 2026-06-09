@extends('layouts.app')

@section('title', 'Locum Management')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-user-md text-primary me-2"></i>
                    Locum Management
                </h2>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#emergencyModal">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Send Emergency Notification
                </button>
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
                    <small class="text-muted">This month</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x text-success mb-2"></i>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Hours</h6>
                    <h3 class="fw-bold text-success mb-0">{{ number_format($totalHours, 2) }}</h3>
                    <small class="text-muted">Hours worked</small>
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

    <!-- Employee Summary Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Supervised Employees Locum Summary
                    </h5>
                </div>
                <div class="card-body">
                    @if($employeeSummaries->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Total Sessions</th>
                                        <th>Total Hours</th>
                                        <th>Total Earnings</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employeeSummaries as $summary)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                        {{ substr($summary['employee']->FirstName, 0, 1) }}{{ substr($summary['employee']->LastName, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $summary['employee']->FirstName }} {{ $summary['employee']->LastName }}</div>
                                                        <small class="text-muted">{{ $summary['employee']->EmployeeNumber }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $summary['employee']->department->DepartmentName ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $summary['total_sessions'] }}</span>
                                            </td>
                                            <td>{{ number_format($summary['total_hours'], 2) }} hrs</td>
                                            <td class="fw-bold text-success">MWK {{ number_format($summary['total_earnings'], 2) }}</td>
                                            <td>
                                                <a href="{{ route('supervisor.locum.employee-sessions', $summary['employee']->EmployeeNumber) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>View Sessions
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No supervised employees found</h5>
                            <p class="text-muted">There are no employees under your supervision with locum activity.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sessions Table -->
    @if($locumSessions->isNotEmpty())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Recent Locum Sessions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Session Date</th>
                                        <th>Sign In</th>
                                        <th>Sign Out</th>
                                        <th>Hours Worked</th>
                                        <th>Earnings</th>
                                        <th>Shift</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locumSessions->take(10) as $session)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $session->employee->FirstName }} {{ $session->employee->LastName }}</div>
                                                <small class="text-muted">{{ $session->employee->EmployeeNumber }}</small>
                                            </td>
                                            <td>{{ $session->session_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-sign-in-alt me-1"></i>
                                                    {{ $session->sign_in_time ? $session->sign_in_time->format('H:i') : 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-sign-out-alt me-1"></i>
                                                    {{ $session->sign_out_time ? $session->sign_out_time->format('H:i') : 'N/A' }}
                                                </span>
                                            </td>
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
                        @if($locumSessions->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">Showing 10 most recent sessions. {{ $locumSessions->count() - 10 }} more sessions available.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Emergency Notification Modal -->
<div class="modal fade" id="emergencyModal" tabindex="-1" aria-labelledby="emergencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('supervisor.locum.send-emergency') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="emergencyModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Send Emergency Locum Notification
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Emergency Notification:</strong> This will send an urgent message to selected employees about locum requirements.
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter your emergency locum message..." required></textarea>
                        <div class="form-text">Describe the emergency situation and any specific locum requirements.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Recipients <span class="text-danger">*</span></label>
                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            <div class="mb-3">
                                <h6>By Department:</h6>
                                @php
                                    $supervisedDepartments = $supervisedEmployees->pluck('DepartmentID')->unique();
                                    $departments = \App\Models\Department::whereIn('DepartmentID', $supervisedDepartments)->get();
                                @endphp
                                @foreach($departments as $department)
                                    <div class="form-check">
                                        <input class="form-check-input department-check" type="checkbox" name="departments[]" value="{{ $department->DepartmentID }}" id="dept_{{ $department->DepartmentID }}">
                                        <label class="form-check-label fw-bold" for="dept_{{ $department->DepartmentID }}">
                                            {{ $department->DepartmentName }} Department
                                            <small class="text-muted">({{ $supervisedEmployees->where('DepartmentID', $department->DepartmentID)->count() }} employees)</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <hr>
                            <div class="mb-3">
                                <h6>Individual Employees:</h6>
                                @foreach($supervisedEmployees as $employee)
                                    <div class="form-check">
                                        <input class="form-check-input employee-check" type="checkbox" name="recipients[]" value="{{ $employee->EmployeeNumber }}" id="recipient_{{ $employee->EmployeeNumber }}">
                                        <label class="form-check-label" for="recipient_{{ $employee->EmployeeNumber }}">
                                            <strong>{{ $employee->FirstName }} {{ $employee->LastName }}</strong>
                                            <small class="text-muted">({{ $employee->EmployeeNumber }}) - {{ $employee->department->DepartmentName ?? 'N/A' }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-text">
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="selectAllRecipients()">Select All Employees</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2 ms-2" onclick="selectAllDepartments()">Select All Departments</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2 ms-2" onclick="deselectAll()">Deselect All</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-paper-plane me-2"></i>
                        Send Emergency Notification
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function selectAllRecipients() {
    const checkboxes = document.querySelectorAll('input[name="recipients[]"]');
    checkboxes.forEach(cb => cb.checked = true);
}

function selectAllDepartments() {
    const checkboxes = document.querySelectorAll('input[name="departments[]"]');
    checkboxes.forEach(cb => cb.checked = true);
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('input[name="recipients[]"], input[name="departments[]"]');
    checkboxes.forEach(cb => cb.checked = false);
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

@endsection