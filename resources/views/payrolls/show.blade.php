@extends('layouts.app')

@section('title', 'Payroll Details')

@section('content')
<div class="container py-4">
    @php
        $dob = $payroll->employee->DateOfBirth ? \Carbon\Carbon::parse($payroll->employee->DateOfBirth) : null;
        $appointment = $payroll->employee->AppointmentDate ? \Carbon\Carbon::parse($payroll->employee->AppointmentDate) : null;
        $age = $dob ? $dob->age : null;
        $yearsBeforeRetirement = $age !== null ? max(0, 60 - $age) : null;
        $serviceYears = $appointment ? $appointment->diffInYears(now()) : null;
    @endphp
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Payroll Details</h4>
            <p class="text-muted mb-0">{{ $payroll->employee->full_name ?? $payroll->EmployeeNumber }} | {{ $payroll->PeriodEnd->format('F Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('payrolls.index') }}" class="btn btn-outline-secondary">Back</a>
            <a href="{{ route('payrolls.receipt', $payroll) }}" class="btn btn-primary">Open Receipt</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Employee Information</div>
                <div class="card-body">
                    <div class="mb-2"><span class="text-muted">Employee No:</span> {{ $payroll->EmployeeNumber }}</div>
                    <div class="mb-2"><span class="text-muted">Home Address:</span> {{ $payroll->employee->HomeAddress ?: '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Residential Address:</span> {{ $payroll->employee->ResidentialAddress ?: '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Next of Kin:</span> {{ $payroll->employee->NextOfKin ?: '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Department:</span> {{ $payroll->employee->department->DepartmentName ?? '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Position:</span> {{ $payroll->employee->position->PositionName ?? '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Grade:</span> {{ $payroll->employee->grade->GradeName ?? '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Appointment Date:</span> {{ $appointment ? $appointment->format('d M Y') : '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Date of Birth:</span> {{ $dob ? $dob->format('d M Y') : '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Age:</span> {{ $age !== null ? $age : '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Years Before Retirement:</span> {{ $yearsBeforeRetirement !== null ? $yearsBeforeRetirement : '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Period in Service (yrs):</span> {{ $serviceYears !== null ? $serviceYears : '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Duty Station:</span> {{ $payroll->employee->DutyStation ?: '-' }}</div>
                    <div class="mb-2"><span class="text-muted">Bank:</span> {{ $payroll->employee->BankName ?: '-' }}</div>
                    <div class="mb-0"><span class="text-muted">Account:</span> {{ $payroll->employee->BankAccountNumber ?: '-' }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Payroll Breakdown</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Gross Pay</div><div class="fs-5 fw-bold">{{ number_format((float) $payroll->GrossPay, 2) }}</div></div></div>
                        <div class="col-md-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Take Home</div><div class="fs-5 fw-bold text-primary">{{ number_format((float) $payroll->NetPay, 2) }}</div></div></div>
                    </div>

                    <div class="row g-4 mt-1">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Earnings</h6>
                            <div class="d-flex justify-content-between py-1"><span>Basic Pay</span><span>{{ number_format((float) $payroll->BasicPay, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Housing Allowance</span><span>{{ number_format((float) $payroll->HousingAllowance, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Other Allowances</span><span>{{ number_format((float) $payroll->OtherAllowance, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Overtime Hrs - Wdays</span><span>{{ number_format((float) $payroll->OvertimeHoursWeekdays, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Overtime Hrs - Wend</span><span>{{ number_format((float) $payroll->OvertimeHoursWeekend, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Overtime Pay</span><span>{{ number_format((float) $payroll->OvertimePay, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Bonus</span><span>{{ number_format((float) $payroll->Bonus, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Transport Allowance</span><span>{{ number_format((float) $payroll->TransportAllowance, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Medical Allowance</span><span>{{ number_format((float) $payroll->MedicalAllowance, 2) }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Deductions</h6>
                            <div class="d-flex justify-content-between py-1"><span>PAYE</span><span>{{ number_format((float) $payroll->PAYE, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Employee Pension</span><span>{{ number_format((float) $payroll->EmployeePension, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>TEVET Levy</span><span>{{ number_format((float) $payroll->TevetLevy, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Loan/Advance</span><span>{{ number_format((float) $payroll->LoanAdvanceDeduction, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Other Deductions</span><span>{{ number_format((float) $payroll->OtherDeductions, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1 border-top mt-2 pt-2 fw-bold"><span>Total Deductions</span><span>{{ number_format((float) $payroll->Deductions, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Employer Pension</span><span>{{ number_format((float) $payroll->EmployerPension, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1"><span>Admin Fees</span><span>{{ number_format((float) $payroll->AdminFees, 2) }}</span></div>
                            <div class="d-flex justify-content-between py-1 fw-bold"><span>Total Contribution</span><span>{{ number_format((float) $payroll->TotalContribution, 2) }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
