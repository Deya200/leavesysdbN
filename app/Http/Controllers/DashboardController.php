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
    // Total employee statistics
    $totalEmployees = Employee::count();
    $maleEmployees = Employee::where('gender', 'male')->count();
    $femaleEmployees = Employee::where('gender', 'female')->count();

    // Positions and Grades statistics
    $totalPositions = Position::count(); // Count all positions
    $totalGrades = Grade::count(); // Count all grades

    // Departments with employee counts
    $departments = Department::withCount('employees')->get();
    $departments = Department::withCount('employees')->with('supervisor')->get();


    // Recent leave requests
    $recentLeaveRequests = LeaveRequest::with(['employee', 'leaveType'])
        ->latest()
        ->take(5)
        ->get();

    return view('dashboards.index', [
        'totalEmployees' => $totalEmployees, 
        'maleEmployees' => $maleEmployees,
        'femaleEmployees' => $femaleEmployees,
        'totalPositions' => $totalPositions,
        'totalGrades' => $totalGrades,
        'departments' => $departments,
        'recentLeaveRequests' => $recentLeaveRequests,
    ]);
}

public function admin()
{
    $leaveRequests = \App\Models\LeaveRequest::latest()->get();
    return view('dashboards.admin', compact('leaveRequests')); // or whatever view you want
}


}
