<!-- Header -->
<header class="py-2 border-bottom shadow-sm"
    style="position: sticky; top: 0; z-index: 1040; background: #ffffff; border-bottom: 1px solid var(--color-slate-200) !important; color: var(--color-slate-900);">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Left: Brand (Mobile Only / Toggle) -->
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link text-slate-600 d-lg-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mainSidebar" aria-controls="mainSidebar" id="menuToggle">
                    <i class="fas fa-bars fs-4"></i>
                </button>
            </div>

            <!-- Center: Dynamic Title -->
            <div class="d-none d-md-flex align-items-center flex-grow-1 ms-3">
                <h5 class="mb-0 fw-bold text-slate-800" style="letter-spacing: -0.5px;">
                    @yield('page_title', 'Dashboard')</h5>
            </div>

            <!-- Right: Actions -->
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-icon-glass border-0 position-relative shadow-sm bg-white" type="button"
                        id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                        style="width: 42px; height: 42px;">
                        <i class="far fa-bell text-slate-600"></i>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span
                                class="position-absolute top-2 end-2 translate-middle p-1 bg-danger rounded-circle border border-white"
                                style="width: 8px; height: 8px;"></span>
                        @endif
                    </button>
                    <!-- Dropdown content -->
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-0 mt-2"
                        aria-labelledby="notificationDropdown"
                        style="width: 320px; border-radius: var(--radius-xl); overflow: hidden;">
                        <li class="p-3 border-bottom d-flex justify-content-between align-items-center bg-slate-50">
                            <h6 class="mb-0 fw-bold">Notifications</h6>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-link btn-sm text-decoration-none p-0 fw-semibold">Mark all
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
                                            <p class="mb-1 small text-dark fw-medium text-wrap">
                                                {{ optional($notification)->Message ?? 'No message content' }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-slate-400"
                                                    style="font-size: 0.75rem;">{{ optional(optional($notification)->created_at)->diffForHumans() ?? '' }}</small>
                                                @if((optional($notification)->Status ?? '') === 'Unread')
                                                    <form
                                                        action="{{ route('notifications.markAsRead', optional($notification)->id ?? 0) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-link btn-sm p-0 text-slate-400 hover-primary"
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
                        <li class="p-2 text-center bg-slate-50 sticky-bottom border-top">
                            <a href="{{ route('notifications') }}" class="small text-decoration-none fw-bold">View All
                                Notifications</a>
                        </li>
                    </ul>
                </div>

                <!-- Profile -->
                <div class="dropdown">
                    <button class="d-flex align-items-center gap-2 p-1 border-0 bg-transparent" type="button"
                        id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex flex-column text-end d-none d-sm-flex me-1">
                            <span class="fw-bold small"
                                style="color: var(--color-slate-900); line-height: 1;">{{ Auth::user()->FirstName ?? 'User' }}</span>
                            <small class="text-slate-400 smaller"
                                style="font-size: 10px;">{{ Auth::user()->role->RoleName ?? 'Employee' }}</small>
                        </div>
                        @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
                            <img src="{{ asset(Auth::user()->profile_photo) }}"
                                class="rounded-circle border border-2 border-primary"
                                style="width: 38px; height: 38px; object-fit: cover;" alt="Profile Picture">
                        @else
                            <div class="rounded-circle bg-primary-light d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                style="width: 38px; height: 38px;">
                                {{ substr(Auth::user()->FirstName ?? 'U', 0, 1) }}
                            </div>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2"
                        style="border-radius: var(--radius-lg); min-width: 200px;" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item py-2 px-3 small d-flex align-items-center gap-2" href="#"
                                data-bs-toggle="offcanvas" data-bs-target="#profileEditOffcanvas">
                                <span class="bg-primary bg-opacity-10 p-2 rounded-circle">
                                    <i class="fas fa-user-edit text-primary" style="width: 14px;"></i>
                                </span>
                                <div>
                                    <div class="fw-bold">Edit Profile</div>
                                    <div class="text-slate-400 smaller">Update details</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 small d-flex align-items-center gap-2" href="#">
                                <span class="bg-slate-100 p-2 rounded-circle">
                                    <i class="fas fa-cog text-slate-500" style="width: 14px;"></i>
                                </span>
                                <div>
                                    <div class="fw-bold">Settings</div>
                                    <div class="text-slate-400 smaller">Preferences</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider mx-3">
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 small d-flex align-items-center gap-2 text-danger"
                                href="#" onclick="event.preventDefault(); confirmLogout();">
                                <span class="bg-danger bg-opacity-10 p-2 rounded-circle">
                                    <i class="fas fa-sign-out-alt text-danger" style="width: 14px;"></i>
                                </span>
                                <div class="fw-bold">Logout</div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>