@extends('layouts.app')

@section('title', 'Locum Monthly Report')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-chart-bar"></i> Locum Monthly Report - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h4>
                    <a href="{{ route('locum.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Sign In
                    </a>
                </div>
                <div class="card-body">
                    <!-- Month Selector -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="month" class="form-label">Select Month</label>
                                <input type="month" name="month" id="month" class="form-control"
                                       value="{{ $month }}" max="{{ now()->format('Y-m') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block">View Report</button>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Sessions</h5>
                                    <h2 class="mb-0">{{ $totalSessions }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Hours</h5>
                                    <h2 class="mb-0">{{ number_format($totalHours, 2) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Earnings</h5>
                                    <h2 class="mb-0">MWK {{ $totalEarnings > 0 ? number_format($totalEarnings, 2) : '0.00' }}</h2>
                                    <small>MWK</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Summary -->
                    @if($employeeSummary->count() > 0)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Employee Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Locum Employee</th>
                                                <th>Sessions</th>
                                                <th>Total Hours</th>
                                                <th>Total Earnings</th>
                                                <th>Average Hours/Session</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employeeSummary as $summary)
                                                <tr>
                                                    <td>{{ $summary['employee'] }}</td>
                                                    <td>{{ $summary['sessions'] }}</td>
                                                    <td>{{ number_format($summary['hours'], 2) }}</td>
                                                    <td>{{ $summary['formatted_earnings'] }}</td>
                                                    <td>{{ $summary['sessions'] > 0 ? number_format($summary['hours'] / $summary['sessions'], 2) : '0.00' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($sessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Locum Employee</th>
                                        <th>Department</th>
                                        <th>Sign In</th>
                                        <th>Sign Out</th>
                                        <th>Hours Worked</th>
                                        <th>Earnings</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessions as $session)
                                        <tr>
                                            <td>{{ $session->session_date->format('d M Y') }}</td>
                                            <td>{{ $session->employee->FullName }} ({{ $session->EmployeeNumber }})</td>
                                            <td>{{ $session->department->DepartmentName }}</td>
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
                                            <td>{{ $session->getFormattedEarnings() }}</td>
                                            <td>{{ $session->notes ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No sessions found for {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h5>
                            <p>You didn't work any locum sessions in this month.</p>
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