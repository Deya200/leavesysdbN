@extends('layouts.app')

@section('title', 'Manage Leave Types')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header text-white" style="background: #1e3c72;">
                    <h5 class="mb-0">Manage Leave Types</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Max Days per Year</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveTypes as $type)
                                <tr>
                                    <td class="fw-bold">{{ $type->LeaveTypeName }}</td>
                                    <td>
                                        @if($type->deductsFromAnnual())
                                            <span class="badge bg-info text-dark">Dynamic (Role/Grade Based)</span>
                                            <small class="d-block text-muted">Annual Leave follows special logic.</small>
                                        @elseif(is_null($type->MaxLeaveDays))
                                            <span class="badge bg-success">Unlimited</span>
                                        @else
                                            {{ $type->MaxLeaveDays }} days
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('leave-types.edit', $type->LeaveTypeID) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
