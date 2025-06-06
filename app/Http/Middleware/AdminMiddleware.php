<?php

namespace App\Http\Middleware;

use Closure;
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
    public function handle(Request $request, Closure $next)
    {
        // Adjust the condition as needed
        if (Auth::check() && Auth::user()->role_id === 1) {
            return $next($request);
        }

        // You can customize the error message or redirect as needed
        abort(403, 'Unauthorized.');
    }
}
