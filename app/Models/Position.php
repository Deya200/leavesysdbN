<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
class Position extends Model
{
    use HasFactory;

    protected $primaryKey = 'PositionID'; // Assumes PositionID is the primary key
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'PositionName', // Add relevant fields here
        'GradeID', // Ensure this field exists in your database
    ];

    /**
     * Define the relationship to Employee.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'PositionID', 'PositionID');
    }

    /**
     * Define the relationship to Grade.
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'GradeID', 'GradeID');
    }
}
