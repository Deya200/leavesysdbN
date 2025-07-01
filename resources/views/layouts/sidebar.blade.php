<!-- Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="mainSidebar" aria-labelledby="mainSidebarLabel" style="width: 320px;">
    <div class="offcanvas-header border-bottom" style="background: #161b22;">
        <img src="{{ asset('logo3.png') }}" alt="Logo" class="rounded-circle bg-light p-1" style="height: 32px;">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column" style="height: 100%; background: #161b22;">
        <nav class="flex-grow-1 overflow-auto">
            <ul class="nav flex-column px-2 pt-2">
                <!-- Main Navigation -->
                <li class="nav-item">
                    <a class="nav-link text-white d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                @if(auth()->user()->role_id === 1)
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" href="{{ route('admin.verification') }}">
                            <i class="fas fa-shield-alt"></i> Admin Verification
                        </a>
                    </li>
                    <!-- Employees Dropdown -->
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#offcanvasEmployees" role="button" aria-expanded="false" aria-controls="offcanvasEmployees">
                            <i class="fas fa-users"></i> Employees
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse ps-4" id="offcanvasEmployees">
                            <ul class="nav flex-column">
                                <li><a class="nav-link text-white-50" href="{{ route('employees.index') }}"><i class="fas fa-eye"></i> View Employees</a></li>
                                <li><a class="nav-link text-white-50" href="{{ route('employees.create') }}"><i class="fas fa-plus"></i> Add Employee</a></li>
                            </ul>
                        </div>
                    </li>
                    <!-- Departments Dropdown -->
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#offcanvasDepartments" role="button" aria-expanded="false" aria-controls="offcanvasDepartments">
                            <i class="fas fa-building"></i> Departments
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse ps-4" id="offcanvasDepartments">
                            <ul class="nav flex-column">
                                <li><a class="nav-link text-white-50" href="{{ route('departments.index') }}"><i class="fas fa-eye"></i> View Departments</a></li>
                                <li><a class="nav-link text-white-50" href="{{ route('departments.create') }}"><i class="fas fa-plus"></i> Add Department</a></li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Positions Dropdown -->
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#offcanvasPositions" role="button" aria-expanded="false" aria-controls="offcanvasPositions">
                            <i class="fas fa-briefcase"></i> Positions
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse ps-4" id="offcanvasPositions">
                            <ul class="nav flex-column">
                                <li><a class="nav-link text-white-50" href="{{ route('positions.index') }}"><i class="fas fa-eye"></i> View Positions</a></li>
                                <li><a class="nav-link text-white-50" href="{{ route('positions.create') }}"><i class="fas fa-plus"></i> Add Positions</a></li>
                            </ul>
                        </div>
                    </li>
                    <!-- Leave Types Dropdown -->
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#offcanvasLeaveTypes" role="button" aria-expanded="false" aria-controls="offcanvasLeaveTypes">
                            <i class="fas fa-calendar"></i> Leave Types
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse ps-4" id="offcanvasLeaveTypes">
                            <ul class="nav flex-column">
                                <li><a class="nav-link text-white-50" href="{{ route('leave_types.index') }}"><i class="fas fa-eye"></i> View Leave Types</a></li>
                                <li><a class="nav-link text-white-50" href="{{ route('leave_types.create') }}"><i class="fas fa-plus"></i> Add Leave Type</a></li>
                                <li><a class="nav-link btn btn-outline-primary text-white mt-1" href="{{ route('leave_requests.create') }}"><i class="fas fa-plus"></i> Apply Leave</a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if(auth()->user()->role_id === 2)
                <!-- Leave Requests Notification -->
                    
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" href="{{ route('supervisor.index') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a href="{{ route('leave_requests.index') }}" class="nav-link text-white d-flex align-items-center gap-2">
                            <i class="fas fa-bell"></i> Leave Requests
                            @php
                                $pendingRequests = $leaveRequests->whereIn('RequestStatus', ['Pending', 'Pending Supervisor Approval', 'Pending Admin Approval'])->count();
                            @endphp
                            @if($pendingRequests > 0)
                                <span class="badge bg-danger ms-2">
                                    {{ $pendingRequests }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role_id === 3)
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" href="{{ route('dashboards.employee') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" href="{{ route('leave_requests.create') }}">
                            <i class="fas fa-calendar-plus"></i> Apply for New Leave
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" href="{{ route('notifications') }}">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                    </li>
                @endif

                <!-- Divider --
                <li class="border-top my-2"></li>
                <!-- Example: Extra section like "Explore", "Marketplace" --
                <li class="nav-item">
                    <a class="nav-link text-white d-flex align-items-center gap-2" href="#">
                        <i class="fas fa-compass"></i> Explore
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white d-flex align-items-center gap-2" href="#">
                        <i class="fas fa-store"></i> Marketplace
                    </a>
                </li>

                <!-- Divider --
                <li class="border-top my-2"></li>

                <!-- Repositories Section (Example, customize as needed) -->
                <!--<div class="px-3 pt-2 pb-1 text-uppercase fw-bold small text-white-50 d-flex align-items-center justify-content-between">
                    Repositories
                    <i class="fas fa-search"></i>
                </div>
                <ul class="nav flex-column px-3">
                    {{-- Example repositories, customize dynamically as needed --}}
                    <li class="nav-item d-flex align-items-center gap-2 mb-1">
                        <img src="https://avatars.githubusercontent.com/u/3369400?v=4" class="rounded-circle" style="width:20px;height:20px;" alt="repo">
                        <a href="#" class="nav-link text-white-50 p-0">Deya200/leavesysdbN</a>
                    </li>
                    <li class="nav-item d-flex align-items-center gap-2 mb-1">
                        <img src="https://avatars.githubusercontent.com/u/3369400?v=4" class="rounded-circle" style="width:20px;height:20px;" alt="repo">
                        <a href="#" class="nav-link text-white-50 p-0">Noel265/Noe</a>
                    </li>
                    <li class="nav-item d-flex align-items-center gap-2 mb-1">
                        <img src="https://avatars.githubusercontent.com/u/3369400?v=4" class="rounded-circle" style="width:20px;height:20px;" alt="repo">
                        <a href="#" class="nav-link text-white-50 p-0">Noel265/Employee-Management</a>
                    </li>
                    <li class="nav-item d-flex align-items-center gap-2 mb-1">
                        <img src="https://avatars.githubusercontent.com/u/3369400?v=4" class="rounded-circle" style="width:20px;height:20px;" alt="repo">
                        <a href="#" class="nav-link text-white-50 p-0">Noel265/leavesys</a>
                    </li>
                    <li class="nav-item d-flex align-items-center gap-2 mb-1">
                        <img src="https://avatars.githubusercontent.com/u/3369400?v=4" class="rounded-circle" style="width:20px;height:20px;" alt="repo">
                        <a href="#" class="nav-link text-white-50 p-0">Noel265/skills-introduction-to-github</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white-50 ps-4">Show more</a>
                    </li>
                </ul>
            </ul>
        </nav>
        <!-- Footer -->
        <div class="border-top p-3 small text-white-50">
            <div class="d-flex flex-column gap-1">
                <span>© 2025 GitHub, Inc.</span>
                <div>
                    <a href="#" class="text-white-50 me-2">About</a>
                    <a href="#" class="text-white-50 me-2">Blog</a>
                    <a href="#" class="text-white-50 me-2">Terms</a>
                    <a href="#" class="text-white-50 me-2">Privacy</a>
                    <a href="#" class="text-white-50 me-2">Security</a>
                    <a href="#" class="text-white-50">Status</a>
                </div>
            </div>
        </div>
    </div>
</div>

