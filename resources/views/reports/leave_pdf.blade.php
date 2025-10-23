<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align: center; font-weight: bold; letter-spacing: 1px;">
  ABC MISSION HOSPITAL LEAVE REPORT SUMMARY
</h2>


    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Department</th>
                <th>Leave Type</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Days</th>
                <th>Submitted</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaveRequests as $request)
            <tr>
                <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>

                <td>{{ $request->employee->department->DepartmentName ?? 'N/A' }}</td>
                <td>{{ $request->leaveType->LeaveTypeName }}</td>
                <td>{{ $request->Reason }}</td>
                <td>{{ $request->RequestStatus }}</td>
                <td>{{ $request->TotalDays }}</td>
                <td>{{ $request->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
