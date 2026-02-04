<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
{
    $users = User::with(['role', 'employee'])->orderBy('created_at', 'desc')->get();

    // Summary statistics
    $totalUsers    = User::count();
    $activeUsers   = User::where('active', true)->count();
    $inactiveUsers = User::where('active', false)->count();
    $adminUsers    = User::where('role_id', 1)->count();

    return view('user_management', compact(
        'users',
        'totalUsers',
        'activeUsers',
        'inactiveUsers',
        'adminUsers'
    ));
}

    /**
     * Show the edit form for a specific user.
     */
    public function edit($id)
    {
        $user = User::with(['role', 'employee'])->findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update a user's details (from edit form).
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'role_id' => 'required|exists:roles,id',
            'active'  => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Update the role of a user (promote/demote).
     */
    public function updateRole(Request $request, $EmployeeNumber)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::where('EmployeeNumber', $EmployeeNumber)->firstOrFail();
        $user->role_id = $validated['role_id'];
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User role updated successfully!');
    }

    /**
     * Activate or deactivate a user.
     */
    public function toggleStatus($EmployeeNumber)
    {
        $user = User::where('EmployeeNumber', $EmployeeNumber)->firstOrFail();
        $user->active = !$user->active;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User status updated successfully!');
    }
}
