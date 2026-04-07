@extends('layouts.app')

@section('title', 'Payroll Hub')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Payroll Hub</h4>
            <p class="text-muted mb-0">Manage payroll records, deductions, bank extracts, and employee receipts.</p>
        </div>
        @if((int) auth()->user()->role_id === 1)
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('payrolls.master-data') }}" class="btn btn-outline-primary">Master Data</a>
                <a href="{{ route('payrolls.pension-deductions') }}" class="btn btn-outline-primary">Pension & Deductions</a>
                <a href="{{ route('payrolls.bank-list') }}" class="btn btn-outline-primary">Bank List</a>
                <a href="{{ route('payrolls.report') }}" class="btn btn-outline-primary">Payroll Report</a>
                <a href="{{ route('payrolls.create') }}" class="btn btn-primary">Create Payroll</a>
            </div>
        @endif
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Employees</div>
                    <div class="fs-4 fw-bold">{{ number_format($summary['employees']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Gross Pay</div>
                    <div class="fs-4 fw-bold">{{ number_format((float) $summary['gross'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Deductions</div>
                    <div class="fs-4 fw-bold">{{ number_format((float) $summary['deductions'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Take Home</div>
                    <div class="fs-4 fw-bold text-primary">{{ number_format((float) $summary['net'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">Payroll Records</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Period</th>
                        <th>Gross</th>
                        <th>Deductions</th>
                        <th>Take Home</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $payroll->employee->full_name ?? $payroll->EmployeeNumber }}</div>
                                <div class="small text-muted">{{ $payroll->EmployeeNumber }}</div>
                            </td>
                            <td>{{ $payroll->PeriodStart->format('d M Y') }} - {{ $payroll->PeriodEnd->format('d M Y') }}</td>
                            <td>{{ number_format((float) $payroll->GrossPay, 2) }}</td>
                            <td>{{ number_format((float) $payroll->Deductions, 2) }}</td>
                            <td class="fw-bold">{{ number_format((float) $payroll->NetPay, 2) }}</td>
                            <td>
                                <span class="badge {{ $payroll->Status === 'Paid' ? 'bg-success' : ($payroll->Status === 'Processed' ? 'bg-info text-dark' : 'bg-secondary') }}">
                                    {{ $payroll->Status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex flex-wrap gap-2 justify-content-end">
                                    <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    <a href="{{ route('payrolls.receipt', $payroll) }}" class="btn btn-sm btn-outline-primary">Receipt</a>
                                    <a href="{{ route('payrolls.payslip', $payroll) }}" class="btn btn-sm btn-outline-primary">Payslip</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No payroll records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $payrolls->links() }}
    </div>
</div>
@endsection
