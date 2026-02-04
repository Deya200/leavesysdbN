<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and is admin
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        // If not admin, redirect or abort
        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
