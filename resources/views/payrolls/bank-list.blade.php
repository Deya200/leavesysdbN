@extends('layouts.app')

@section('title', 'Bank List')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">Bank List</h4>
            <p class="text-muted mb-0">Bank extract from payroll master data showing account details and take-home pay.</p>
        </div>
        <a href="{{ route('payrolls.index') }}" class="btn btn-outline-secondary">Back to Payroll</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee Name</th>
                        <th>Position</th>
                        <th>Take Home</th>
                        <th>Bank Name</th>
                        <th>Account Number</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        @php $latestPayroll = $employee->payrolls->first(); @endphp
                        <tr>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->position->PositionName ?? '-' }}</td>
                            <td class="fw-bold">{{ $latestPayroll ? number_format((float) $latestPayroll->NetPay, 2) : '-' }}</td>
                            <td>{{ $employee->BankName ?: '-' }}</td>
                            <td>{{ $employee->BankAccountNumber ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No bank list entries found.</td>
                        </tr>
                    @endforelse
                    @if($employees->count() > 0)
                        <tr class="table-light fw-bold">
                            <td>TOTALS</td>
                            <td></td>
                            <td>{{ number_format((float) $totals['NetPay'], 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $employees->links() }}
    </div>
</div>
@endsection
