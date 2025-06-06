<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasFactory, HasRoles; // Added HasRoles for role-based access

    protected $table = 'employees';
    protected $primaryKey = 'EmployeeNumber';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'EmployeeNumber' => 'string',
        'SupervisorID' => 'string',
        'RemainingAnnualLeaveDays' => 'integer', // Cast to integer
    ];

    /**
     * Relationship: Employee's Department
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'DepartmentID', 'DepartmentID'); // Use correct FK reference
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
     * Get the Employee's computed leave days remaining.
     * (This is a computed accessor if you want to show the theoretical remaining days based on the grade.)
     * @return int
     */
    public function getLeaveDaysRemainingAttribute(): int
    {
        $totalLeaveDays = optional($this->grade)->AnnualLeaveDays ?? 0;
        $usedLeaveDays = $this->leaveRequests()
            ->where('RequestStatus', 'Approved')
            ->sum('TotalDays');

        return max(0, $totalLeaveDays - $usedLeaveDays);
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
}
