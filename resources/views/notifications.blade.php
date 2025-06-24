@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<!--
<div class="container">
    <h1>Notifications</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($notifications->count())
        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-secondary mb-3">Mark All as Read</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Received At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($notifications as $notification)
                <tr @if($notification->Status == 'Unread') style="font-weight:bold;" @endif>
                    <td>{{ $notification->Message }}</td>
                    <td>
                        @if($notification->Status == 'Unread')
                            <span class="badge bg-warning">Unread</span>
                        @else
                            <span class="badge bg-success">Read</span>
                        @endif
                    </td>
                    <td>{{ $notification->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($notification->Status == 'Unread')
                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Mark as Read</button>
                        </form>
                        @endif
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this notification?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No notifications.</p>
    @endif
</div>
@endsection
