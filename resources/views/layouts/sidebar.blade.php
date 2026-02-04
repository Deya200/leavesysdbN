<!-- Sidebar as Offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mainSidebar" aria-labelledby="mainSidebarLabel" style="width: 320px; background: #fdfdfd; z-index: 1050;">
    
   <div class="offcanvas-body p-0 d-flex flex-column" style="height: calc(100% - 60px); max-height: 100vh; overflow: hidden;">
        <nav class="flex-grow-1">
            <ul class="nav flex-column px-2 pt-2">
                <!-- Main Navigation -->
@if(auth()->user()->role_id === 1)
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('dashboard') }}" style="text-decoration: none;">
        <i class="fas fa-home"></i> Home
    </a>
</li>
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('admin.verification') }}" style="text-decoration: none;">
        <i class="fas fa-shield-alt"></i> Admin Verification
    </a>
</li>

<!-- User Management Button (Added Here) -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('admin.users.index') }}" style="text-decoration: none;">
        <i class="fas fa-users-cog"></i> User Management
    </a>
</li>

<!-- Employees Dropdown -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" data-bs-toggle="collapse" href="#sidebarEmployees" role="button" aria-expanded="false" aria-controls="sidebarEmployees" style="text-decoration: none;">
        <i class="fas fa-users"></i> Employees
    </a>
    <div class="collapse ps-4" id="sidebarEmployees">
        <ul class="nav flex-column">
            <li><a class="nav-link text-dark py-2" href="{{ route('employees.index') }}" style="text-decoration: none;"><i class="fas fa-eye"></i> View Employees</a></li>
            <li><a class="nav-link text-dark py-2" href="{{ route('employees.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Add Employee</a></li>
        </ul>
    </div>
</li>

<!-- Departments Dropdown -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" data-bs-toggle="collapse" href="#sidebarDepartments" role="button" aria-expanded="false" aria-controls="sidebarDepartments" style="text-decoration: none;">
        <i class="fas fa-building"></i> Departments
    </a>
    <div class="collapse ps-4" id="sidebarDepartments">
        <ul class="nav flex-column">
            <li><a class="nav-link text-dark py-2" href="{{ route('departments.index') }}" style="text-decoration: none;"><i class="fas fa-eye"></i> View Departments</a></li>
            <li><a class="nav-link text-dark py-2" href="{{ route('departments.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Add Department</a></li>
        </ul>
    </div>
</li>

<!-- Positions Dropdown -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" data-bs-toggle="collapse" href="#sidebarPositions" role="button" aria-expanded="false" aria-controls="sidebarPositions" style="text-decoration: none;">
        <i class="fas fa-briefcase"></i> Positions
    </a>
    <div class="collapse ps-4" id="sidebarPositions">
        <ul class="nav flex-column">
            <li><a class="nav-link text-dark py-2" href="{{ route('positions.index') }}" style="text-decoration: none;"><i class="fas fa-eye"></i> View Positions</a></li>
            <li><a class="nav-link text-dark py-2" href="{{ route('positions.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Add Positions</a></li>
        </ul>
    </div>
</li>

<!-- Leave Types Dropdown -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" data-bs-toggle="collapse" href="#sidebarLeaveTypes" role="button" aria-expanded="false" aria-controls="sidebarLeaveTypes" style="text-decoration: none;">
        <i class="fas fa-calendar"></i> Leave Types
    </a>
    <div class="collapse ps-4" id="sidebarLeaveTypes">
        <ul class="nav flex-column">
            <li><a class="nav-link text-dark py-2" href="{{ route('leave_types.index') }}" style="text-decoration: none;"><i class="fas fa-eye"></i> View Leave Types</a></li>
            <li><a class="nav-link text-dark py-2" href="{{ route('leave_types.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Add Leave Type</a></li>
            <li><a class="nav-link btn btn-outline-primary text-dark mt-2 mb-1 mx-2 py-2" href="{{ route('leave_requests.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Apply Leave</a></li>
        </ul>
    </div>
</li>

<!-- Grades -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" data-bs-toggle="collapse" href="#sidebarGrades" role="button" aria-expanded="false" aria-controls="sidebarGrades" style="text-decoration: none;">
        <i class="fas fa-layer-group"></i> Grades
    </a>
    <div class="collapse ps-4" id="sidebarGrades">
        <ul class="nav flex-column">
            <li><a class="nav-link text-dark py-2" href="{{ route('grades.index') }}" style="text-decoration: none;"><i class="fas fa-eye"></i> View Grades</a></li>
            <li><a class="nav-link text-dark py-2" href="{{ route('grades.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Add Grade</a></li>
        </ul>
    </div>
