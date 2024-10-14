<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // Import the Rule class
use Illuminate\Support\Facades\Log; // Import the Log facade

class ProfileController extends Controller
{
    // Fetch the authenticated user's profile
    public function getProfile()
    {
        return response()->json(Auth::user());
    }

    // Update the authenticated user's profile
    public function updateUser(Request $request)
    {
        // Log the incoming request data
        Log::info('Updating user profile', [
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
        ]);

        // Get the authenticated user instance
        $user = Auth::user();

        // Validate the incoming request
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Remain nullable
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
            // Log the avatar upload
            Log::info('Avatar upload detected for user', [
                'user_id' => $user->id,
            ]);

            // Delete the old avatar if it exists
            if ($user->avatar) {
                Storage::delete($user->avatar);
                Log::info('Deleted old avatar', ['user_id' => $user->id, 'avatar' => $user->avatar]);
            }

            // Store the new avatar
            $path = $request->file('avatar')->store('avatars', 'public'); // Store in the public disk
            $user->avatar = $path; // Save the path to the database
            Log::info('New avatar stored', ['user_id' => $user->id, 'path' => $path]);
        }

        $user->save(); // Save the updated user data

        Log::info('User profile updated successfully', ['user_id' => $user->id]);

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
