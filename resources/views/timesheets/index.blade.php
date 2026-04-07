@extends('layouts.app')

@section('title', 'Timesheets')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Timesheets</h4>
        @if((int) auth()->user()->role_id === 3)
            <a href="{{ route('timesheets.create') }}" class="btn btn-primary">Submit Timesheet</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Work Date</th>
                        <th>Hours</th>
                        <th>Overtime</th>
                        <th>Status</th>
                        @if(in_array((int) auth()->user()->role_id, [1, 2], true))
                            <th class="text-end">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($timesheets as $timesheet)
                        <tr>
                            <td>{{ optional($timesheet->employee)->FirstName }} {{ optional($timesheet->employee)->LastName }}</td>
                            <td>{{ \Carbon\Carbon::parse($timesheet->WorkDate)->format('d M Y') }}</td>
                            <td>{{ $timesheet->HoursWorked }}</td>
                            <td>{{ $timesheet->OvertimeHours }}</td>
                            <td>
                                <span class="badge {{ $timesheet->Status === 'Approved' ? 'bg-success' : ($timesheet->Status === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ $timesheet->Status }}
                                </span>
                            </td>
                            @if(in_array((int) auth()->user()->role_id, [1, 2], true))
                                <td class="text-end">
                                    @if($timesheet->Status === 'Pending')
                                        <form action="{{ route('timesheets.approve', $timesheet) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form action="{{ route('timesheets.reject', $timesheet) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No timesheets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $timesheets->links() }}
    </div>
</div>
@endsection
