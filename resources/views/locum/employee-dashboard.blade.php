@extends('layouts.app')

@section('title', 'Locum Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">
                        <i class="fas fa-user-clock"></i> Locum Employee Dashboard
                    </h3>
                    <div class="card-tools">
                        <small class="text-light">Welcome, {{ Auth::user()->FullName }}</small>
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

                    <!-- Current Status -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card {{ $currentSession ? 'border-success' : 'border-warning' }}">
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        <i class="fas {{ $currentSession ? 'fa-clock text-success' : 'fa-sign-in-alt text-warning' }}"></i>
                                        Current Status
                                    </h5>
                                    <h2 class="mb-0 {{ $currentSession ? 'text-success' : 'text-warning' }}">
                                        {{ $currentSession ? 'Signed In' : 'Signed Out' }}
                                    </h2>
                                    @if($currentSession)
                                        <small class="text-muted">
                                            Since: <span class="local-time" data-time="{{ $currentSession->sign_in_time->toIso8601String() }}"></span>
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        <i class="fas fa-calendar-day text-info"></i>
                                        Today's Sessions
                                    </h5>
                                    <h2 class="mb-0 text-info">{{ $todaySessions->count() }}</h2>
                                    <small class="text-muted">
                                        Total Hours: {{ number_format($todaySessions->sum('hours_worked'), 2) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sign In/Out Actions -->
                    @if(!$currentSession)
                        <!-- Sign In Form -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-sign-in-alt"></i> Sign In for Work</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('locum.employee.sign_in') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="DepartmentID" class="form-label">Department</label>
                                                <select name="DepartmentID" id="DepartmentID" class="form-select" required>
                                                    <option value="">Select Department</option>
                                                    @foreach(\App\Models\Department::all() as $department)
                                                        <option value="{{ $department->DepartmentID }}">{{ $department->DepartmentName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="notes" class="form-label">Notes (Optional)</label>
                                                <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Any additional notes..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt"></i> Sign In
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Sign Out Form -->
                        <div class="card border-success mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-sign-out-alt"></i> Sign Out</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Current Session:</strong> Started at {{ $currentSession->sign_in_time->format('H:i') }}
                                    in {{ $currentSession->department->DepartmentName }}
                                </div>
                                <form action="{{ route('locum.employee.sign_out') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to sign out?')">
                                        <i class="fas fa-sign-out-alt"></i> Sign Out Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Today's Sessions -->
                    @if($todaySessions->count() > 0)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-history"></i> Today's Sessions</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sign In</th>
                                                <th>Sign Out</th>
                                                <th>Department</th>
                                                <th>Hours</th>
                                                <th>Earnings</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($todaySessions as $session)
                                                <tr>
                                                    <td>{{ $session->sign_in_time->format('H:i') }}</td>
                                                    <td>{{ $session->sign_out_time ? $session->sign_out_time->format('H:i') : '-' }}</td>
                                                    <td>{{ $session->department->DepartmentName }}</td>
                                                    <td>{{ $session->hours_worked ? number_format($session->hours_worked, 2) : '-' }}</td>
                                                    <td>{{ $session->getFormattedEarnings() }}</td>
                                                    <td>
                                                        @if($session->sign_out_time)
                                                            <span class="badge bg-success text-white">Completed</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">Active</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Recent Sessions -->
                    @if($recentSessions->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-calendar-week"></i> Recent Sessions (Last 7 Days)</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Sign In</th>
                                                <th>Sign Out</th>
                                                <th>Department</th>
                                                <th>Hours</th>
                                                <th>Earnings</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentSessions as $session)
                                                <tr>
                                                    <td>{{ $session->session_date->format('M d') }}</td>
                                                    <td>{{ $session->sign_in_time->format('H:i') }}</td>
                                                    <td>{{ $session->sign_out_time ? $session->sign_out_time->format('H:i') : '-' }}</td>
                                                    <td>{{ $session->department->DepartmentName }}</td>
                                                    <td>{{ $session->hours_worked ? number_format($session->hours_worked, 2) : '-' }}</td>
                                                    <td>{{ $session->getFormattedEarnings() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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