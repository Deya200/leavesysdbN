@extends('layouts.app')

@section('title', 'Payroll Report')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">Payroll Report</h4>
            <p class="text-muted mb-0">Summary report mirroring the payrollrep sheet.</p>
        </div>
        <a href="{{ route('payrolls.index') }}" class="btn btn-outline-secondary">Back to Payroll</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee Name</th>
                        <th>Employee Number</th>
                        <th>Position</th>
                        <th>Grade</th>
                        <th>Department</th>
                        <th>Basic Pay</th>
                        <th>Housing Allowance</th>
                        <th>Other Allowances</th>
                        <th>Overtime Pay</th>
                        <th>Bonus</th>
                        <th>Gross Pay</th>
                        <th>PAYE</th>
                        <th>Employee Pension</th>
                        <th>TEVET Levy</th>
                        <th>Loan/Advance</th>
                        <th>Other Deductions</th>
                        <th>Total Deductions</th>
                        <th>Takehome</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->employee->full_name ?? $payroll->EmployeeNumber }}</td>
                            <td>{{ $payroll->EmployeeNumber }}</td>
                            <td>{{ $payroll->employee->position->PositionName ?? '-' }}</td>
                            <td>{{ $payroll->employee->grade->GradeName ?? '-' }}</td>
                            <td>{{ $payroll->employee->department->DepartmentName ?? '-' }}</td>
                            <td>{{ number_format((float) $payroll->BasicPay, 2) }}</td>
                            <td>{{ number_format((float) $payroll->HousingAllowance, 2) }}</td>
                            <td>{{ number_format((float) $payroll->OtherAllowance, 2) }}</td>
                            <td>{{ number_format((float) $payroll->OvertimePay, 2) }}</td>
                            <td>{{ number_format((float) $payroll->Bonus, 2) }}</td>
                            <td>{{ number_format((float) $payroll->GrossPay, 2) }}</td>
                            <td>{{ number_format((float) $payroll->PAYE, 2) }}</td>
                            <td>{{ number_format((float) $payroll->EmployeePension, 2) }}</td>
                            <td>{{ number_format((float) $payroll->TevetLevy, 2) }}</td>
                            <td>{{ number_format((float) $payroll->LoanAdvanceDeduction, 2) }}</td>
                            <td>{{ number_format((float) $payroll->OtherDeductions, 2) }}</td>
                            <td class="fw-bold">{{ number_format((float) $payroll->Deductions, 2) }}</td>
                            <td class="fw-bold">{{ number_format((float) $payroll->NetPay, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="18" class="text-center py-4 text-muted">No payroll records found.</td>
                        </tr>
                    @endforelse
                    @if($payrolls->count() > 0)
                        <tr class="table-light fw-bold">
                            <td>TOTALS</td>
                            <td colspan="4"></td>
                            <td>{{ number_format((float) $totals['BasicPay'], 2) }}</td>
                            <td>{{ number_format((float) $totals['HousingAllowance'], 2) }}</td>
                            <td>{{ number_format((float) $totals['OtherAllowance'], 2) }}</td>
                            <td>{{ number_format((float) $totals['OvertimePay'], 2) }}</td>
                            <td>{{ number_format((float) $totals['Bonus'], 2) }}</td>
                            <td>{{ number_format((float) $totals['GrossPay'], 2) }}</td>
                            <td>{{ number_format((float) $totals['PAYE'], 2) }}</td>
                            <td>{{ number_format((float) $totals['EmployeePension'], 2) }}</td>
                            <td>{{ number_format((float) $totals['TevetLevy'], 2) }}</td>
                            <td>{{ number_format((float) $totals['LoanAdvanceDeduction'], 2) }}</td>
                            <td>{{ number_format((float) $totals['OtherDeductions'], 2) }}</td>
                            <td>{{ number_format((float) $totals['Deductions'], 2) }}</td>
                            <td>{{ number_format((float) $totals['NetPay'], 2) }}</td>
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
