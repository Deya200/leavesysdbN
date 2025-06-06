<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // or abort(403, 'Unauthorized.');
        }

        $user = $request->user();

        // Check if the given role exists in the roles table
        if (!Role::where('name', $role)->exists()) {
            abort(403, "Role '{$role}' does not exist.");
        }

        // Check if authenticated user has the given role
        if (!$user->role || $user->role->name !== $role) {
            abort(403, 'Access denied: insufficient role.');
        }


        return $next($request);
    }
}
