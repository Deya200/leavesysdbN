@extends('layouts.app')

@section('title', 'Staff Voice')

@php
    $statusOptions = ['Submitted', 'Under Review', 'In Progress', 'Contact Arranged', 'Ongoing Support', 'In Mediation', 'Resolved', 'Implemented', 'Closed'];
    $sections = [
        'idea' => ['label' => 'Ideas', 'icon' => 'fa-lightbulb'],
        'recognition' => ['label' => 'Recognition', 'icon' => 'fa-heart'],
        'poll' => ['label' => 'Polls', 'icon' => 'fa-chart-bar'],
        'question' => ['label' => 'Questions', 'icon' => 'fa-message'],
        'help' => ['label' => 'Confidential Help', 'icon' => 'fa-life-ring'],
        'grievance' => ['label' => 'Grievances', 'icon' => 'fa-people-arrows'],
        'report' => ['label' => 'Reports', 'icon' => 'fa-shield-halved'],
    ];
    $activeSection = array_key_exists(request('section', 'idea'), $sections) ? request('section', 'idea') : 'idea';

    function staff_voice_status_class($status) {
        return match ($status) {
            'Resolved', 'Implemented', 'Closed' => 'success',
            'Under Review', 'In Progress', 'Contact Arranged', 'Ongoing Support', 'In Mediation' => 'warning',
            default => 'secondary',
        };
    }
@endphp

