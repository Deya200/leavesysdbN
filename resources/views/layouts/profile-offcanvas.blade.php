<div class="offcanvas offcanvas-end telegram-profile-drawer" tabindex="-1" id="profileOffcanvas" aria-labelledby="profileOffcanvasLabel">
  <div class="offcanvas-header">
    <span class="offcanvas-title" id="profileOffcanvasLabel">My Profile</span>
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('profile.edit') }}" class="btn btn-link text-light p-0" title="Edit Profile">
        <i class="fas fa-pen"></i>
      </a>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
  </div>
  <div class="offcanvas-body">
    <div class="profile-section">
      @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
        <img src="{{ asset(Auth::user()->profile_photo) }}" alt="Profile Picture">
      @else
        <i class="fas fa-user-circle fa-5x text-secondary"></i>
      @endif
      <h5>{{ Auth::user()->name }}</h5>
      <div class="text-success">online</div>
    </div>
    <div class="info-section">
      <div class="row">
        <i class="fas fa-info-circle"></i>
        <span>{{ Auth::user()->phone ?? '+265 88 690 9827' }}</span>
        <span class="label ms-auto">Mobile</span>
      </div>
      <div class="row">
        <i class="fas fa-at"></i>
        <span>@{{ Auth::user()->username ?? 'NoelDen' }}</span>
        <span class="label ms-auto">Username</span>
      </div>
      <div class="row">
        <i class="fas fa-birthday-cake"></i>
        <span>{{ Auth::user()->dob ?? 'Dec 24, 2002 (22 years old)' }}</span>
        <span class="label ms-auto">Date of birth</span>
      </div>
    </div>
    <div class="archive-section">
      <div class="fw-bold mb-2">Story Archive <span class="badge ms-1">3</span></div>
      <div class="archive-previews">
        <div class="story-preview">
          <img src="/path/to/story1.jpg" alt="Story 1">
          <span class="duration">00:35</span>
        </div>
        <div class="story-preview">
          <img src="/path/to/story2.jpg" alt="Story 2">
          <span class="duration">00:05</span>
        </div>
        <div class="story-preview">
          <img src="/path/to/story3.jpg" alt="Story 3">
          <span class="duration">00:30</span>
        </div>
      </div>
    </div>
  </div>
</div>
