@extends('layouts.app')

@section('title', 'User Profile')

@section('styles')
<style>
    .profile-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .profile-cover {
        height: 120px;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }
    .profile-avatar-container {
        margin-top: -60px;
        position: relative;
        z-index: 10;
    }
    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        background-color: white;
    }
    .info-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .info-value {
        font-size: 1rem;
        color: #1e293b;
        font-weight: 500;
        margin-bottom: 1.25rem;
    }
    .detail-card {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        height: 100%;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card profile-card">
                <div class="profile-cover"></div>
                <div class="card-body p-4 pt-0 text-center">
                    <div class="profile-avatar-container mb-3">
                        @if(!empty($user->profile_photo) && file_exists(public_path($user->profile_photo)))
                            <img src="{{ asset($user->profile_photo) }}?v={{ time() }}" class="profile-photo shadow" alt="Profile Picture">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}" class="profile-photo shadow" alt="Default Profile Picture">
                        @endif
                    </div>
                    
                    <h3 class="fw-bold text-slate-900 mb-1">{{ $user->FirstName }} {{ $user->LastName }}</h3>
                    <p class="text-muted mb-4">{{ $user->position->PositionName ?? 'Employee' }}</p>

                    <div class="text-start mt-5">
                        <div class="row g-4">
                            <!-- Personal Info -->
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="fw-bold mb-3 d-flex align-items-center">
                                        <i class="fas fa-user-circle text-primary me-2"></i>Personal Information
                                    </h6>
                                    
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value">{{ $user->name }}</div>
                                    
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value text-primary">{{ $user->email }}</div>
                                    
                                    <div class="info-label">Gender</div>
                                    <div class="info-value">{{ $user->Gender ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <!-- Employment Info -->
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="fw-bold mb-3 d-flex align-items-center">
                                        <i class="fas fa-briefcase text-primary me-2"></i>Employment Details
                                    </h6>
                                    
                                    <div class="info-label">Employee Number</div>
                                    <div class="info-value fw-bold">{{ $user->EmployeeNumber }}</div>
                                    
                                    <div class="info-label">Department</div>
                                    <div class="info-value">{{ $user->department->DepartmentName ?? 'N/A' }}</div>
                                    
                                    <div class="info-label">Grade / Position</div>
                                    <div class="info-value">
                                        {{ $user->grade->GradeName ?? 'N/A' }} / {{ $user->position->PositionName ?? 'N/A' }}
                                    </div>
                                    
                                    <div class="info-label">Joined Date</div>
                                    <div class="info-value">{{ $user->created_at->format('d M, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 mb-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                        <a href="{{ auth()->user()->role_id == 1 ? route('admin.verification') : (auth()->user()->role_id == 2 ? route('supervisor.index') : route('dashboards.employee')) }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill ms-2">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
