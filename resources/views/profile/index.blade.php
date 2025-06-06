@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
<div class="container mt-4 profile-container">
    <h2 class="text-center text-primary">User Profile</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display Profile Photo -->
    <div class="text-center">
        @if(!empty($user->profile_photo) && file_exists(public_path($user->profile_photo)))
            <img src="{{ asset($user->profile_photo) }}" class="img-thumbnail profile-photo shadow-lg" alt="Profile Picture">
        @else
            <img src="{{ asset('images/default-avatar.png') }}" class="img-thumbnail profile-photo shadow-lg" alt="Default Profile Picture">
        @endif
    </div>

    <!-- User Details -->
    <div class="card p-4 mt-3">
        <p class="text-success"><strong>Name:</strong> {{ $user->name }}</p>
        <p class="text-info"><strong>Email:</strong> {{ $user->email }}</p>
        <p class="text-warning"><strong>Joined:</strong> {{ $user->created_at->format('d M Y') }}</p>
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary shadow">Edit Profile</a>
    </div>
</div>

@endsection
