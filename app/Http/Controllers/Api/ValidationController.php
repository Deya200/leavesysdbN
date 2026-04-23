<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

use App\Services\EmailVerificationService;

class ValidationController extends Controller
{
    protected EmailVerificationService $emailVerification;

    public function __construct(EmailVerificationService $emailVerification)
    {
        $this->emailVerification = $emailVerification;
    }

    /**
     * Check if an email is available (not taken by another employee) and verify deliverability.
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'ignore_employee_number' => 'nullable|string'
        ]);

        $query = Employee::where('email', $request->email);

        if ($request->ignore_employee_number) {
            $query->where('EmployeeNumber', '!=', $request->ignore_employee_number);
        }

        $exists = $query->exists();

        $verification = $this->emailVerification->verify($request->email);

        return response()->json([
            'available' => !$exists,
            'message' => $exists
                ? 'This email is valid but already assigned to another individual.'
                : 'Email is available.',
            'is_valid' => $verification['valid'],
            'domain_ok' => $verification['domain_ok'],
            'has_mx' => $verification['has_mx'],
            'smtp_ok' => $verification['smtp_ok'],
            'verification_reason' => $verification['reason'],
        ]);
    }
}
