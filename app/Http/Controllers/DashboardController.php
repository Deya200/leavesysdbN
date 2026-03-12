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
        }
        elseif (auth()->user()->role_id == 3) {
            return redirect()->route('dashboards.employee');
        }

        // --- Personal Leave Stats (Admin as Employee) ---
        $user = auth()->user();
        $personalLeaveBalance = $user ? $user->leave_days_remaining : 0;
        $personalRecentRequests = LeaveRequest::where('EmployeeNumber', $user->EmployeeNumber)
            ->where('is_archived', false)
            ->with('leaveType')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // --- Global Stats ---
        $totalEmployees = Employee::count();
        $maleEmployees = Employee::where('Gender', 'Male')->count();
        $femaleEmployees = Employee::where('Gender', 'Female')->count();
        $totalPositions = Position::count();
        $totalGrades = Grade::count();
        $departments = Department::withCount('employees')->with('supervisor')->get();

        $recentLeaveRequests = LeaveRequest::with(['employee', 'leaveType'])->latest()->take(5)->get();

        $today = now()->format('Y-m-d');
        $employeesOnLeave = LeaveRequest::with(['employee', 'employee.department', 'leaveType'])
            ->where('RequestStatus', 'Approved')
            ->where('StartDate', '<=', $today)
            ->where('EndDate', '>=', $today)
            ->get();

        // ── Analytics: Monthly trends (last 12 months) ──────────────────────
        $monthlyLabels   = [];
        $monthlyApproved = [];
        $monthlyRejected = [];
        $monthlyPending  = [];

        for ($i = 11; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $monthlyLabels[]   = $d->format('M Y');
            $monthlyApproved[] = LeaveRequest::whereYear('created_at', $d->year)
                ->whereMonth('created_at', $d->month)
                ->where('RequestStatus', 'Approved')->count();
            $monthlyRejected[] = LeaveRequest::whereYear('created_at', $d->year)
                ->whereMonth('created_at', $d->month)
                ->where('RequestStatus', 'like', '%Rejected%')->count();
            $monthlyPending[]  = LeaveRequest::whereYear('created_at', $d->year)
                ->whereMonth('created_at', $d->month)
                ->where('RequestStatus', 'like', '%Pending%')->count();
        }

        // ── Analytics: Leave type distribution ──────────────────────────────
        $leaveTypeStats = LeaveRequest::with('leaveType')
            ->selectRaw('"LeaveTypeID", COUNT(*) as total')
            ->groupBy('LeaveTypeID')
            ->get()
            ->map(fn($r) => [
                'name'  => optional($r->leaveType)->LeaveTypeName ?? 'Unknown',
                'count' => $r->total,
            ]);

        // ── Analytics: Gender utilization ────────────────────────────────────
        $maleRequests   = LeaveRequest::whereHas('employee', fn($q) => $q->where('Gender', 'Male'))->count();
        $femaleRequests = LeaveRequest::whereHas('employee', fn($q) => $q->where('Gender', 'Female'))->count();

        // ── Analytics: Department leave days ──────────────────────────────────
        $deptLeaveStats = Department::all()->map(function ($dept) {
            $days = LeaveRequest::whereHas('employee', fn($q) => $q->where('DepartmentID', $dept->DepartmentID))
                ->where('RequestStatus', 'Approved')
                ->sum('TotalDays');
            return ['name' => $dept->DepartmentName, 'days' => (int) $days];
        })->filter(fn($d) => $d['days'] > 0)->values();

        // ── Analytics: KPIs ────────────────────────────────────────────────
        $totalRequests = LeaveRequest::count();
        $totalApproved = LeaveRequest::where('RequestStatus', 'Approved')->count();
        $approvalRate  = $totalRequests > 0 ? round(($totalApproved / $totalRequests) * 100, 1) : 0;
        $avgDuration   = round((float) LeaveRequest::where('RequestStatus', 'Approved')->avg('TotalDays'), 1);

        $statusBreakdown = [
            'Approved' => $totalApproved,
            'Rejected' => LeaveRequest::where('RequestStatus', 'like', '%Rejected%')->count(),
            'Pending'  => LeaveRequest::where('RequestStatus', 'like', '%Pending%')->count(),
        ];

        $deptBalanceStats = Department::all()->map(function ($dept) {
            $employees = Employee::with('grade')->where('DepartmentID', $dept->DepartmentID)->get();
            $avgRemaining = $employees->isNotEmpty()
                ? round($employees->avg(fn($e) => $e->leave_days_remaining), 1)
                : 0;
            return ['name' => $dept->DepartmentName, 'avg' => $avgRemaining];
        })->filter(fn($d) => $d['avg'] > 0)->values();

        return view('dashboards.index', compact(
            'totalEmployees', 'maleEmployees', 'femaleEmployees',
            'totalPositions', 'totalGrades', 'departments',
            'recentLeaveRequests', 'personalLeaveBalance', 'personalRecentRequests',
            'employeesOnLeave',
            'monthlyLabels', 'monthlyApproved', 'monthlyRejected', 'monthlyPending',
            'leaveTypeStats', 'maleRequests', 'femaleRequests',
            'deptLeaveStats', 'approvalRate', 'avgDuration',
            'statusBreakdown', 'deptBalanceStats'
        ));
    }

    public function admin()
    {
        $leaveRequests = \App\Models\LeaveRequest::where('is_archived', false)->latest()->get();

        $totalRequests = LeaveRequest::count();
        $totalApproved = LeaveRequest::where('RequestStatus', 'Approved')->count();
        $approvalRate  = $totalRequests > 0 ? round(($totalApproved / $totalRequests) * 100, 1) : 0;
        $avgDuration   = round((float) LeaveRequest::where('RequestStatus', 'Approved')->avg('TotalDays'), 1);

        $statusBreakdown = [
            'Approved' => $totalApproved,
            'Rejected' => LeaveRequest::where('RequestStatus', 'like', '%Rejected%')->count(),
            'Pending'  => LeaveRequest::where('RequestStatus', 'like', '%Pending%')->count(),
        ];

        $monthlyVerified = [];
        $monthlyLabels   = [];
        for ($i = 11; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $monthlyLabels[]   = $d->format('M Y');
            $monthlyVerified[] = LeaveRequest::whereYear('updated_at', $d->year)
                ->whereMonth('updated_at', $d->month)
                ->whereIn('RequestStatus', ['Approved', 'Rejected by Admin'])
                ->count();
        }

        $deptBalanceStats = Department::all()->map(function ($dept) {
            $employees = Employee::with('grade')->where('DepartmentID', $dept->DepartmentID)->get();
            $avgRemaining = $employees->isNotEmpty()
                ? round($employees->avg(fn($e) => $e->leave_days_remaining), 1)
                : 0;
            return ['name' => $dept->DepartmentName, 'avg' => $avgRemaining];
        })->filter(fn($d) => $d['avg'] > 0)->values();

        return view('dashboards.admin', compact(
            'leaveRequests',
            'statusBreakdown', 'monthlyVerified', 'monthlyLabels',
            'deptBalanceStats', 'approvalRate', 'avgDuration'
        ));
    }
}
