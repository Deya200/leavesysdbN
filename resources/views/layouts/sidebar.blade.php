<!-- Modern Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start border-0 shadow-lg" tabindex="-1" id="mainSidebar" aria-labelledby="mainSidebarLabel" style="width: 280px; top: 60px; height: calc(100vh - 60px); background: #1e1b4b; color: white;" data-bs-scroll="true" data-bs-backdrop="false">
    
    <!-- Sidebar Header -->
<<<<<<< HEAD
    <div class="offcanvas-header p-0" style="background-color: var(--color-primary) !important;">
        <div class="p-4 d-flex align-items-center gap-3 w-100 border-bottom border-white border-opacity-10">
            <div class="bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center"
                style="width: 42px; height: 42px; min-width: 42px; overflow: hidden;">
                <img src="{{ asset('logo3.png') }}" alt="ABC Logo"
                    style="width: 100%; height: 100%; object-fit: contain; padding: 4px;">
            </div>
            <div class="d-flex flex-column overflow-hidden">
                <h6 class="text-white fw-bold mb-0 text-nowrap" style="letter-spacing: -0.5px; font-size: 1.1rem;">ABC Leave</h6>
                <small class="text-white text-opacity-75 text-nowrap" style="font-size: 0.75rem;">Management System</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 d-md-none"
            data-bs-dismiss="offcanvas" aria-label="Close"></button>
=======
    <div class="offcanvas-header border-bottom border-light border-opacity-10 py-4 d-flex flex-column align-items-center">

        <h5 class="offcanvas-title mt-2 fw-bold text-white small text-uppercase tracking-wider text-center">ABC Leave Management System</h5>
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 d-md-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
>>>>>>> 99b4aaf3f9db029efb59209ba49918a6140f7474
    </div>

    <!-- Sidebar Body -->
    <div class="offcanvas-body p-0 d-flex flex-column">
        <nav class="flex-grow-1 overflow-auto py-2 custom-scrollbar">
            <ul class="nav flex-column px-3 gap-1">
                
                <!-- Section Header -->
                <li class="nav-item px-3 mb-2 small text-uppercase text-white-50 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Menu</li>

                <!-- Main Navigation -->
                @if(auth()->check() && auth()->user()->role_id === 1)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('dashboard') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-th-large " style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Dashboard</span>
                        </a>
                    </li>
                @endif

                @if(auth()->check() && auth()->user()->role_id === 1)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('admin.verification') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('admin.verification') }}">
                            <i class="fas fa-shield-alt" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Verification</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('employees.*') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('employees.index') }}">
                            <i class="fas fa-user-friends" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Employees</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('leave_requests.*') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('leave_requests.admin_all') }}">
                            <i class="fas fa-calendar-check" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Leave Requests</span>
                        </a>
                    </li>
                @endif

                @if(auth()->check() && auth()->user()->role_id === 2)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('supervisor.index') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('supervisor.index') }}">
                            <i class="fas fa-tachometer-alt" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('leave_requests.index') }}"
                            class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('leave_requests.index') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}">
                            <div class="position-relative">
                                <i class="fas fa-bell" style="width: 18px;"></i>
                                @php
                                    $pendingRequests = $globalPendingLeaves->whereIn('RequestStatus', ['Pending', 'Pending Supervisor Approval', 'Pending Admin Verification', 'Pending Admin Approval'])->count();
                                @endphp
                                @if($pendingRequests > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-dark rounded-circle" style="width: 8px; height: 8px;"></span>
                                @endif
                            </div>
                            <span class="flex-grow-1" style="font-size: 0.875rem;">Leave Requests</span>
                            @if($pendingRequests > 0)
                                <span class="badge bg-primary rounded-pill" style="font-size: 0.7rem;">{{ $pendingRequests }}</span>
                            @endif
                        </a>
                    </li>
                @endif

                @if(auth()->check() && auth()->user()->role_id === 3)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('dashboards.employee') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('dashboards.employee') }}">
                            <i class="fas fa-th-large" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('leave_requests.create') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('leave_requests.create') }}">
                            <i class="fas fa-calendar-plus" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Apply for Leave</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('notifications') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('notifications') }}">
                            <i class="fas fa-bell" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Notifications</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        
        <!-- Footer -->
        <div class="p-3 border-top border-light border-opacity-10 bg-black bg-opacity-10">
            <div class="d-flex align-items-center gap-3">
                 <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                    {{ substr(Auth::user()->FirstName ?? 'U', 0, 1) }}
                 </div>
                 <div class="d-flex flex-column" style="font-size: 0.8rem; line-height: 1.2;">
                    <span class="fw-bold text-white">{{ auth()->user()->FirstName ?? 'User' }}</span>
                    <small class="text-white-50">Log out</small>
                 </div>
                 <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="ms-auto text-white-50 hover-text-white transition-all">
                    <i class="fas fa-sign-out-alt"></i>
                 </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Sidebar Custom Styles */
    .amused-sidebar {
        background: linear-gradient(180deg, #1f2f63 0%, #1a2551 50%, #151e40 100%) !important;
    }

    .sidebar-section-title {
        position: relative;
        padding-left: 1.5rem !important;
    }

    .sidebar-section-title::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 50%;
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #60a5fa;
        transform: translateY(-50%);
        box-shadow: 0 0 0 6px rgba(96, 165, 250, 0.18);
    }

    .offcanvas .nav-link {
        border: 1px solid transparent;
    }

    .offcanvas .nav-link:hover {
        transform: translateX(3px);
        border-color: rgba(255, 255, 255, 0.16);
    }

    .offcanvas .nav-link.bg-white,
    .offcanvas .nav-link.bg-white.bg-opacity-10 {
        border-color: rgba(255, 255, 255, 0.22);
        box-shadow: inset 2px 0 0 #93c5fd;
    }

    .menu-alert-dot {
        animation: softPing 1.8s infinite;
    }

    @keyframes softPing {
        0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        70% { transform: translate(-50%, -50%) scale(1.45); opacity: 0.4; }
        100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    }

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