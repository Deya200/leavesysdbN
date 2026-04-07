@extends('layouts.app')

@section('title', 'Payroll Receipt')

@section('styles')
<style>
    .receipt-sheet {
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .receipt-banner {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: #fff;
        padding: 2rem;
    }

    @media print {
        header, footer, .offcanvas, .btn, .no-print {
            display: none !important;
        }

        main {
            margin: 0 !important;
            width: 100% !important;
            padding: 0 !important;
        }

        .receipt-sheet {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 no-print">
        <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-outline-secondary">Back to Details</a>
        <div class="d-flex gap-2">
            <a href="{{ route('payrolls.receipt', [$payroll, 'download' => 'pdf']) }}" class="btn btn-outline-primary">Download PDF</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">Print Receipt</button>
        </div>
    </div>

    <div class="receipt-sheet">
        <div class="receipt-banner">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="text-uppercase small opacity-75">Payroll Receipt</div>
                    <h3 class="mb-1 fw-bold">{{ $payroll->employee->full_name ?? $payroll->EmployeeNumber }}</h3>
                    <div>{{ $payroll->employee->position->PositionName ?? 'Employee' }} | {{ $payroll->employee->department->DepartmentName ?? 'Department' }}</div>
                </div>
                <div class="text-lg-end">
                    <div class="small opacity-75">Period</div>
                    <div class="fw-semibold">{{ $payroll->PeriodStart->format('d M Y') }} - {{ $payroll->PeriodEnd->format('d M Y') }}</div>
                    <div class="small mt-2">Employee No: {{ $payroll->EmployeeNumber }}</div>
                </div>
            </div>
        </div>

        <div class="p-4 p-lg-5">
            <div class="row g-4 mb-4">
                <div class="col-md-4"><div class="border rounded-3 p-3 h-100"><div class="small text-muted">Home Address</div><div class="fw-semibold">{{ $payroll->employee->HomeAddress ?: '-' }}</div></div></div>
                <div class="col-md-4"><div class="border rounded-3 p-3 h-100"><div class="small text-muted">Residential Address</div><div class="fw-semibold">{{ $payroll->employee->ResidentialAddress ?: '-' }}</div></div></div>
                <div class="col-md-4"><div class="border rounded-3 p-3 h-100"><div class="small text-muted">Next of Kin</div><div class="fw-semibold">{{ $payroll->employee->NextOfKin ?: '-' }}</div></div></div>
                <div class="col-md-4"><div class="border rounded-3 p-3 h-100"><div class="small text-muted">Duty Station</div><div class="fw-semibold">{{ $payroll->employee->DutyStation ?: '-' }}</div></div></div>
                <div class="col-md-4"><div class="border rounded-3 p-3 h-100"><div class="small text-muted">Bank Account</div><div class="fw-semibold">{{ $payroll->employee->BankName ?: '-' }}</div><div class="text-muted small">{{ $payroll->employee->BankAccountNumber ?: 'No account on file' }}</div></div></div>
                <div class="col-md-4"><div class="border rounded-3 p-3 h-100"><div class="small text-muted">Pension Number</div><div class="fw-semibold">{{ $payroll->employee->PensionNumber ?: '-' }}</div></div></div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Earnings</h5>
                    <div class="border rounded-3 p-3">
                        <div class="d-flex justify-content-between py-2"><span>Basic Pay</span><span>{{ number_format((float) $payroll->BasicPay, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Housing Allowance</span><span>{{ number_format((float) $payroll->HousingAllowance, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Other Allowances</span><span>{{ number_format((float) $payroll->OtherAllowance, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Overtime Hrs - Wdays</span><span>{{ number_format((float) $payroll->OvertimeHoursWeekdays, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Overtime Hrs - Wend</span><span>{{ number_format((float) $payroll->OvertimeHoursWeekend, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Overtime Pay</span><span>{{ number_format((float) $payroll->OvertimePay, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Bonus</span><span>{{ number_format((float) $payroll->Bonus, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Transport Allowance</span><span>{{ number_format((float) $payroll->TransportAllowance, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Medical Allowance</span><span>{{ number_format((float) $payroll->MedicalAllowance, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2 border-top mt-2 pt-3 fw-bold"><span>Gross Pay</span><span>{{ number_format((float) $payroll->GrossPay, 2) }}</span></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Deductions</h5>
                    <div class="border rounded-3 p-3">
                        <div class="d-flex justify-content-between py-2"><span>PAYE</span><span>{{ number_format((float) $payroll->PAYE, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Employee Pension Contribution</span><span>{{ number_format((float) $payroll->EmployeePension, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>TEVET Levy</span><span>{{ number_format((float) $payroll->TevetLevy, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Loan/Advance Deduction</span><span>{{ number_format((float) $payroll->LoanAdvanceDeduction, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Other Deductions</span><span>{{ number_format((float) $payroll->OtherDeductions, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2 border-top mt-2 pt-3 fw-bold"><span>Total Deductions</span><span>{{ number_format((float) $payroll->Deductions, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Employer Pension Contribution</span><span>{{ number_format((float) $payroll->EmployerPension, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Admin Fees</span><span>{{ number_format((float) $payroll->AdminFees, 2) }}</span></div>
                        <div class="d-flex justify-content-between py-2 fw-bold"><span>Total Contribution</span><span>{{ number_format((float) $payroll->TotalContribution, 2) }}</span></div>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-4 rounded-3" style="background:#eff6ff;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-muted">Take Home Pay</div>
                        <div class="fs-3 fw-bold text-primary">{{ number_format((float) $payroll->NetPay, 2) }}</div>
                    </div>
                    <div class="text-end text-muted small">
                        <div>Status: {{ $payroll->Status }}</div>
                        <div>Processed: {{ optional($payroll->ProcessedAt)->format('d M Y H:i') ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
