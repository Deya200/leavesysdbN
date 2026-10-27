@extends('layouts.app')

@section('title', 'Grades')

@section('styles')
<style>
    .grades-container {
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
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(68, 9, 219, 0.1);
    }

    .table thead {
        background-color: #2E3A87;
        color: #ffffff;
    }

    .table tbody tr {
        background-color: #ffffff;
        color: #000000;
    }

    .table th, .table td {
        padding: 12px;
        text-align: center;
        vertical-align: middle;
        border: none;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .btn-edit {
        background-color: #2E3A87;
        color: white;
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 4px;
        border: none;
    }

    .btn-edit:hover {
        background-color: rgb(31, 117, 53);
        color: white;
    }

    .form-control {
        font-size: 0.9rem;
    }

    .btn-sm {
        font-size: 0.8rem;
    }
</style>
@endsection

@section('content')
<div class="grades-container">

    <!-- Welcome Section -->
    <div class="card-custom text-center mb-4" style="background-color: #2E3A87; color: white;">
        <h4 class="fw-bold mb-1">Welcome, {{ auth()->user()->FirstName ?? 'Admin' }}!</h4>
        <p class="mb-2">Here you can view, edit, and manage all job grades and annual leave allowances.</p>
    </div>

    <!-- Search Form -->
    <div class="text-center mb-4">
        <form action="{{ route('grades.index') }}" method="GET" style="max-width: 600px; margin: auto;">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by Grade Name" value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-success" style="background-color: #2E3A87; color: white;">Search</button>
            </div>
        </form>
    </div>



    <!-- Add Grade Button -->
    <div class="mb-4 text-end">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#gradeModal" onclick="openGradeModal()">Add New Grade</button>
    </div>

    <!-- Grades Table -->
    <div id="grades-table" class="card-custom">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Grade Name</th>
                        <th>Annual Leave Days</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($grades as $grade)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $grade->GradeName }}</td>
                            <td>{{ $grade->AnnualLeaveDays }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#gradeModal"
                                        onclick="openGradeModal('{{ route('grades.update', $grade->GradeID) }}', '{{ addslashes($grade->GradeName) }}', {{ $grade->AnnualLeaveDays }})">Edit</button>
                                    <form action="{{ route('grades.destroy', $grade->GradeID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this grade?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No grades found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($grades->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $grades->links() }}
            </div>
        @endif
    </div>

    <div class="modal fade" id="gradeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><form id="gradeForm" method="POST" action="{{ route('grades.store') }}" class="modal-content">
            @csrf <input type="hidden" id="gradeMethod" name="_method" value="POST">
            <div class="modal-header"><h5 id="gradeModalTitle" class="modal-title">Add Grade</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><div class="mb-3"><label for="gradeName" class="form-label">Grade Name</label><input id="gradeName" name="GradeName" class="form-control" required maxlength="100"></div><div><label for="gradeAnnualLeaveDays" class="form-label">Annual Leave Days</label><input id="gradeAnnualLeaveDays" name="AnnualLeaveDays" type="number" min="0" class="form-control" required></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Save Grade</button></div>
        </form></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openGradeModal(updateUrl = '', gradeName = '', annualLeaveDays = '') {
    const form = document.getElementById('gradeForm');
    form.action = updateUrl || '{{ route('grades.store') }}';
    document.getElementById('gradeMethod').value = updateUrl ? 'PUT' : 'POST';
    document.getElementById('gradeModalTitle').textContent = updateUrl ? 'Edit Grade' : 'Add Grade';
    document.getElementById('gradeName').value = gradeName;
    document.getElementById('gradeAnnualLeaveDays').value = annualLeaveDays;
}
    (function () {
        const url = new URL(window.location.href);
        if (url.searchParams.has('page')) {
            const section = document.getElementById('grades-table');
            if (section) setTimeout(() => section.scrollIntoView({ behavior: 'smooth', block: 'start' }), 150);
        }
        document.querySelectorAll('.pagination a').forEach(link => {
            const href = link.getAttribute('href');
            if (href && !href.includes('#')) link.setAttribute('href', href + '#grades-table');
        });
    })();
</script>
@endsection
