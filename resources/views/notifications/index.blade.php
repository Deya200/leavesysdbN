@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Notifications</h1>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- List of Notifications -->
    <ul class="list-group">
        @forelse ($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $notification->Message }}</strong>
                    <br>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <div>
                    @if ($notification->Status === 'Unread')
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Mark as Read</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </li>
        @empty
            <li class="list-group-item text-center">No notifications available.</li>
        @endforelse
    </ul>

    <!-- Mark All as Read Button -->
    @if ($notifications->where('Status', 'Unread')->count() > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-primary">Mark All as Read</button>
        </form>
    @endif
</div>
@endsection
