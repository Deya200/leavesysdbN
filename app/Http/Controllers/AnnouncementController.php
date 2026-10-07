<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Employee;
use App\Mail\AnnouncementMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    /**
     * Display all announcements
     */
    public function index()
    {
        $announcements = Announcement::latest()->paginate(15);
        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show form to create announcement
     */
    public function create()
    {
        return view('announcements.create');
    }

    /**
     * Store announcement and optionally send emails
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'send_email' => 'boolean',
        ]);

        return DB::transaction(function () use ($validated) {
            $announcement = Announcement::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            Log::info("Announcement created: {$announcement->id}");

            // Send emails to all employees if requested
            if ($validated['send_email'] ?? false) {
                $this->sendAnnouncementEmails($announcement);
            }

            return redirect()->route('announcements.index')
                ->with('success', 'Announcement created successfully!' . 
                    ($validated['send_email'] ? ' Emails sent to all employees.' : ''));
        });
    }

    /**
     * Show announcement details
     */
    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show edit form
     */
    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $announcement->update($validated);

        Log::info("Announcement updated: {$announcement->id}");

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Delete announcement
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        Log::info("Announcement deleted: {$announcement->id}");

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    /**
     * Send announcement email to all employees
     */
    public function sendEmails(Announcement $announcement)
    {
        $this->sendAnnouncementEmails($announcement);

        return redirect()->back()->with('success', 'Announcement emails sent to all employees!');
    }

    /**
     * Helper method to send emails to all employees
     */
    protected function sendAnnouncementEmails(Announcement $announcement)
    {
        try {
            $employees = Employee::whereNotNull('email')->get();

            foreach ($employees as $employee) {
                try {
                    Mail::to($employee->email)->send(
                        new AnnouncementMail($announcement, "{$employee->FirstName} {$employee->LastName}")
                    );
                } catch (\Exception $e) {
                    Log::error("Failed to send announcement email to {$employee->email}: " . $e->getMessage());
                }
            }

            Log::info("Announcement emails sent. Total recipients: " . count($employees));
        } catch (\Exception $e) {
            Log::error("Error sending announcement emails: " . $e->getMessage());
            throw $e;
        }
    }
}
