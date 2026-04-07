<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveType;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $this->ensureStatutoryLeaveTypes();

        $leaveTypes = LeaveType::orderBy('LeaveTypeName')->get();
        return view('leave_types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('leave_types.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'LeaveTypeName' => 'required|string|max:255',
            'IsPaidLeave' => 'required|boolean',
            'GenderApplicable' => 'required|string|in:Male,Female,Both',
            'MaxLeaveDays' => 'nullable|integer|min:0',
        ]);

        if ($this->leaveTypeNameExists($validatedData['LeaveTypeName'])) {
            return redirect()->back()->withErrors([
                'LeaveTypeName' => 'This leave type already exists.',
            ])->withInput();
        }

        LeaveType::create($validatedData);

        return redirect()->route('leave_types.index')->with('success', 'Leave type created successfully.');
    }

    public function edit($LeaveTypeID)
    {
        $leaveType = LeaveType::findOrFail($LeaveTypeID);
        return view('leave_types.edit', compact('leaveType'));
    }

    public function update(Request $request, $LeaveTypeID)
    {
        $leaveType = LeaveType::findOrFail($LeaveTypeID);

        if ($leaveType->isStatutory()) {
            return redirect()->route('leave_types.index')->with('error', 'This leave type is mandatory under Malawi Employment Act settings and cannot be edited.');
        }

        $validatedData = $request->validate([
            'LeaveTypeName' => 'required|string|max:255',
            'IsPaidLeave' => 'required|boolean',
            'GenderApplicable' => 'required|string|in:Male,Female,Both',
            'MaxLeaveDays' => 'nullable|integer|min:0',
        ]);

        if ($this->leaveTypeNameExists($validatedData['LeaveTypeName'], (int) $leaveType->LeaveTypeID)) {
            return redirect()->back()->withErrors([
                'LeaveTypeName' => 'This leave type already exists.',
            ])->withInput();
        }

        $leaveType->update($validatedData);

        return redirect()->route('leave_types.index')->with('success', 'Leave type updated successfully.');
    }

    public function destroy($LeaveTypeID)
    {
        $leaveType = LeaveType::findOrFail($LeaveTypeID);

        if ($leaveType->isStatutory()) {
            return redirect()->route('leave_types.index')->with('error', 'This leave type is mandatory under Malawi Employment Act settings and cannot be deleted.');
        }

        $leaveType->delete();

        return redirect()->route('leave_types.index')->with('success', 'Leave type deleted successfully.');
    }

    private function ensureStatutoryLeaveTypes(): void
    {
        foreach (LeaveType::STATUTORY_LEAVES as $leave) {
            LeaveType::updateOrCreate(
                ['LeaveTypeName' => $leave['LeaveTypeName']],
                [
                    'IsPaidLeave' => $leave['IsPaidLeave'],
                    'GenderApplicable' => $leave['GenderApplicable'],
                    'MaxLeaveDays' => $leave['MaxLeaveDays'],
                    'MinServiceYears' => $leave['MinServiceYears'],
                    'DeductsFromAnnual' => $leave['DeductsFromAnnual'],
                ]
            );
        }
    }

    private function leaveTypeNameExists(string $name, ?int $ignoreId = null): bool
    {
        $query = LeaveType::query();

        if ($ignoreId !== null) {
            $query->where('LeaveTypeID', '!=', $ignoreId);
        }

        return $query
            ->pluck('LeaveTypeName')
            ->contains(fn($existingName) => strcasecmp($existingName, $name) === 0);
    }
}
