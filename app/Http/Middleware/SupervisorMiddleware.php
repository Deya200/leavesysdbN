<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SupervisorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures that only users with the 'Supervisor' role may proceed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): mixed $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Using Spatie's hasRole method to check if the user has the Supervisor role.
        if ($request->user() && $request->user()->hasRole('Supervisor')) {
            return $next($request);
        }

        return redirect('/home')->with('error', 'You do not have access to this resource.');
    }
}
