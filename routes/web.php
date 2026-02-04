<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    DashboardController,
    EmployeeController,
    DepartmentController,
    LeaveTypeController,
    LeaveRequestController,
    ProfileController,
    NotificationController,
    UserController,
    PositionController,
    GradeController,
    Auth\LoginController,
    SupervisorController,
    AdminController,
    ReportController,
    UserManagementController // ✅ make sure this is imported
};

// ✅ Fallback route for undefined URLs
Route::fallback(function () {
    return redirect()->route('dashboard')->with('error', 'Page not found.');
});

// Public Routes
Auth::routes();

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Login & Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register/thankyou', function () {
    return view('auth.thankyou');
})->name('register.thankyou');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Dashboards
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 
    Route::get('/dashboards/employee', [LeaveRequestController::class, 'employeeDashboard'])->name('dashboards.employee');
    Route::get('/supervisors', [SupervisorController::class, 'index'])->name('supervisor.index');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Employees
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Departments
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    Route::get('/departments/{department}/employees', [DepartmentController::class, 'getEmployeesByDepartment'])->name('departments.employees');

    // Grades
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/create', [GradeController::class, 'create'])->name('grades.create');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit');
    Route::put('/grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::get('/users/{user}/profile', [UserController::class, 'show'])->name('users.show');

    // Leave Requests
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave_requests.index');
    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave_requests.create');
    Route::post('/leave-requests/store', [LeaveRequestController::class, 'store'])->name('leave_requests.store');
    Route::post('/leave-requests/review', [LeaveRequestController::class, 'review'])->name('leave_requests.review');
    Route::get('/leave-requests/review', [LeaveRequestController::class, 'showReview'])->name('leave_requests.review.show');
    Route::get('/leave-requests/{leaveRequest}/show', [LeaveRequestController::class, 'show'])->name('leave_requests.show');
    Route::get('/leave-requests/{leaveRequest}/submitted', [LeaveRequestController::class, 'submitted'])->name('leave_requests.submitted');
    Route::get('/leave-requests/{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])->name('leave_requests.edit');
    Route::put('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'update'])->name('leave_requests.update');
    Route::delete('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('leave_requests.destroy');

    // Approval Routes
    Route::post('/leave-requests/{leaveRequest}/supervisor-approve', [LeaveRequestController::class, 'supervisorApprove'])->name('leave_requests.supervisor.approve');
    Route::post('/leave-requests/{leaveRequest}/supervisor-reject', [LeaveRequestController::class, 'supervisorReject'])->name('leave_requests.supervisor.reject');
    Route::post('/leave-requests/{leaveRequest}/admin-approve', [LeaveRequestController::class, 'adminApprove'])->name('leave_requests.admin.approve');
    Route::post('/leave-requests/{leaveRequest}/admin-reject', [LeaveRequestController::class, 'adminReject'])->name('leave_requests.admin.reject');
    Route::get('/leave-requests/{leaveRequest}/admin-reject', [LeaveRequestController::class, 'showAdminRejectForm'])->name('leave_requests.admin.reject.form');

    Route::get('/leave-requests/my-requests', [LeaveRequestController::class, 'myLeaveRequests'])->name('leave_requests.my_requests');
    Route::get('/leave-requests/calculate-leave-days', [LeaveRequestController::class, 'calculateRemainingLeaveDays'])->name('leave_requests.calculate_days');

    // Positions
    Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
    Route::get('/positions/create', [PositionController::class, 'create'])->name('positions.create');
    Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
    Route::get('/positions/{position}/edit', [PositionController::class, 'edit'])->name('positions.edit');
    Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');

    // Leave Types
    Route::get('/leave-types', [LeaveTypeController::class, 'index'])->name('leave_types.index');
    Route::get('/leave-types/create', [LeaveTypeController::class, 'create'])->name('leave_types.create');
    Route::post('/leave-types', [LeaveTypeController::class, 'store'])->name('leave_types.store');
    Route::get('/leave-types/{leaveType}/edit', [LeaveTypeController::class, 'edit'])->name('leave_types.edit');
    Route::put('/leave-types/{leaveType}', [LeaveTypeController::class, 'update'])->name('leave_types.update');
    Route::delete('/leave-types/{leaveType}', [LeaveTypeController::class, 'destroy'])->name('leave_types.destroy');

        // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Admin verification page
    Route::get('/admin/verification', [DashboardController::class, 'admin'])
        ->name('admin.verification')
        ->middleware(['auth']);

    // Employee gender lookup
    Route::get('/employee-gender/{employeeNumber}', [EmployeeController::class, 'getGenderByEmployeeNumber'])
        ->name('employee.gender');

    // Reports
    Route::get('/leave-report-pdf', [ReportController::class, 'generatePDF'])->name('leave.report.pdf');
});








// User Management Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // User Management Page
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    
    // Update user role
    Route::post('/users/{employeeNumber}/update-role', [UserManagementController::class, 'updateRole'])
        ->name('users.updateRole');
    
    // Toggle user status
    Route::post('/users/{employeeNumber}/toggle-status', [UserManagementController::class, 'toggleStatus'])
        ->name('users.toggleStatus');
});

// If you already have these routes defined without 'admin.' prefix, add them:
Route::middleware(['auth'])->group(function () {
    // Edit user
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    
    // Update user
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    
    // Toggle user status (alternative route)
    Route::put('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])
        ->name('users.toggleStatus');
    
    // Update user role (alternative route)
    Route::put('/users/{user}/update-role', [UserManagementController::class, 'updateRole'])
        ->name('users.updateRole');
});
    // routes/web.php
use App\Http\Controllers\SettingsController;

Route::get('/settings', [SettingsController::class, 'index'])->name('settings');


