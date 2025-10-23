<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\Paginator; // ✅ Import this
use App\Models\Employee;
use App\Models\Supervisor;

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
