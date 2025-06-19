<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class LeaveType extends Model
{
    use HasFactory;

    // Specify the primary key if it's not "id"
    protected $primaryKey = 'LeaveTypeID';

    // Fillable fields for mass assignment
    protected $fillable = [
        'LeaveTypeName',
        'IsPaidLeave',
        'GenderApplicable',
        'MaxLeaveDays',
        'MinServiceYears',
    ];

    //Leave checks
    public function isAnnualLeave()
    {
        return $this->LeaveTypeName === 'Annual Leave';
    }

    public function isPartenityLeave()
    {
        return $this->LeaveTypeName === 'Partenity Leave';
    }

    public function isSickLeave()
    {
        return $this->LeaveTypeName === 'Sick Leave';
    }
}
