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

        // ✅ Share pending leaves with all views for authenticated users (for sidebar badges)
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $query = LeaveRequest::whereIn('RequestStatus', [
                        'Pending',
                        'Pending Supervisor Approval',
                        'Pending Admin Verification',
                        'Pending Admin Approval'
                    ]);

                // Role-based filtering for notification badge
                if ($user->role_id === 2) {
                    // SUPERVISOR: Only pending requests from employees in their department
                    $query->whereHas('employee', function ($q) use ($user) {
                        $q->where('DepartmentID', $user->DepartmentID);
                    });
                } elseif ($user->role_id === 3) {
                    // EMPLOYEE: Only their own pending requests
                    $query->where('EmployeeNumber', $user->EmployeeNumber);
                }
                // ADMIN (role_id = 1): See all pending requests (no filter)

                $globalPendingLeaves = $query->get();
                $view->with('globalPendingLeaves', $globalPendingLeaves);
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

        // Ensure the mail view namespace points to the HTML mail component views.
        View::addNamespace('mail', [
            resource_path('views/vendor/mail/html'),
            base_path('vendor/laravel/framework/src/Illuminate/Mail/resources/views/html'),
        ]);
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
