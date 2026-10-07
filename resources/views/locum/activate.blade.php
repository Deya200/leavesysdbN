@extends('layouts.app')

@section('title', 'Activate Locum Account')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-key"></i> Activate Your Locum Account
                    </h4>
                </div>
                <div class="card-body p-5">
                    <p class="text-muted mb-4">
                        Welcome, <strong>{{ $employee->FullName }}</strong>!
                    </p>
                    <p class="text-muted mb-4">
                        Please set a secure password to activate your locum account.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('locum.activate', $token) }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Enter a strong password (minimum 8 characters)"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   placeholder="Confirm your password"
                                   required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-lock"></i> Activate Account
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="alert alert-info" role="alert">
                        <small>
                            <strong>Password Requirements:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Minimum 8 characters</li>
                                <li>Mix of uppercase and lowercase letters</li>
                                <li>Include numbers and special characters for extra security</li>
                            </ul>
                        </small>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted">
                    Once activated, you'll be able to sign in and out of locum sessions.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
