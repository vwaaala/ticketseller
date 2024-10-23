<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Import Auth
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show()
    {
        $user = Auth::user();  // Get the authenticated user
        return view('profile.show', compact('user'));
    }

    /**
     * Display the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();  // Get the authenticated user
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();  // Get the authenticated user

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user details
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}
