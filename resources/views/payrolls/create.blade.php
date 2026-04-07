@extends('layouts.app')

@section('title', 'Create Payroll')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Create Payroll Record</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('payrolls.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Employee</label>
                            <select name="EmployeeNumber" class="form-select" required>
                                <option value="" disabled selected>Select employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->EmployeeNumber }}" {{ old('EmployeeNumber') === $employee->EmployeeNumber ? 'selected' : '' }}>
                                        {{ $employee->EmployeeNumber }} - {{ $employee->FirstName }} {{ $employee->LastName }}
                                        @if($employee->BankName)
                                            | {{ $employee->BankName }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Period Start</label>
                                <input type="date" name="PeriodStart" class="form-control" value="{{ old('PeriodStart') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Period End</label>
                                <input type="date" name="PeriodEnd" class="form-control" value="{{ old('PeriodEnd') }}" required>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3">Earnings and Allowances</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Basic Pay</label>
                                <input type="number" step="0.01" min="0" name="BasicPay" class="form-control" value="{{ old('BasicPay') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Housing Allowance</label>
                                <input type="number" step="0.01" min="0" name="HousingAllowance" class="form-control" value="{{ old('HousingAllowance', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Other Allowances</label>
                                <input type="number" step="0.01" min="0" name="OtherAllowance" class="form-control" value="{{ old('OtherAllowance', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Overtime Hours - Weekdays</label>
                                <input type="number" step="0.01" min="0" name="OvertimeHoursWeekdays" class="form-control" value="{{ old('OvertimeHoursWeekdays', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Overtime Hours - Weekend</label>
                                <input type="number" step="0.01" min="0" name="OvertimeHoursWeekend" class="form-control" value="{{ old('OvertimeHoursWeekend', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Overtime Pay</label>
                                <input type="number" step="0.01" min="0" name="OvertimePay" class="form-control" value="{{ old('OvertimePay', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bonus</label>
                                <input type="number" step="0.01" min="0" name="Bonus" class="form-control" value="{{ old('Bonus', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Transport Allowance</label>
                                <input type="number" step="0.01" min="0" name="TransportAllowance" class="form-control" value="{{ old('TransportAllowance', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Medical Allowance</label>
                                <input type="number" step="0.01" min="0" name="MedicalAllowance" class="form-control" value="{{ old('MedicalAllowance', 0) }}">
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3">Deductions</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">PAYE</label>
                                <input type="number" step="0.01" min="0" name="PAYE" class="form-control" value="{{ old('PAYE', 0) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Employee Pension Contribution</label>
                                <input type="number" step="0.01" min="0" name="EmployeePension" class="form-control" value="{{ old('EmployeePension', 0) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">TEVET Levy</label>
                                <input type="number" step="0.01" min="0" name="TevetLevy" class="form-control" value="{{ old('TevetLevy', 0) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Loan/Advance Deduction</label>
                                <input type="number" step="0.01" min="0" name="LoanAdvanceDeduction" class="form-control" value="{{ old('LoanAdvanceDeduction', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Other Deductions</label>
                                <input type="number" step="0.01" min="0" name="OtherDeductions" class="form-control" value="{{ old('OtherDeductions', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Employer Pension Contribution</label>
                                <input type="number" step="0.01" min="0" name="EmployerPension" class="form-control" value="{{ old('EmployerPension', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Admin Fees</label>
                                <input type="number" step="0.01" min="0" name="AdminFees" class="form-control" value="{{ old('AdminFees', 0) }}">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="Status" class="form-select" required>
                                    <option value="Draft" {{ old('Status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Processed" {{ old('Status') === 'Processed' ? 'selected' : '' }}>Processed</option>
                                    <option value="Paid" {{ old('Status') === 'Paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('payrolls.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Payroll</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
