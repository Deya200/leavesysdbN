<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $timestamps = true;

    // Only the name is fillable because our table only has 'name'
    protected $fillable = ['name'];

    /**
     * Relationship: A role can have multiple users.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Run the role initialization.
     *
     * You can call this method (for example, in your AppServiceProvider's boot method)
     * to automatically create basic roles if they don't exist.
     */
    public static function run()
    {
        self::firstOrCreate(['name' => 'Admin']);
        self::firstOrCreate(['name' => 'Supervisor']);
        self::firstOrCreate(['name' => 'Employee']);
    }
}
