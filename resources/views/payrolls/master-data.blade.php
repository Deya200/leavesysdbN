@extends('layouts.app')

@section('title', 'Payroll Master Data')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">Payroll Master Data</h4>
            <p class="text-muted mb-0">Employee whereabouts, deductions, pension references, and account details.</p>
        </div>
        <a href="{{ route('payrolls.index') }}" class="btn btn-outline-secondary">Back to Payroll</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee Name</th>
                        <th>Home Address</th>
                        <th>Residential Address</th>
                        <th>Next of Kin</th>
                        <th>Employee No</th>
                        <th>Position</th>
                        <th>Grade</th>
                        <th>Department</th>
                        <th>Appointment Date</th>
                        <th>Date of Birth</th>
                        <th>Age</th>
                        <th>Years Before Retirement</th>
                        <th>Period in Service (yrs)</th>
                        <th>Basic Pay</th>
                        <th>Housing Allowance</th>
                        <th>Other Allowances</th>
                        <th>Overtime Hrs - Wdays</th>
                        <th>Overtime Hrs - Wend</th>
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
                        <th>Employer Pension</th>
                        <th>Admin Fees</th>
                        <th>Total Contribution</th>
                        <th>Bank Name</th>
                        <th>Account Number</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        @php
                            $latestPayroll = $employee->payrolls->first();
                            $dob = $employee->DateOfBirth ? \Carbon\Carbon::parse($employee->DateOfBirth) : null;
                            $appointment = $employee->AppointmentDate ? \Carbon\Carbon::parse($employee->AppointmentDate) : null;
                            $age = $dob ? $dob->age : null;
                            $yearsBeforeRetirement = $age !== null ? max(0, 60 - $age) : null;
                            $serviceYears = $appointment ? $appointment->diffInYears(now()) : null;
                        @endphp
                        <tr>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->HomeAddress ?: '-' }}</td>
                            <td>{{ $employee->ResidentialAddress ?: '-' }}</td>
                            <td>{{ $employee->NextOfKin ?: '-' }}</td>
                            <td>{{ $employee->EmployeeNumber }}</td>
                            <td>{{ $employee->position->PositionName ?? '-' }}</td>
                            <td>{{ $employee->grade->GradeName ?? '-' }}</td>
                            <td>{{ $employee->department->DepartmentName ?? '-' }}</td>
                            <td>{{ $employee->AppointmentDate ? \Carbon\Carbon::parse($employee->AppointmentDate)->format('d M Y') : '-' }}</td>
                            <td>{{ $dob ? $dob->format('d M Y') : '-' }}</td>
                            <td>{{ $age !== null ? $age : '-' }}</td>
                            <td>{{ $yearsBeforeRetirement !== null ? $yearsBeforeRetirement : '-' }}</td>
                            <td>{{ $serviceYears !== null ? $serviceYears : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->BasicPay, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->HousingAllowance, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->OtherAllowance, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->OvertimeHoursWeekdays, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->OvertimeHoursWeekend, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->OvertimePay, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->Bonus, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->GrossPay, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->PAYE, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->EmployeePension, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->TevetLevy, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->LoanAdvanceDeduction, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->OtherDeductions, 2) : '-' }}</td>
                            <td class="fw-bold">{{ $latestPayroll ? number_format((float) $latestPayroll->Deductions, 2) : '-' }}</td>
                            <td class="fw-bold">{{ $latestPayroll ? number_format((float) $latestPayroll->NetPay, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->EmployerPension, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->AdminFees, 2) : '-' }}</td>
                            <td>{{ $latestPayroll ? number_format((float) $latestPayroll->TotalContribution, 2) : '-' }}</td>
                            <td>{{ $employee->BankName ?: '-' }}</td>
                            <td>{{ $employee->BankAccountNumber ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="33" class="text-center py-4 text-muted">No employees found.</td>
                        </tr>
                    @endforelse
                    @if($employees->count() > 0)
                        <tr class="table-light fw-bold">
                            <td>TOTALS</td>
                            <td colspan="12"></td>
                            <td>{{ number_format((float) $totals['BasicPay'], 2) }}</td>
                            <td>{{ number_format((float) $totals['HousingAllowance'], 2) }}</td>
                            <td>{{ number_format((float) $totals['OtherAllowance'], 2) }}</td>
                            <td>{{ number_format((float) $totals['OvertimeHoursWeekdays'], 2) }}</td>
                            <td>{{ number_format((float) $totals['OvertimeHoursWeekend'], 2) }}</td>
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
                            <td>{{ number_format((float) $totals['EmployerPension'], 2) }}</td>
                            <td>{{ number_format((float) $totals['AdminFees'], 2) }}</td>
                            <td>{{ number_format((float) $totals['TotalContribution'], 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $employees->links() }}
    </div>
</div>
@endsection
