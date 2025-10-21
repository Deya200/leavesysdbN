<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Department extends Model
{
    use HasFactory;

    // Define table and primary key
    protected $table = 'departments';
    protected $primaryKey = 'DepartmentID';
    public $timestamps = false; // Disable timestamps for the table

    // Mass-assignable attributes
    protected $fillable = [
        'DepartmentName',
        'SupervisorID', // Supervisor assignment
    ];

    /**
     * Get the supervisor of the department.
     * A department belongs to one employee (supervisor).
     */
 public function supervisor()
    {
        return $this->belongsTo(User::class, 'SupervisorID', 'EmployeeNumber');
    }

    /**
     * Get all employees in this department.
     * A department has many employees.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'DepartmentID', 'DepartmentID');
    }
}
