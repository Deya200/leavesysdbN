<!-- Modern Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start border-end shadow-sm" tabindex="-1" id="mainSidebar"
    aria-labelledby="mainSidebarLabel"
    style="width: 260px; top: 0; height: 100vh; background: #ffffff; color: var(--color-slate-900); border-radius: 0 !important;"
    data-bs-scroll="true" data-bs-backdrop="false">

    <!-- Sidebar Header -->
    <div class="offcanvas-header p-0">
        <div class="p-4 d-flex align-items-center gap-3 w-100">
            <div class="bg-white rounded-3 border d-flex align-items-center justify-content-center"
                style="width: 40px; height: 40px; min-width: 40px; overflow: hidden;">
                <img src="{{ asset('logo3.png') }}" alt="ABC Logo"
                    style="width: 100%; height: 100%; object-fit: contain; padding: 2px;">
            </div>
            <div class="d-flex flex-column overflow-hidden">
                <h6 class="text-slate-900 fw-bold mb-0 text-nowrap" style="letter-spacing: -0.5px; font-size: 1rem;">ABC Leave</h6>
                <small class="text-slate-500 text-nowrap" style="font-size: 0.7rem;">Management System</small>
            </div>
        </div>
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3 d-md-none"
            data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <!-- Sidebar Body -->
    <div class="offcanvas-body p-0 d-flex flex-column">
        <nav class="flex-grow-1 overflow-auto py-2 custom-scrollbar">
            <ul class="nav flex-column px-3 gap-1">

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
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-white rounded-circle"
                                        style="width: 6px; height: 6px;"></span>
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