<div class="offcanvas offcanvas-end telegram-profile-drawer" tabindex="-1" id="profileEditOffcanvas" aria-labelledby="profileEditOffcanvasLabel">
    <div class="offcanvas-header">
        <span class="offcanvas-title" id="profileEditOffcanvasLabel">Edit Profile</span>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <!-- Display Profile Picture -->
        <div class="text-center mb-3">
            @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
                <img id="imagePreview" src="{{ asset(Auth::user()->profile_photo) }}" class="img-thumbnail profile-photo shadow-lg" alt="Profile Picture">
            @else
                <img id="imagePreview" src="{{ asset('images/default-avatar.png') }}" class="img-thumbnail profile-photo shadow-lg" alt="Default Profile Picture">
            @endif
        </div>

        <!-- Profile Edit Form -->
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="name" class="text-success">Name</label>
                <input id="name" type="text" name="name" class="form-control border border-primary" value="{{ old('name', Auth::user()->name) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="email" class="text-info">Email</label>
                <input id="email" type="email" name="email" class="form-control border border-success" value="{{ old('email', Auth::user()->email) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="profile_photo" class="text-danger">Profile Photo</label>
                <input id="profile_photo" type="file" name="profile_photo" class="form-control border border-warning" onchange="previewImage(event)">
            </div>
            <button type="submit" class="btn btn-primary w-100 shadow">Save Changes</button>
        </form>
    </div>
</div>
