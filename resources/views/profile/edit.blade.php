@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="profile-container">
        <h1 class="text-center text-primary">Edit Profile - {{ $user->name }}</h1>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <!-- Display Profile Picture -->
        <div class="text-center mb-3">
            @if(!empty($user->profile_photo) && file_exists(public_path($user->profile_photo)))
                <img id="imagePreview" src="{{ asset($user->profile_photo) }}" class="img-thumbnail profile-photo shadow-lg" alt="Profile Picture">
            @else
                <img id="imagePreview" src="{{ asset('images/default-avatar.png') }}" class="img-thumbnail profile-photo shadow-lg" alt="Default Profile Picture">
            @endif
        </div>

        <!-- Profile Edit Form -->
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="name" class="text-success">Name</label>
                <input id="name" type="text" name="name" class="form-control border border-primary" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="email" class="text-info">Email</label>
                <input id="email" type="email" name="email" class="form-control border border-success" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="profile_photo" class="text-danger">Profile Photo</label>
                <input id="profile_photo" type="file" name="profile_photo" class="form-control border border-warning" onchange="previewImage(event)">
            </div>
            <button type="submit" class="btn btn-primary w-100 shadow">Save Changes</button>
        </form>
    </div>
</div>

<!-- Profile Photo Preview Script -->
<script>
    function previewImage(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<!-- Custom Styling -->
<style>
    .vh-100 {
        height: 100vh;
    }
    .profile-container {
        max-width: 500px;
        padding: 25px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }
    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        transition: transform 0.3s ease-in-out;
    }
    .profile-photo:hover {
        transform: scale(1.05);
    }
    label {
        font-weight: bold;
    }
</style>

@endsection
