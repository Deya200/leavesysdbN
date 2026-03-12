<!-- Header -->
<<<<<<< HEAD
<header class="py-3 border-0" style="position: sticky; top: 0; z-index: 1050; background: linear-gradient(135deg, #3D519F 0%, #4C63B6 50%, #5A75CD 100%); color: #fff; font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif; backdrop-filter: blur(10px); box-shadow: 0 8px 32px rgba(61, 81, 159, 0.3);">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Left: Hamburger (Sidebar Toggle) with Logo -->
            <div class="d-flex align-items-center gap-3">
                <!-- Hamburger button: always visible with modern styling -->
                <button class="hamburger-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainSidebar" aria-controls="mainSidebar" id="menuToggle" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 12px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s ease;">
                    <i class="fas fa-bars fs-5"></i>
                </button>
                <a href="{{ route('dashboard') }}" class="logo-container">
                    <img src="{{ asset('logo3.png') }}" alt="Logo" class="rounded-circle bg-white p-2" style="height: 44px; width: 44px; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: transform 0.3s ease;">
                </a>
            </div>

            <!-- Center: Optional Page Title/Time (can be customized) -->
            <div class="d-none d-md-block">
                <h6 class="mb-0 fw-light opacity-75" style="letter-spacing: 0.5px;">
                    <i class="fas fa-calendar-alt me-2"></i>{{ now()->format('l, F j, Y') }}
                </h6>
                <p id="headerMoodLine" class="mb-0 small text-center" style="opacity: 0.88;">Loading good vibes...</p>
            </div>

            <!-- Right: Notifications, Dark Mode, and Profile -->
            <div class="d-flex align-items-center gap-2">
                <!-- Dark mode toggle with modern styling -->
                <button id="darkModeToggle" class="theme-toggle-btn" type="button" aria-label="Toggle dark mode" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 12px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s ease;">
                    <i class="fas fa-moon fs-5"></i>
=======
<header class="py-2 border-bottom bg-white sticky-top" style="z-index: 1040; border-bottom: 1px solid var(--color-slate-200) !important;">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Left: Brand (Mobile Only / Toggle) -->
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link text-slate-600 d-lg-none p-0" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mainSidebar" aria-controls="mainSidebar" id="menuToggle">
                    <i class="fas fa-bars fs-5"></i>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                </button>
            </div>

<<<<<<< HEAD
                <!-- Notifications Dropdown with Modern Design -->
                <div class="dropdown">
                    <button class="notification-btn" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 12px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; color: white; transition: all 0.3s ease; position: relative;">
                        <i class="fas fa-bell fs-5"></i>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: #FF6B6B; font-size: 0.65rem; padding: 0.25rem 0.4rem; margin-left: -8px; margin-top: -4px; border: 2px solid #4C63B6;">
                                {{ $unreadCount }}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-0 border-0 shadow-lg" aria-labelledby="notificationDropdown" style="width: 360px; max-height: 450px; overflow-y: auto; border-radius: 16px; margin-top: 12px; animation: slideDown 0.3s ease;">
                        <li class="p-3 border-bottom d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-radius: 16px 16px 0 0;">
                            <h6 class="mb-0 fw-bold" style="color: #2C3E50;">Notifications</h6>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0" style="color: #3D519F; font-size: 0.85rem;">
                                        <i class="fas fa-check-circle me-1"></i>Mark all read
                                    </button>
                                </form>
                            @endif
                        </li>
                        @forelse($headerNotifications ?? [] as $notification)
                            <li>
                                <div class="dropdown-item px-3 py-3 border-bottom notification-item {{ $notification->Status === 'Unread' ? 'unread' : '' }}" style="transition: background 0.2s ease; cursor: pointer;">
                                    <div class="d-flex gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: {{ $notification->Status === 'Unread' ? 'rgba(61, 81, 159, 0.1)' : 'rgba(108, 117, 125, 0.1)' }};">
                                                <i class="fas {{ $notification->Status === 'Unread' ? 'fa-bell text-primary' : 'fa-check text-muted' }}"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 small" style="color: #2C3E50; line-height: 1.4; {{ $notification->Status === 'Unread' ? 'font-weight: 600;' : '' }}">{{ $notification->Message }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted" style="font-size: 0.7rem;">
                                                    <i class="far fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                </small>
                                                @if($notification->Status === 'Unread')
                                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link btn-sm p-0 text-success" title="Mark as read">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="p-4 text-center">
                                <div class="mb-3">
                                    <i class="fas fa-bell-slash fa-3x text-muted opacity-25"></i>
                                </div>
                                <h6 class="text-muted mb-1">No notifications. Peaceful, right?</h6>
                                <p class="small text-muted">Inbox zen achieved. Keep the streak going.</p>
                            </li>
                        @endforelse
                        <li class="p-2 text-center" style="background: #f8f9fa; border-radius: 0 0 16px 16px;">
                            <a href="{{ route('notifications') }}" class="small text-decoration-none" style="color: #3D519F; font-weight: 600;">
                                View All Notifications <i class="fas fa-arrow-right ms-1"></i>
                            </a>
