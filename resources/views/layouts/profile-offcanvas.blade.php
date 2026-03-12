<div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="profileOffcanvas" aria-labelledby="profileOffcanvasLabel" style="width: 380px;">
    <!-- Profile Header -->
    <div class="offcanvas-header p-4 d-flex flex-column align-items-center text-center position-relative" style="background: var(--color-primary); min-height: 220px;">
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        
        <div class="position-relative mb-3 mt-2">
            @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
                <img src="{{ asset(Auth::user()->profile_photo) }}" class="rounded-circle border border-4 border-white shadow-lg" style="width: 100px; height: 100px; object-fit: cover;" alt="Profile Picture">
            @else
                <div class="rounded-circle border border-4 border-white shadow-lg bg-white bg-opacity-20 d-flex align-items-center justify-content-center text-white fw-bold" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    {{ substr(Auth::user()->FirstName ?? 'U', 0, 1) }}
                </div>
            @endif
            <a href="{{ route('profile.edit') }}" class="position-absolute bottom-0 end-0 bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm hover-up text-primary" style="width: 32px; height: 32px; text-decoration: none;">
                <i class="fas fa-camera" style="font-size: 0.8rem;"></i>
            </a>
        </div>
        
        <h5 class="text-white fw-bold mb-0" id="profileOffcanvasLabel">{{ Auth::user()->FirstName . ' ' . Auth::user()->LastName }}</h5>
        <p class="text-white text-opacity-75 small mb-0">{{ Auth::user()->role->RoleName ?? 'Employee' }}</p>
        <span class="badge rounded-pill bg-white bg-opacity-20 text-white mt-2 px-3 py-2 fw-normal" style="font-size: 0.75rem;">
            ID: {{ Auth::user()->EmployeeNumber }}
        </span>
    </div>

    <div class="offcanvas-body p-0 custom-scrollbar">
        <!-- Personal Info Section -->
        <div class="p-4">
            <h6 class="text-slate-400 fw-bold text-uppercase small mb-3 ls-wide" style="font-size: 0.7rem;">Personal Details</h6>
            
            <div class="d-flex flex-column gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-slate-500 smaller" style="font-size: 0.7rem;">Email Address</span>
                        <span class="text-slate-900 fw-medium small">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                        <i class="fas fa-venus-mars"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-slate-500 smaller" style="font-size: 0.7rem;">Gender</span>
                        <span class="text-slate-900 fw-medium small">{{ Auth::user()->Gender ?? 'Not Specified' }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-slate-500 smaller" style="font-size: 0.7rem;">Date of Birth</span>
                        <span class="text-slate-900 fw-medium small">{{ Auth::user()->DateOfBirth ? \Carbon\Carbon::parse(Auth::user()->DateOfBirth)->format('M d, Y') : 'Not Specified' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4"> <hr class="my-0 text-slate-100"> </div>

        <!-- Work Info Section -->
        <div class="p-4">
            <h6 class="text-slate-400 fw-bold text-uppercase small mb-3 ls-wide" style="font-size: 0.7rem;">Employment Information</h6>
            
            <div class="d-flex flex-column gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-secondary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-secondary" style="width: 38px; height: 38px;">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-slate-500 smaller" style="font-size: 0.7rem;">Department</span>
                        <span class="text-slate-900 fw-medium small">{{ Auth::user()->department->DepartmentName ?? 'Not Assigned' }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="bg-secondary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-secondary" style="width: 38px; height: 38px;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-slate-500 smaller" style="font-size: 0.7rem;">Supervisor</span>
                        <span class="text-slate-900 fw-medium small">{{ Auth::user()->supervisor->FirstName ?? 'No Supervisor' }} {{ Auth::user()->supervisor->LastName ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4"> <hr class="my-0 text-slate-100"> </div>

        <!-- Settings Section -->
        <div class="p-4 bg-slate-50 mt-2">
            <h6 class="text-slate-400 fw-bold text-uppercase small mb-3 ls-wide" style="font-size: 0.7rem;">Account Settings</h6>
            
            <div class="form-check form-switch mb-3">
                <input class="form-check-input shadow-none" type="checkbox" role="switch" id="emailNotifications" {{ Auth::user()->email_notifications_enabled ? 'checked' : '' }} disabled>
                <label class="form-check-label small fw-medium text-slate-700" for="emailNotifications">Email Notifications</label>
            </div>
            
            <div class="form-check form-switch">
                <input class="form-check-input shadow-none" type="checkbox" role="switch" id="systemNotifications" {{ Auth::user()->system_notifications_enabled ? 'checked' : '' }} disabled>
                <label class="form-check-label small fw-medium text-slate-700" for="systemNotifications">Push Notifications</label>
            </div>
        </div>
    </div>

    <!-- Footer Action -->
    <div class="offcanvas-footer p-4 border-top">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100 fw-bold py-2 shadow-sm rounded-3">
            <i class="fas fa-edit me-2"></i> Edit Account Info
        </a>
    </div>
</div>

<style>
    .ls-wide { letter-spacing: 0.05em; }
    .smaller { font-size: 0.75rem; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--color-slate-200); border-radius: 4px; }
</style>
