<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EmployeeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated and has the 'employee' role
        if (Auth::check() && Auth::user()->role === 'employee') {
            return $next($request); // Allow the request to proceed
        }

        // Redirect unauthorized users to login with an error message
        return redirect()->route('login')->withErrors(['error' => 'Unauthorized access!']);
    }
}
