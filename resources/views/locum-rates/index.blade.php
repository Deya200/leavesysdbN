@extends('layouts.app')

@section('title', 'Locum Rates Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Locum Rates Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#locumRateModal" onclick="openLocumRateModal(this)">
                            <i class="fas fa-plus"></i> Add New Rate
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100 p-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <h6 class="mb-1">Current Month Spend</h6>
                                        <small class="text-muted">Total locum payout</small>
                                    </div>
                                    <span class="badge bg-success">MWK</span>
                                </div>
                                <h3 class="fw-bold mb-0">{{ number_format($currentMonthSpend, 2) }}</h3>
                                <p class="text-muted mb-0">Across all departments for {{ now()->format('F Y') }}.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100 p-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <h6 class="mb-1">Sessions This Month</h6>
                                        <small class="text-muted">Logged locum shifts</small>
                                    </div>
                                    <i class="fas fa-clock text-primary fs-4"></i>
                                </div>
                                <h3 class="fw-bold mb-0">{{ $locumSessionsThisMonth->count() }}</h3>
                                <p class="text-muted mb-0">Recorded locum sessions in {{ now()->format('M Y') }}.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100 p-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <h6 class="mb-1">Active Rate Plans</h6>
                                        <small class="text-muted">Locum rate entries</small>
                                    </div>
                                    <i class="fas fa-dollar-sign text-success fs-4"></i>
                                </div>
                                <h3 class="fw-bold mb-0">{{ $activeRateCount }}</h3>
                                <p class="text-muted mb-0">Covering {{ $departmentRateCount }} departments.</p>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Position Type</th>
                                    <th>Shift</th>
                                    <th>Daily Rate</th>
                                    <th>Hourly Rate</th>
                                    <th>Currency</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rates as $rate)
                                    <tr>
                                        <td>{{ $rate->department->DepartmentName ?? 'N/A' }}</td>
                                        <td>{{ $rate->position_type }}</td>
                                        <td>
                                            <span class="badge {{ $rate->shift === 'day' ? 'bg-info' : 'bg-dark' }}">
                                                {{ ucfirst($rate->shift) }} Shift
                                            </span>
                                        </td>
                                        <td>{{ number_format($rate->daily_rate, 2) }}</td>
                                        <td>{{ $rate->hourly_rate ? number_format($rate->hourly_rate, 2) : 'N/A' }}</td>
                                        <td>{{ $rate->currency }}</td>
                                        <td>
                                            <span class="badge {{ $rate->is_active ? 'bg-success' : 'bg-danger' }} text-white">
                                                {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning"
                                                data-update-url="{{ route('admin.locum_rates.update', $rate) }}"
                                                data-department-id="{{ $rate->DepartmentID }}"
                                                data-position-type="{{ $rate->position_type }}"
                                                data-shift="{{ $rate->shift }}"
                                                data-daily-rate="{{ $rate->daily_rate }}"
                                                data-hourly-rate="{{ $rate->hourly_rate }}"
                                                data-currency="{{ $rate->currency }}"
                                                data-active="{{ $rate->is_active ? '1' : '0' }}"
                                                onclick="openLocumRateModal(this)">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.locum_rates.destroy', $rate) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this rate?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No locum rates found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="locumRateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="locumRateForm" method="POST" action="{{ route('admin.locum_rates.store') }}" class="modal-content">
                @csrf
                <input type="hidden" id="locumRateMethod" name="_method" value="POST">
                <div class="modal-header">
                    <h5 id="locumRateModalTitle" class="modal-title">Add Locum Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label for="locumDepartment" class="form-label">Department</label><select id="locumDepartment" name="DepartmentID" class="form-select" required><option value="">Select Department</option>@foreach($departments as $department)<option value="{{ $department->DepartmentID }}">{{ $department->DepartmentName }}</option>@endforeach</select></div>
                        <div class="col-md-6"><label for="locumPositionType" class="form-label">Position Type</label><input id="locumPositionType" name="position_type" class="form-control" required maxlength="255"></div>
                        <div class="col-md-6"><label for="locumShift" class="form-label">Shift</label><select id="locumShift" name="shift" class="form-select" required><option value="day">Day Shift</option><option value="night">Night Shift</option></select></div>
                        <div class="col-md-6"><label for="locumCurrency" class="form-label">Currency</label><select id="locumCurrency" name="currency" class="form-select" required><option value="MWK">MWK</option><option value="USD">USD</option><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="KES">KES</option></select></div>
                        <div class="col-md-6"><label for="locumDailyRate" class="form-label">Daily Rate</label><input id="locumDailyRate" name="daily_rate" type="number" step="0.01" min="0" class="form-control" required></div>
                        <div class="col-md-6"><label for="locumHourlyRate" class="form-label">Hourly Rate (Optional)</label><input id="locumHourlyRate" name="hourly_rate" type="number" step="0.01" min="0" class="form-control"></div>
                        <div class="col-12"><div class="form-check"><input id="locumActive" name="is_active" type="checkbox" value="1" class="form-check-input" checked><label for="locumActive" class="form-check-label">Active</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Rate</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function openLocumRateModal(button) {
    const form = document.getElementById('locumRateForm');
    const isEdit = Boolean(button.dataset.updateUrl);
    form.action = isEdit ? button.dataset.updateUrl : '{{ route('admin.locum_rates.store') }}';
    document.getElementById('locumRateMethod').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('locumRateModalTitle').textContent = isEdit ? 'Edit Locum Rate' : 'Add Locum Rate';
    document.getElementById('locumDepartment').value = isEdit ? button.dataset.departmentId : '';
    document.getElementById('locumPositionType').value = isEdit ? button.dataset.positionType : '';
    document.getElementById('locumShift').value = isEdit ? button.dataset.shift : 'day';
    document.getElementById('locumDailyRate').value = isEdit ? button.dataset.dailyRate : '';
    document.getElementById('locumHourlyRate').value = isEdit ? button.dataset.hourlyRate : '';
    document.getElementById('locumCurrency').value = isEdit ? button.dataset.currency : 'MWK';
    document.getElementById('locumActive').checked = isEdit ? button.dataset.active === '1' : true;
}
</script>
@endsection