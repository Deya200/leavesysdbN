<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Employee;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve notifications for the logged-in user
        $notifications = Notification::where('EmployeeNumber', auth()->user()->EmployeeNumber)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Notification $notification)
    {
        // Find the notification by ID and update its status
        $notification->update(['Status' => 'Read']);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        // Update all unread notifications for the logged-in user
        Notification::where('EmployeeNumber', auth()->user()->EmployeeNumber)
            ->where('Status', 'Unread')
            ->update(['Status' => 'Read']);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Notification $notification)
    {
        // Find the notification by ID and delete it
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }
}
