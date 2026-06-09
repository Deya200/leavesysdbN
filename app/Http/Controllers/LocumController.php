<?php

namespace App\Http\Controllers;

use App\Models\LocumSession;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LocumController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if logged-in user is a locum employee
        if ($user && $user->is_locum) {
            return $this->locumEmployeeDashboard();
        }

        // For permanent employees managing locum staff
        $departments = Department::all();
        $locumEmployees = Employee::where(function ($query) {
                $query->where('is_locum', true)
                      ->orWhere('employment_type', 'Locum');
            })
            ->orderBy('FirstName')
            ->get();

        // Check if the current user has an active locum session
        $currentUserSession = LocumSession::where('EmployeeNumber', $user->EmployeeNumber)
            ->where('session_date', today())
            ->whereNull('sign_out_time')
            ->first();

        // Get locum invitations
        $recentNotifications = $user->notifications()->orderByDesc('created_at')->take(20)->get();
        $locumInvitations = $recentNotifications->filter(function ($notification) {
            return str_contains(strtolower($notification->Message), 'locum');
        });

        return view('locum.index', compact('departments', 'locumEmployees', 'currentUserSession', 'locumInvitations'));
    }

    private function locumEmployeeDashboard()
    {
        $user = Auth::user();
        $today = today();

        // Check if already signed in today
        $currentSession = LocumSession::where('EmployeeNumber', $user->EmployeeNumber)
            ->where('session_date', $today)
            ->whereNull('sign_out_time')
            ->first();

        // Get today's sessions
        $todaySessions = LocumSession::where('EmployeeNumber', $user->EmployeeNumber)
            ->where('session_date', $today)
            ->orderBy('sign_in_time', 'desc')
            ->get();

        // Get recent sessions (last 7 days)
        $recentSessions = LocumSession::where('EmployeeNumber', $user->EmployeeNumber)
            ->where('session_date', '>=', $today->copy()->subDays(7))
            ->orderBy('session_date', 'desc')
            ->orderBy('sign_in_time', 'desc')
            ->limit(10)
            ->get();

        return view('locum.employee-dashboard', compact('currentSession', 'todaySessions', 'recentSessions'));
    }

    public function signIn(Request $request)
    {
        $request->validate([
            'locum_employee_id' => 'required|exists:employees,EmployeeNumber',
            'DepartmentID' => 'required|exists:departments,DepartmentID',
            'notes' => 'nullable|string|max:255',
        ]);

        $locumEmployee = Employee::where('EmployeeNumber', $request->locum_employee_id)
            ->where('is_locum', true)
            ->first();

        if (!$locumEmployee) {
            return redirect()->back()->with('error', 'Invalid locum employee selected.');
        }

        // Check if already signed in today
        $existingSession = LocumSession::where('EmployeeNumber', $locumEmployee->EmployeeNumber)
            ->where('session_date', today())
            ->whereNull('sign_out_time')
            ->first();

        if ($existingSession) {
            return redirect()->back()->with('error', 'This locum employee is already signed in for today.');
        }

        LocumSession::create([
            'EmployeeNumber' => $locumEmployee->EmployeeNumber,
            'DepartmentID' => $request->DepartmentID,
            'sign_in_time' => now(),
            'session_date' => today(),
            'notes' => $request->notes,
            'hourly_rate' => 2000, // Default hourly rate
        ]);

        return redirect()->back()->with('success', 'Successfully signed in ' . $locumEmployee->FullName . ' for locum work.');
    }

    public function signOut(Request $request)
    {
        $request->validate([
            'locum_employee_id' => 'required|exists:employees,EmployeeNumber',
        ]);

        $locumEmployee = Employee::where('EmployeeNumber', $request->locum_employee_id)
            ->where('is_locum', true)
            ->first();

        if (!$locumEmployee) {
            return redirect()->back()->with('error', 'Invalid locum employee selected.');
        }

        $session = LocumSession::where('EmployeeNumber', $locumEmployee->EmployeeNumber)
            ->where('session_date', today())
            ->whereNull('sign_out_time')
            ->first();

        if (!$session) {
            return redirect()->back()->with('error', 'No active session found for this locum employee today.');
        }

        $session->update([
            'sign_out_time' => now(),
        ]);

        $session->calculateHoursWorked();

        return redirect()->back()->with('success', 'Successfully signed out ' . $locumEmployee->FullName . '. Hours worked: ' . $session->hours_worked);
    }

    // Locum Employee Self-Service Methods
    public function employeeSignIn(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'DepartmentID' => 'required|exists:departments,DepartmentID',
            'notes' => 'nullable|string|max:255',
        ]);

        // Check if already signed in today
        $existingSession = LocumSession::where('EmployeeNumber', $user->EmployeeNumber)
            ->where('session_date', today())
            ->whereNull('sign_out_time')
            ->first();

        if ($existingSession) {
            return redirect()->route('locum.index')->with('error', 'You are already signed in for today.');
        }

        LocumSession::create([
            'EmployeeNumber' => $user->EmployeeNumber,
            'DepartmentID' => $request->DepartmentID,
            'sign_in_time' => now(),
            'session_date' => today(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('locum.index')->with('success', 'Successfully signed in for locum work.');
    }

    public function employeeSignOut(Request $request)
    {
        $user = Auth::user();

        $session = LocumSession::where('EmployeeNumber', $user->EmployeeNumber)
            ->where('session_date', today())
            ->whereNull('sign_out_time')
            ->first();

        if (!$session) {
            return redirect()->route('locum.index')->with('error', 'No active session found for today.');
        }

        $session->update([
            'sign_out_time' => now(),
        ]);

        $session->calculateHoursWorked();

        return redirect()->route('locum.index')->with('success', 'Successfully signed out. Hours: ' . $session->hours_worked . ' | Earnings: ' . number_format($session->total_earnings ?? 0, 2));
    }

    public function sessions()
    {
        // Show all locum sessions - permanent staff can see what they signed in/out
        $sessions = LocumSession::with(['employee', 'department'])
            ->orderBy('session_date', 'desc')
            ->paginate(20);

        return view('locum.sessions', compact('sessions'));
    }

    public function monthlyReport(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $sessions = LocumSession::whereYear('session_date', Carbon::parse($month)->year)
            ->whereMonth('session_date', Carbon::parse($month)->month)
            ->with(['employee', 'department'])
            ->get();

        $totalHours = $sessions->sum('hours_worked');
        $totalSessions = $sessions->count();
        $totalEarnings = $sessions->sum(function ($session) {
            return $session->total_earnings ?? $session->calculateEarnings();
        });

        // Group by employee for summary
        $employeeSummary = $sessions->groupBy('EmployeeNumber')->map(function ($employeeSessions) {
            $employee = $employeeSessions->first()->employee;
            $hours = $employeeSessions->sum('hours_worked');
            $earnings = $employeeSessions->sum(function ($session) {
                return $session->total_earnings ?? $session->calculateEarnings();
            });

            return [
                'employee' => $employee->FullName,
                'employee_number' => $employee->EmployeeNumber,
                'sessions' => $employeeSessions->count(),
                'hours' => $hours,
                'earnings' => $earnings,
                'formatted_earnings' => 'MWK ' . number_format($earnings, 2),
            ];
        });

        return view('locum.report', compact('sessions', 'totalHours', 'totalSessions', 'totalEarnings', 'month', 'employeeSummary'));
    }
}