</li>
@endif

                @if(auth()->user()->role_id === 2)
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('dashboard') }}" style="text-decoration: none;">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('supervisor.index') }}" style="text-decoration: none;">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a href="{{ route('leave_requests.index') }}" class="nav-link text-dark d-flex align-items-center gap-2 py-3" style="text-decoration: none;">
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
                        <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('dashboards.employee') }}" style="text-decoration: none;">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('leave_requests.create') }}" style="text-decoration: none;">
                            <i class="fas fa-calendar-plus"></i> Apply for New Leave
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('notifications') }}" style="text-decoration: none;">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- Footer -->
        <div class="border-top p-3 small text-secondary" style="border-color: #e0e0e0 !important;">
            <div class="d-flex flex-column gap-1">
                <span>© 2025 CYBERTECH</span>
                <div>
                    <a href="#" class="text-secondary me-2" style="text-decoration: none;">About</a>
                    <a href="#" class="text-secondary me-2" style="text-decoration: none;">Blog</a>
                    <a href="#" class="text-secondary me-2" style="text-decoration: none;">Terms</a>
                    <a href="#" class="text-secondary me-2" style="text-decoration: none;">Privacy</a>
                    <a href="#" class="text-secondary me-2" style="text-decoration: none;">Security</a>
                    <a href="#" class="text-secondary" style="text-decoration: none;">Status</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Wrapper -->
<div id="contentWrapper" style="min-height: 100vh;">

<!-- SIMPLIFIED CSS for Sidebar -->
<style>
    /* Sidebar as offcanvas styles - Hidden by default */
    .offcanvas-start {
        width: 320px !important;
        z-index: 1050 !important;
    }
    
    .offcanvas {
        background-color: #fdfdfd !important;
        color: #000 !important;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        overflow: hidden !important;
    }
    
    .offcanvas-header {
        border-bottom: 1px solid #e0e0e0 !important;
    }
    
    .offcanvas-header .btn-close {
        opacity: 0.7;
        background-size: 0.8em;
        padding: 0.5rem;
        margin: -0.5rem -0.5rem -0.5rem auto;
    }
    
    .offcanvas-header .btn-close:hover {
        opacity: 1;
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .offcanvas-title {
        color: #000;
        font-weight: 600;
        display: flex;
        align-items: center;
        font-size: 1.1rem;
    }
    
    /* Sidebar link styles */
    .offcanvas .nav-link {
        border-radius: 8px;
        transition: all 0.2s ease;
        margin: 2px 4px;
        color: #333333 !important;
        text-decoration: none !important;
        font-weight: 500;
    }
    
    .offcanvas .nav-link:hover {
        background: rgba(61, 81, 159, 0.1);
        color: #3D519F !important;
        transform: translateX(3px);
    }
    
    .offcanvas .nav-link.active {
        background: rgba(61, 81, 159, 0.15);
        color: #3D519F !important;
        border-left: 3px solid #3D519F;
        font-weight: 600;
    }
    
    .offcanvas .collapse .nav-link {
        padding-left: 20px;
        margin-left: 8px;
        border-left: 2px solid rgba(0, 0, 0, 0.08);
        color: #555555 !important;
        font-size: 0.9rem;
    }
    
    .offcanvas .collapse .nav-link:hover {
        border-left-color: #3D519F;
        background: rgba(61, 81, 159, 0.05);
    }
    
    /* Dropdown menu button styles */
    .offcanvas .btn-outline-primary {
        border: 2px solid #3D519F;
        color: #3D519F;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .offcanvas .btn-outline-primary:hover {
        background: #3D519F;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(61, 81, 159, 0.2);
    }
    
    /* REMOVE ALL SCROLLBARS */
    .offcanvas-body {
        overflow: hidden !important;
        overflow-y: hidden !important;
        overflow-x: hidden !important;
    }
    
    .offcanvas nav {
        overflow: hidden !important;
    }
    
    /* Force no scroll on the entire sidebar */
    #mainSidebar,
    #mainSidebar * {
        overflow: hidden !important;
    }
    
    /* Backdrop styling */
    .offcanvas-backdrop {
        z-index: 1045 !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .offcanvas-start {
            width: 280px !important;
        }
    }
    
</style>

<!-- SIMPLIFIED JavaScript - REMOVE ALL CONFLICTING CODE -->
<script>
    // PREVENT SCROLLING IN SIDEBAR
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('mainSidebar');
        
        if (sidebar) {
            // Prevent wheel scrolling
            sidebar.addEventListener('wheel', function(e) {
                if (e.target.closest('#mainSidebar')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }, { passive: false });
            
            // Prevent touch scrolling
            sidebar.addEventListener('touchmove', function(e) {
                if (e.target.closest('#mainSidebar')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }, { passive: false });
            
            // Close sidebar when clicking on links (for mobile)
            const navLinks = sidebar.querySelectorAll('.nav-link:not([data-bs-toggle="collapse"])');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        const bsSidebar = bootstrap.Offcanvas.getInstance(sidebar);
                        if (bsSidebar) bsSidebar.hide();
                    }
                });
            });
        }
        
        console.log('Sidebar loaded - Scroll disabled');
    });
</script>