=======
            <!-- Center: Dynamic Title -->
            <div class="d-none d-md-flex align-items-center flex-grow-1 ms-3">
                <h5 class="mb-0 fw-bold text-slate-900" style="letter-spacing: -0.5px; font-size: 1.1rem;">
                    @yield('page_title', 'Dashboard')</h5>
            </div>

            <!-- Right: Actions -->
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-icon-glass border shadow-none" type="button"
                        id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="far fa-bell text-slate-500"></i>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle border border-white"
                                style="width: 8px; height: 8px; margin-top: 4px; margin-left: -4px;"></span>
                        @endif
                    </button>
                    <!-- Dropdown content -->
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-0 mt-3"
                        aria-labelledby="notificationDropdown"
                        style="width: 320px; border-radius: var(--radius-xl); overflow: hidden;">
                        <li class="p-3 border-bottom d-flex justify-content-between align-items-center bg-slate-50">
                            <h6 class="mb-0 fw-bold text-slate-900">Notifications</h6>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-link btn-sm text-decoration-none p-0 fw-semibold text-primary">Mark all
                                        read</button>
                                </form>
                            @endif
                        </li>
                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse($headerNotifications ?? [] as $notification)
                                <li class="px-3 py-3 border-bottom hover-bg-slate-50 transition-all">
                                    <div class="d-flex gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="fas fa-info text-primary small"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 small text-slate-700 fw-medium">
                                                {{ optional($notification)->Message ?? 'No message content' }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-slate-400"
                                                    style="font-size: 0.7rem;">{{ optional(optional($notification)->created_at)->diffForHumans() ?? '' }}</small>
                                                @if((optional($notification)->Status ?? '') === 'Unread')
                                                    <form
                                                        action="{{ route('notifications.markAsRead', optional($notification)->id ?? 0) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-link btn-sm p-0 text-slate-300 hover-text-primary"
                                                            title="Mark as read"><i class="fas fa-check"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="p-4 text-center text-slate-400 small">No notifications</li>
                            @endforelse
                        </div>
                        <li class="p-2 text-center bg-slate-50 border-top">
                            <a href="{{ route('notifications') }}" class="small text-decoration-none fw-bold text-primary">View All
                                Notifications</a>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                        </li>
                    </ul>
                </div>

<<<<<<< HEAD
                <!-- Profile dropdown with Modern Design -->
                <div class="dropdown">
                    <button class="profile-btn d-flex align-items-center dropdown-toggle p-0 border-0 bg-transparent" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none; box-shadow: none;">
                        <div class="profile-wrapper" style="position: relative;">
                            @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
                                <img src="{{ asset(Auth::user()->profile_photo) }}" class="profile-photo-navbar rounded-circle" alt="Profile Picture" style="height: 44px; width: 44px; object-fit: cover; border: 3px solid rgba(255,255,255,0.5); box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: transform 0.3s ease;">
                            @else
                                <div style="height: 44px; width: 44px; background: rgba(255,255,255,0.2); border: 3px solid rgba(255,255,255,0.5); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                                    <i class="fas fa-user-circle fa-2x text-white"></i>
                                </div>
                            @endif
                            <span class="position-absolute bottom-0 end-0" style="width: 12px; height: 12px; background: #4CAF50; border: 2px solid white; border-radius: 50%; margin-bottom: 2px; margin-right: 2px;"></span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" aria-labelledby="profileDropdown" style="border-radius: 16px; margin-top: 12px; min-width: 240px; animation: slideDown 0.3s ease;">
                        <li class="px-3 py-2 border-bottom" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-radius: 16px 16px 0 0;">
                            <div class="d-flex align-items-center gap-2">
                                @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
                                    <img src="{{ asset(Auth::user()->profile_photo) }}" class="rounded-circle" alt="Profile" style="height: 40px; width: 40px; object-fit: cover;">
                                @else
                                    <i class="fas fa-user-circle fa-2x" style="color: #3D519F;"></i>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-bold" style="color: #2C3E50;">{{ Auth::user()->name ?? 'User' }}</h6>
                                    <small class="text-muted">{{ Auth::user()->email ?? '' }}</small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="#" data-bs-toggle="offcanvas" data-bs-target="#profileEditOffcanvas" aria-controls="profileEditOffcanvas" style="color: #2C3E50; transition: all 0.2s ease;">
                                <i class="fas fa-user me-3" style="color: #3D519F; width: 20px;"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="#" style="color: #2C3E50; transition: all 0.2s ease;">
                                <i class="fas fa-cog me-3" style="color: #3D519F; width: 20px;"></i> Settings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('notifications') }}" style="color: #2C3E50; transition: all 0.2s ease;">
                                <i class="fas fa-bell me-3" style="color: #3D519F; width: 20px;"></i> Notifications
                            </a>
