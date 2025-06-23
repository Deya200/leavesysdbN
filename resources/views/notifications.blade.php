@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Notifications</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($notifications->count())
        <form method="POST" action="{{ route('notifications.readAll') }}" class="mb-3">
            @csrf
            <button class="btn btn-primary" type="submit">Mark All as Read</button>
        </form>
        <ul class="list-group">
            @foreach($notifications as $notification)
                <li class="list-group-item d-flex justify-content-between align-items-center {{ $notification->Status == 'Unread' ? 'fw-bold' : '' }}">
                    {{ $notification->message ?? 'No message' }}
                    <span>
                        @if($notification->Status == 'Unread')
                            <form style="display:inline" method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-success">Mark as Read</button>
                            </form>
                        @endif
                        <form style="display:inline" method="POST" action="{{ route('notifications.destroy', $notification->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </span>
                </li>
            @endforeach
        </ul>
    @else
        <p>You don’t have any notifications at the moment.</p>
    @endif
</div>
@endsection
