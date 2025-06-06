<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee; // Ensure this model exists

class Grade extends Model
{
    use HasFactory;

    // Define the associated database table
    protected $table = 'grades';

    // Specify the primary key column
    protected $primaryKey = 'GradeID';

    // Indicate whether the primary key is auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Disable timestamps if the table does not have created_at and updated_at columns
    public $timestamps = false;

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'GradeName',        // Name of the grade
        'AnnualLeaveDays',  // Number of annual leave days
    ];

    /**
     * Define a one-to-many relationship with the Employee model.
     *
     * A grade can have multiple employees assigned to it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'GradeID', 'GradeID');
    }
}
