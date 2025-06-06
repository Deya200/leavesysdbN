<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display the leave request form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Fetch all leave types to populate the form dropdown
        $leaveTypes = LeaveType::all();

        // Return the leave request form view
        return view('leaves.request', compact('leaveTypes'));
    }

    /**
     * Store a new leave request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the input fields
        $validated = $request->validate([
            'EmployeeNumber' => 'required|string|exists:employees,EmployeeNumber',
            'LeaveTypeID' => 'required|exists:leave_types,LeaveTypeID',
            'StartDate' => 'required|date|after_or_equal:today',
            'EndDate' => 'required|date|after_or_equal:StartDate',
            'reason' => 'required|string|max:500',
        ]);

        // Calculate the total leave days (inclusive of StartDate and EndDate)
        $totalDays = Carbon::parse($validated['EndDate'])
            ->diffInDays(Carbon::parse($validated['StartDate'])) + 1;

        // Save the leave request in the database
        LeaveRequest::create([
            'EmployeeNumber' => $validated['EmployeeNumber'],
            'LeaveTypeID' => $validated['LeaveTypeID'],
            'StartDate' => $validated['StartDate'],
            'EndDate' => $validated['EndDate'],
            'TotalDays' => $totalDays,
            'RequestStatus' => 'Pending',
            'SupervisorApproval' => 0,
            'HRApproval' => 0,
            'reason' => $validated['reason'],
        ]);

        // Redirect back to the leave request page with a success message
        return redirect()->route('leave_requests.request')->with('success', 'Leave request submitted successfully.');
    }
}