@section('styles')
<style>
    .sv-page { max-width: 1420px; margin: 0 auto; }
    .sv-hero { background: #fff; border: 1px solid #e6ece8; border-radius: 8px; padding: 1.35rem; margin-bottom: 1rem; display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .sv-title { margin: 0; font-weight: 800; color: #204a3f; letter-spacing: 0; }
    .sv-sub { margin: .25rem 0 0; color: #64746d; }
    .sv-tabs { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1rem; }
    .sv-tab { border: 1px solid #d9e3dd; background: #fff; color: #42564e; border-radius: 6px; padding: .55rem .8rem; text-decoration: none; font-weight: 700; font-size: .88rem; }
    .sv-tab.active { background: #2c6353; border-color: #2c6353; color: #fff; }
    .sv-grid { display: grid; grid-template-columns: minmax(280px, 410px) 1fr; gap: 1rem; align-items: start; }
    .sv-card { background: #fff; border: 1px solid #e1e8e3; border-radius: 8px; box-shadow: 0 1px 2px rgba(15, 23, 42, .04); }
    .sv-card-header { padding: 1rem 1.1rem; border-bottom: 1px solid #edf2ef; display: flex; align-items: center; justify-content: space-between; gap: .75rem; }
    .sv-card-body { padding: 1.1rem; }
    .sv-card h2, .sv-card h3 { margin: 0; color: #204a3f; font-weight: 800; }
    .sv-card h2 { font-size: 1.05rem; }
    .sv-card h3 { font-size: 1rem; }
    .sv-help { color: #64746d; font-size: .86rem; margin: .25rem 0 0; }
    .sv-list { display: grid; gap: .75rem; }
    .sv-item { padding: 1rem; border: 1px solid #e6ece8; border-radius: 8px; background: #fff; }
    .sv-item-title { margin: 0; font-size: 1rem; color: #1f332c; font-weight: 800; }
    .sv-meta { display: flex; flex-wrap: wrap; gap: .45rem; color: #64746d; font-size: .78rem; margin-top: .5rem; }
    .sv-pill { border-radius: 999px; padding: .16rem .55rem; background: #eef2ec; color: #42564e; font-weight: 700; }
    .sv-body { margin: .65rem 0 0; color: #41534c; line-height: 1.55; white-space: pre-wrap; }
    .sv-response { margin-top: .75rem; border-left: 3px solid #2c6353; background: #eef2ec; border-radius: 6px; padding: .75rem; color: #33443d; }
    .sv-empty { border: 1px dashed #cfdad3; border-radius: 8px; padding: 2rem; text-align: center; color: #6b7c74; background: #fbfcfa; }
    .sv-option { display: grid; grid-template-columns: 1fr auto; gap: .5rem; align-items: center; margin-bottom: .45rem; }
    .sv-option-bar { background: #eef2ec; border-radius: 6px; overflow: hidden; height: 10px; margin-top: .25rem; }
    .sv-option-fill { background: #d89a48; height: 100%; }
    .sv-ref { font-weight: 800; color: #204a3f; letter-spacing: .05em; }
    .sv-admin { margin-top: .85rem; padding-top: .85rem; border-top: 1px dashed #d9e3dd; }
    @media (max-width: 1000px) { .sv-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="sv-page">
    <div class="sv-hero">
        <div>
            <h1 class="sv-title">ABCMH Staff Voice</h1>
            <p class="sv-sub">Share ideas, recognise colleagues, join polls, ask questions, or use confidential support channels.</p>
        </div>
        <form method="GET" action="{{ route('staff_voice.index') }}" class="d-flex gap-2 align-items-start">
            <input type="text" name="reference" class="form-control" value="{{ request('reference') }}" placeholder="Reference code">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i> Look up</button>
        </form>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please check your submission.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(request('reference'))
        <div class="sv-card mb-3">
            <div class="sv-card-body">
                @if($referenceResult)
                    <div class="d-flex justify-content-between gap-2 flex-wrap">
                        <div>
                            <span class="sv-ref">{{ $referenceResult->reference_code }}</span>
                            <h3 class="mt-2">{{ $referenceResult->title }}</h3>
                            <p class="sv-body">{{ $referenceResult->body }}</p>
                            @if($referenceResult->response)
                                <div class="sv-response"><strong>Response:</strong> {{ $referenceResult->response }}</div>
                            @endif
                        </div>
                        <span class="badge bg-{{ staff_voice_status_class($referenceResult->status) }}">{{ $referenceResult->status }}</span>
                    </div>
                @else
                    <div class="sv-empty">No matching item was found for that reference code.</div>
                @endif
            </div>
        </div>
    @endif

    <div class="sv-tabs">
        @foreach($sections as $key => $section)
            <a class="sv-tab {{ $activeSection === $key ? 'active' : '' }}" href="{{ route('staff_voice.index', ['section' => $key]) }}">
                <i class="fas {{ $section['icon'] }} me-1"></i>{{ $section['label'] }}
            </a>
        @endforeach
    </div>

    <div class="sv-grid">
        <div class="sv-card">
            <div class="sv-card-header">
                <div>
                    <h2>Submit {{ $sections[$activeSection]['label'] ?? 'Item' }}</h2>
                    <p class="sv-help">Only confidential channels receive reference codes.</p>
                </div>
            </div>
            <div class="sv-card-body">
                <form method="POST" action="{{ route('staff_voice.store') }}">
                    @csrf
                    <input type="hidden" name="type" value="{{ $activeSection }}">

                    @if(in_array($activeSection, ['idea', 'poll', 'grievance'], true))
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input class="form-control" name="title" value="{{ old('title') }}" {{ in_array($activeSection, ['idea', 'poll'], true) ? 'required' : '' }}>
                        </div>
                    @endif

                    @if(in_array($activeSection, ['idea', 'recognition', 'poll', 'question', 'help', 'grievance'], true))
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department">
                                <option value="">Select department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department }}" @selected(old('department') === $department)>{{ $department }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if($activeSection === 'recognition')
                        <div class="mb-3">
                            <label class="form-label">Colleague</label>
                            <input class="form-control" name="recipient" value="{{ old('recipient') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <select class="form-select" name="category" required>
                                @foreach($values as $value)
                                    <option value="{{ $value }}" @selected(old('category') === $value)>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if($activeSection === 'question')
                        <div class="mb-3">
                            <label class="form-label">Send to</label>
                            <select class="form-select" name="recipient" required>
                                @foreach($recipients as $recipient)
                                    <option value="{{ $recipient }}" @selected(old('recipient') === $recipient)>{{ $recipient }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if($activeSection === 'help')
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" required>
                                @foreach($helpCategories as $category)
                                    <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preferred contact</label>
                            <input class="form-control" name="contact" value="{{ old('contact') }}">
                        </div>
                    @endif

                    @if($activeSection === 'report')
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" required>
                                @foreach($reportCategories as $category)
                                    <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Optional contact</label>
                            <input class="form-control" name="contact" value="{{ old('contact') }}">
                        </div>
                        <input type="hidden" name="is_anonymous" value="0">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_anonymous" value="1" id="is_anonymous" @checked(old('is_anonymous', '1'))>
                            <label class="form-check-label" for="is_anonymous">Submit anonymously</label>
                        </div>
                    @endif

                    @if($activeSection === 'grievance')
                        <div class="mb-3">
                            <label class="form-label">Person involved</label>
                            <input class="form-control" name="about_person" value="{{ old('about_person') }}">
                        </div>
                        <input type="hidden" name="wants_mediation" value="0">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="wants_mediation" value="1" id="wants_mediation" @checked(old('wants_mediation'))>
                            <label class="form-check-label" for="wants_mediation">I would like HR mediation</label>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">{{ $activeSection === 'recognition' ? 'Message' : 'Details' }}</label>
                        <textarea class="form-control" name="body" rows="5" required>{{ old('body') }}</textarea>
                    </div>

                    @if($activeSection === 'poll')
                        <div class="mb-3">
                            <label class="form-label">Poll options</label>
                            @for($i = 0; $i < 4; $i++)
                                <input class="form-control mb-2" name="options[]" value="{{ old('options.' . $i) }}" placeholder="Option {{ $i + 1 }}" {{ $i < 2 ? 'required' : '' }}>
                            @endfor
                        </div>
                    @endif

                    <button class="btn btn-primary w-100" type="submit">Submit</button>
                </form>
            </div>
        </div>

        <div class="sv-card">
            <div class="sv-card-header">
                <h2>{{ $sections[$activeSection]['label'] ?? 'Items' }}</h2>
            </div>
            <div class="sv-card-body">
                @php
                    $items = match($activeSection) {
                        'idea' => $ideas,
                        'recognition' => $recognitions,
                        'poll' => $polls,
                        'question' => $questions,
                        default => $confidentialItems->where('type', $activeSection),
                    };
                @endphp

                @if($items->isEmpty())
                    <div class="sv-empty">Nothing here yet.</div>
                @else
                    <div class="sv-list">
                        @foreach($items as $item)
                            <article class="sv-item">
                                <div class="d-flex justify-content-between gap-2 flex-wrap">
                                    <h3 class="sv-item-title">{{ $item->title }}</h3>
                                    <span class="badge bg-{{ staff_voice_status_class($item->status) }}">{{ $item->status }}</span>
                                </div>
                                <div class="sv-meta">
                                    <span class="sv-pill">{{ $item->display_author }}</span>
                                    @if($item->department)<span class="sv-pill">{{ $item->department }}</span>@endif
                                    @if($item->recipient)<span class="sv-pill">To: {{ $item->recipient }}</span>@endif
                                    @if($item->category)<span class="sv-pill">{{ $item->category }}</span>@endif
                                    @if($item->reference_code)<span class="sv-pill sv-ref">{{ $item->reference_code }}</span>@endif
                                    <span>{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="sv-body">{{ $item->body }}</p>

                                @if($item->type === 'idea')
                                    <form method="POST" action="{{ route('staff_voice.ideas.vote', $item) }}" class="mt-3">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-primary" type="submit"><i class="fas fa-arrow-up me-1"></i>{{ $item->votes }} votes</button>
                                    </form>
                                @endif

                                @if($item->type === 'poll')
                                    @php($totalVotes = max(1, $item->pollOptions->sum('votes')))
                                    <div class="mt-3">
                                        @foreach($item->pollOptions as $option)
                                            @php($percent = round(($option->votes / $totalVotes) * 100))
                                            <form method="POST" action="{{ route('staff_voice.polls.vote', $option) }}" class="sv-option">
                                                @csrf
                                                <div>
                                                    <strong>{{ $option->label }}</strong>
                                                    <div class="sv-option-bar"><div class="sv-option-fill" style="width: {{ $percent }}%"></div></div>
                                                </div>
                                                <button class="btn btn-sm btn-outline-primary" type="submit">{{ $option->votes }}</button>
                                            </form>
                                        @endforeach
                                    </div>
                                @endif

                                @if($item->response)
                                    <div class="sv-response"><strong>Response:</strong> {{ $item->response }}</div>
                                @endif

                                @if($isAdmin)
                                    <form method="POST" action="{{ route('staff_voice.respond', $item) }}" class="sv-admin">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <select class="form-select form-select-sm" name="status" required>
                                                    @foreach($statusOptions as $status)
                                                        <option value="{{ $status }}" @selected($item->status === $status)>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control form-control-sm" name="response" value="{{ $item->response }}" placeholder="Response note">
                                            </div>
                                            <div class="col-md-2 d-grid">
                                                <button class="btn btn-sm btn-primary" type="submit">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