=======
                <!-- Profile -->
                <div class="dropdown">
                    <button class="d-flex align-items-center gap-2 p-1 border rounded-pill bg-white hover-bg-slate-50 transition-all shadow-none" type="button"
                        id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding-right: 12px !important;">
                        @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
                            <img src="{{ asset(Auth::user()->profile_photo) }}"
                                class="rounded-circle"
                                style="width: 32px; height: 32px; object-fit: cover;" alt="Profile Picture">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                style="width: 32px; height: 32px; font-size: 0.8rem;">
                                {{ substr(Auth::user()->FirstName ?? 'U', 0, 1) }}
                            </div>
                        @endif
                        <div class="d-flex flex-column text-start d-none d-sm-flex">
                            <span class="fw-semibold text-slate-900"
                                style="line-height: 1; font-size: 0.85rem;">{{ Auth::user()->FirstName ?? 'User' }}</span>
                            <small class="text-slate-500"
                                style="font-size: 0.7rem;">{{ Auth::user()->role->RoleName ?? 'Employee' }}</small>
                        </div>
                        <i class="fas fa-chevron-down text-slate-400 ms-1" style="font-size: 0.7rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3"
                        style="border-radius: var(--radius-lg); min-width: 220px;" aria-labelledby="profileDropdown">
                        <li class="px-3 py-2 border-bottom bg-slate-50 mb-1">
                            <div class="fw-bold text-slate-900 small">{{ Auth::user()->name }}</div>
                            <div class="text-slate-500 smaller">{{ Auth::user()->email }}</div>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 small d-flex align-items-center gap-3" href="#"
                                data-bs-toggle="offcanvas" data-bs-target="#profileOffcanvas">
                                <i class="fas fa-user-circle text-slate-400" style="width: 16px;"></i>
                                <span class="fw-medium">View Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 small d-flex align-items-center gap-3" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-edit text-slate-400" style="width: 16px;"></i>
                                <span class="fw-medium">Edit Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider mx-3">
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                        </li>
                        <li><hr class="dropdown-divider mx-2"></li>
                        <li>
