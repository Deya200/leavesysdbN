<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['name'];

    /**
     * Relationship: A role can have multiple employees.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'role_id', 'id');
    }

    /**
     * Initialize default roles if they don't exist.
     */
    public static function run()
    {
        self::firstOrCreate(['name' => 'Admin']);
        self::firstOrCreate(['name' => 'Supervisor']);
        self::firstOrCreate(['name' => 'Employee']);
    }

    /**
     * Helper: Check if this role is Supervisor.
     */
    public function isSupervisor(): bool
    {
        return strtolower($this->name) === 'supervisor';
    }

    /**
     * Helper: Check if this role is Admin.
     */
    public function isAdmin(): bool
    {
        return strtolower($this->name) === 'admin';
    }
}
