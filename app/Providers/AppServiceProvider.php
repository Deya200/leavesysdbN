<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\Employee;
use App\Models\Supervisor;
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

        // Bind the {supervisor} parameter to the Supervisor model.
        Route::model('supervisor', Supervisor::class);

        // ✅ Share leaveRequests with all views for supervisors
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role_id === 2) {
                $leaveRequests = LeaveRequest::with('employee')
                    ->whereIn('RequestStatus', [
                        'Pending',
                        'Pending Supervisor Approval',
                        'Pending Admin Approval'
                    ])
                    ->get();

                $view->with('leaveRequests', $leaveRequests);
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
