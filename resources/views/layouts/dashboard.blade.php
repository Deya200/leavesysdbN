\@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Dashboard Header -->
    <h1 class="text-center mb-4">Welcome to Your Dashboard</h1>

    <!-- Dashboard Boxes -->
    <div class="row">
        <!-- Leave Requests Box -->
        <div class="col-md-4">
            <div class="card card-primary text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-alt fa-2x mb-3"></i>
                    <h5 class="card-title">Leave Requests</h5>
                    <p class="card-text">Manage your leave requests here.</p>
                    <a href="{{ route('leave.requests.index') }}" class="btn btn-light">View</a> <!-- Corrected route -->
                </div>
            </div>
        </div>

        <!-- Profile Box -->
        <div class="col-md-4">
            <div class="card card-secondary text-center">
                <div class="card-body">
                    <i class="fas fa-user fa-2x mb-3"></i>
                    <h5 class="card-title">Profile</h5>
                    <p class="card-text">Update your personal details and preferences.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-light">Edit</a>
                </div>
            </div>
        </div>

        <!-- Notifications Box -->
        <div class="col-md-4">
            <div class="card card-accent text-center">
                <div class="card-body">
                    <i class="fas fa-bell fa-2x mb-3"></i>
                    <h5 class="card-title">Notifications</h5>
                    <p class="card-text">Check your latest updates and alerts.</p>
                    <a href="{{ route('notifications') }}" class="btn btn-light">View</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
