<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\Role;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'EmployeeNumber'; // Define correct primary key
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'EmployeeNumber',
        'role_id',
        'profile_photo' // ✅ Ensure correct foreign key for roles
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationship with Role Table (FIXED)
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id'); // ✅ Correct role relationship
    }

    /**
     * Relationship with Employee Table
     */
    public function employee()
    {
        return $this->hasOne(Employee::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    /**
     * Relationship with Grade through Employee
     */
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

    /**
     * Relationship with Leave Requests
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    /**
     * Determine if User is Admin (Fixes Role-Based Routing)
     */
    public function isAdmin()
    {
        return $this->role && strtolower($this->role->name) === 'admin'; // ✅ Fix role detection
    }
    public function isSupervisor()
{
    return $this->role_id === 2; // Adjust based on your actual supervisor role ID
}
    public function isEmployee()
    {
        return $this->role_id === 3; // Adjust based on your actual employee role ID
    }

    /**
     * Accessor for First Name (Now Uses Relationship)
     */
    public function getFirstNameAttribute()
    {
        return optional($this->employee)->FirstName ?? 'N/A';
    }

    /**
     * Accessor for Role Name (Fixes Undefined Role Issues)
     */
    public function getRoleNameAttribute()
    {
        return ucfirst(optional($this->role)->name ?? 'Employee');
    }
        public function getProfilePhotoUrlAttribute()
    {
        if (!empty($this->profile_photo) && file_exists(public_path($this->profile_photo))) {
            return asset($this->profile_photo); // ✅ Correct file retrieval
        }
        return asset('images/default-avatar.png'); // ✅ Default image fallback
    }


}
