@extends('layouts.auth')

@section('title', 'Thank You')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h2 class="mb-4">Registration Successful!</h2>
            <p>Thank you for registering. Your account has been created successfully.</p>
            <p>Please log in to access your account.</p>
            <a href="{{ route('login') }}" class="btn btn-primary mt-3">Go to Login</a>
        </div>
    </div>
</div>
@endsection
