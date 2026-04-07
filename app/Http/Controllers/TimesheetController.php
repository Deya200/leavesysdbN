<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (in_array((int) $user->role_id, [1, 2], true)) {
            $timesheets = Timesheet::with('employee')
                ->orderByDesc('WorkDate')
                ->paginate(20);
        } else {
            $timesheets = Timesheet::with('employee')
                ->where('EmployeeNumber', $user->EmployeeNumber)
                ->orderByDesc('WorkDate')
                ->paginate(20);
        }

        return view('timesheets.index', compact('timesheets'));
    }

    public function create()
    {
        if ((int) auth()->user()->role_id !== 3) {
            return redirect()->route('timesheets.index')->with('error', 'Only employees can submit timesheets.');
        }

        return view('timesheets.create');
    }

    public function store(Request $request)
    {
        $employee = auth()->user();

        if ((int) $employee->role_id !== 3) {
            return redirect()->route('timesheets.index')->with('error', 'Only employees can submit timesheets.');
        }

        $validated = $request->validate([
            'WorkDate' => 'required|date|before_or_equal:today',
            'HoursWorked' => 'required|numeric|min:0|max:24',
            'OvertimeHours' => 'nullable|numeric|min:0|max:24',
            'Notes' => 'nullable|string|max:1000',
        ]);

        $exists = Timesheet::where('EmployeeNumber', $employee->EmployeeNumber)
            ->whereDate('WorkDate', $validated['WorkDate'])
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'WorkDate' => 'A timesheet for this date already exists.',
            ])->withInput();
        }

        Timesheet::create([
            'EmployeeNumber' => $employee->EmployeeNumber,
            'WorkDate' => $validated['WorkDate'],
            'HoursWorked' => $validated['HoursWorked'],
            'OvertimeHours' => $validated['OvertimeHours'] ?? 0,
            'Notes' => $validated['Notes'] ?? null,
            'Status' => 'Pending',
        ]);

        return redirect()->route('timesheets.index')->with('success', 'Timesheet submitted successfully.');
    }

    public function approve(Timesheet $timesheet)
    {
        $user = auth()->user();

        if (!in_array((int) $user->role_id, [1, 2], true)) {
            return redirect()->back()->with('error', 'You do not have permission to approve timesheets.');
        }

        $timesheet->update([
            'Status' => 'Approved',
            'ApprovedBy' => $user->EmployeeNumber,
            'ApprovedAt' => now(),
        ]);

        return redirect()->route('timesheets.index')->with('success', 'Timesheet approved successfully.');
    }

    public function reject(Timesheet $timesheet)
    {
        $user = auth()->user();

        if (!in_array((int) $user->role_id, [1, 2], true)) {
            return redirect()->back()->with('error', 'You do not have permission to reject timesheets.');
        }

        $timesheet->update([
            'Status' => 'Rejected',
            'ApprovedBy' => $user->EmployeeNumber,
            'ApprovedAt' => now(),
        ]);

        return redirect()->route('timesheets.index')->with('success', 'Timesheet rejected successfully.');
    }
}
