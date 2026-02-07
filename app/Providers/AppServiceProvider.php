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
                // Supervisor logic remains...
                // (Optimized query: avoid N+1 if needed, but keeping existing logic)
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

        // ✅ Share notifications with header
        View::composer('layouts.header', function ($view) {
            if (Auth::check()) {
                $headerNotifications = \App\Models\Notification::where('EmployeeNumber', Auth::user()->EmployeeNumber)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
                
                $unreadCount = \App\Models\Notification::where('EmployeeNumber', Auth::user()->EmployeeNumber)
                    ->where('Status', 'Unread')
                    ->count();

                $view->with('headerNotifications', $headerNotifications);
                $view->with('unreadCount', $unreadCount);
            } else {
                 $view->with('headerNotifications', collect([]));
                 $view->with('unreadCount', 0);
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
