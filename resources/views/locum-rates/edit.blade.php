@extends('layouts.app')

@section('title', 'Edit Locum Rate')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Locum Rate</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.locum_rates.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.locum_rates.update', $locumRate) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="DepartmentID">Department</label>
                            <select name="DepartmentID" id="DepartmentID" class="form-control @error('DepartmentID') is-invalid @enderror" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->DepartmentID }}" {{ old('DepartmentID', $locumRate->DepartmentID) == $department->DepartmentID ? 'selected' : '' }}>
                                        {{ $department->DepartmentName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('DepartmentID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="position_type">Position Type</label>
                            <input type="text" name="position_type" id="position_type" class="form-control @error('position_type') is-invalid @enderror"
                                   value="{{ old('position_type', $locumRate->position_type) }}" placeholder="e.g., Nurse, Doctor, Specialist" required>
                            @error('position_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="shift">Shift</label>
                            <select name="shift" id="shift" class="form-control @error('shift') is-invalid @enderror" required>
                                <option value="">Select Shift</option>
                                <option value="day" {{ old('shift', $locumRate->shift) == 'day' ? 'selected' : '' }}>Day Shift (7:30 AM - 4:30 PM)</option>
                                <option value="night" {{ old('shift', $locumRate->shift) == 'night' ? 'selected' : '' }}>Night Shift (4:30 PM - 8:30 AM)</option>
                            </select>
                            @error('shift')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="daily_rate">Daily Rate</label>
                            <input type="number" name="daily_rate" id="daily_rate" class="form-control @error('daily_rate') is-invalid @enderror"
                                   value="{{ old('daily_rate', $locumRate->daily_rate) }}" step="0.01" min="0" required>
                            @error('daily_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="hourly_rate">Hourly Rate (Optional)</label>
                            <input type="number" name="hourly_rate" id="hourly_rate" class="form-control @error('hourly_rate') is-invalid @enderror"
                                   value="{{ old('hourly_rate', $locumRate->hourly_rate) }}" step="0.01" min="0">
                            @error('hourly_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <select name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror" required>
                                <option value="USD" {{ old('currency', $locumRate->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency', $locumRate->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ old('currency', $locumRate->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                <option value="KES" {{ old('currency', $locumRate->currency) == 'KES' ? 'selected' : '' }}>KES</option>
                                <option value="MWK" {{ old('currency', $locumRate->currency) == 'MWK' ? 'selected' : '' }}>MWK</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $locumRate->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Rate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection