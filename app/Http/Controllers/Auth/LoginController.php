<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            return route('fallback.route');
        }

        $roleName = strtolower(trim($user->role->name));

        return match ($roleName) {
            'admin' => route('dashboards.admin'),
            'supervisor' => route('supervisor.index'),
            'employee' => route('dashboards.employee'),
           // default => route('employee.dashboard'),
        };
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout'); // Restrict login page to guests only
    }

    /**
     * Get the login credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('EmployeeNumber', 'password'); // ✅ Login via EmployeeNumber
    }

    /**
     * Handle failed login attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only('EmployeeNumber'))
            ->withErrors(['EmployeeNumber' => 'Invalid Employee Number or password.']);
    }

    /**
     * Validate the login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'EmployeeNumber' => 'required|string|exists:users,EmployeeNumber',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Log the user out and redirect to login page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
