@extends('layouts.app')

@section('title', 'Manage Leave Appeals')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Leave Appeals</h2>
        <a href="{{ route('supervisor.index') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>



    <div class="card shadow-sm">
        <div class="card-body">
            @if($appeals->isEmpty())
                <p class="text-center my-3">No pending appeals found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Leave Date</th>
                                <th>Rejection Reason</th>
                                <th>Appeal Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appeals as $appeal)
                                <tr>
                                    <td>{{ $appeal->employee->FirstName }} {{ $appeal->employee->LastName }}</td>
                                    <td>
                                        #{{ $appeal->leaveRequest->id }} <br>
                                        <small>{{ $appeal->leaveRequest->StartDate->format('d M') }} - {{ $appeal->leaveRequest->EndDate->format('d M Y') }}</small>
                                    </td>
                                    <td class="text-danger">{{ $appeal->leaveRequest->RejectionReason ?? $appeal->leaveRequest->SupervisorRejectionReason }}</td>
                                    <td>{{ $appeal->appeal_reason }}</td>
                                    <td>
                                        <form action="{{ route('leave_appeals.approve', $appeal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectAppealModal"
                                            data-id="{{ $appeal->id }}">Reject</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $appeals->links() }}
            @endif
        </div>
    </div>
</div>

<!-- Reject Appeal Modal -->
<div class="modal fade" id="rejectAppealModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="rejectAppealForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Appeal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="review_reason" class="form-label">Rejection Reason</label>
                        <textarea class="form-control" id="review_reason" name="review_reason" rows="3" required></textarea>
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
        var rejectModal = document.getElementById('rejectAppealModal');
        rejectModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var form = document.getElementById('rejectAppealForm');
            form.action = '/leave-appeals/' + id + '/reject';
        });
    });
</script>
@endsection
