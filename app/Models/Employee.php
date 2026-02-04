<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\Grade;
use App\Models\Position;
use App\Models\Role;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'EmployeeNumber';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    public function getRouteKeyName()
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
        'RemainingAnnualLeaveDays',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'EmployeeNumber' => 'string',
        'SupervisorID'   => 'string',
        'RemainingAnnualLeaveDays' => 'integer',
    ];

    /** 🔗 Relationships **/

    // Employee belongs to a Department
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'DepartmentID', 'DepartmentID');
    }

    // Employee belongs to a Supervisor (another Employee)
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'SupervisorID', 'EmployeeNumber')
                    ->with('user'); // ✅ eager-load supervisor's User account
    }

    // Employee can have many subordinates
    public function subordinates(): HasMany
    {
        return $this->hasMany(self::class, 'SupervisorID', 'EmployeeNumber');
    }

    // Leave Requests
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    // Notifications
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    // Grade
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'GradeID', 'GradeID');
    }

    // Position
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'PositionID', 'PositionID');
    }

    // Role
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // Link to User account
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    /** 🔧 Helpers **/

    // Remaining leave days
    public function getLeaveDaysRemainingAttribute(): int
    {
        $totalLeaveDays = optional($this->grade)->AnnualLeaveDays ?? 0;
        $usedLeaveDays = $this->leaveRequests()
            ->where('RequestStatus', 'Approved')
            ->sum('TotalDays');

        return max(0, $totalLeaveDays - $usedLeaveDays);
    }

    // Role checks
    public function hasRole(string $roleName): bool
    {
        return $this->role && strtolower($this->role->name) === strtolower($roleName);
    }

    public function isSupervisor(): bool
    {
        return $this->hasRole('Supervisor');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    /** 🔧 Cascade Helper **/

    // Update supervisor for all employees in the same department
    public static function cascadeSupervisorUpdate(int $departmentId, string $newSupervisorId): void
    {
        self::where('DepartmentID', $departmentId)
            ->update(['SupervisorID' => $newSupervisorId]);
    }
}
