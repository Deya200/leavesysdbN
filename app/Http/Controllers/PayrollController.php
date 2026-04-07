<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $payrolls = $this->basePayrollQuery($user)->paginate(20);
        $latestPayrolls = $this->basePayrollQuery($user)->get();

        $summary = [
            'employees' => $latestPayrolls->pluck('EmployeeNumber')->unique()->count(),
            'gross' => $latestPayrolls->sum(fn ($payroll) => (float) $payroll->GrossPay),
            'deductions' => $latestPayrolls->sum(fn ($payroll) => (float) $payroll->Deductions),
            'net' => $latestPayrolls->sum(fn ($payroll) => (float) $payroll->NetPay),
        ];

        return view('payrolls.index', compact('payrolls', 'summary'));
    }

    public function create()
    {
        $user = auth()->user();
        if ((int) $user->role_id !== 1) {
            return redirect()->route('payrolls.index')->with('error', 'Only admins can create payroll records.');
        }

        $employees = Employee::with(['department', 'position'])
            ->orderBy('FirstName')
            ->orderBy('LastName')
            ->get();

        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ((int) $user->role_id !== 1) {
            return redirect()->route('payrolls.index')->with('error', 'Only admins can create payroll records.');
        }

        $validated = $request->validate([
            'EmployeeNumber' => 'required|exists:employees,EmployeeNumber',
            'PeriodStart' => 'required|date|before_or_equal:PeriodEnd',
            'PeriodEnd' => 'required|date|after_or_equal:PeriodStart',
            'BasicPay' => 'required|numeric|min:0',
            'HousingAllowance' => 'nullable|numeric|min:0',
            'OtherAllowance' => 'nullable|numeric|min:0',
            'OvertimeHoursWeekdays' => 'nullable|numeric|min:0',
            'OvertimeHoursWeekend' => 'nullable|numeric|min:0',
            'OvertimePay' => 'nullable|numeric|min:0',
            'Bonus' => 'nullable|numeric|min:0',
            'TransportAllowance' => 'nullable|numeric|min:0',
            'MedicalAllowance' => 'nullable|numeric|min:0',
            'PAYE' => 'nullable|numeric|min:0',
            'EmployeePension' => 'nullable|numeric|min:0',
            'TevetLevy' => 'nullable|numeric|min:0',
            'LoanAdvanceDeduction' => 'nullable|numeric|min:0',
            'OtherDeductions' => 'nullable|numeric|min:0',
            'EmployerPension' => 'nullable|numeric|min:0',
            'AdminFees' => 'nullable|numeric|min:0',
            'Status' => 'required|in:Draft,Processed,Paid',
        ]);

        $duplicate = Payroll::where('EmployeeNumber', $validated['EmployeeNumber'])
            ->whereDate('PeriodStart', $validated['PeriodStart'])
            ->whereDate('PeriodEnd', $validated['PeriodEnd'])
            ->exists();

        if ($duplicate) {
            return redirect()->back()->withErrors([
                'PeriodStart' => 'Payroll for this employee and period already exists.',
            ])->withInput();
        }

        $basic = (float) $validated['BasicPay'];
        $housing = (float) ($validated['HousingAllowance'] ?? 0);
        $otherAllowance = (float) ($validated['OtherAllowance'] ?? 0);
        $overtimeHoursWeekdays = (float) ($validated['OvertimeHoursWeekdays'] ?? 0);
        $overtimeHoursWeekend = (float) ($validated['OvertimeHoursWeekend'] ?? 0);
        $overtimePay = (float) ($validated['OvertimePay'] ?? 0);
        $bonus = (float) ($validated['Bonus'] ?? 0);
        $transport = (float) ($validated['TransportAllowance'] ?? 0);
        $medical = (float) ($validated['MedicalAllowance'] ?? 0);

        $gross = $basic + $housing + $otherAllowance + $overtimePay + $bonus + $transport + $medical;

        $paye = (float) ($validated['PAYE'] ?? 0);
        $employeePension = (float) ($validated['EmployeePension'] ?? 0);
        $tevetLevy = (float) ($validated['TevetLevy'] ?? 0);
        $loanAdvance = (float) ($validated['LoanAdvanceDeduction'] ?? 0);
        $otherDeductions = (float) ($validated['OtherDeductions'] ?? 0);
        $employerPension = (float) ($validated['EmployerPension'] ?? 0);
        $adminFees = (float) ($validated['AdminFees'] ?? 0);

        $totalDeductions = $paye + $employeePension + $tevetLevy + $loanAdvance + $otherDeductions;
        $takeHome = max(0, $gross - $totalDeductions);
        $totalContribution = $employerPension + $adminFees;

        Payroll::create([
            'EmployeeNumber' => $validated['EmployeeNumber'],
            'PeriodStart' => $validated['PeriodStart'],
            'PeriodEnd' => $validated['PeriodEnd'],
            'BasicPay' => $basic,
            'HousingAllowance' => $housing,
            'OtherAllowance' => $otherAllowance,
            'OvertimeHoursWeekdays' => $overtimeHoursWeekdays,
            'OvertimeHoursWeekend' => $overtimeHoursWeekend,
            'OvertimePay' => $overtimePay,
            'Bonus' => $bonus,
            'TransportAllowance' => $transport,
            'MedicalAllowance' => $medical,
            'GrossPay' => $gross,
            'PAYE' => $paye,
            'EmployeePension' => $employeePension,
            'TevetLevy' => $tevetLevy,
            'LoanAdvanceDeduction' => $loanAdvance,
            'OtherDeductions' => $otherDeductions,
            'Deductions' => $totalDeductions,
            'NetPay' => $takeHome,
            'EmployerPension' => $employerPension,
            'AdminFees' => $adminFees,
            'TotalContribution' => $totalContribution,
            'Status' => $validated['Status'],
            'ProcessedBy' => $user->EmployeeNumber,
            'ProcessedAt' => now(),
        ]);

        return redirect()->route('payrolls.index')->with('success', 'Payroll record created successfully.');
    }

    public function masterData()
    {
        $this->ensureAdmin();

        $employees = Employee::with([
                'department',
                'position',
                'grade',
                'payrolls' => fn ($query) => $query->orderByDesc('PeriodEnd'),
            ])
            ->orderBy('FirstName')
            ->orderBy('LastName')
            ->paginate(20);

        $pagePayrolls = $employees->getCollection()
            ->map(fn ($employee) => $employee->payrolls->first())
            ->filter();

        $totals = [
            'BasicPay' => $pagePayrolls->sum('BasicPay'),
            'HousingAllowance' => $pagePayrolls->sum('HousingAllowance'),
            'OtherAllowance' => $pagePayrolls->sum('OtherAllowance'),
            'OvertimeHoursWeekdays' => $pagePayrolls->sum('OvertimeHoursWeekdays'),
            'OvertimeHoursWeekend' => $pagePayrolls->sum('OvertimeHoursWeekend'),
            'OvertimePay' => $pagePayrolls->sum('OvertimePay'),
            'Bonus' => $pagePayrolls->sum('Bonus'),
            'GrossPay' => $pagePayrolls->sum('GrossPay'),
            'PAYE' => $pagePayrolls->sum('PAYE'),
            'EmployeePension' => $pagePayrolls->sum('EmployeePension'),
            'TevetLevy' => $pagePayrolls->sum('TevetLevy'),
            'LoanAdvanceDeduction' => $pagePayrolls->sum('LoanAdvanceDeduction'),
            'OtherDeductions' => $pagePayrolls->sum('OtherDeductions'),
            'Deductions' => $pagePayrolls->sum('Deductions'),
            'NetPay' => $pagePayrolls->sum('NetPay'),
            'EmployerPension' => $pagePayrolls->sum('EmployerPension'),
            'AdminFees' => $pagePayrolls->sum('AdminFees'),
            'TotalContribution' => $pagePayrolls->sum('TotalContribution'),
        ];

        return view('payrolls.master-data', compact('employees', 'totals'));
    }

    public function pensionDeductions()
    {
        $this->ensureAdmin();

        $payrolls = Payroll::with('employee')
            ->orderByDesc('PeriodEnd')
            ->paginate(20);

        $totals = [
            'employee_pension' => Payroll::sum('EmployeePension'),
            'employer_pension' => Payroll::sum('EmployerPension'),
            'paye' => Payroll::sum('PAYE'),
            'other_deductions' => Payroll::sum('OtherDeductions'),
            'deductions' => Payroll::sum('Deductions'),
            'admin_fees' => Payroll::sum('AdminFees'),
            'total_contribution' => Payroll::sum('TotalContribution'),
        ];

        return view('payrolls.pension-deductions', compact('payrolls', 'totals'));
    }

    public function bankList()
    {
        $this->ensureAdmin();

        $employees = Employee::with([
                'payrolls' => fn ($query) => $query->orderByDesc('PeriodEnd'),
                'position',
            ])
            ->orderBy('FirstName')
            ->orderBy('LastName')
            ->paginate(20);

        $pagePayrolls = $employees->getCollection()
            ->map(fn ($employee) => $employee->payrolls->first())
            ->filter();

        $totals = [
            'NetPay' => $pagePayrolls->sum('NetPay'),
        ];

        return view('payrolls.bank-list', compact('employees', 'totals'));
    }

    public function payrollReport()
    {
        $this->ensureAdmin();

        $payrolls = Payroll::with(['employee.position', 'employee.grade', 'employee.department'])
            ->orderByDesc('PeriodEnd')
            ->paginate(20);

        $totals = [
            'BasicPay' => Payroll::sum('BasicPay'),
            'HousingAllowance' => Payroll::sum('HousingAllowance'),
            'OtherAllowance' => Payroll::sum('OtherAllowance'),
            'OvertimePay' => Payroll::sum('OvertimePay'),
            'Bonus' => Payroll::sum('Bonus'),
            'GrossPay' => Payroll::sum('GrossPay'),
            'PAYE' => Payroll::sum('PAYE'),
            'EmployeePension' => Payroll::sum('EmployeePension'),
            'TevetLevy' => Payroll::sum('TevetLevy'),
            'LoanAdvanceDeduction' => Payroll::sum('LoanAdvanceDeduction'),
            'OtherDeductions' => Payroll::sum('OtherDeductions'),
            'Deductions' => Payroll::sum('Deductions'),
            'NetPay' => Payroll::sum('NetPay'),
        ];

        return view('payrolls.payroll-report', compact('payrolls', 'totals'));
    }

    public function show(Payroll $payroll)
    {
        $this->authorizePayrollAccess($payroll);

        $payroll->load(['employee.department', 'employee.position', 'employee.grade', 'processor']);

        return view('payrolls.show', compact('payroll'));
    }

    public function receipt(Payroll $payroll, Request $request)
    {
        $this->authorizePayrollAccess($payroll);

        $payroll->load(['employee.department', 'employee.position', 'employee.grade', 'processor']);

        if ($request->query('download') === 'pdf') {
            $pdf = PDF::loadView('payrolls.receipt-pdf', compact('payroll'));
            return $pdf->download('payroll_receipt_' . $payroll->EmployeeNumber . '_' . $payroll->PayrollID . '.pdf');
        }

        return view('payrolls.receipt', compact('payroll'));
    }

    public function payslip(Payroll $payroll)
    {
        $this->authorizePayrollAccess($payroll);

        $payroll->load(['employee.department', 'employee.position', 'employee.grade']);

        return view('payrolls.payslip', compact('payroll'));
    }

    protected function basePayrollQuery($user)
    {
        $query = Payroll::with(['employee.department', 'employee.position', 'employee.grade'])
            ->orderByDesc('PeriodEnd');

        if ((int) $user->role_id !== 1) {
            $query->where('EmployeeNumber', $user->EmployeeNumber);
        }

        return $query;
    }

    protected function ensureAdmin(): void
    {
        if ((int) auth()->user()->role_id !== 1) {
            abort(403);
        }
    }

    protected function authorizePayrollAccess(Payroll $payroll): void
    {
        $user = auth()->user();

        if ((int) $user->role_id !== 1 && $payroll->EmployeeNumber !== $user->EmployeeNumber) {
            abort(403);
        }
    }
}
