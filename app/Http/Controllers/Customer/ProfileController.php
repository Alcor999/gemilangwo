<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $stats = [
            'orders' => $user->orders()->count(),
            'reviews' => $user->reviews()->count(),
            'wishlists' => $user->wishlists()->count(),
        ];
        
        return view('customer.profile.show', compact('user', 'stats'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('customer.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'wedding_date' => 'nullable|date|after:today',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        $user->update($validated);

        return redirect()
            ->route('customer.profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('avatar')->store('profiles', 'public');
        $user->update(['profile_image' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar berhasil diperbarui!',
            'image_url' => Storage::url($path),
        ]);
    }
}
