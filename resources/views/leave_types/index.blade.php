@extends('layouts.app')

@section('title', 'Manage Leave Types')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark">
            <i class="fas fa-calendar-alt text-primary me-2"></i> Manage Leave Types
        </h4>
        <a href="{{ route('leave_types.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Create New Leave Type
        </a>
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
                            @if($type->isStatutory())
                                <small class="badge bg-warning text-dark mt-1">Mandatory (Malawi Act)</small>
                            @endif
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
                                @if($type->isStatutory())
                                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" disabled title="Mandatory leave type">
                                        <i class="fas fa-lock me-1"></i> Locked
                                    </button>
                                @else
                                    <a href="{{ route('leave_types.edit', $type->LeaveTypeID) }}" class="btn btn-sm btn-outline-primary px-3">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('leave_types.destroy', $type->LeaveTypeID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this leave type? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
