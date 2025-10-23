<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LeaveRequest;

class ReportController extends Controller
{
    public function generatePDF()
    {
        $leaveRequests = LeaveRequest::with(['employee.department', 'leaveType'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('reports.leave_pdf', compact('leaveRequests'));
        return $pdf->download('leave_report.pdf');
    }
}
