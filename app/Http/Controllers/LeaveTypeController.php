<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveType;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::all();
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
            'GenderApplicable' => 'required|string|max:10',
        ]);

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
        $validatedData = $request->validate([
            'LeaveTypeName' => 'required|string|max:255',
            'IsPaidLeave' => 'required|boolean',
            'GenderApplicable' => 'required|string|max:10',
        ]);

        $leaveType = LeaveType::findOrFail($LeaveTypeID);
        $leaveType->update($validatedData);

        return redirect()->route('leave_types.index')->with('success', 'Leave type updated successfully.');
    }

    public function destroy($LeaveTypeID)
    {
        $leaveType = LeaveType::findOrFail($LeaveTypeID);
        $leaveType->delete();

        return redirect()->route('leave_types.index')->with('success', 'Leave type deleted successfully.');
    }
}
