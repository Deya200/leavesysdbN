<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Models\Employee;
class LeaveTypeController extends Controller
{
    /**
     * Display a listing of leave types.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $leaveTypes = LeaveType::all();
        return view('leave_types.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new leave type.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('leave_types.create'); // Ensure this Blade view exists
    }

    /**
     * Store a newly created leave type in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Show the form for editing the specified leave type.
     *
     * @param int $LeaveTypeID
     * @return \Illuminate\View\View
     */
    public function edit($LeaveTypeID)
    {
        $leaveType = LeaveType::findOrFail($LeaveTypeID);
        return view('leave_types.edit', compact('leaveType')); // Ensure this Blade view exists
    }

    /**
     * Update the specified leave type in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $LeaveTypeID
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Remove the specified leave type from storage.
     *
     * @param int $LeaveTypeID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($LeaveTypeID)
    {
        $leaveType = LeaveType::findOrFail($LeaveTypeID);
        $leaveType->delete();

        return redirect()->route('leave_types.index')->with('success', 'Leave type deleted successfully.');
    }
}
