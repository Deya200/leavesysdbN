<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

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
        return view('users.create');
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
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']); // Hash the password

        User::create($validatedData);

        session()->flash('success', 'User has been successfully added!');
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
        return view('users.edit', compact('user'));
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
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|string',
        'is_active' => 'required|boolean',
    ]);

    $user = User::findOrFail($id);
    $user->update($request->only('name', 'email', 'role', 'is_active'));

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