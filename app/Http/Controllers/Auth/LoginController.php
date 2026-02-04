<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        // If user or role missing, try fallback route if defined, otherwise go to login
        if (! $user || ! $user->role) {
            if (Route::has('fallback.route')) {
                return route('fallback.route');
            }

            Log::warning('Redirect target fallback.route not defined; redirecting to login.');
            return route('login');
        }

        $roleName = strtolower(trim($user->role->name));

        return match ($roleName) {
            'admin' => (Route::has('dashboard') ? route('dashboard') : route('login')),
            'supervisor' => (Route::has('supervisor.index') ? route('supervisor.index') : route('login')),
            'employee' => (Route::has('dashboards.employee') ? route('dashboards.employee') : route('login')),
            default => route('login'),
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
