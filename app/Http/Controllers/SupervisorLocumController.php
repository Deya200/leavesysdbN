<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\LocumSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmergencyLocumNotification;
use Carbon\Carbon;

class SupervisorLocumController extends Controller
{
    public function index()
    {
        $supervisor = Auth::user();

        // Get supervised employees
        $supervisedEmployees = Employee::where('SupervisorID', $supervisor->EmployeeNumber)->get();

        // Get locum sessions for supervised employees in the current month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $locumSessions = LocumSession::whereIn('EmployeeNumber', $supervisedEmployees->pluck('EmployeeNumber'))
            ->whereYear('session_date', $currentYear)
            ->whereMonth('session_date', $currentMonth)
            ->with('employee')
            ->orderBy('session_date', 'desc')
            ->get();

        // Calculate summary stats
        $totalSessions = $locumSessions->count();
        $totalHours = $locumSessions->sum('hours_worked');
        $totalEarnings = $locumSessions->sum(function ($session) {
            return $session->calculateEarnings();
        });

        // Group by employee for summary table
        $employeeSummaries = $supervisedEmployees->map(function ($employee) use ($locumSessions) {
            $employeeSessions = $locumSessions->where('EmployeeNumber', $employee->EmployeeNumber);
            return [
                'employee' => $employee,
                'total_sessions' => $employeeSessions->count(),
                'total_hours' => $employeeSessions->sum('hours_worked'),
                'total_earnings' => $employeeSessions->sum(function ($session) {
                    return $session->calculateEarnings();
                }),
            ];
        });

        return view('supervisor.locum.index', compact(
            'supervisedEmployees',
            'locumSessions',
            'totalSessions',
            'totalHours',
            'totalEarnings',
            'employeeSummaries'
        ));
    }

    public function employeeSessions($employeeNumber)
    {
        $supervisor = Auth::user();

        $employee = Employee::where('EmployeeNumber', $employeeNumber)
            ->where('SupervisorID', $supervisor->EmployeeNumber)
            ->with('department')
            ->firstOrFail();

        $employeeSessions = LocumSession::where('EmployeeNumber', $employee->EmployeeNumber)
            ->with('employee')
            ->orderBy('session_date', 'desc')
            ->get();

        $summary = [
            'total_sessions' => $employeeSessions->count(),
            'total_hours' => $employeeSessions->sum('hours_worked'),
            'total_earnings' => $employeeSessions->sum(function ($session) {
                return $session->calculateEarnings();
            }),
        ];

        return view('supervisor.locum.employee-sessions', compact('employee', 'employeeSessions', 'summary'));
    }

    public function sendEmergencyNotification(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'recipients' => 'nullable|array',
            'recipients.*' => 'exists:employees,EmployeeNumber',
            'departments' => 'nullable|array',
            'departments.*' => 'exists:departments,DepartmentID',
        ]);

        $supervisor = Auth::user();

        $recipients = collect();

        // Add individual recipients
        if ($request->recipients) {
            $individualRecipients = Employee::whereIn('EmployeeNumber', $request->recipients)
                ->where('SupervisorID', $supervisor->EmployeeNumber)
                ->get();
            $recipients = $recipients->merge($individualRecipients);
        }

        // Add department recipients
        if ($request->departments) {
            $departmentRecipients = Employee::whereIn('DepartmentID', $request->departments)
                ->where('SupervisorID', $supervisor->EmployeeNumber)
                ->get();
            $recipients = $recipients->merge($departmentRecipients);
        }

        // Remove duplicates
        $recipients = $recipients->unique('EmployeeNumber');

        if ($recipients->isEmpty()) {
            return redirect()->back()->with('error', 'No valid recipients found.');
        }

        // Send notifications using Laravel's notification system and custom notifications
        foreach ($recipients as $recipient) {
            $notification = new EmergencyLocumNotification($request->message, $supervisor);
            $recipient->notify($notification);
            $notification->toCustomNotification($recipient);
        }

        return redirect()->back()->with('success', 'Emergency notification sent successfully to ' . $recipients->count() . ' employees.');
    }
}