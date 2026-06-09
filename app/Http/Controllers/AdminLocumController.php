<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LocumSession;
use Illuminate\Http\Request;

class AdminLocumController extends Controller
{
    public function index()
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Get all employees who have locum sessions this month
        $employeesWithLocum = Employee::whereHas('locumSessions', function ($query) use ($currentYear, $currentMonth) {
            $query->whereYear('session_date', $currentYear)
                  ->whereMonth('session_date', $currentMonth);
        })->with(['locumSessions' => function ($query) use ($currentYear, $currentMonth) {
            $query->whereYear('session_date', $currentYear)
                  ->whereMonth('session_date', $currentMonth)
                  ->orderBy('session_date', 'desc');
        }, 'department', 'grade'])->get();

        // Calculate totals
        $totalSessions = $employeesWithLocum->sum(function ($employee) {
            return $employee->locumSessions->count();
        });

        $totalEarnings = $employeesWithLocum->sum(function ($employee) {
            return $employee->locumSessions->sum(function ($session) {
                return $session->total_earnings ?? ($session->hours_worked * ($session->hourly_rate ?? 2000));
            });
        });

        return view('admin.locum.index', compact('employeesWithLocum', 'totalSessions', 'totalEarnings'));
    }

    public function employeeSessions(Employee $employee)
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $locumSessions = $employee->locumSessions()
            ->whereYear('session_date', $currentYear)
            ->whereMonth('session_date', $currentMonth)
            ->with('employee')
            ->orderBy('session_date', 'desc')
            ->get();

        $totalEarnings = $locumSessions->sum(function ($session) {
            return $session->total_earnings ?? ($session->hours_worked * ($session->hourly_rate ?? 2000));
        });

        return view('admin.locum.employee-sessions', compact('employee', 'locumSessions', 'totalEarnings'));
    }
}