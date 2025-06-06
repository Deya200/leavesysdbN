<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Task;
use App\Models\Training;
use App\Models\Announcement;

class GeneralEmployeeDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $employee = Employee::where('UserID', $user->id)->first();

        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }

        $tasks = Task::where('EmployeeNumber', $employee->EmployeeNumber)->get();
        $trainingSessions = Training::where('status', 'Active')->latest()->take(5)->get();
        $companyAnnouncements = Announcement::latest()->take(5)->get();
        $supervisor = $employee->supervisor;

        return view('dashboard.general_employee', compact(
            'employee',
            'tasks',
            'trainingSessions',
            'companyAnnouncements',
            'supervisor'
        ));
    }
}
