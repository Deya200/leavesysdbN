<!-- Modern Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start border-0 shadow-lg sidebar-main" tabindex="-1" id="mainSidebar" aria-labelledby="mainSidebarLabel" style="width: 280px; top: 0; height: 100vh; border-radius: 0 !important;" data-bs-scroll="true" data-bs-backdrop="false">
    
    <!-- Sidebar Header -->
    <div class="offcanvas-header p-0 sidebar-header-accent">
        <div class="p-4 d-flex align-items-center gap-3 w-100">
            <div class="logo-box rounded-3 shadow-sm d-flex align-items-center justify-content-center"
                style="width: 42px; height: 42px; min-width: 42px; overflow: hidden; background: white;">
                <img src="{{ asset('logo3.png') }}" alt="ABC Logo"
                    style="width: 100%; height: 100%; object-fit: contain; padding: 4px;">
            </div>
            <div class="d-flex flex-column overflow-hidden">
                <h6 class="fw-bold mb-0 text-nowrap sidebar-branding-text" style="letter-spacing: -0.5px; font-size: 1.1rem;">ABC Leave</h6>
                <small class="text-nowrap mt-n1 sidebar-subtitle-text" style="font-size: 0.7rem; font-weight: 500;">Management System</small>
            </div>
        </div>
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3 d-md-none shadow-none"
            data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <!-- Sidebar Body -->
    <div class="offcanvas-body p-0 d-flex flex-column">
        <nav class="flex-grow-1 overflow-auto py-2 custom-scrollbar">
            <ul class="nav flex-column px-3 gap-1">
                
                <!-- Section Header -->
                <li class="nav-item px-3 mb-2 small text-uppercase fw-bold sidebar-section-title-text" style="font-size: 0.7rem; letter-spacing: 1px;">Menu</li>

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

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('timesheets.*') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('timesheets.index') }}">
                            <i class="fas fa-clock" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Timesheets</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('payrolls.*') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('payrolls.index') }}">
                            <i class="fas fa-money-check-alt" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Payroll</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('grades.*') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('grades.index') }}">
                            <i class="fas fa-layer-group" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Grade Management</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('positions.*') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('positions.index') }}">
                            <i class="fas fa-briefcase" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Position Management</span>
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
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('timesheets.*') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('timesheets.index') }}">
                            <i class="fas fa-clock" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">Timesheets</span>
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
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('timesheets.*') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('timesheets.index') }}">
                            <i class="fas fa-clock" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">My Timesheets</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-slate-800 hover-bg-slate-50 transition-all {{ request()->routeIs('payrolls.*') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}"
                            href="{{ route('payrolls.index') }}">
                            <i class="fas fa-money-check-alt" style="width: 18px;"></i>
                            <span style="font-size: 0.875rem;">My Payroll</span>
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
        
    </div>
</div>

<style>
    /* Sidebar Custom Styles - Responsive Theme */
    .sidebar-main {
        background: #f1f5f9 !important; /* Soft neutral for light theme */
        color: #334155 !important;
        border-right: 1px solid rgba(0,0,0,0.05) !important;
        transition: all 0.3s ease;
    }

    .sidebar-branding-text {
        color: #1e293b;
    }

    .sidebar-subtitle-text {
        color: #64748b;
    }

    .sidebar-section-title-text {
        color: #94a3b8;
    }

    .sidebar-main .nav-link {
        color: #475569 !important;
    }

    .sidebar-main .nav-link:hover {
        background-color: rgba(61, 81, 159, 0.05) !important;
        color: #3D519F !important;
    }

    .sidebar-main .nav-link.bg-primary.bg-opacity-10 {
        background-color: #3D519F !important;
        color: white !important;
    }

    .sidebar-header-accent {
        background-color: transparent !important;
    }

    /* Dark Mode Overrides */
    body.dark-mode .sidebar-main {
        background: #0f172a !important;
        color: white !important;
        border-right: none !important;
    }

    body.dark-mode .sidebar-branding-text {
        color: white !important;
    }

    body.dark-mode .sidebar-subtitle-text {
        color: rgba(255,255,255,0.7) !important;
    }

    body.dark-mode .sidebar-section-title-text {
        color: rgba(255,255,255,0.4) !important;
    }

    body.dark-mode .sidebar-main .nav-link {
        color: rgba(255,255,255,0.8) !important;
    }

    body.dark-mode .sidebar-main .nav-link:hover {
        background-color: rgba(255,255,255,0.1) !important;
        color: white !important;
    }

    body.dark-mode .sidebar-header-accent {
        background-color: transparent !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-mode .logo-box {
        background-color: white !important;
    }

    .amused-sidebar {
        background: #1e1b4b !important;
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
