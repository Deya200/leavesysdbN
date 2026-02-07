<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Employee;
use App\Models\LeaveRequest;

$supervisor = Employee::where('FirstName', 'Lucious')->first();
$subordinate = Employee::where('FirstName', 'John')->first();
$request = LeaveRequest::where('EmployeeNumber', $subordinate->EmployeeNumber)->first();

echo "Supervisor (Lucious): Name={$supervisor->FirstName}, ID={$supervisor->EmployeeNumber}, Role={$supervisor->role_id}\n";
echo "Subordinate (John): Name={$subordinate->FirstName}, ID={$subordinate->EmployeeNumber}, SupervisorID={$subordinate->SupervisorID}\n";

if ($request) {
    echo "Leave Request: ID={$request->LeaveRequestID}, Status='{$request->RequestStatus}'\n";
    echo "String Length of Status: " . strlen($request->RequestStatus) . "\n";
    echo "Comparison with 'Pending Supervisor Approval': " . strcasecmp($request->RequestStatus, 'Pending Supervisor Approval') . "\n";
} else {
    echo "No leave request found for John.\n";
}
