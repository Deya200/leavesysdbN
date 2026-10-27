@extends('layouts.app')

@section('title', 'Manage Positions')

@section('styles')
<style>
    .positions-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    .card-custom {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 20px;
    }

    .table {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table thead {
        background-color: #2E3A87;
        color: white;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
        padding: 12px;
        border: none;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .btn-sm {
        font-size: 0.8rem;
    }

    .btn-edit {
        background-color: #2E3A87;
        color: white;
        border: none;
    }

    .btn-edit:hover {
        background-color: #1f2f75;
        color: white;
    }

    .form-control {
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="positions-container">

    <!-- Welcome Card -->
    <div class="card-custom text-center mb-4" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Manage Positions</h4>
        <p class="mb-2">Here you can add, edit, or remove employee positions and assign grades.</p>
    </div>

    <!-- Search Form -->
    <div class="text-center mb-4">
        <form action="{{ route('positions.index') }}" method="GET" style="max-width: 600px; margin: auto;">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by Position Name or Grade" value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>



    <div class="text-end mb-4">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#positionModal" onclick="openPositionModal()">Add New Position</button>
    </div>

    <div id="positions-table" class="card-custom">
        @if ($positions->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Position Name</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="positionsTableBody">
                        @foreach ($positions as $position)
                            <tr class="position-row">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $position->PositionName }}</td>
                                <td>{{ $position->grade->GradeName ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#positionModal"
                                            onclick="openPositionModal('{{ route('positions.update', $position->PositionID) }}', '{{ addslashes($position->PositionName) }}', '{{ $position->GradeID }}')">Edit</button>
                                        <form action="{{ route('positions.destroy', $position->PositionID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this position?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            {{-- Pagination --}}
            @if ($positions->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $positions->links() }}
                </div>
            @endif
        @else
            <div class="alert alert-info text-center m-0">
                No positions found. Click "Add New Position" above to add one.
            </div>
        @endif
    </div>

    <div class="modal fade" id="positionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><form id="positionForm" method="POST" action="{{ route('positions.store') }}" class="modal-content">
            @csrf <input type="hidden" id="positionMethod" name="_method" value="POST">
            <div class="modal-header"><h5 id="positionModalTitle" class="modal-title">Add Position</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><div class="mb-3"><label for="positionName" class="form-label">Position Name</label><input id="positionName" name="PositionName" class="form-control" required maxlength="150"></div><div><label for="positionGrade" class="form-label">Grade</label><select id="positionGrade" name="GradeID" class="form-select" required><option value="">Select a grade</option>@foreach($grades as $grade)<option value="{{ $grade->GradeID }}">{{ $grade->GradeName }}</option>@endforeach</select></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Save Position</button></div>
        </form></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openPositionModal(updateUrl = '', positionName = '', gradeId = '') {
    const form = document.getElementById('positionForm');
    form.action = updateUrl || '{{ route('positions.store') }}';
    document.getElementById('positionMethod').value = updateUrl ? 'PUT' : 'POST';
    document.getElementById('positionModalTitle').textContent = updateUrl ? 'Edit Position' : 'Add Position';
    document.getElementById('positionName').value = positionName;
    document.getElementById('positionGrade').value = gradeId;
}
    (function () {
        const url = new URL(window.location.href);
        if (url.searchParams.has('page')) {
            const section = document.getElementById('positions-table');
            if (section) setTimeout(() => section.scrollIntoView({ behavior: 'smooth', block: 'start' }), 150);
        }
        document.querySelectorAll('.pagination a').forEach(link => {
            const href = link.getAttribute('href');
            if (href && !href.includes('#')) link.setAttribute('href', href + '#positions-table');
        });
    })();
</script>
@endsection
