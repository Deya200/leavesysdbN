@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Notifications</h1>
            <p class="text-muted mb-0">Your latest notifications, including locum invitations and action items.</p>
        </div>
        <a href="{{ route('locum.index') }}" class="btn btn-primary">
            <i class="fas fa-user-md me-2"></i>Open Locum Booking
        </a>
    </div>

    @if(isset($locumInvitations) && $locumInvitations->count())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body bg-light">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Locum Invitations</h5>
                        <small class="text-muted">Showing {{ $locumInvitations->count() }} locum-related notification(s).</small>
                    </div>
                    <a href="{{ route('locum.index') }}" class="btn btn-sm btn-outline-primary">Go to Locum Booking</a>
                </div>

                @foreach($locumInvitations as $invitation)
                    <div class="border-bottom py-3">
                        <p class="mb-1">{{ $invitation->Message }}</p>
                        <small class="text-muted">{{ $invitation->created_at->diffForHumans() }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($notifications->count())
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">Mark All as Read</button>
            </form>
            <form action="{{ route('notifications.bulkDelete') }}" method="POST" id="bulk-delete-form" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" id="bulk-delete-btn" disabled onclick="return confirm('Delete selected notifications?')">Delete Selected</button>
            </form>
        </div>
        <form id="notifications-form">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="select-all">
                        </th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Received At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($notifications as $notification)
                    <tr @if($notification->Status == 'Unread') style="font-weight:bold;" @endif>
                        <td>
                            <input type="checkbox" name="notification_ids[]" value="{{ $notification->id }}" class="notification-checkbox">
                        </td>
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
        </form>
    @else
        <p>No notifications.</p>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const notificationCheckboxes = document.querySelectorAll('.notification-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkDeleteForm = document.getElementById('bulk-delete-form');

    // Handle select all checkbox
    selectAllCheckbox.addEventListener('change', function() {
        notificationCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkDeleteButton();
    });

    // Handle individual checkboxes
    notificationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.notification-checkbox:checked');
            selectAllCheckbox.checked = checkedBoxes.length === notificationCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < notificationCheckboxes.length;
            updateBulkDeleteButton();
        });
    });

    // Update bulk delete button state
    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.notification-checkbox:checked');
        bulkDeleteBtn.disabled = checkedBoxes.length === 0;
    }

    // Handle bulk delete form submission
    bulkDeleteForm.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.notification-checkbox:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one notification to delete.');
            return;
        }

        // Add selected IDs to the form
        checkedBoxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'notification_ids[]';
            input.value = checkbox.value;
            bulkDeleteForm.appendChild(input);
        });
    });
});
</script>
@endsection
