<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payroll Receipt</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        .header { background: #1d4ed8; color: white; padding: 18px; }
        .section { margin-top: 18px; }
        .box { border: 1px solid #d1d5db; padding: 12px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .right { text-align: right; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0 0 6px 0;">Payroll Receipt</h2>
        <div>{{ $payroll->employee->full_name ?? $payroll->EmployeeNumber }}</div>
        <div>{{ $payroll->PeriodStart->format('d M Y') }} - {{ $payroll->PeriodEnd->format('d M Y') }}</div>
    </div>

    <div class="section">
        <div class="box">
            <strong>Employee Number:</strong> {{ $payroll->EmployeeNumber }}<br>
            <strong>Home Address:</strong> {{ $payroll->employee->HomeAddress ?: '-' }}<br>
            <strong>Residential Address:</strong> {{ $payroll->employee->ResidentialAddress ?: '-' }}<br>
            <strong>Next of Kin:</strong> {{ $payroll->employee->NextOfKin ?: '-' }}<br>
            <strong>Department:</strong> {{ $payroll->employee->department->DepartmentName ?? '-' }}<br>
            <strong>Position:</strong> {{ $payroll->employee->position->PositionName ?? '-' }}<br>
            <strong>Grade:</strong> {{ $payroll->employee->grade->GradeName ?? '-' }}<br>
            <strong>Duty Station:</strong> {{ $payroll->employee->DutyStation ?: '-' }}<br>
            <strong>Bank:</strong> {{ $payroll->employee->BankName ?: '-' }} {{ $payroll->employee->BankAccountNumber ? ' - ' . $payroll->employee->BankAccountNumber : '' }}<br>
            <strong>Pension Number:</strong> {{ $payroll->employee->PensionNumber ?: '-' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Earnings</th>
                <th class="right">Amount</th>
                <th>Deductions</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Basic Pay</td><td class="right">{{ number_format((float) $payroll->BasicPay, 2) }}</td><td>PAYE</td><td class="right">{{ number_format((float) $payroll->PAYE, 2) }}</td></tr>
            <tr><td>Housing Allowance</td><td class="right">{{ number_format((float) $payroll->HousingAllowance, 2) }}</td><td>Employee Pension</td><td class="right">{{ number_format((float) $payroll->EmployeePension, 2) }}</td></tr>
            <tr><td>Other Allowances</td><td class="right">{{ number_format((float) $payroll->OtherAllowance, 2) }}</td><td>TEVET Levy</td><td class="right">{{ number_format((float) $payroll->TevetLevy, 2) }}</td></tr>
            <tr><td>Overtime Hrs - Wdays</td><td class="right">{{ number_format((float) $payroll->OvertimeHoursWeekdays, 2) }}</td><td>Loan/Advance</td><td class="right">{{ number_format((float) $payroll->LoanAdvanceDeduction, 2) }}</td></tr>
            <tr><td>Overtime Hrs - Wend</td><td class="right">{{ number_format((float) $payroll->OvertimeHoursWeekend, 2) }}</td><td>Other Deductions</td><td class="right">{{ number_format((float) $payroll->OtherDeductions, 2) }}</td></tr>
            <tr><td>Overtime Pay</td><td class="right">{{ number_format((float) $payroll->OvertimePay, 2) }}</td><td class="total">Total Deductions</td><td class="right total">{{ number_format((float) $payroll->Deductions, 2) }}</td></tr>
            <tr><td>Bonus</td><td class="right">{{ number_format((float) $payroll->Bonus, 2) }}</td><td>Employer Pension</td><td class="right">{{ number_format((float) $payroll->EmployerPension, 2) }}</td></tr>
            <tr><td>Transport Allowance</td><td class="right">{{ number_format((float) $payroll->TransportAllowance, 2) }}</td><td>Admin Fees</td><td class="right">{{ number_format((float) $payroll->AdminFees, 2) }}</td></tr>
            <tr><td>Medical Allowance</td><td class="right">{{ number_format((float) $payroll->MedicalAllowance, 2) }}</td><td class="total">Total Contribution</td><td class="right total">{{ number_format((float) $payroll->TotalContribution, 2) }}</td></tr>
            <tr><td class="total">Gross Pay</td><td class="right total">{{ number_format((float) $payroll->GrossPay, 2) }}</td><td class="total">Take Home</td><td class="right total">{{ number_format((float) $payroll->NetPay, 2) }}</td></tr>
        </tbody>
    </table>
</body>
</html>
