<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Retrieve the authenticated user
    public function getAuthenticatedUser()
    {
        return response()->json(Auth::user());
    }

    // Update the authenticated user's profile
    public function updateUser(Request $request)
    {
        $user = Auth::user();

        // Validate the incoming request
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6|confirmed',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate avatar
        ]);

        // Update user information
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password); // Hash the password
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete the old avatar if it exists
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // Store the new avatar
            $path = $request->file('avatar')->store('avatars', 'public'); // Store in the public disk
            $user->avatar = $path; // Save the path to the database
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
