<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'must_change_password' => 'nullable|boolean',
            'is_admin' => 'nullable|boolean',
            'is_creator' => 'nullable|boolean',
            'is_editor' => 'nullable|boolean',
            'is_approver' => 'nullable|boolean',
            'is_viewer' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'must_change_password' => $request->must_change_password ?? false,
            'is_admin' => $request->is_admin ?? false,
            'is_creator' => $request->is_creator ?? false,
            'is_editor' => $request->is_editor ?? false,
            'is_approver' => $request->is_approver ?? false,
            'is_viewer' => $request->is_viewer ?? false,
        ]);

        // Save the user to the database
        if ($user) {
            return redirect()->back()->with('success', 'User added successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to add user');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'must_change_password' => 'nullable|boolean',
            'is_admin' => 'nullable|boolean',
            'is_creator' => 'nullable|boolean',
            'is_editor' => 'nullable|boolean',
            'is_approver' => 'nullable|boolean',
            'is_viewer' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
            'must_change_password' => $request->must_change_password ?? false,
            'is_admin' => $request->is_admin ?? false,
            'is_creator' => $request->is_creator ?? false,
            'is_editor' => $request->is_editor ?? false,
            'is_approver' => $request->is_approver ?? false,
            'is_viewer' => $request->is_viewer ?? false,
        ]);

        if ($user) {
            return redirect()->back()->with('success', 'User updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update user');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
