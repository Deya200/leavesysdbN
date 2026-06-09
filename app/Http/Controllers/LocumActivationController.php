<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LocumActivationController extends Controller
{
    /**
     * Show activation form for locum employee
     */
    public function show(string $token): View
    {
        $employee = Employee::where('activation_token', $token)
            ->where('is_locum', true)
            ->firstOrFail();

        if ($employee->isLocumActivated()) {
            return view('locum.already-activated');
        }

        return view('locum.activate', compact('employee', 'token'));
    }

    /**
     * Activate locum account with password
     */
    public function activate(Request $request, string $token): RedirectResponse
    {
        $employee = Employee::where('activation_token', $token)
            ->where('is_locum', true)
            ->firstOrFail();

        if ($employee->isLocumActivated()) {
            return redirect()->route('login')->with('info', 'Account already activated. Please log in.');
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $employee->activateLocumAccount($validated['password']);

        return redirect()->route('login')
            ->with('success', 'Your locum account has been activated successfully. You can now log in.');
    }
}
