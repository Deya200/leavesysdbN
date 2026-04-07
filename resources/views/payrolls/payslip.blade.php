@extends('layouts.app')

@section('title', 'Payslip')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-outline-secondary">Back to Details</a>
        <a href="{{ route('payrolls.receipt', $payroll) }}" class="btn btn-outline-primary">Print Receipt</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h4 class="fw-bold mb-2">ABC LIMITED PAY SLIP</h4>
            <div class="text-muted mb-4">Period: {{ $payroll->PeriodStart->format('d M Y') }} - {{ $payroll->PeriodEnd->format('d M Y') }}</div>

            <div class="row g-3 mb-4">
                <div class="col-md-3"><div class="small text-muted">Employee Name</div><div class="fw-semibold">{{ $payroll->employee->full_name }}</div></div>
                <div class="col-md-3"><div class="small text-muted">Employee Number</div><div class="fw-semibold">{{ $payroll->EmployeeNumber }}</div></div>
                <div class="col-md-3"><div class="small text-muted">Position</div><div class="fw-semibold">{{ $payroll->employee->position->PositionName ?? '-' }}</div></div>
                <div class="col-md-3"><div class="small text-muted">Grade</div><div class="fw-semibold">{{ $payroll->employee->grade->GradeName ?? '-' }}</div></div>
                <div class="col-md-3"><div class="small text-muted">Department</div><div class="fw-semibold">{{ $payroll->employee->department->DepartmentName ?? '-' }}</div></div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="fw-bold">Earnings</h6>
                    <div class="d-flex justify-content-between py-1"><span>Basic Pay</span><span>{{ number_format((float) $payroll->BasicPay, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>Housing Allowance</span><span>{{ number_format((float) $payroll->HousingAllowance, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>Other Allowances</span><span>{{ number_format((float) $payroll->OtherAllowance, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>Overtime</span><span>{{ number_format((float) $payroll->OvertimePay, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>Bonus</span><span>{{ number_format((float) $payroll->Bonus, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>Gross Pay</span><span class="fw-bold">{{ number_format((float) $payroll->GrossPay, 2) }}</span></div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold">Deductions</h6>
                    <div class="d-flex justify-content-between py-1"><span>PAYE</span><span>{{ number_format((float) $payroll->PAYE, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>Employee Pension</span><span>{{ number_format((float) $payroll->EmployeePension, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>TEVET Levy</span><span>{{ number_format((float) $payroll->TevetLevy, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>Loan/Advance</span><span>{{ number_format((float) $payroll->LoanAdvanceDeduction, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>Other Deductions</span><span>{{ number_format((float) $payroll->OtherDeductions, 2) }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span>Total Deductions</span><span class="fw-bold">{{ number_format((float) $payroll->Deductions, 2) }}</span></div>
                </div>
            </div>

            <div class="mt-4 p-3 rounded-3" style="background:#eff6ff;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">Takehome</div>
                    <div class="fs-4 fw-bold text-primary">{{ number_format((float) $payroll->NetPay, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
