@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'System Settings')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Settings</h5>

        {{-- Common settings for all users --}}
        <p>Update your profile, change password, and manage notifications.</p>

        {{-- Admin-only section --}}
        @if($user->role_id === 1)
            <hr>
            <h6>Admin Settings</h6>
            <p>Manage system configurations, user roles, and global preferences.</p>
        @endif

        {{-- User-only section --}}
        @if($user->role_id !== 1)
            <hr>
            <h6>User Preferences</h6>
            <p>Customize your dashboard view and personal options.</p>
        @endif
    </div>
</div>
@endsection
