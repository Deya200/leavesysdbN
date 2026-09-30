<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ClinicalDepartmentMiddleware
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
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $isClinicalStaff = $user->department && strtolower($user->department->DepartmentName) === 'clinical';
        $isSupervisorOrAdmin = in_array((int) ($user->role_id ?? 0), [1, 2], true);

        if ($isClinicalStaff || $isSupervisorOrAdmin) {
            return $next($request);
        }

        return redirect()->route('dashboard')->withErrors(['error' => 'Access restricted to clinical department staff only.']);
    }
}
