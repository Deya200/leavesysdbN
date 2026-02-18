<!-- Modern Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start border-0 shadow-lg" tabindex="-1" id="mainSidebar" aria-labelledby="mainSidebarLabel" style="width: 280px; top: 60px; height: calc(100vh - 60px); background: #1e1b4b; color: white;" data-bs-scroll="true" data-bs-backdrop="false">
    
    <!-- Sidebar Header -->
    <div class="offcanvas-header border-bottom border-light border-opacity-10 py-4 d-flex flex-column align-items-center">

        <h5 class="offcanvas-title mt-2 fw-bold text-white small text-uppercase tracking-wider text-center">ABC Leave Management System</h5>
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 d-md-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <!-- Sidebar Body -->
    <div class="offcanvas-body p-0 d-flex flex-column">
        <nav class="flex-grow-1 overflow-auto py-3 custom-scrollbar">
            <ul class="nav flex-column px-3 gap-1">
                
                <!-- Section Header -->
                <li class="nav-item px-3 mb-2 small text-uppercase text-white-50 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Menu</li>

                <!-- Main Navigation -->
                @if(auth()->user()->role_id === 1)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all" href="{{ route('dashboard') }}">
                        <i class="fas fa-home fs-5 " style="width: 24px;"></i> 
                        <span>Home</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->role_id === 1)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all {{ request()->routeIs('admin.verification') ? 'bg-white bg-opacity-10 text-white' : '' }}" href="{{ route('admin.verification') }}">
                            <i class="fas fa-shield-alt fs-5" style="width: 24px;"></i>
                            <span>Admin Verification</span>
                        </a>
                    </li>
                    
                    <!-- Employees Dropdown -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all collapsed {{ request()->routeIs('employees.*') ? 'bg-white bg-opacity-10 text-white' : '' }}" 
                           data-bs-toggle="collapse" 
                           href="#offcanvasEmployees" 
                           role="button" 
                           aria-expanded="{{ request()->routeIs('employees.*') ? 'true' : 'false' }}">
                            <i class="fas fa-users fs-5" style="width: 24px;"></i> 
                            <span class="flex-grow-1">Employees</span>
                            <i class="fas fa-chevron-down small opacity-50 transition-transform"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('employees.*') ? 'show' : '' }}" id="offcanvasEmployees">
                            <ul class="nav flex-column ms-2 mt-1 ps-4 border-start border-white border-opacity-10 gap-1">
                                <li><a class="nav-link py-1 text-white-50 hover-text-white small {{ request()->routeIs('employees.index') ? 'text-white fw-bold' : '' }}" href="{{ route('employees.index') }}">View All</a></li>
                                <li><a class="nav-link py-1 text-white-50 hover-text-white small {{ request()->routeIs('employees.create') ? 'text-white fw-bold' : '' }}" href="{{ route('employees.create') }}">Add New</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Departments Dropdown -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all collapsed {{ request()->routeIs('departments.*') ? 'bg-white bg-opacity-10 text-white' : '' }}" 
                           data-bs-toggle="collapse" 
                           href="#offcanvasDepartments" 
                           role="button" 
                           aria-expanded="{{ request()->routeIs('departments.*') ? 'true' : 'false' }}">
                            <i class="fas fa-building fs-5" style="width: 24px;"></i> 
                            <span class="flex-grow-1">Departments</span>
                            <i class="fas fa-chevron-down small opacity-50 transition-transform"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('departments.*') ? 'show' : '' }}" id="offcanvasDepartments">
                             <ul class="nav flex-column ms-2 mt-1 ps-4 border-start border-white border-opacity-10 gap-1">
                                <li><a class="nav-link py-1 text-white-50 hover-text-white small {{ request()->routeIs('departments.index') ? 'text-white fw-bold' : '' }}" href="{{ route('departments.index') }}">View All</a></li>
                                <li><a class="nav-link py-1 text-white-50 hover-text-white small {{ request()->routeIs('departments.create') ? 'text-white fw-bold' : '' }}" href="{{ route('departments.create') }}">Add New</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Positions, Grades, Leave Types (Grouped for brevity visually, but kept structurally) -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all collapsed {{ request()->routeIs('positions.*') || request()->routeIs('grades.*') ? 'bg-white bg-opacity-10 text-white' : '' }}" 
                           data-bs-toggle="collapse" 
                           href="#offcanvasStructure" 
                           role="button" 
                           aria-expanded="{{ request()->routeIs('positions.*') || request()->routeIs('grades.*') ? 'true' : 'false' }}">
                            <i class="fas fa-sitemap fs-5" style="width: 24px;"></i> 
                            <span class="flex-grow-1">Organization</span>
                            <i class="fas fa-chevron-down small opacity-50 transition-transform"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('positions.*') || request()->routeIs('grades.*') ? 'show' : '' }}" id="offcanvasStructure">
                            <ul class="nav flex-column ms-2 mt-1 ps-4 border-start border-white border-opacity-10 gap-1">
                                <!-- Positions -->
                                <li><a class="nav-link py-1 text-white-50 hover-text-white small {{ request()->routeIs('positions.index') ? 'text-white fw-bold' : '' }}" href="{{ route('positions.index') }}">Positions</a></li>
                                
                                <!-- Grades -->
                                <li><a class="nav-link py-1 text-white-50 hover-text-white small {{ request()->routeIs('grades.index') ? 'text-white fw-bold' : '' }}" href="{{ route('grades.index') }}">Grades</a></li>
                            </ul>
                        </div>
                    </li>

                     <!-- Leave Types Dropdown -->
                     <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all collapsed {{ request()->routeIs('leave_types.*') || (request()->routeIs('leave_requests.create') && auth()->user()->role_id === 1) ? 'bg-white bg-opacity-10 text-white' : '' }}" 
                           data-bs-toggle="collapse" 
                           href="#offcanvasLeaveTypes" 
                           role="button" 
                           aria-expanded="{{ request()->routeIs('leave_types.*') ? 'true' : 'false' }}">
                            <i class="fas fa-calendar-alt fs-5" style="width: 24px;"></i> 
                            <span class="flex-grow-1">Leave Config</span>
                            <i class="fas fa-chevron-down small opacity-50 transition-transform"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('leave_types.*') ? 'show' : '' }}" id="offcanvasLeaveTypes">
                            <ul class="nav flex-column ms-2 mt-1 ps-4 border-start border-white border-opacity-10 gap-1">
                                <li><a class="nav-link py-1 text-white-50 hover-text-white small {{ request()->routeIs('leave_types.index') ? 'text-white fw-bold' : '' }}" href="{{ route('leave_types.index') }}">Leave Types</a></li>
                                <li><a class="nav-link py-1 text-white-50 hover-text-white small {{ request()->routeIs('leave_requests.create') ? 'text-white fw-bold' : '' }}" href="{{ route('leave_requests.create') }}">Apply Leave</a></li>
                            </ul>
                        </div>
                    </li>


                                        
                @endif

                @if(auth()->user()->role_id === 2)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all {{ request()->routeIs('supervisor.index') ? 'bg-white bg-opacity-10 text-white' : '' }}" href="{{ route('supervisor.index') }}">
                            <i class="fas fa-tachometer-alt fs-5" style="width: 24px;"></i> 
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('leave_requests.index') }}" class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all {{ request()->routeIs('leave_requests.index') ? 'bg-white bg-opacity-10 text-white' : '' }}">
                            <div class="position-relative">
                                <i class="fas fa-bell fs-5" style="width: 24px;"></i>
                                @php
                                    $pendingRequests = $globalPendingLeaves->whereIn('RequestStatus', ['Pending', 'Pending Supervisor Approval', 'Pending Admin Verification', 'Pending Admin Approval'])->count();
                                @endphp
                                @if($pendingRequests > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-dark rounded-circle" style="width: 8px; height: 8px;"></span>
                                @endif
                            </div>
                            <span class="flex-grow-1">Leave Requests</span>
                            @if($pendingRequests > 0)
                                <span class="badge bg-danger rounded-pill">{{ $pendingRequests }}</span>
                            @endif
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role_id === 3)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all {{ request()->routeIs('dashboards.employee') ? 'bg-white bg-opacity-10 text-white' : '' }}" href="{{ route('dashboards.employee') }}">
                            <i class="fas fa-tachometer-alt fs-5" style="width: 24px;"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all {{ request()->routeIs('leave_requests.create') ? 'bg-white bg-opacity-10 text-white' : '' }}" href="{{ route('leave_requests.create') }}">
                            <i class="fas fa-calendar-plus fs-5" style="width: 24px;"></i>
                            <span>Apply for Leave</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-3 rounded-2 px-3 py-2 text-white-75 hover-bg-white-10 transition-all {{ request()->routeIs('notifications') ? 'bg-white bg-opacity-10 text-white' : '' }}" href="{{ route('notifications') }}">
                            <i class="fas fa-bell fs-5" style="width: 24px;"></i>
                            <span>Notifications</span>
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
