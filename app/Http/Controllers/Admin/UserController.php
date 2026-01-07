<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Show the specified user
     */
    public function show(User $user)
    {
        $user->load(['orders', 'reviews']);
        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Update user role
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,customer,owner',
        ]);

        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot change your own role');
        }

        $user->update($validated);

        return redirect()->back()
            ->with('success', 'User role updated successfully');
    }

    /**
     * Deactivate user
     */
    public function deactivate(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account');
        }

        $user->delete();

        return redirect()->back()
            ->with('success', 'User deactivated successfully');
    }
}
