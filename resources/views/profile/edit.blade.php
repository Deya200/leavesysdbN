@extends('layouts.app')
@section('page_title', 'Edit Profile')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-slate-900">Edit Profile</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Avatar Upload Section -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if(!empty($user->profile_photo) && file_exists(public_path($user->profile_photo)))
                                    <img id="imagePreview" src="{{ asset($user->profile_photo) }}" 
                                        class="rounded-circle border border-4 border-white shadow-sm"
                                        style="width: 120px; height: 120px; object-fit: cover;"
                                        alt="Profile Picture">
                                @else
                                    <div id="imagePreviewPlaceholder" class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary fw-bold shadow-sm"
                                        style="width: 120px; height: 120px; font-size: 2.5rem;">
                                        {{ substr($user->FirstName ?? 'U', 0, 1) }}
                                    </div>
                                    <img id="imagePreview" src="#" class="rounded-circle border border-4 border-white shadow-sm d-none"
                                        style="width: 120px; height: 120px; object-fit: cover;" alt="Profile Picture">
                                @endif
                                <label for="profile_photo" class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                    style="width: 32px; height: 32px; cursor: pointer;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input id="profile_photo" type="file" name="profile_photo" class="d-none" onchange="previewImage(event)">
                            </div>
                            <div class="mt-2 small text-slate-500">Click the camera icon to update your photo</div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-slate-700">Full Name</label>
                            <input id="name" type="text" name="name" class="form-control @if(auth()->user()->role_id !== 1) bg-light border-dashed @endif"
                                value="{{ old('name', $user->name) }}" 
                                @if(auth()->user()->role_id !== 1) readonly @else required @endif>
                            @if(auth()->user()->role_id !== 1)
                                <div class="form-text text-slate-400 smaller">Contact administrator to change your name.</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-slate-700">Email Address</label>
                            <input id="email" type="email" name="email" class="form-control @if(auth()->user()->role_id !== 1) bg-light border-dashed @endif"
                                value="{{ old('email', $user->email) }}"
                                @if(auth()->user()->role_id !== 1) readonly @else required @endif>
                            @if(auth()->user()->role_id !== 1)
                                <div class="form-text text-slate-400 smaller">Contact administrator to change your email.</div>
                            @endif
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                const placeholder = document.getElementById('imagePreviewPlaceholder');
                
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    .border-dashed {
        border-style: dashed !important;
    }
    .form-control:focus {
        border-color: var(--color-primary-light);
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.1);
    }
</style>
@endsection