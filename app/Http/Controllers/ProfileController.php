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
            $user->name = $request->name;
            $user->email = $request->email;
        }

        
        // Handle Profile Photo Upload
        try {
            if ($request->hasFile('profile_photo')) {
                $uploadedFile = $request->file('profile_photo');
                $destinationPath = 'external_photos'; // ✅ Store inside `public/external_photos`
                $fileName = time() . '_' . $uploadedFile->getClientOriginalName();

                $publicPath = public_path($destinationPath);
                // Ensure the directory exists
                if (!file_exists($publicPath)) {
                    if (!mkdir($publicPath, 0777, true)) {
                        \Log::error("Failed to create directory: {$publicPath}");
                        return redirect()->back()->with('error', 'Failed to create upload directory.');
                    }
                }

                // Move the uploaded file
                if ($uploadedFile->move($publicPath, $fileName)) {
                    // ✅ Save only the relative file path in the database
                    $user->profile_photo = $destinationPath . '/' . $fileName;
                    \Log::info("Profile photo updated for User #{$user->id}: {$user->profile_photo}");
                } else {
                    \Log::error("Failed to move uploaded file to: {$publicPath}");
                    return redirect()->back()->with('error', 'Failed to save profile photo.');
                }
            }

            $user->save();
            return redirect()->back()->with('success', 'Profile updated successfully.');

        } catch (\Exception $e) {
            \Log::error("Error updating profile for User #{$user->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating your profile.');
        }
    }
}
