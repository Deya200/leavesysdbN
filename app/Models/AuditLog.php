<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'EmployeeNumber',
        'action',
        'table_name',
        'record_id',
        'timestamp',
    ];

    public $timestamps = true;

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public static function record(string $employeeNumber, string $action, string $tableName, int $recordId)
    {
        self::create([
            'EmployeeNumber' => $employeeNumber,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'timestamp' => now(),
        ]);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeNumber', 'EmployeeNumber');
    }
}
