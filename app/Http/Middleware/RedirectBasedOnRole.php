<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    public function handle($request, Closure $next)
{
    if (Auth::check()) {
        $role = Auth::user()->role_id;

        if ($role == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 2) {
            return redirect()->route('supervisor.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
        }
    }

    return $next($request);
}

}
