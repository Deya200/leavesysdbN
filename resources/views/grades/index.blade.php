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
        <a href="{{ route('grades.create') }}" class="btn btn-success">Add New Grade</a>
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
                                    <a href="{{ route('grades.edit', $grade->GradeID) }}" class="btn btn-edit">Edit</a>
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
</div>
@endsection

@section('scripts')
<script>
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
