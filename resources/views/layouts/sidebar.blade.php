<!-- Modern Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start border-0 shadow-lg" tabindex="-1" id="mainSidebar"
    aria-labelledby="mainSidebarLabel"
    style="width: 280px; top: var(--header-height); height: calc(100vh - var(--header-height)); background: var(--color-slate-900); color: white;"
    data-bs-scroll="true" data-bs-backdrop="false">

    <!-- Sidebar Header -->
    <div class="offcanvas-header p-0">
        <div class="p-4 d-flex align-items-center gap-3">
            <div class="bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px; min-width: 48px; overflow: hidden;">
                <img src="{{ asset('logo3.png') }}" alt="ABC Logo"
                    style="width: 100%; height: 100%; object-fit: contain; padding: 4px;">
            </div>
            <div class="d-flex flex-column overflow-hidden">
                <h6 class="text-white fw-bold mb-0 text-nowrap" style="letter-spacing: -0.5px; font-size: 1.1rem;">ABC
                    leave</h6>
                <small class="text-white-50 text-nowrap" style="font-size: 0.75rem;">Management system</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 d-md-none"
            data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <!-- Sidebar Body -->
    <div class="offcanvas-body p-0 d-flex flex-column">
        <nav class="flex-grow-1 overflow-auto py-3 custom-scrollbar">
            <ul class="nav flex-column px-3 gap-2">

                <!-- Main Navigation -->
                @if(auth()->check() && auth()->user()->role_id === 1)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-white-50 hover-bg-white-10 transition-all {{ request()->routeIs('dashboard') ? 'bg-white bg-opacity-10 text-white' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-th-large " style="width: 20px;"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif

                @if(auth()->check() && auth()->user()->role_id === 1)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-white-50 hover-bg-white-10 transition-all {{ request()->routeIs('admin.verification') ? 'bg-white bg-opacity-10 text-white' : '' }}"
                            href="{{ route('admin.verification') }}">
                            <i class="fas fa-shield-alt" style="width: 20px;"></i>
                            <span>Verification</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-white-50 hover-bg-white-10 transition-all {{ request()->routeIs('employees.*') ? 'bg-white bg-opacity-10 text-white' : '' }}"
                            href="{{ route('employees.index') }}">
                            <i class="fas fa-user-friends" style="width: 20px;"></i>
                            <span>Employees</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-white-50 hover-bg-white-10 transition-all {{ request()->routeIs('leave_requests.*') ? 'bg-white bg-opacity-10 text-white' : '' }}"
                            href="{{ route('leave_requests.admin_all') }}">
                            <i class="fas fa-calendar-check" style="width: 20px;"></i>
                            <span>Leave Requests</span>
                        </a>
                    </li>
                @endif

                @if(auth()->check() && auth()->user()->role_id === 2)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-white-50 hover-bg-white-10 transition-all {{ request()->routeIs('supervisor.index') ? 'bg-white bg-opacity-10 text-white' : '' }}"
                            href="{{ route('supervisor.index') }}">
                            <i class="fas fa-tachometer-alt" style="width: 20px;"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('leave_requests.index') }}"
                            class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-white-50 hover-bg-white-10 transition-all {{ request()->routeIs('leave_requests.index') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                            <div class="position-relative">
                                <i class="fas fa-bell" style="width: 20px;"></i>
                                @php
                                    $pendingRequests = $globalPendingLeaves->whereIn('RequestStatus', ['Pending', 'Pending Supervisor Approval', 'Pending Admin Verification', 'Pending Admin Approval'])->count();
                                @endphp
                                @if($pendingRequests > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-dark rounded-circle"
                                        style="width: 8px; height: 8px;"></span>
                                @endif
                            </div>
                            <span class="flex-grow-1">Leave Requests</span>
                            @if($pendingRequests > 0)
                                <span class="badge bg-danger rounded-pill">{{ $pendingRequests }}</span>
                            @endif
                        </a>
                    </li>
                @endif

                @if(auth()->check() && auth()->user()->role_id === 3)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-white-50 hover-bg-white-10 transition-all {{ request()->routeIs('dashboards.employee') ? 'bg-white bg-opacity-10 text-white' : '' }}"
                            href="{{ route('dashboards.employee') }}">
                            <i class="fas fa-th-large" style="width: 20px;"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-white-50 hover-bg-white-10 transition-all {{ request()->routeIs('leave_requests.create') ? 'bg-white bg-opacity-10 text-white' : '' }}"
                            href="{{ route('leave_requests.create') }}">
                            <i class="fas fa-calendar-plus" style="width: 20px;"></i>
                            <span>Apply for Leave</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-white-50 hover-bg-white-10 transition-all {{ request()->routeIs('notifications') ? 'bg-white bg-opacity-10 text-white' : '' }}"
                            href="{{ route('notifications') }}">
                            <i class="fas fa-bell" style="width: 20px;"></i>
                            <span>Notifications</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        <!-- Footer -->
        <div class="px-3 border-top border-light border-opacity-10 bg-black bg-opacity-10 d-flex align-items-center"
            style="height: var(--footer-height); min-height: var(--footer-height);">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold"
                    style="width: 36px; height: 36px;">
                    {{ substr(auth()->check() ? (auth()->user()->FirstName ?? 'U') : 'U', 0, 1) }}
                </div>
                <div class="d-flex flex-column" style="font-size: 0.8rem; line-height: 1.2;">
                    <span
                        class="fw-bold text-white">{{ auth()->check() ? (auth()->user()->FirstName ?? 'User') : 'User' }}</span>
                    <small class="text-white-50">Log out</small>
                </div>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="ms-auto text-white-50 hover-text-white transition-all">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Sidebar Custom Styles */
    .hover-bg-white-10:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }

    .hover-text-white:hover {
        color: white !important;
    }

    .transition-all {
        transition: all 0.2s ease-in-out;
    }

    .nav-link[aria-expanded="true"] .fa-chevron-down {
        transform: rotate(180deg);
    }

    /* Custom Scrollbar for sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
</style>