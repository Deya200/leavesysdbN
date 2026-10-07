@extends('layouts.app')

@section('title', 'Account Already Activated')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle"></i> Account Already Activated
                    </h4>
                </div>
                <div class="card-body p-5 text-center">
                    <i class="fas fa-shield-alt text-success fa-3x mb-3"></i>

                    <p class="text-muted mb-4">
                        Your locum account has already been activated.
                    </p>

                    <p class="mb-4">
                        Please proceed to log in with your credentials.
                    </p>

                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Go to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
