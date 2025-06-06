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
        // Validate the input data
        $validatedData = $this->validator($request->all())->validate();

        // Create a new user record in the 'users' table
        $user = $this->create($validatedData);

        // Log in the newly created user
        auth()->login($user);

        // Redirect to the Employee Dashboard with a success message
        return redirect()->route('dashboards.employee')
                         ->with('success', 'Registration successful! Welcome to your dashboard.');
    }

    /**
     * Validate the registration input data.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'           => ['required', 'string', 'max:255'],
            'EmployeeNumber' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    // Ensure the EmployeeNumber exists in the 'employees' table
                    if (! Employee::where('EmployeeNumber', $value)->exists()) {
                        $fail('The selected Employee Number does not exist in our records.');
                    }
                },
            ],
            'email'          => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
            'gender'         => ['required', 'string', 'in:Male,Female'], // ✅ Gender validation is now properly enforced
            'profile_photo'  => ['nullable', 'image', 'max:2048'],
        ]);
    }

    /**
     * Create a new user record in the 'users' table.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // ✅ Handle profile photo upload if provided
        $photoPath = null;
        if (request()->hasFile('profile_photo')) {
            $file = request()->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profile_photos', $filename, 'public');
            $photoPath = $filename;
        }

        // ✅ Ensure gender is stored correctly as a VARCHAR string
        return User::create([
            'name'           => $data['name'],
            'EmployeeNumber' => $data['EmployeeNumber'],
            'email'          => $data['email'] ?? null,
            'password'       => Hash::make($data['password']),
            'gender'         => $data['gender'], // ✅ Now correctly storing "Male" or "Female"
            'role_id'        => 3, // Default to Employee role (role_id=3)
            'profile_photo'  => $photoPath,
        ]);
    }
}
