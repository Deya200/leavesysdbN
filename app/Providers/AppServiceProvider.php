<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\Employee;
use App\Models\LeaveRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // ✅ Force Bootstrap pagination
        Paginator::useBootstrap();

        // Bind the {employee} parameter to the Employee model.
        Route::model('employee', Employee::class);

        // Supervisors are just employees with the Supervisor role
        Route::model('supervisor', Employee::class);

        // ✅ Share leaveRequests with all views for supervisors
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user() instanceof Employee) {
                $user = Auth::user();

                if ($user->hasRole('Supervisor')) {
                    $leaveRequests = LeaveRequest::with('employee')
                        ->whereIn('RequestStatus', [
                            'Pending',
                            'Pending Supervisor Approval',
                            'Pending Admin Approval'
                        ])
                        ->get();

                    $view->with('leaveRequests', $leaveRequests);
                }
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
