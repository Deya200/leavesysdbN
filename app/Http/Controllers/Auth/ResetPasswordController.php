<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Override the redirect path to check user role after password reset.
     *
     * @return string
     */
    protected function redirectPath()
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            return route('login');
        }

        $roleName = strtolower(trim($user->role->name));

        return match ($roleName) {
            'admin' => route('dashboard'),
            'supervisor' => route('supervisor.index'),
            'employee' => route('dashboards.employee'),
            default => route('login'),
        };
    }
}
