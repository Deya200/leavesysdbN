@extends('layouts.app')

@section('title', 'Pension and Deductions')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Pension and Deductions</h4>
            <p class="text-muted mb-0">Track pension obligations and all payroll deductions per employee.</p>
        </div>
        <a href="{{ route('payrolls.index') }}" class="btn btn-outline-secondary">Back to Payroll</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted mb-2">Employee Pension</div><div class="fs-5 fw-bold">{{ number_format((float) $totals['employee_pension'], 2) }}</div></div></div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted mb-2">Employer Pension</div><div class="fs-5 fw-bold">{{ number_format((float) $totals['employer_pension'], 2) }}</div></div></div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted mb-2">Admin Fees</div><div class="fs-5 fw-bold">{{ number_format((float) $totals['admin_fees'], 2) }}</div></div></div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="small text-muted mb-2">Total Contribution</div><div class="fs-5 fw-bold text-primary">{{ number_format((float) $totals['total_contribution'], 2) }}</div></div></div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee Name</th>
                        <th>Position</th>
                        <th>Employee Pension</th>
                        <th>Employer Pension</th>
                        <th>Admin Fees</th>
                        <th>Total Contribution</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->employee->full_name ?? $payroll->EmployeeNumber }}</td>
                            <td>{{ $payroll->employee->position->PositionName ?? '-' }}</td>
                            <td>{{ number_format((float) $payroll->EmployeePension, 2) }}</td>
                            <td>{{ number_format((float) $payroll->EmployerPension, 2) }}</td>
                            <td>{{ number_format((float) $payroll->AdminFees, 2) }}</td>
                            <td class="fw-bold">{{ number_format((float) $payroll->TotalContribution, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No pension records found.</td>
                        </tr>
                    @endforelse
                    @if($payrolls->count() > 0)
                        <tr class="table-light fw-bold">
                            <td>TOTALS</td>
                            <td></td>
                            <td>{{ number_format((float) $totals['employee_pension'], 2) }}</td>
                            <td>{{ number_format((float) $totals['employer_pension'], 2) }}</td>
                            <td>{{ number_format((float) $totals['admin_fees'], 2) }}</td>
                            <td>{{ number_format((float) $totals['total_contribution'], 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $payrolls->links() }}
    </div>
</div>
@endsection
