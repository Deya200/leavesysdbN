<?php

namespace App\Http\Controllers;

use App\Models\StaffVoiceEntry;
use App\Models\StaffVoicePollOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffVoiceController extends Controller
{
    private const PUBLIC_TYPES = ['idea', 'recognition', 'poll', 'question'];
    private const CONFIDENTIAL_TYPES = ['help', 'grievance', 'report'];

    public function index(Request $request): View
    {
        $user = $request->user();
        $isAdmin = (int) $user->role_id === 1;

        $entries = StaffVoiceEntry::with('pollOptions')
            ->where(function ($query) use ($user, $isAdmin) {
                $query->where('is_public', true);

                if ($isAdmin) {
                    $query->orWhereIn('type', self::CONFIDENTIAL_TYPES);
                } else {
                    $query->orWhere('submitted_by', $user->EmployeeNumber);
                }
            })
            ->latest()
            ->get();

        $referenceResult = null;
        if ($request->filled('reference')) {
            $referenceResult = StaffVoiceEntry::with('pollOptions')
                ->where('reference_code', strtoupper(trim($request->string('reference'))))
                ->where(function ($query) use ($user, $isAdmin) {
                    if (!$isAdmin) {
                        $query->where('submitted_by', $user->EmployeeNumber)
                            ->orWhere('is_anonymous', true);
                    }
                })
                ->first();
        }

        return view('staff_voice.index', [
            'ideas' => $entries->where('type', 'idea'),
            'recognitions' => $entries->where('type', 'recognition'),
            'polls' => $entries->where('type', 'poll'),
            'questions' => $entries->where('type', 'question'),
            'confidentialItems' => $entries->whereIn('type', self::CONFIDENTIAL_TYPES),
            'referenceResult' => $referenceResult,
            'isAdmin' => $isAdmin,
            'departments' => $this->departments(),
            'recipients' => ['HR', 'Finance', 'Payroll', 'Administration', 'Management'],
            'values' => ['Compassion', 'Integrity', 'Teamwork', 'Excellence'],
            'helpCategories' => ['Workload or stress', 'Conflict outside work', 'Health and wellbeing', 'Financial hardship', 'Family or caregiving', 'Other'],
            'reportCategories' => ['Theft or fraud', 'Corruption', 'Diversion of hospital resources', 'Abuse', 'Concealed patient-safety risk', 'Other serious wrongdoing'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $type = $request->string('type')->toString();
        abort_unless(in_array($type, StaffVoiceEntry::TYPES, true), 422);

        $validated = $this->validateByType($request, $type);
        $user = $request->user();

        $entry = DB::transaction(function () use ($request, $validated, $type, $user) {
            $isConfidential = in_array($type, self::CONFIDENTIAL_TYPES, true);
            $isAnonymous = $type === 'report' && $request->boolean('is_anonymous', true);

            $entry = StaffVoiceEntry::create([
                'type' => $type,
                'title' => $validated['title'] ?? $this->titleFor($type),
                'body' => $validated['body'],
                'author_name' => $isAnonymous ? null : ($validated['author_name'] ?? $this->currentUserName($user)),
                'department' => $validated['department'] ?? null,
                'recipient' => $validated['recipient'] ?? null,
                'category' => $validated['category'] ?? null,
                'status' => $this->initialStatus($type),
                'reference_code' => $isConfidential ? $this->referenceCode($type) : null,
                'meta' => $this->metaFor($validated, $type),
                'submitted_by' => $isAnonymous ? null : $user->EmployeeNumber,
                'is_anonymous' => $isAnonymous,
                'is_public' => !$isConfidential,
            ]);

            if ($type === 'poll') {
                foreach ($validated['options'] as $option) {
                    $entry->pollOptions()->create(['label' => $option]);
                }
            }

            return $entry;
        });

        $message = in_array($type, self::CONFIDENTIAL_TYPES, true)
            ? 'Submitted safely. Your reference number is ' . $entry->reference_code . '.'
            : 'Thank you. Your contribution has been posted.';

        return redirect()
            ->route('staff_voice.index', ['section' => $type])
            ->with('success', $message);
    }

    public function voteIdea(Request $request, StaffVoiceEntry $entry): RedirectResponse
    {
        abort_unless($entry->type === 'idea', 404);

        $voted = session('staff_voice_idea_votes', []);
        if (!in_array($entry->id, $voted, true)) {
            $entry->increment('votes');
            $voted[] = $entry->id;
            session(['staff_voice_idea_votes' => $voted]);
        }

        return back()->with('success', 'Vote recorded.');
    }

    public function votePoll(Request $request, StaffVoicePollOption $option): RedirectResponse
    {
        $entry = $option->entry;
        abort_unless($entry && $entry->type === 'poll', 404);

        $voted = session('staff_voice_poll_votes', []);
        if (!in_array($entry->id, $voted, true)) {
            $option->increment('votes');
            $voted[] = $entry->id;
            session(['staff_voice_poll_votes' => $voted]);
        }

        return back()->with('success', 'Poll vote recorded.');
    }

    public function respond(Request $request, StaffVoiceEntry $entry): RedirectResponse
    {
        abort_unless((int) $request->user()->role_id === 1, 403);

        $validated = $request->validate([
            'status' => ['required', 'string', 'max:40'],
            'response' => ['nullable', 'string', 'max:2000'],
        ]);

        $entry->update([
            'status' => $validated['status'],
            'response' => $validated['response'] ?? null,
            'responded_by' => $this->currentUserName($request->user()),
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Staff voice item updated.');
    }

    private function validateByType(Request $request, string $type): array
    {
        $common = [
            'department' => ['nullable', 'string', 'max:120'],
            'author_name' => ['nullable', 'string', 'max:160'],
        ];

        return match ($type) {
            'idea' => $request->validate($common + [
                'title' => ['required', 'string', 'max:180'],
                'body' => ['required', 'string', 'max:3000'],
            ]),
            'recognition' => $request->validate($common + [
                'recipient' => ['required', 'string', 'max:160'],
                'category' => ['required', 'string', 'max:80'],
                'body' => ['required', 'string', 'max:1200'],
            ]),
            'poll' => $request->validate($common + [
                'title' => ['required', 'string', 'max:180'],
                'body' => ['required', 'string', 'max:800'],
                'options' => ['required', 'array', 'min:2', 'max:6'],
                'options.*' => ['required', 'string', 'max:160'],
            ]),
            'question' => $request->validate($common + [
                'recipient' => ['required', 'string', 'max:120'],
                'body' => ['required', 'string', 'max:2000'],
            ]),
            'help' => $request->validate($common + [
                'category' => ['required', 'string', 'max:120'],
                'body' => ['required', 'string', 'max:2500'],
                'contact' => ['nullable', 'string', 'max:180'],
            ]),
            'grievance' => $request->validate($common + [
                'title' => ['nullable', 'string', 'max:180'],
                'body' => ['required', 'string', 'max:3000'],
                'about_person' => ['nullable', 'string', 'max:160'],
                'wants_mediation' => ['nullable', 'boolean'],
            ]),
            'report' => $request->validate([
                'category' => ['required', 'string', 'max:160'],
                'body' => ['required', 'string', 'max:3000'],
                'contact' => ['nullable', 'string', 'max:180'],
                'is_anonymous' => ['nullable', 'boolean'],
            ]),
        };
    }

    private function metaFor(array $validated, string $type): array
    {
        return array_filter([
            'contact' => $validated['contact'] ?? null,
            'about_person' => $validated['about_person'] ?? null,
            'wants_mediation' => $validated['wants_mediation'] ?? false,
        ], fn ($value) => $value !== null && $value !== '');
    }

    private function initialStatus(string $type): string
    {
        return match ($type) {
            'idea', 'question' => 'Under Review',
            default => 'Submitted',
        };
    }

    private function titleFor(string $type): string
    {
        return match ($type) {
            'recognition' => 'Colleague recognition',
            'question' => 'Question or feedback',
            'help' => 'Confidential help request',
            'grievance' => 'Named grievance',
            'report' => 'Serious wrongdoing report',
            default => 'Staff voice item',
        };
    }

    private function referenceCode(string $type): string
    {
        $prefix = match ($type) {
            'grievance' => 'GR',
            'report' => 'WB',
            default => 'CH',
        };

        do {
            $code = $prefix . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(3));
        } while (StaffVoiceEntry::where('reference_code', $code)->exists());

        return $code;
    }

    private function currentUserName($user): string
    {
        return trim(($user->FirstName ?? '') . ' ' . ($user->LastName ?? '')) ?: ($user->name ?? 'Staff member');
    }

    private function departments(): array
    {
        return [
            'Nursing',
            'Administration',
            'Finance',
            'IT',
            'Facilities & Maintenance',
            'Pharmacy',
            'Laboratory',
            'Kitchen & Catering',
            'Social Welfare Committee',
            'Other',
        ];
    }
}
