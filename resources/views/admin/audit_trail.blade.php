@extends('layouts.app')
@section('page_title', 'Audit Trail')
@section('title', 'Audit Trail')

@section('content')
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Audit Trail</h4>
                <p class="mb-0 text-muted">Admin actions are logged and available for review.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Search Filters -->
                <form method="GET" action="{{ route('admin.audit_trail') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="action" class="form-label">Action</label>
                            <input type="text" class="form-control" id="action" name="action" value="{{ request('action') }}" placeholder="e.g., Approved">
                        </div>
                        <div class="col-md-2">
                            <label for="employee_number" class="form-label">Employee #</label>
                            <input type="text" class="form-control" id="employee_number" name="employee_number" value="{{ request('employee_number') }}" placeholder="e.g., 12345">
                        </div>
                        <div class="col-md-2">
                            <label for="table_name" class="form-label">Table</label>
                            <input type="text" class="form-control" id="table_name" name="table_name" value="{{ request('table_name') }}" placeholder="e.g., leave_requests">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('admin.audit_trail') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </div>
                </form>

                @if ($auditLogs->count())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Action</th>
                                    <th>Table</th>
                                    <th>Record ID</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($auditLogs as $log)
                                    <tr>
                                        <td>{{ $loop->iteration + ($auditLogs->currentPage() - 1) * $auditLogs->perPage() }}</td>
                                        <td>{{ $log->employee->FirstName ?? 'N/A' }} {{ $log->employee->LastName ?? '' }} ({{ $log->EmployeeNumber }})</td>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->table_name }}</td>
                                        <td>{{ $log->record_id }}</td>
                                        <td>{{ $log->timestamp->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $auditLogs->links() }}
                @else
                    <p>No audit records found.</p>
                @endif
            </div>
        </div>
    </div>
@endsection