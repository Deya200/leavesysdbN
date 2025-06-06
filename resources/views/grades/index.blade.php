@extends('layouts.app')

@section('title', 'Manage Grades')

@section('header')

    <!-- Search Form -->
    <form action="{{ route('grades.index') }}" method="GET" class="mb-4" style="width:75%;">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Grade" value="{{ $search ?? '' }}" >
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

@endsection

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Manage Grades</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Add New Grade Button -->
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('grades.create') }}" class="btn btn-success">Add New Grade</a>
    </div>

    <!-- Grades Table -->
    @if ($grades->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-primary text-center">
                    <tr>
                        <th id="main-table" style="border: none;">#</th>
                        <th id="main-table" style="border: none;">Grade Name</th>
                        <th id="main-table" style="border: none;">Annual Leave Days</th>
                        <th id="main-table" style="border: none;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grades as $grade)
                        <tr>
                            <td style="border: none;">{{ $loop->iteration }}</td>
                            <td style="border: none;">{{ $grade->GradeName }}</td>
                            <td style="border: none;">{{ $grade->AnnualLeaveDays }}</td>
                            <td style="border: none;">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('grades.edit', $grade->GradeID) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('grades.destroy', $grade->GradeID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this grade?');">
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
    @else
        <div class="alert alert-info text-center">No grades found. Click "Add New Grade" above to create one.</div>
    @endif
</div>
@endsection
