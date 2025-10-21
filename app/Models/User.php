<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\Role;
use App\Models\Department;
use App\Models\LeaveRequest;

class User extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'EmployeeNumber';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'EmployeeNumber',
        'role_id',
        'profile_photo',
        'DepartmentID',
        'SupervisorID',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** ✅ Relationships **/

    // Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // Employee
    public function employee()
    {
        return $this->hasOne(Employee::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    // Grade (via Employee)
    public function grade()
    {
        return $this->hasOneThrough(
            Grade::class,
            Employee::class,
            'EmployeeNumber',
            'GradeID',
            'EmployeeNumber',
            'GradeID'
        );
    }

    // Leave Requests
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    // Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'DepartmentID');
    }

    // Supervisor
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'SupervisorID');
    }

    /** ✅ Role Checks **/

    public function isAdmin()
    {
        return $this->role_id === 1
            || ($this->role && strtolower($this->role->name) === 'admin');
    }

    public function isSupervisor()
    {
        return $this->role_id === 2;
    }

    public function isEmployee()
    {
        return $this->role_id === 3;
    }

    /** ✅ Accessors **/

    public function getFirstNameAttribute()
    {
        return optional($this->employee)->FirstName ?? 'N/A';
    }

    public function getRoleNameAttribute()
    {
        return ucfirst(optional($this->role)->name ?? 'Employee');
    }

    public function getProfilePhotoUrlAttribute()
    {
        if (!empty($this->profile_photo) && file_exists(public_path($this->profile_photo))) {
            return asset($this->profile_photo);
        }
        return asset('images/default-avatar.png');
    }
}