<<<<<<< HEAD
                            <a class="dropdown-item py-2 text-danger" href="#" onclick="event.preventDefault(); confirmLogout();" style="transition: all 0.2s ease;">
                                <i class="fas fa-sign-out-alt me-3" style="width: 20px;"></i> Logout
=======
                            <a class="dropdown-item py-2 px-3 small d-flex align-items-center gap-3 text-danger"
                                href="#" onclick="event.preventDefault(); confirmLogout();">
                                <i class="fas fa-sign-out-alt" style="width: 16px;"></i>
                                <span class="fw-bold">Logout</span>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</header>

<style>
    /* Modern Font Import */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    /* Header Styles */
    header {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    /* Hover Effects */
    .hamburger-btn:hover,
    .theme-toggle-btn:hover,
    .notification-btn:hover {
        background: rgba(255,255,255,0.25) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .logo-container:hover img {
        transform: rotate(5deg) scale(1.05);
    }

    .logo-container img {
        animation: logoFloat 4s ease-in-out infinite;
    }

    .profile-wrapper:hover img,
    .profile-wrapper:hover div {
        transform: scale(1.05);
    }

    /* Dropdown Animations */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes logoFloat {
        0% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
        100% { transform: translateY(0); }
    }

    #headerMoodLine {
        transition: opacity 0.25s ease, transform 0.25s ease;
    }

    /* Notification Item Styles */
    .notification-item {
        position: relative;
        overflow: hidden;
    }

    .notification-item.unread {
        background: rgba(61, 81, 159, 0.03);
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: #3D519F;
    }

    .notification-item:hover {
        background: rgba(61, 81, 159, 0.05);
    }

    /* Dropdown Item Hover */
    .dropdown-item:hover {
        background: rgba(61, 81, 159, 0.05);
        padding-left: 2rem !important;
    }

    .dropdown-item.text-danger:hover {
        background: rgba(220, 53, 69, 0.05);
    }

    /* Custom Scrollbar for Notifications */
    .dropdown-menu::-webkit-scrollbar {
        width: 6px;
    }

    .dropdown-menu::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .dropdown-menu::-webkit-scrollbar-thumb {
        background: #3D519F;
        border-radius: 10px;
    }

    .dropdown-menu::-webkit-scrollbar-thumb:hover {
        background: #2C3E50;
    }

    /* Responsive Adjustments */
    @media (max-width: 576px) {
        .dropdown-menu[aria-labelledby="notificationDropdown"] {
            width: 300px !important;
            right: -70px !important;
        }

        .dropdown-menu[aria-labelledby="profileDropdown"] {
            width: 200px !important;
        }
    }

    /* Dark Mode Support (will be enhanced by JavaScript) */
    body.dark-mode header {
        background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #303f9f 100%);
    }
</style>

<script>
    function confirmLogout() {
        if (typeof Swal !== 'undefined' && Swal.fire) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will be logged out of your account',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3D519F',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
            return;
        }

        if (confirm('Are you sure you want to log out?')) {
            document.getElementById('logout-form').submit();
        }
    }

    // Optional: Add smooth transitions for dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        const moodLine = document.getElementById('headerMoodLine');
        if (moodLine) {
            const lines = [
                'No pending chaos. Nice.',
                'Today looks shippable.',
                'Small wins still count.',
                'Hydrate. Then dominate.',
                'Calm inbox, clear mind.'
            ];
            const pick = lines[Math.floor(Math.random() * lines.length)];
            moodLine.style.opacity = '0';
            moodLine.style.transform = 'translateY(4px)';
            setTimeout(() => {
                moodLine.textContent = pick;
                moodLine.style.opacity = '0.88';
                moodLine.style.transform = 'translateY(0)';
            }, 120);
        }

        // Add click handlers for notification items if needed
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach(item => {
            item.addEventListener('click', function(e) {
                if (!e.target.closest('form')) {
                    // Handle notification click (e.g., mark as read and redirect)
                    console.log('Notification clicked');
                }
            });
        });
    });
</script>
=======
</header>
>>>>>>> dbb18b2c20d3f27999de42da0021f1e1122b805f
