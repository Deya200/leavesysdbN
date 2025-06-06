@extends('layouts.auth')

@section('title', 'Employee Registration')

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h2 class="text-center mb-4">Employee Registration</h2>

      <!-- Success Message -->
      @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      <!-- Validation Errors -->
      @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <!-- Registration Form -->
      <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Employee Number -->
        <div class="form-group mb-3">
          <label for="EmployeeNumber" class="form-label">Employee Number</label>
          <input id="EmployeeNumber" type="text" name="EmployeeNumber" class="form-control @error('EmployeeNumber') is-invalid @enderror" value="{{ old('EmployeeNumber') }}" required>
          @error('EmployeeNumber')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Name -->
        <div class="form-group mb-3">
          <label for="name" class="form-label">Name</label>
          <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
          @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

      <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror" required>
    <option value="">Select Gender</option>
    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
    </select>






        <!-- Email -->
        <div class="form-group mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
          @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Profile Photo Upload -->
        <div class="form-group mb-3">
          <label for="profile_photo" class="form-label">Profile Photo (optional)</label>
          <input id="profile_photo" type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror">
          @error('profile_photo')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Role (Default to Employee) -->
        <div class="form-group mb-3">
          <label for="role_display" class="form-label">Role</label>
          <input id="role_display" type="text" class="form-control" value="Employee" disabled>
          <input type="hidden" name="role_id" value="2"> <!-- Employees are registered with role_id=2 -->
        </div>

        <!-- Password -->
        <div class="form-group mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
          @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group mb-4">
          <label for="password_confirmation" class="form-label">Confirm Password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100">Register</button>
      </form>
    </div>
  </div>
</div>
@endsection
