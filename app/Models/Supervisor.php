<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Employee;

class Supervisor extends Model
{
    protected $table = 'supervisors';
    protected $primaryKey = 'SupervisorID';
    protected $fillable = [
        'FirstName',
        'LastName',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'SupervisorID', 'SupervisorID');
    }
}
