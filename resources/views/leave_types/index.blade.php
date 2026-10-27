@extends('layouts.app')

@section('title', 'Manage Leave Types')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark">
            <i class="fas fa-calendar-alt text-primary me-2"></i> Manage Leave Types
        </h4>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#leaveTypeModal" onclick="openLeaveTypeModal()">
            <i class="fas fa-plus me-1"></i> Create New Leave Type
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Leave Type Name</th>
                        <th>Type Status</th>
                        <th>Max Days (Per Year)</th>
                        <th>Applicable Gender</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaveTypes as $type)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $type->LeaveTypeName }}</div>
                        </td>
                        <td>
                            @if($type->IsPaidLeave)
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Paid</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">Unpaid</span>
                            @endif
                        </td>
                        <td>
                            @if($type->deductsFromAnnual())
                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">Dynamic</span>
                                <small class="d-block text-muted">Grade/Role Based</small>
                            @elseif(is_null($type->MaxLeaveDays) || $type->MaxLeaveDays <= 0)
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Unlimited</span>
                            @else
                                <span class="fw-bold text-dark">{{ $type->MaxLeaveDays }}</span> <small class="text-muted">days</small>
                            @endif
                        </td>
                        <td>{{ $type->GenderApplicable }}</td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary px-3" data-bs-toggle="modal" data-bs-target="#leaveTypeModal"
                                    onclick="openLeaveTypeModal('{{ route('leave_types.update', $type->LeaveTypeID) }}', '{{ addslashes($type->LeaveTypeName) }}', {{ (int) $type->IsPaidLeave }}, '{{ $type->GenderApplicable }}', {{ is_null($type->MaxLeaveDays) ? 'null' : (int) $type->MaxLeaveDays }})">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                                <form action="{{ route('leave_types.destroy', $type->LeaveTypeID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this leave type? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="leaveTypeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><form id="leaveTypeForm" method="POST" action="{{ route('leave_types.store') }}" class="modal-content">
            @csrf <input type="hidden" id="leaveTypeMethod" name="_method" value="POST">
            <div class="modal-header"><h5 id="leaveTypeModalTitle" class="modal-title">Create Leave Type</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><div class="mb-3"><label for="leaveTypeName" class="form-label">Leave Type Name</label><input id="leaveTypeName" name="LeaveTypeName" class="form-control" required maxlength="255"></div><div class="row g-3"><div class="col-md-6"><label for="leaveTypePaid" class="form-label">Paid Leave</label><select id="leaveTypePaid" name="IsPaidLeave" class="form-select" required><option value="1">Yes</option><option value="0">No</option></select></div><div class="col-md-6"><label for="leaveTypeGender" class="form-label">Applicable Gender</label><select id="leaveTypeGender" name="GenderApplicable" class="form-select" required><option value="Male">Male</option><option value="Female">Female</option><option value="Both">Both</option></select></div></div><div class="mt-3"><label for="leaveTypeMaxDays" class="form-label">Max Days Allowed</label><input id="leaveTypeMaxDays" name="MaxLeaveDays" type="number" min="0" class="form-control" placeholder="Blank = unlimited"></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Leave Type</button></div>
        </form></div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function openLeaveTypeModal(updateUrl = '', leaveTypeName = '', isPaid = 1, gender = 'Both', maxDays = null) {
    const form = document.getElementById('leaveTypeForm');
    form.action = updateUrl || '{{ route('leave_types.store') }}';
    document.getElementById('leaveTypeMethod').value = updateUrl ? 'PUT' : 'POST';
    document.getElementById('leaveTypeModalTitle').textContent = updateUrl ? 'Edit Leave Type' : 'Create Leave Type';
    document.getElementById('leaveTypeName').value = leaveTypeName;
    document.getElementById('leaveTypePaid').value = String(isPaid);
    document.getElementById('leaveTypeGender').value = gender;
    document.getElementById('leaveTypeMaxDays').value = maxDays === null ? '' : maxDays;
}
</script>
@endsection
