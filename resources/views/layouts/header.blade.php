<!-- Header -->
<header class="py-2 border-bottom" style="position: sticky; top: 0; z-index: 1040; background: #3D519F; color: #fff; font-family: system-ui, 'Segoe UI', Arial, sans-serif;">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Left: Hamburger (Sidebar Toggle) -->
            <div class="d-flex align-items-center gap-2">
                <!-- Hamburger button: always visible -->
                <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainSidebar" aria-controls="mainSidebar" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('logo3.png') }}" alt="Logo" class="rounded-circle bg-light p-1" style="height: 38px; width: 38px;">
                </a>
            </div>
            <!-- Right: Notifications, Profile and Logout -->
            <div class="d-flex align-items-center gap-3">
                <!-- Dark mode toggle -->
                <button id="darkModeToggle" class="btn btn-outline-secondary" type="button" aria-label="Toggle dark mode">
                    <i class="fas fa-moon"></i>
                </button>
                <!-- Profile dropdown -->
                <div class="dropdown">
                    <button class="profile-btn d-flex align-items-center dropdown-toggle"
                        style="background:none; border:none; box-shadow:none;" type="button"
                        id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
                            <img src="{{ asset(Auth::user()->profile_photo) }}" class="profile-photo-navbar rounded-circle" alt="Profile Picture">
                        @else
                            <i class="fas fa-user-circle fa-2x text-white"></i>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="offcanvas" data-bs-target="#profileEditOffcanvas" aria-controls="profileEditOffcanvas">
                                <i class="fas fa-user me-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('notifications') }}">
                                <i class="fas fa-bell me-2"></i> Notifications
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); confirmLogout();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</header>
<script>
    function confirmLogout() {
        if (confirm('Are you sure you want to log out?')) {
            document.getElementById('logout-form').submit();
        }
    }
</script>
