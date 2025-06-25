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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="text-end mb-4">
        <a href="{{ route('positions.create') }}" class="btn btn-success">Add New Position</a>
    </div>

    <div class="card-custom">
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
                            <tr class="position-row" @if($loop->index >= 10) style="display: none;" @endif>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $position->PositionName }}</td>
                                <td>{{ $position->grade->GradeName ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('positions.edit', $position->PositionID) }}" class="btn btn-sm btn-edit">Edit</a>
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

                <div class="text-center mt-3">
                    <button id="seeMoreBtn" class="btn btn-outline-primary btn-sm">See More</button>
                    <button id="seeLessBtn" class="btn btn-outline-secondary btn-sm" style="display: none;">See Less</button>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center m-0">
                No positions found. Click "Add New Position" above to add one.
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('.position-row');
        const seeMoreBtn = document.getElementById('seeMoreBtn');
        const seeLessBtn = document.getElementById('seeLessBtn');

        if (seeMoreBtn && seeLessBtn) {
            rows.forEach((row, index) => {
                row.style.display = index < 10 ? 'table-row' : 'none';
            });

            seeMoreBtn.addEventListener('click', () => {
                rows.forEach(row => row.style.display = 'table-row');
                seeMoreBtn.style.display = 'none';
                seeLessBtn.style.display = 'inline-block';
            });

            seeLessBtn.addEventListener('click', () => {
                rows.forEach((row, index) => {
                    row.style.display = index < 10 ? 'table-row' : 'none';
                });
                seeMoreBtn.style.display = 'inline-block';
                seeLessBtn.style.display = 'none';
            });
        }
    });
</script>
@endsection
