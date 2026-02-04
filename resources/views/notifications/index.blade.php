@extends('layouts.app')

@section('title', 'Notifications')

@section('styles')
<style>
/* === MATCHING DEPARTMENTS PAGE THEME === */
.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* Card Styles - Matching Departments */
.card-custom {
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 1px solid #e9ecef;
    padding: 0;
    margin-bottom: 25px;
}

/* Creamy White Header - Matching Departments */
.header-card {
    background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
    color: #2E3A87;
    padding: 24px 30px;
    border-bottom: 2px solid #e9ecef;
    border-radius: 12px 12px 0 0;
    position: relative;
    overflow: hidden;
}

.header-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
}

/* Table Container Card - Matching Departments */
.table-card {
    padding: 25px;
    background-color: #f8fafc;
}

/* Table Styling - Matching Departments */
.table {
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid #e9ecef;
}

.table thead tr {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    color: white;
    font-weight: 600;
    height: 56px;
}

.table thead th {
    padding: 16px;
    border: none;
    font-size: 0.95rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.table tbody tr {
    background-color: #ffffff;
    color: #333;
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f5;
}

.table tbody tr:hover {
    background-color: #f8faff !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(46, 58, 135, 0.1);
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table td {
    padding: 16px;
    vertical-align: middle;
    border: none;
    font-size: 0.95rem;
}

/* Zebra Striping - Matching Departments */
.table tbody tr:nth-child(even) {
    background-color: #f9fafc;
}

/* Highlight unread notifications */
.table tbody tr.unread-notification {
    background-color: rgba(46, 58, 135, 0.05);
    border-left: 4px solid #2E3A87;
}

.table tbody tr.unread-notification:hover {
    background-color: rgba(46, 58, 135, 0.1) !important;
}

/* Badges - Matching Departments Style */
.badge {
    padding: 6px 12px;
    font-weight: 500;
    font-size: 12px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.badge-warning {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.badge-success {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.2);
}

/* Buttons - Matching Departments */
.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 8px 16px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 8px rgba(46, 58, 135, 0.2);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(46, 58, 135, 0.3);
    background: linear-gradient(135deg, #26327A 0%, #3D4DC7 100%);
    color: white;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.2);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    color: white;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
    min-width: 100px;
}

/* Alert Messages - Matching Departments */
.alert {
    border-radius: 8px;
    border: none;
    padding: 16px 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border-left: 4px solid #28a745;
}

.alert-success i {
    color: #28a745;
    margin-right: 10px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-state i {
    font-size: 48px;
    color: #dee2e6;
    margin-bottom: 15px;
}

.empty-state h4 {
    color: #6c757d;
    margin-bottom: 10px;
    font-weight: 600;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 0;
}

/* Notification Message Styling */
.notification-message {
    color: #2E3A87;
    font-weight: 500;
    max-width: 400px;
    word-wrap: break-word;
}

.unread-notification .notification-message {
    font-weight: 600;
    color: #1a237e;
}

/* Notification Time */
.notification-time {
    color: #6c757d;
    font-size: 0.85rem;
    white-space: nowrap;
}

/* Header Actions */
.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 0 5px;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-title i {
    color: #2E3A87;
    font-size: 20px;
}

.header-title h1 {
    font-size: 24px;
    font-weight: 700;
    color: #2E3A87;
    margin: 0;
}

/* Stats Badge */
.stats-badge {
    background: rgba(46, 58, 135, 0.1);
    color: #2E3A87;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Responsive Adjustments - Matching Departments */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    
    .header-card {
        padding: 20px;
    }
    
    .table-card {
        padding: 15px;
    }
    
    .table th, .table td {
        padding: 12px 8px;
        font-size: 0.9rem;
    }
    
    .header-actions {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-sm {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .notification-message {
        max-width: 200px;
    }
}

@media (max-width: 576px) {
    .table-responsive {
        border-radius: 8px;
        border: 1px solid #eef1f5;
    }
    
    .table thead {
        display: none;
    }
    
    .table tbody tr {
        display: block;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    
    .table td {
        display: block;
        text-align: right;
        padding: 10px 15px;
        border-bottom: 1px solid #f1f3f5;
    }
    
    .table td::before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        color: #2E3A87;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    
    .table td:last-child {
        border-bottom: none;
    }
    
    .notification-message {
        max-width: 100%;
    }
    
    .action-buttons {
        justify-content: flex-end;
    }
}
</style>
@endsection

@section('content')
<div class="container">
    <!-- Main Card - Matching Departments Structure -->
    <div class="card-custom">
        <!-- Header -->
        <div class="header-card">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-title">
                    <i class="fas fa-bell fa-lg"></i>
                    <h1>Notifications</h1>
                </div>
                <div class="stats-badge">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $notifications->count() }} Total</span>
                    @if($notifications->where('Status', 'Unread')->count())
                        <span class="ms-2" style="color: #dc3545;">
                            ({{ $notifications->where('Status', 'Unread')->count() }} Unread)
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notifications Content -->
        <div class="table-card">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($notifications->count())
                <div class="header-actions">
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-check-double me-2"></i>Mark All as Read
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
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
                                <tr class="{{ $notification->Status == 'Unread' ? 'unread-notification' : '' }}"
                                    data-label="{{ $notification->Status == 'Unread' ? 'unread-notification' : '' }}">
                                    <td data-label="Message">
                                        <div class="notification-message">
                                            <i class="fas
                                                @if(str_contains(strtolower($notification->Message), 'approved')) fa-check-circle text-success
                                                @elseif(str_contains(strtolower($notification->Message), 'rejected')) fa-times-circle text-danger
                                                @elseif(str_contains(strtolower($notification->Message), 'pending')) fa-clock text-warning
                                                @elseif(str_contains(strtolower($notification->Message), 'leave')) fa-umbrella-beach text-primary
                                                @else fa-info-circle text-info
                                                @endif
                                                me-2">
                                            </i>
                                            {{ $notification->Message }}
                                        </div>
                                    </td>
                                    <td data-label="Status">
                                        @if($notification->Status == 'Unread')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-envelope me-1"></i>Unread
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                <i class="fas fa-envelope-open me-1"></i>Read
                                            </span>
                                        @endif
                                    </td>
                                    <td data-label="Received At" class="notification-time">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $notification->created_at->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">{{ $notification->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-buttons">
                                            @if($notification->Status == 'Unread')
                                            <form action="{{ route('notifications.markAsRead', $notification->NotificationID) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-check me-1"></i>Mark Read
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('notifications.destroy', $notification->NotificationID) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this notification?')" 
                                                        class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash-alt me-1"></i>Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-bell-slash fa-3x"></i>
                    <h4>No Notifications</h4>
                    <p>You don't have any notifications at the moment.</p>
                    <p class="small text-muted">Notifications will appear here when you receive updates.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            if (!this.classList.contains('unread-notification')) {
                this.style.backgroundColor = '#f8faff';
            }
            this.style.transform = 'translateY(-1px)';
            this.style.boxShadow = '0 2px 8px rgba(46, 58, 135, 0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            if (!this.classList.contains('unread-notification')) {
                this.style.backgroundColor = '';
            }
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });

    // Enhanced delete confirmation
    const deleteButtons = document.querySelectorAll('form button.btn-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const notificationMessage = this.closest('tr').querySelector('.notification-message').textContent.trim();
            
            if (!confirm(`Are you sure you want to delete this notification?\n\n"${notificationMessage.substring(0, 50)}${notificationMessage.length > 50 ? '...' : ''}"`)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });

    // Auto-refresh notifications every 30 seconds if there are unread ones
    @if($notifications->where('Status', 'Unread')->count() > 0)
    setInterval(() => {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                // Check if the page has new notifications
                console.log('Checking for new notifications...');
                // You could implement actual update logic here
            })
            .catch(error => console.error('Error checking notifications:', error));
    }, 30000);
    @endif
});
</script>
@endsection