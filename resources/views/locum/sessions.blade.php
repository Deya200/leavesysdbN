@extends('layouts.app')

@section('title', 'My Locum Sessions')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-history"></i> My Locum Sessions</h4>
                    <a href="{{ route('locum.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Sign In
                    </a>
                </div>
                <div class="card-body">
                    @if($sessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Locum Employee</th>
                                        <th>Department</th>
                                        <th>Shift</th>
                                        <th>Sign In</th>
                                        <th>Sign Out</th>
                                        <th>Hours Worked</th>
                                        <th>Rate/Hour</th>
                                        <th>Earnings</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessions as $session)
                                        @php
                                            $breakdown = $session->getEarningsBreakdown();
                                        @endphp
                                        <tr>
                                            <td>{{ $session->session_date->format('d M Y') }}</td>
                                            <td>{{ $session->employee->FullName }} ({{ $session->EmployeeNumber }})</td>
                                            <td>{{ $session->department->DepartmentName }}</td>
                                            <td>
                                                <span class="badge {{ $session->getShiftType() === 'Day Shift' ? 'bg-info' : 'bg-dark' }}">
                                                    {{ $session->getShiftType() }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($session->sign_in_time)
                                                    <span class="local-time" data-time="{{ $session->sign_in_time->toIso8601String() }}"></span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($session->sign_out_time)
                                                    <span class="local-time" data-time="{{ $session->sign_out_time->toIso8601String() }}"></span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $session->hours_worked ? number_format($session->hours_worked, 2) : '-' }}</td>
                                            <td>
                                                @if($breakdown)
                                                    {{ $breakdown['formatted_per_hour'] }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($breakdown)
                                                    <strong>{{ $breakdown['formatted_total'] }}</strong>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $session->notes ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $sessions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No locum sessions found</h5>
                            <p>You haven't signed in for any locum work yet.</p>
                            <a href="{{ route('locum.index') }}" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Start Your First Session
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.local-time').forEach(function(el) {
            var iso = el.dataset.time;
            if (!iso) return;
            var date = new Date(iso);
            if (isNaN(date)) return;
            var hours = date.getHours().toString().padStart(2, '0');
            var minutes = date.getMinutes().toString().padStart(2, '0');
            el.textContent = hours + ':' + minutes;
        });
    });
</script>
@endsection