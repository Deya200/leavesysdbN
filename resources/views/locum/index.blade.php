@extends('layouts.app')

@section('title', 'Locum Management')

@section('content')
<div class="container mt-4">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm summary-card h-100 p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-user-clock fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Locum Invitations</h6>
                        <p class="mb-0 text-muted">Pending locum requests</p>
                    </div>
                </div>
                <p class="display-6 mb-3">{{ isset($locumInvitations) ? $locumInvitations->count() : 0 }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm summary-card h-100 p-4" style="background: linear-gradient(135deg, #2e3a87 0%, #4f46e5 100%); color: #fff;">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-white bg-opacity-15 text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-calendar-check fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-white">Locum Booking</h6>
                        <p class="mb-0 text-white-75">Manage your sessions</p>
                    </div>
                </div>
                <p class="display-6 mb-3">Ready</p>
            </div>
        </div>
    </div>

    <!-- Locum Invitations Section -->
    @if(isset($locumInvitations) && $locumInvitations->count())
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="fas fa-envelope-open-text me-2"></i>Locum Invitations
                    </h5>
                </div>
                <div class="card-body p-0">
                    @foreach($locumInvitations as $invitation)
                        <div class="border-bottom p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <p class="mb-1 text-dark fw-500">{{ $invitation->Message }}</p>
                                    <small class="text-muted">{{ $invitation->created_at->diffForHumans() }}</small>
                                </div>
                                @if($invitation->Status === 'Unread')
                                    <span class="badge bg-warning text-dark ms-2">New</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Booking Form Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success bg-opacity-10 border-0">
                    <h5 class="mb-0 text-success fw-bold">
                        <i class="fas fa-sign-in-alt me-2"></i>Locum Session Booking
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(!auth()->user()->is_locum)
                        <h6 class="fw-bold mb-3">Sign In for Locum Work</h6>
                        
                        @if($currentUserSession)
                            <div class="alert alert-info mb-3">
                                <strong><i class="fas fa-check-circle me-2"></i>Active Session</strong>
                                <br>You are currently signed in for locum work.
                            </div>
                            <div class="card bg-light border-info mb-3">
                                <div class="card-body">
                                    <p class="mb-2"><strong>Sign In Time:</strong> <span class="local-time" data-time="{{ $currentUserSession->sign_in_time->toIso8601String() }}"></span></p>
                                    <p class="mb-2"><strong>Department:</strong> {{ $currentUserSession->department->DepartmentName ?? 'N/A' }}</p>
                                    <p class="mb-0"><strong>Shift:</strong> {{ $currentUserSession->getShiftType() }}</p>
                                </div>
                            </div>
                            <form action="{{ route('locum.employee.sign_out') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg w-100">
                                    <i class="fas fa-sign-out-alt me-2"></i>Sign Out of Locum Session
                                </button>
                            </form>
                        @else
                            <form action="{{ route('locum.employee.sign_in') }}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="self_DepartmentID" class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                                        <select name="DepartmentID" id="self_DepartmentID" class="form-select" required>
                                            <option value="">-- Select Department --</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->DepartmentID }}">{{ $department->DepartmentName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="self_notes" class="form-label fw-bold">Notes (Optional)</label>
                                        <input type="text" name="notes" id="self_notes" class="form-control" placeholder="e.g., Special requirements...">
                                    </div>
                                </div>

                                @php
                                    $now = now();
                                    $dayStart = today()->setTime(7, 30);
                                    $dayEnd = today()->setTime(16, 30);
                                    $nightStart = today()->setTime(16, 30);
                                    $nightEnd = today()->addDay()->setTime(8, 30);
                                    $currentShift = 'Day Shift';
                                    if ($now->greaterThanOrEqualTo($nightStart) || $now->lessThan($dayStart)) {
                                        $currentShift = 'Night Shift';
                                    }
                                @endphp

                                <div class="alert alert-info">
                                    <strong>Current Shift:</strong> {{ $currentShift }}
                                    <br>
                                    <small>{{ $currentShift === 'Day Shift' ? '7:30 AM - 4:30 PM' : '4:30 PM - 8:30 AM Next Morning' }}</small>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In to Locum Session
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info bg-opacity-10 border-0">
                    <h6 class="mb-0 text-info fw-bold">
                        <i class="fas fa-link me-2"></i>Quick Links
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('locum.sessions') }}" class="btn btn-outline-info w-100 mb-2">
                        <i class="fas fa-history me-2"></i>View My Sessions
                    </a>
                    <a href="{{ route('locum.report') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-chart-bar me-2"></i>Monthly Report
                    </a>
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