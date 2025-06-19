@extends('layouts.app')

@section('content')
<h2>Admin Reject Leave Request #{{ $leaveRequest->id }}</h2>

<form action="{{ route('leave_requests.admin.reject', $leaveRequest->id) }}" method="POST">
    @csrf
    <div>
        <label for="RejectionReason">Reason for Rejection:</label>
        <input type="text" name="RejectionReason" id="RejectionReason" required>
    </div>
    <button type="submit">Reject</button>
</form>
@endsection
