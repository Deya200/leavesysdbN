<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Models\User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response)  $next
     * @return mixed
     */
     public function handle($request, Closure $next)
    {
        // Check if the user is authenticated and has the 'employee' role
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request); // Allow the request to proceed
        }

        // Redirect unauthorized users to login with an error message
        return redirect()->route('login')->withErrors(['error' => 'Unauthorized access!']);
    }
}
