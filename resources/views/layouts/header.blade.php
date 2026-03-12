<!-- Header -->
<header class="py-2 border-bottom bg-white sticky-top" style="z-index: 1040; border-bottom: 1px solid var(--color-slate-200) !important;">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Left: Brand (Mobile Only / Toggle) -->
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link text-slate-600 d-lg-none p-0" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mainSidebar" aria-controls="mainSidebar" id="menuToggle">
                    <i class="fas fa-bars fs-5"></i>
                </button>
            </div>

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
                        </li>
                    </ul>
                </div>

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
                            <div class="fw-bold text-slate-900 small">{{ Auth::user()->FirstName . ' ' . Auth::user()->LastName }}</div>
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
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 small d-flex align-items-center gap-3 text-danger"
                                href="#" onclick="event.preventDefault(); confirmLogout();">
                                <i class="fas fa-sign-out-alt" style="width: 16px;"></i>
                                <span class="fw-bold">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>