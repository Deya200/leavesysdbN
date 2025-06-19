@extends('layouts.app')

@section('content')
    <h2>Employee Details: {{ $employee->FirstName }} {{ $employee->LastName }}</h2>
    <ul>
        <li><strong>Employee Number:</strong> {{ $employee->EmployeeNumber }}</li>
        <li><strong>Department:</strong> {{ $employee->department->DepartmentName ?? '-' }}</li>
        <li><strong>Grade:</strong> {{ $employee->grade->GradeName ?? '-' }}</li>
        <li><strong>Position:</strong> {{ $employee->position->PositionName ?? '-' }}</li>
        <li><strong>Gender:</strong> {{ $employee->Gender }}</li>
        <li><strong>Date of Birth:</strong> {{ $employee->DateOfBirth }}</li>
    </ul>
    <a href="{{ route('employees.edit', $employee->EmployeeNumber) }}">Edit</a>
@endsection
