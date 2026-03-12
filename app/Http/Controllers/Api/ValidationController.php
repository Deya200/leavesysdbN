<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class ValidationController extends Controller
{
    /**
     * Check if an email is available (not taken by another employee).
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

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'This email is already in use.' : 'Email is available.'
        ]);
    }
}
