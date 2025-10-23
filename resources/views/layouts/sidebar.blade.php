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
                        <a class="nav-link text-white d-flex align-items-center gap-2"
                           href="{{ route('employees.index') }}">
                            <i class="fas fa-users"></i> Employees
                        </a>
                    
                        <!--<div class="collapse ps-4" id="offcanvasEmployees">
                            <ul class="nav flex-column">
                                <li><a class="nav-link text-white-50" href="{{ route('employees.index') }}"><i class="fas fa-eye"></i> View Employees</a></li>
                                <li><a class="nav-link text-white-50" href="{{ route('employees.create') }}"><i class="fas fa-plus"></i> Add Employee</a></li>
                            </ul>
                        </div> -->
                    </li>

                    <!-- Departments Dropdown -->
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2"
                           href="{{ route('departments.index') }}">
                            <i class="fas fa-users"></i> Departments
                        </a>
                        <!--div class="collapse ps-4" id="offcanvasDepartments">
                            <ul class="nav flex-column">
                                <li><a class="nav-link text-white-50" href="{{ route('departments.index') }}"><i class="fas fa-eye"></i> View Departments</a></li>
                                <li><a class="nav-link text-white-50" href="{{ route('departments.create') }}"><i class="fas fa-plus"></i> Add Department</a></li>
                            </ul>
                        </div-->
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
                    <!-- Grades -->
                     <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#offcanvasGrades" role="button" aria-expanded="false" aria-controls="offcanvasGrades">
                            <i class="fas fa-briefcase"></i> Grades
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse ps-4" id="offcanvasGrades">
                            <ul class="nav flex-column">
                                 <li><a class="nav-link text-white-50" href="{{ route('grades.index') }}"><i class="fas fa-eye"></i>View Grades</a></li>
                                 <li><a class="nav-link text-white-50" href="{{ route('grades.create') }}"><i class="fas fa-plus"></i>Add Grade</a></li>
                            </ul>
                        </div>
                    </li>
                                        
                @endif

                @if(auth()->user()->role_id === 2)
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
            </ul>
        </nav>
        <!-- Footer (optional) -->
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
