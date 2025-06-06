<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'NotificationID'; // Set NotificationID as the primary key
    public $incrementing = true; // Auto-increment enabled
    protected $keyType = 'int'; // Primary key is an integer

    protected $fillable = [
        'EmployeeNumber', // Updated to match the table structure
        'Message',        // Updated to match the table structure
        'Status',         // Updated to match the table structure
    ];

    /**
     * Define a relationship to the employee who received the notification.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeNumber', 'EmployeeNumber');
    }

    /**
     * Scope to filter notifications by status (e.g., Unread, Read).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('Status', $status);
    }

    /**
     * Scope to filter notifications by employee.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $employeeNumber
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEmployee($query, $employeeNumber)
    {
        return $query->where('EmployeeNumber', $employeeNumber);
    }

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead()
    {
        $this->update(['Status' => 'Read']);
    }

    /**
     * Mark all notifications as read for a given employee.
     *
     * @param string $employeeNumber
     * @return int Number of notifications updated.
     */
    public static function markAllAsReadByEmployee(string $employeeNumber): int
    {
        return static::where('EmployeeNumber', $employeeNumber)->update(['Status' => 'Read']);
    }

    /**
     * Check if the notification is unread.
     *
     * @return bool
     */
    public function isUnread()
    {
        return $this->Status === 'Unread';
    }

    /**
     * Check if the notification is read.
     *
     * @return bool
     */
    public function isRead()
    {
        return $this->Status === 'Read';
    }
}
