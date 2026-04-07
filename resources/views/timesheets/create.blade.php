@extends('layouts.app')

@section('title', 'Submit Timesheet')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Submit Timesheet</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('timesheets.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Work Date</label>
                            <input type="date" name="WorkDate" class="form-control" value="{{ old('WorkDate') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hours Worked</label>
                            <input type="number" step="0.25" min="0" max="24" name="HoursWorked" class="form-control" value="{{ old('HoursWorked') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Overtime Hours</label>
                            <input type="number" step="0.25" min="0" max="24" name="OvertimeHours" class="form-control" value="{{ old('OvertimeHours', 0) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="Notes" class="form-control" rows="4">{{ old('Notes') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('timesheets.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
