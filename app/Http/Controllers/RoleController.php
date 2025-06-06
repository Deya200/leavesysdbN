<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    /**
     * Display all roles.
     */
    public function index()
    {
        return response()->json(['roles' => Role::all()], 200);
    }

    /**
     * Store a new role.
     */
    public function store(Request $request)
    {
        // Validate request with better error messages
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $role = Role::create(['name' => $request->name]);
            return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create role'], 500);
        }
    }

    /**
     * Show a specific role.
     */
    public function show($id)
    {
        try {
            $role = Role::findOrFail($id);
            return response()->json(['role' => $role], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        }
    }

    /**
     * Update a role.
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:roles,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $role = Role::findOrFail($id);
            $role->update(['name' => $request->name]);

            return response()->json(['message' => 'Role updated successfully', 'role' => $role], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update role'], 500);
        }
    }

    /**
     * Delete a role.
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return response()->json(['message' => 'Role deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete role'], 500);
        }
    }
}
