<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\Role;

class UserController extends Controller
{
    // /
    //  * Display a listing of users.
    //  *
    //  * @return \Illuminate\View\View
    //  */
    public function index()
    {
        $users = User::with('employee')->orderBy('created_at', 'desc')->get();
        return view('user_management', compact('users'));
    }
    
    // /
    //  * Show the form for creating a new user.
    //  *
    //  * @return \Illuminate\View\View
    //  */
    public function create()
    {
        return view('user.create');
    }

    // /
    //  * Store a newly created user in the database.
    //  *
    //  * @param \Illuminate\Http\Request $request
    //  * @return \Illuminate\Http\RedirectResponse
    //  */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'EmployeeNumber' => 'nullable|string|unique:users,EmployeeNumber',
        ]);

        // Generate a random password for new users
        $password = Str::random(12);
        $validatedData['password'] = bcrypt($password);
        $validatedData['is_active'] = true;

        $user = User::create($validatedData);

        // Send invitation link
        try {
            // Since Employee is the auth model, and User shares EmployeeNumber,
            // we should send the notification to the Employee model if possible,
            // or just use the Password broker which is configured for Employee.
            $token = \Illuminate\Support\Facades\Password::getRepository()->create($user);
            $user->sendPasswordResetNotification($token);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send invitation to new user: " . $e->getMessage());
        }

        session()->flash('success', 'User has been successfully added! An invitation has been sent to their email.');
        return redirect()->route('users.index');
    }

    // /
    //  * Show the form for editing an existing user.
    //  *
    //  * @param int $id
    //  * @return \Illuminate\View\View
    //  */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function toggleStatus($id)
{
    $user = User::findOrFail($id);
    $user->update([
        'is_active' => !$user->is_active, // Toggle status
    ]);

    return redirect()->route('users.index')->with('success', 'User status updated.');
}


    // /
    //  * Update a user's information in the database.
    //  *
    //  * @param \Illuminate\Http\Request $request
    //  * @param int $id
    //  * @return \Illuminate\Http\RedirectResponse
    //  */
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id . ',EmployeeNumber',
        'role_id' => 'required|exists:roles,id',
        'is_active' => 'required|boolean',
    ]);

    $user = User::findOrFail($id);
    $user->update($request->only('name', 'email', 'role_id', 'is_active'));

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}

    
    // /
    //  * Remove a user from the database.
    //  *
    //  * @param int $id
    //  * @return \Illuminate\Http\RedirectResponse
    //  */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        session()->flash('success', 'User ' . $user->name . ' has been successfully deleted!');
        return redirect()->route('users.index');
    }
}