<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Position;
use App\Models\Grade;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Redirect non-admins to their respective dashboards
        if (auth()->user()->role_id == 2) {
            return redirect()->route('supervisor.index');
        } elseif (auth()->user()->role_id == 3) {
            return redirect()->route('dashboards.employee');
        }

        // --- Personal Leave Stats (Admin as Employee) ---
        $user = auth()->user();
        $personalLeaveBalance = $user ? $user->RemainingAnnualLeaveDays : 0;
        $personalRecentRequests = LeaveRequest::where('EmployeeNumber', $user->EmployeeNumber)
            ->with('leaveType')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // --- Global Stats ---
        $totalEmployees = Employee::count();
        $maleEmployees = Employee::where('Gender', 'Male')->count();
        $femaleEmployees = Employee::where('Gender', 'Female')->count();

        // Positions and Grades statistics
        $totalPositions = Position::count();
        $totalGrades = Grade::count();

        // Departments with employee counts
        $departments = Department::withCount('employees')->with('supervisor')->get();

        // Recent leave requests (Global)
        $recentLeaveRequests = LeaveRequest::with(['employee', 'leaveType'])
            ->latest()
            ->take(5)
            ->get();

        // --- Employees Currently on Leave (Global) ---
        $today = now()->format('Y-m-d');
        $employeesOnLeave = LeaveRequest::with(['employee', 'employee.department', 'leaveType'])
            ->where('RequestStatus', 'Approved')
            ->where('StartDate', '<=', $today)
            ->where('EndDate', '>=', $today)
            ->get();

        return view('dashboards.index', compact(
            'totalEmployees',
            'maleEmployees',
            'femaleEmployees',
            'totalPositions',
            'totalGrades',
            'departments',
            'recentLeaveRequests',
            'personalLeaveBalance',
            'personalRecentRequests',
            'employeesOnLeave'
        ));
}

public function admin()
{
    //dashboard.admin is the verification page in the admin function
    $leaveRequests = \App\Models\LeaveRequest::latest()->get();
    return view('dashboards.admin', compact('leaveRequests')); // or whatever view you want
}


}
