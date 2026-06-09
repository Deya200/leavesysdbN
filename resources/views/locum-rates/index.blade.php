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
                        <a href="{{ route('admin.locum_rates.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Rate
                        </a>
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
                                            <a href="{{ route('admin.locum_rates.edit', $rate) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
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
</div>
@endsection