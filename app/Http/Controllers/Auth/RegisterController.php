<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration of a new user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'EmployeeNumber' => 'required|string|max:255|unique:users',
            'email'          => 'required|email|max:255',
            'password'       => 'required|string|min:8|confirmed',
            'gender'         => 'required|string|in:Male,Female',
            'profile_photo'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $this->create($request->all());
        auth()->login($user);

        return redirect()->route('dashboards.employee')
                         ->with('success', 'Registration successful! Welcome to your dashboard.');
    }

    /**
     * Create a new user record in the 'users' table.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Handle profile photo upload if provided
        $photoPath = null;
        if (isset($data['profile_photo']) && $data['profile_photo']) {
            $file = $data['profile_photo'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profile_photos', $filename, 'public');
            $photoPath = $filename;
        }

        return User::create([
            'name'           => $data['name'],
            'EmployeeNumber' => $data['EmployeeNumber'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'gender'         => $data['gender'],
            'role_id'        => 3,
            'profile_photo'  => $photoPath,
        ]);
    }
}
