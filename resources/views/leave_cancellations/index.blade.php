@extends('layouts.app')

@section('title', 'Manage Leave Cancellations')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Leave Cancellations</h2>
        <a href="{{ route('supervisor.index') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($cancellations->isEmpty())
                <p class="text-center my-3">No pending cancellation requests.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Leave Date</th>
                                <th>Reason</th>
                                <th>Refundable Days</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cancellations as $cancellation)
                                <tr>
                                    <td>{{ $cancellation->employee->FirstName }} {{ $cancellation->employee->LastName }}</td>
                                    <td>
                                        #{{ $cancellation->leaveRequest->id }} <br>
                                        <small>{{ \Carbon\Carbon::parse($cancellation->leaveRequest->StartDate)->format('d M') }} - {{ \Carbon\Carbon::parse($cancellation->leaveRequest->EndDate)->format('d M Y') }}</small>
                                    </td>
                                    <td>{{ $cancellation->cancellation_reason }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">{{ $cancellation->refundable_days }} days</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('leave_cancellations.approve', $cancellation->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Approve Cancellation</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $cancellations->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
