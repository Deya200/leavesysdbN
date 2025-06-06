<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // Allows images up to 5MB
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;

        
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
        

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
}
