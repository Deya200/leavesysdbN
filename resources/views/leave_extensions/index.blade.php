@extends('layouts.app')

@section('title', 'Manage Leave Extensions')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Leave Extensions</h2>
        <a href="{{ route('supervisor.index') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($extensions->isEmpty())
                <p class="text-center my-3">No pending extension requests.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Original Leaves</th>
                                <th>Extension</th>
                                <th>Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($extensions as $extension)
                                <tr>
                                    <td>{{ $extension->employee->FirstName }} {{ $extension->employee->LastName }}</td>
                                    <td>
                                        <small>Ends: {{ \Carbon\Carbon::parse($extension->leaveRequest->EndDate)->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        <strong>+{{ $extension->extension_days }} days</strong><br>
                                        <small>New End: {{ \Carbon\Carbon::parse($extension->new_end_date)->format('d M Y') }}</small>
                                    </td>
                                    <td>{{ $extension->reason }}</td>
                                    <td>
                                        <form action="{{ route('leave_extensions.approve', $extension->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectExtensionModal"
                                            data-id="{{ $extension->id }}">Reject</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $extensions->links() }}
            @endif
        </div>
    </div>
</div>

<!-- Reject Extension Modal -->
<div class="modal fade" id="rejectExtensionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="rejectExtensionForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Extension</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Rejection Reason</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var rejectModal = document.getElementById('rejectExtensionModal');
        rejectModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var form = document.getElementById('rejectExtensionForm');
            form.action = '/leave-extensions/' + id + '/reject';
        });
    });
</script>
@endsection
