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
            'admin' => route('dashboard'),
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
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'EmployeeNumber'; // Use EmployeeNumber instead of email
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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        try {
            if ($this->attemptLogin($request)) {
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }

                return $this->sendLoginResponse($request);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Log the error for internal debugging
            \Illuminate\Support\Facades\Log::error('Login Connection Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([
                    $this->username() => 'Unable to connect to the login service. Please try again later.',
                ]);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
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
            'EmployeeNumber' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Log the user out and redirect to login page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Show the application's login form.
     * Overridden to prevent caching of the login page (fixes 419 Page Expired).
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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

        // Forcibly clear the session cookie from the browser
        $cookie = \Illuminate\Support\Facades\Cookie::forget(config('session.cookie'));

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.')
            ->withCookie($cookie)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
