@extends('layouts.app')

@section('title', 'Clinical Roster')

@section('styles')
<style>
    .roster-header {
        background: linear-gradient(135deg, #2E3A87 0%, #1e2556 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .roster-filters {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .ward-section {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .ward-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #2E3A87;
        font-weight: 600;
        color: #2E3A87;
    }

    .staff-table {
        width: 100%;
        border-collapse: collapse;
    }

    .staff-table th {
        background: #2E3A87;
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .staff-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    .staff-table tbody tr:hover {
        background: #f8f9fa;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-present {
        background: #d4edda;
        color: #155724;
    }

    .status-sick {
        background: #f8d7da;
        color: #721c24;
    }

    .status-holiday {
        background: #cfe2ff;
        color: #084298;
    }

    .status-absent {
        background: #fff3cd;
        color: #664d03;
    }

    .devotion-check {
        text-align: center;
    }

    .devotion-check input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        border-left: 4px solid #2E3A87;
    }

    .summary-card h6 {
        color: #666;
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .summary-card .number {
        font-size: 1.8rem;
        font-weight: bold;
        color: #2E3A87;
    }

    .no-data {
        padding: 2rem;
        text-align: center;
        color: #999;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="roster-header">
        <h1 class="mb-2">
            <i class="fas fa-hospital-user me-2"></i> Clinical Roster
        </h1>
        <p class="mb-0">Manage daily staff assignments, shifts, and attendance records</p>
    </div>

    <!-- Filters -->
    <div class="roster-filters">
        <div class="row align-items-end gap-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Department</label>
                <select id="departmentSelect" class="form-select">
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->DepartmentID }}" 
                            {{ $selectedDepartment && $selectedDepartment->DepartmentID == $dept->DepartmentID ? 'selected' : '' }}>
                            {{ $dept->DepartmentName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">Date</label>
                <input type="date" id="rosterDate" class="form-control" value="{{ $rosterDate }}">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="applyFilters()">
                    <i class="fas fa-filter me-2"></i> Apply Filters
                </button>
            </div>

            <div class="col-md-2">
                <a href="{{ route('clinical-rosters.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-2"></i> Reset
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    @if($selectedDepartment)
        <div class="summary-cards">
            <div class="summary-card">
                <h6>Total Staff</h6>
                <div class="number">{{ $summary['total_staff'] ?? 0 }}</div>
            </div>

            <div class="summary-card">
                <h6>On Duty (Day)</h6>
                <div class="number">{{ $summary['on_duty_day'] ?? 0 }}</div>
            </div>

            <div class="summary-card">
                <h6>On Duty (Night)</h6>
                <div class="number">{{ $summary['on_duty_night'] ?? 0 }}</div>
            </div>

            <div class="summary-card">
                <h6>On Sick Leave</h6>
                <div class="number">{{ $summary['on_sick_leave'] ?? 0 }}</div>
            </div>

            <div class="summary-card">
                <h6>On Holiday</h6>
                <div class="number">{{ $summary['on_holiday'] ?? 0 }}</div>
            </div>

            <div class="summary-card">
                <h6>Devotion Attended</h6>
                <div class="number">{{ $summary['devotion_attended'] ?? 0 }}</div>
            </div>
        </div>

        @if(auth()->user()?->isSupervisor() || auth()->user()?->isAdmin())
            <div class="roster-filters mb-4">
                <form action="{{ route('clinical-rosters.bulkStore') }}" method="POST" class="row g-3 align-items-end">
                    @csrf
                    <input type="hidden" name="department_id" value="{{ $selectedDepartment->DepartmentID }}">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $rosterDate }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Shift</label>
                        <select name="shift" class="form-select" required>
                            <option value="day">Day</option>
                            <option value="night">Night</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="present">Present</option>
                            <option value="sick">Sick</option>
                            <option value="on_holiday">On Holiday</option>
                            <option value="absent">Absent</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Devotion</label>
                        <select name="morning_devotion_attended" class="form-select">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Notes</label>
                        <input type="text" name="notes" class="form-control" placeholder="Optional notes">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100">Create</button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Ward Sections -->
        @forelse($wards as $ward)
            <div class="ward-section">
                <div class="ward-header">
                    <i class="fas fa-clinic-medical me-2"></i> {{ $ward->WardName }}
                </div>

                @if(isset($staffByWard[$ward->WardID]) && count($staffByWard[$ward->WardID]) > 0)
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Position</th>
                                <th>Day Shift Status</th>
                                <th>Night Shift Status</th>
                                <th class="text-center">Morning Devotion</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffByWard[$ward->WardID] as $staff)
                                <tr>
                                    <td>
                                        <strong>{{ $staff['employee']->FirstName }} {{ $staff['employee']->LastName }}</strong><br>
                                        <small class="text-muted">{{ $staff['employee']->EmployeeNumber }}</small>
                                    </td>
                                    <td>
                                        {{ $staff['employee']->position?->PositionName ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @if($staff['day_attendance'])
                                            <span class="status-badge status-{{ $staff['day_attendance']->Status }}">
                                                {{ ucfirst(str_replace('_', ' ', $staff['day_attendance']->Status)) }}
                                            </span>
                                        @else
                                            <span class="text-muted">Not recorded</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($staff['night_attendance'])
                                            <span class="status-badge status-{{ $staff['night_attendance']->Status }}">
                                                {{ ucfirst(str_replace('_', ' ', $staff['night_attendance']->Status)) }}
                                            </span>
                                        @else
                                            <span class="text-muted">Not recorded</span>
                                        @endif
                                    </td>
                                    <td class="devotion-check">
                                        @if($staff['day_attendance'])
                                            <input type="checkbox" 
                                                {{ $staff['day_attendance']->MorningDevotionAttended ? 'checked' : '' }}
                                                onchange="updateDevotion(this, '{{ $staff['employee']->EmployeeNumber }}', '{{ $rosterDate }}')">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            @if($staff['day_attendance']?->Notes)
                                                {{ $staff['day_attendance']->Notes }}
                                            @endif
                                        </small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-data">
                        <p>No staff assigned to this ward for the selected date.</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="ward-section">
                <div class="no-data">
                    <p>No wards found for this department. <a href="{{ route('departments.index') }}">Create wards</a></p>
                </div>
            </div>
        @endforelse
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Please select a department to view the roster.
        </div>
    @endif
</div>

<script>
function applyFilters() {
    const deptId = document.getElementById('departmentSelect').value;
    const date = document.getElementById('rosterDate').value;
    
    let url = '{{ route("clinical-rosters.index") }}?';
    if (deptId) url += 'department_id=' + deptId;
    if (date) url += (deptId ? '&' : '') + 'date=' + date;
    
    window.location.href = url;
}

function updateDevotion(checkbox, employeeNumber, date) {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('EmployeeNumber', employeeNumber);
    formData.append('AttendanceDate', date);
    formData.append('Shift', 'day');
    formData.append('Status', 'present');
    formData.append('MorningDevotionAttended', checkbox.checked ? 1 : 0);

    fetch('{{ route("clinical-rosters.storeAttendance") }}', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            console.log('Devotion attendance updated');
        }
    }).catch(error => console.error('Error:', error));
}
</script>
@endsection
