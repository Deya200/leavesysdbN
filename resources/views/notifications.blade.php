@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Notifications</h1>

    <p>You don’t have any new notifications at the moment.</p>

    <!-- Example notifications list -->
    <ul class="list-group">
        <li class="list-group-item">Your leave request has been approved.</li>
        <li class="list-group-item">Your profile was updated successfully.</li>
        <li class="list-group-item">New company policy available for review.</li>
    </ul>
</div>
@endsection
