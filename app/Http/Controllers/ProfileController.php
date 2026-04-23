<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class ProfileController extends Controller
{
    /**
     * Display the user profile.
     */
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->role_id === 1;

        $rules = [
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ];

        if ($isAdmin) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $request->validate($rules);

        if ($isAdmin) {
            // Assuming name is full name, split into FirstName and LastName
            $nameParts = explode(' ', $request->name, 2);
            $user->FirstName = $nameParts[0];
            $user->LastName = $nameParts[1] ?? '';
            $user->email = $request->email;
        }

        
        // Handle Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            $uploadedFile = $request->file('profile_photo');
            $destinationPath = 'external_photos'; // ✅ Store inside `public/external_photos`
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();

            // Ensure the directory exists
            if (!file_exists(public_path($destinationPath))) {
                mkdir(public_path($destinationPath), 0777, true);
            }

            // Move the uploaded file to the correct directory
            $uploadedFile->move(public_path($destinationPath), $fileName);

            // ✅ Save only the relative file path in the database
            $user->profile_photo = $destinationPath . '/' . $fileName;
        }

        $user->save();
        

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
