<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Position;
use App\Models\Grade;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard (global stats).
     */
    public function index()
    {
        // Employee statistics
        $totalEmployees = Employee::count();
        $maleEmployees  = Employee::where('Gender', 'male')->count();
        $femaleEmployees = Employee::where('Gender', 'female')->count();

        // Positions and Grades statistics
        $totalPositions = Position::count();
        $totalGrades    = Grade::count();

        // Departments with employee counts and supervisor
        $departments = Department::withCount('employees')
            ->with(['supervisor', 'employees'])
            ->get();

        // Recent leave requests
        $recentLeaveRequests = LeaveRequest::with(['employee.department', 'leaveType'])
            ->latest()
            ->take(5)
            ->get();

        // ✅ Summary card counts (filtered properly)
        $pendingSupervisorCount = LeaveRequest::where('SupervisorApproval', 0)
                                              ->where('AdminVerified', 0)
                                              ->where('RequestStatus', 'Pending')
                                              ->count();

        $pendingAdminCount      = LeaveRequest::where('AdminVerified', 0)
                                              ->where('RequestStatus', 'Pending')
                                              ->count();

        $approvedCount          = LeaveRequest::where('RequestStatus', 'Approved')->count();
        $rejectedCount          = LeaveRequest::where('RequestStatus', 'Rejected')->count();

        // Total requests
        $totalRequests = LeaveRequest::count();

        // Safe maxCount (avoid division by zero)
        $maxCount = max($pendingSupervisorCount, $pendingAdminCount, $approvedCount, $rejectedCount, 1);

        return view('dashboards.index', [
            'totalEmployees'         => $totalEmployees,
            'maleEmployees'          => $maleEmployees,
            'femaleEmployees'        => $femaleEmployees,
            'totalPositions'         => $totalPositions,
            'totalGrades'            => $totalGrades,
            'departments'            => $departments,
            'recentLeaveRequests'    => $recentLeaveRequests,

            // Summary card data
            'pendingSupervisorCount' => $pendingSupervisorCount,
            'pendingAdminCount'      => $pendingAdminCount,
            'approvedCount'          => $approvedCount,
            'rejectedCount'          => $rejectedCount,
            'maxCount'               => $maxCount,
            'totalRequests'          => $totalRequests,
        ]);
    }

    /**
     * Admin dashboard (leave verification + user management).
     */
    public function admin()
    {
        $leaveRequests = LeaveRequest::with(['employee.department', 'leaveType'])
            ->latest()
            ->get();

        $users = User::with([
            'role',
            'employee.department',
        ])->get();

        // ✅ Summary card counts (filtered properly)
        $pendingSupervisorCount = LeaveRequest::where('SupervisorApproval', 0)
                                              ->where('AdminVerified', 0)
                                              ->where('RequestStatus', 'Pending')
                                              ->count();

        $pendingAdminCount      = LeaveRequest::where('AdminVerified', 0)
                                              ->where('RequestStatus', 'Pending')
                                              ->count();

        $approvedCount          = LeaveRequest::where('RequestStatus', 'Approved')->count();
        $rejectedCount          = LeaveRequest::where('RequestStatus', 'Rejected')->count();

        // ✅ Total requests
        $totalRequests = LeaveRequest::count();

        // ✅ Safe maxCount (avoid division by zero)
        $maxCount = max($pendingSupervisorCount, $pendingAdminCount, $approvedCount, $rejectedCount, 1);

        return view('dashboards.admin', compact(
            'leaveRequests',
            'users',
            'pendingSupervisorCount',
            'pendingAdminCount',
            'approvedCount',
            'rejectedCount',
            'totalRequests',
            'maxCount'
        ));
    }

    /**
     * Employee dashboard (personal view).
     */
    public function employee()
    {
        // Load the logged-in employee with supervisor + department
        $employee = Employee::with([
            'supervisor.user',            // supervisor and their linked user account
            'department.supervisor.user', // department and its supervisor
        ])
        ->where('EmployeeNumber', auth()->user()->EmployeeNumber)
        ->firstOrFail();

        return view('dashboards.employee', compact('employee'));
    }
}
