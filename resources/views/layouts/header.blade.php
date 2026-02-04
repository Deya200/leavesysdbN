<div class="header-container">
    <div class="container-fluid h-100">
        <div class="row align-items-center h-100">
<!-- Left: Menu Toggle, Logo, and Brand -->
<div class="col-md-6">
    <div class="d-flex align-items-center" style="height: 70px;">
        <!-- Desktop Sidebar Toggle Button -->
        <button class="btn btn-outline-primary d-none d-lg-flex align-items-center me-3" id="desktopSidebarToggle" data-bs-toggle="offcanvas" data-bs-target="#mainSidebar" style="height: 40px; padding: 8px 16px;">
            <i class="fas fa-bars me-2"></i> Menu
        </button>

        <!-- Logo and Brand -->
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none" style="height: 100%;">
            <img src="{{ asset('logo3.png') }}" alt="ABCMH Logo" style="height: 40px; object-fit: contain;" class="me-2">
            <span class="fw-bold text-dark" style="font-size: 1.5rem; line-height: 1;">ABCMH</span>
        </a>
    </div>

</div>

            
            <!-- Center: Page Title (for mobile) -->
            <div class="col-md-3 d-md-none text-center">
                <h5 class="mb-0 text-dark">Admin Dashboard</h5>
            </div>
            
            <!-- Right: User Profile and Actions -->
            <div class="col-md-6">
                <div class="d-flex justify-content-end align-items-center gap-3">
                    
                    @section('page-actions')
    <div class="d-flex justify-content-start align-items-start gap-2 mt-n2">
          
                    <!-- Notifications -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm position-relative d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            @if(isset($pendingRequests) && $pendingRequests > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    {{ $pendingRequests }}
                                </span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="min-width: 300px;">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            @if(isset($recentNotifications) && $recentNotifications->isNotEmpty())
                                @foreach($recentNotifications as $notification)
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex w-100">
                                                <div class="flex-shrink-0 me-2">
                                                    <i class="fas fa-{{ $notification->type == 'leave' ? 'calendar' : 'info-circle' }} text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                    <p class="mb-0 small">{{ $notification->message }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @else
                                <li><a class="dropdown-item text-muted" href="#"><i class="fas fa-inbox me-2"></i> No new notifications</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="{{ route('notifications') }}">View All Notifications</a></li>
                        </ul>
                    </div>
                    
                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary d-flex align-items-center gap-2 p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="d-none d-md-block text-start">
                                <div class="small fw-semibold" style="font-size: 0.875rem;">{{ auth()->user()->name }}</div>
                                <div class="x-small text-muted" style="font-size: 0.75rem;">{{ auth()->user()->role->role_name ?? 'User' }}</div>
                            </div>
                            <i class="fas fa-chevron-down small d-none d-md-block"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-md bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px;">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0" style="font-size: 1rem;">{{ auth()->user()->name }}</h6>
                                            <p class="mb-0 text-muted small">{{ auth()->user()->email }}</p>
                                            <small class="text-primary">{{ auth()->user()->role->role_name ?? 'User' }}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Use # for now to avoid route errors -->
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user me-2"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            </li>
                            @if(auth()->user()->role_id === 1)
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-shield-alt me-2"></i> Admin Panel
                                    </a>
                                </li>
                            @endif
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Menu Toggle - Only for mobile -->
<button class="btn btn-primary d-lg-none mobile-menu-toggle" id="mobileMenuToggle" data-bs-toggle="offcanvas" data-bs-target="#mainSidebar">
    <i class="fas fa-bars"></i>
</button>

<style>
    /* Header specific styles */
    .header-container {
        height: var(--header-height, 70px);
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 0 20px;
    }
    
    /* Logo and brand styling */
    .navbar-brand {
        padding: 0;
    }
    
    .navbar-brand span {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3D519F;
    }
    
    /* Menu button styling */
    #desktopSidebarToggle {
        padding: 8px 16px;
        border: 2px solid #3D519F;
        color: #3D519F;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    #desktopSidebarToggle:hover {
        background: #3D519F;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(61, 81, 159, 0.2);
    }
    
    /* Mobile menu toggle */
    .mobile-menu-toggle {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1060;
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Button spacing */
    .d-flex.gap-3 > * {
        margin-right: 0.5rem;
    }
    
    .d-flex.gap-3 > *:last-child {
        margin-right: 0;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .header-container {
            padding: 0 15px;
        }
        
        .navbar-brand span {
            font-size: 1.25rem;
        }
        
        .avatar-sm {
            width: 32px !important;
            height: 32px !important;
            font-size: 0.875rem;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
        }
    }
    
    @media (max-width: 576px) {
        .header-container {
            padding: 0 10px;
        }
        
        .navbar-brand span {
            font-size: 1.1rem;
        }
        
        .navbar-brand img {
            height: 32px;
        }
        
        /* Hide text on buttons for very small screens */
        .btn-sm .d-sm-inline {
            display: none !important;
        }
        
        .btn-sm i {
            margin-right: 0 !important;
        }
    }
</style>