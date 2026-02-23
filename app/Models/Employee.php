<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Notifications\PasswordResetNotification;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable; // Added Notifiable for invitation emails

    protected $table = 'employees';
    protected $primaryKey = 'EmployeeNumber';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    public function getRouteKeyName()
    {
        return 'EmployeeNumber';
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'EmployeeNumber';
    }

    protected $fillable = [
        'EmployeeNumber',
        'FirstName',
        'LastName',
        'DateOfBirth',
        'DepartmentID',
        'Gender',
        'GradeID',
        'PositionID',
        'SupervisorID',
        'email',
        'password',
        'role_id',
        'RemainingAnnualLeaveDays', // Added to allow persistent tracking and updates.
        'email_notifications_enabled',
        'system_notifications_enabled',
        'carried_over_leave_days',
        'last_password_reset_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'EmployeeNumber' => 'string',
        'SupervisorID' => 'string',
        'RemainingAnnualLeaveDays' => 'integer', // Cast to integer
        'email_notifications_enabled' => 'boolean',
        'system_notifications_enabled' => 'boolean',
        'carried_over_leave_days' => 'integer',
        'last_password_reset_at' => 'datetime',
    ];



    /**
     * Relationship: Employee's Department
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'DepartmentID', 'DepartmentID'); // Use correct FK and Pascal PK
    }

    /**
     * Relationship: Employee's Supervisor
     * @return BelongsTo
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'SupervisorID', 'EmployeeNumber');
    }

    /**
     * Relationship: Employees supervised by this employee.
     * @return HasMany
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(self::class, 'SupervisorID', 'EmployeeNumber');
    }

    /**
     * Relationship: Leave Requests Submitted by the Employee.
     * @return HasMany
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    /**
     * Relationship: Notifications for the Employee.
     * @return HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    /**
     * Relationship: Employee's Grade.
     * @return BelongsTo
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'GradeID', 'GradeID');
    }

    /**
     * Relationship: Employee's Position
     * @return BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'PositionID', 'PositionID');
    }

    /**
     * Relationship: Leave Appeals submitted by the employee
     * @return HasMany
     */
    public function leaveAppeals(): HasMany
    {
        return $this->hasMany(LeaveAppeal::class, 'employee_number', 'EmployeeNumber');
    }

    /**
     * Relationship: Leave Extensions requested by the employee
     * @return HasMany
     */
    public function leaveExtensions(): HasMany
    {
        return $this->hasMany(LeaveExtension::class, 'employee_number', 'EmployeeNumber');
    }

    /**
     * Relationship: Leave Cancellations requested by the employee
     * @return HasMany
     */
    public function leaveCancellations(): HasMany
    {
        return $this->hasMany(LeaveCancellation::class, 'employee_number', 'EmployeeNumber');
    }

    /**
     * Get the Employee's computed leave days remaining.
     * (This is a computed accessor if you want to show the theoretical remaining days based on the grade.)
     * Excludes archived leave requests from the calculation.
     * @return int
     */
    public function getLeaveDaysRemainingAttribute(): int
    {
        $totalLeaveDays = optional($this->grade)->AnnualLeaveDays ?? 0;
        $usedLeaveDays = $this->leaveRequests()
            ->where('RequestStatus', 'Approved')
            ->where('is_archived', false)
            ->whereHas('leaveType', fn($q) => $q->where('LeaveTypeName', 'Annual Leave'))
            ->sum('TotalDays');

        // Include carried over days from previous year
        $totalAvailable = $totalLeaveDays + ($this->carried_over_leave_days ?? 0);
        
        return max(0, $totalAvailable - $usedLeaveDays);
    }

    /**
     * Get total available leave days (grade + carried over)
     * @return int
     */
    public function getTotalAvailableLeaveDays(): int
    {
        $gradeLeaveDays = optional($this->grade)->AnnualLeaveDays ?? 0;
        $carriedOver = $this->carried_over_leave_days ?? 0;
        return $gradeLeaveDays + $carriedOver;
    }

    /**
     * Check if Employee is a Supervisor.
     * @return bool
     */
    public function isSupervisor(): bool
    {
        return $this->subordinates()->exists();
    }

    /**
     * Check if Employee is an Admin.
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin'); // Uses Spatie's role-checking method
    }
    
    /**
     * Ensure that the employee has at least one role.
     * If no role is assigned, assign the default 'Employee' role.
     *
     * @return void
     */
    public function ensureDefaultRole(): void
    {
        if ($this->roles()->count() === 0) {
            $this->assignRole('Employee');
        }
    }
    
    /**
     * Accessor to get a comma-separated list of roles assigned to the employee.
     *
     * @return string
     */
    public function getRolesListAttribute(): string
    {
        return $this->roles->pluck('name')->implode(', ');
    }

        public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // ✅ Check if employee has any role
    public function hasRole(string $roleName): bool
    {
        return $this->role && strtolower($this->role->name) === strtolower($roleName);
    }

    // ✅ Check if employee is admin
   // public function isAdmin(): bool
    //{
    //    return $this->hasRole('admin');
    //}

    /**
     * Send password reset notification to employee.
     * @param string $token
     * @param Employee|null $admin
     * @return void
     */
    public function sendPasswordResetNotification($token, $admin = null)
    {
        // Use custom notification with admin info
        if ($admin === null) {
            $admin = auth()->user(); // Get current authenticated user (admin)
        }
        $this->notify(new PasswordResetNotification($token, $admin));
    }

}
