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
    AdminController
};

Route::fallback(function () {
    return redirect()->route('dashboard')->with('error', 'Page not found.');
});

    
    // You can define other admin routes here as needed:
    Route::get('/admin/leave-requests', [AdminController::class, 'leaveRequests'])->name('leave_verification');
    Route::post('/admin/leave-requests/{id}/approve', [AdminController::class, 'approveLeave'])->name('leave_requests.admin.approve');
    Route::post('/admin/leave-requests/{id}/reject', [AdminController::class, 'rejectLeave'])->name('leave_requests.admin.reject');


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

    // Dashboard
    //Note this is the main admin or HR  dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 

    // Profile Management
  



    // Leave Requests
    Route::get('/leave-requests/{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])->name('leave_requests.edit');

    Route::prefix('leave-requests')->group(function () {
        Route::get('/', [LeaveRequestController::class, 'index'])->name('leave_requests.index');
        Route::get('/create', [LeaveRequestController::class, 'create'])->name('leave_requests.create');
        Route::get('/leave-requests/{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])->name('leave_requests.edit');

        Route::post('/', [LeaveRequestController::class, 'store'])->name('leave_requests.store');
        Route::get('/{leaveRequest}/show', [LeaveRequestController::class, 'show'])->name('leave_requests.show');
        Route::get('/{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])->name('leave_requests.edit');
        Route::put('/{leaveRequest}/update', [LeaveRequestController::class, 'update'])->name('leave_requests.update');
        Route::get('/my-requests', [LeaveRequestController::class, 'myLeaveRequests'])->name('leave_requests.my-requests');
        Route::get('/calculate-leave-days', [LeaveRequestController::class, 'calculateRemainingLeaveDays'])->name('leave_requests.calculate-days');
    });

    // Approval Routes
    Route::prefix('leave-requests')->group(function () {
        Route::post('/{leaveRequest}/supervisor-approve', [LeaveRequestController::class, 'supervisorApprove'])->name('leave_requests.supervisor.approve');
        Route::post('/{leaveRequest}/supervisor-reject', [LeaveRequestController::class, 'supervisorReject'])->name('leave_requests.supervisor.reject');
        Route::post('/{leaveRequest}/admin-approve', [LeaveRequestController::class, 'adminApprove'])->name('leave_requests.admin.approve');
        Route::post('/{leaveRequest}/admin-reject', [LeaveRequestController::class, 'adminReject'])->name('leave_requests.admin.reject');
    });

    // Admin Routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave_requests');
        
        // User Management
        Route::resource('users', UserController::class)->except(['show']);
        Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
        
        // Employee Management
        Route::prefix('employees')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('manage.employees');
            Route::post('/{employee}/assign-supervisor', [EmployeeController::class, 'assignSupervisor'])->name('employees.assignSupervisor');
            
        });
        Route::resource('employees', EmployeeController::class);
        //Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');

        // Department Management
        Route::resource('departments', DepartmentController::class);
        Route::get('/departments/{department}/employees', [DepartmentController::class, 'getEmployeesByDepartment'])->name('departments.employees');

        // Position Management
        Route::resource('positions', PositionController::class);
        Route::resource('grades', GradeController::class);

        // Leave Types Management
        Route::resource('leave-types', LeaveTypeController::class);
    });

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    // Employee Dashboard
    Route::get('/dashboards/employee', [LeaveRequestController::class, 'employeeDashboard'])->name('dashboards.employee');

});

// Supervisor Routes
Route::get('/supervisors', [SupervisorController::class, 'index'])->name('supervisor.index');





Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
Route::get('/grades/create', [GradeController::class, 'create'])->name('grades.create');
Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
Route::get('/grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit');
Route::put('/grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
Route::get('/users/{user}/profile', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/{user}/delete', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave_requests.index');
Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave_requests.create');
Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave_requests.store');
Route::get('/leave-requests/{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])->name('leave_requests.edit');
Route::put('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'update'])->name('leave_requests.update');
Route::delete('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('leave_requests.destroy');
Route::get('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave_requests.approve');
Route::get('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave_requests.reject');
Route::get('/leave-requests/{leaveRequest}/show', [LeaveRequestController::class, 'show'])->name('leave_requests.show');
Route::post('/leave-requests/{leaveRequest}/supervisor-approve', [LeaveRequestController::class, 'supervisorApprove'])->name('leave_requests.supervisor.approve');
Route::post('/leave-requests/{leaveRequest}/supervisor-reject', [LeaveRequestController::class, 'supervisorReject'])->name('leave_requests.supervisor.reject');


Route::post('/leave_requests/supervisor/reject/{id}', [SupervisorController::class, 'reject'])
    ->name('leave_requests.supervisor.reject');

//Route::get('/leave-requests/{leaveRequest}/admin-approve', [LeaveRequestController::class, 'adminApprove'])->name('leave_requests.admin.approve');
//Route::get('/leave-requests/{leaveRequest}/admin-reject', [LeaveRequestController::class, 'adminReject'])->name('leave_requests.admin.reject');

Route::get('/leave-requests/my-requests', [LeaveRequestController::class, 'myLeaveRequests'])->name('leave_requests.my_requests');
Route::get('/leave-requests/calculate-leave-days', [LeaveRequestController::class, 'calculateRemainingLeaveDays'])->name('leave_requests.calculate_days');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

Route::get('/departments/{department}/employees', [DepartmentController::class, 'getEmployeesByDepartment'])->name('departments.employees');

Route::get('/supervisors', [SupervisorController::class, 'index'])->name('supervisor.index');
Route::get('/supervisors/create', [SupervisorController::class, 'create'])->name('supervisor.create');
Route::post('/supervisors', [SupervisorController::class, 'store'])->name('supervisor.store');
Route::get('/supervisors/{supervisor}/edit', [SupervisorController::class, 'edit'])->name('supervisor.edit');
Route::put('/supervisors/{supervisor}', [SupervisorController::class, 'update'])->name('supervisor.update');
Route::delete('/supervisors/{supervisor}', [SupervisorController::class, 'destroy'])->name('supervisor.destroy');

Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
Route::get('/positions/create', [PositionController::class, 'create'])->name('positions.create');
Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
Route::get('/positions/{position}/edit', [PositionController::class, 'edit'])->name('positions.edit');
Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');

Route::get('/leave-types', [LeaveTypeController::class, 'index'])->name('leave_types.index');
Route::get('/leave-types/create', [LeaveTypeController::class, 'create'])->name('leave_types.create');
Route::post('/leave-types', [LeaveTypeController::class, 'store'])->name('leave_types.store');
Route::get('/leave-types/{leaveType}/edit', [LeaveTypeController::class, 'edit'])->name('leave_types.edit');
Route::put('/leave-types/{leaveType}', [LeaveTypeController::class, 'update'])->name('leave_types.update');
Route::delete('/leave-types/{leaveType}', [LeaveTypeController::class, 'destroy'])->name('leave_types.destroy');

Route::get('/leave-requests/{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])->name('leave_requests.edit');



Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

//admin verification page 
Route::get('/admin/verification', [DashboardController::class, 'admin'])
    ->name('admin.verification')
    ->middleware(['auth']);
    

    
Route::get('/employee-gender/{employeeNumber}', [EmployeeController::class, 'getGenderByEmployeeNumber']);

Route::get('/employee-gender/{employeeNumber}', [EmployeeController::class, 'getGenderByEmployeeNumber'])
    ->name('employee.gender');


    //Modifications
Route::get('/leave-requests/{leaveRequest}/admin-reject', [LeaveRequestController::class, 'showAdminRejectForm'])->name('leave_requests.admin.reject.form');
