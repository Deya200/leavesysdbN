<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\User;

class Department extends Model
{
    use HasFactory;

    // Define table and primary key
    protected $table = 'departments';
    protected $primaryKey = 'DepartmentID';
    public $timestamps = true; // table has created_at/updated_at

    // Mass-assignable attributes
    protected $fillable = [
        'DepartmentName',
        'SupervisorID',
    ];

    /**
     * Get the supervisor of the department.
     * SupervisorID stores the EmployeeNumber of the supervisor (linked to User).
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'SupervisorID', 'EmployeeNumber')
                    ->with('employee'); // eager-load supervisor's Employee record
    }

    /**
     * Get all employees in this department.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'DepartmentID', 'DepartmentID');
    }

    /**
     * 🔧 Cascade helper: update supervisor for all employees in this department.
     *
     * @param string $newSupervisorId EmployeeNumber of the new supervisor
     */
    public function cascadeSupervisorUpdate(string $newSupervisorId): void
    {
        // Update department supervisor
        $this->update(['SupervisorID' => $newSupervisorId]);

        // Cascade update: all employees in this department get new supervisor
        $this->employees()->update(['SupervisorID' => $newSupervisorId]);
    }
}
