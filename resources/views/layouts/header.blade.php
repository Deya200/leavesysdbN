<!-- Header -->
<header class="py-2 border-bottom" style="position: sticky; top: 0; z-index: 1050; background: #3D519F; color: #fff; font-family: system-ui, 'Segoe UI', Arial, sans-serif;">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Left: Hamburger (Sidebar Toggle) -->
            <div class="d-flex align-items-center gap-2">
                <!-- Hamburger button: always visible -->
                <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainSidebar" aria-controls="mainSidebar" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('logo3.png') }}" alt="Logo" class="rounded-circle bg-light p-1" style="height: 38px; width: 38px;">
                </a>
            </div>
            <!-- Right: Notifications, Profile and Logout -->
            <div class="d-flex align-items-center gap-3">
                <!-- Dark mode toggle -->
                <button id="darkModeToggle" class="btn btn-outline-secondary" type="button" aria-label="Toggle dark mode">
                    <i class="fas fa-moon"></i>
                </button>

                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount }}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notificationDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <li class="p-2 border-bottom d-flex justify-content-between align-items-center bg-light">
                            <h6 class="mb-0">Notifications</h6>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0">Mark all read</button>
                                </form>
                            @endif
                        </li>
                        @forelse($headerNotifications ?? [] as $notification)
                            <li>
                                <div class="dropdown-item d-flex gap-3 py-3 border-bottom {{ $notification->Status === 'Unread' ? 'bg-light' : '' }}">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-info text-primary small"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-1 small text-wrap">{{ $notification->Message }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                            @if($notification->Status === 'Unread')
                                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link btn-sm p-0" title="Mark as read"><i class="fas fa-check"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="p-4 text-center text-muted">No notifications</li>
                        @endforelse
                        <li class="p-2 text-center bg-light sticky-bottom">
                            <a href="{{ route('notifications') }}" class="small text-decoration-none">View All Notifications</a>
                        </li>
                    </ul>
                </div>

                <!-- Profile dropdown -->
                <div class="dropdown">
                    <button class="profile-btn d-flex align-items-center dropdown-toggle"
                        style="background:none; border:none; box-shadow:none;" type="button"
                        id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
                            <img src="{{ asset(Auth::user()->profile_photo) }}" class="profile-photo-navbar rounded-circle" alt="Profile Picture">
                        @else
                            <i class="fas fa-user-circle fa-2x text-white"></i>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="offcanvas" data-bs-target="#profileEditOffcanvas" aria-controls="profileEditOffcanvas">
                                <i class="fas fa-user me-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('notifications') }}">
                                <i class="fas fa-bell me-2"></i> Notifications
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); confirmLogout();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</header>
<script>
    function confirmLogout() {
        if (confirm('Are you sure you want to log out?')) {
            document.getElementById('logout-form').submit();
        }
    }
</script>
