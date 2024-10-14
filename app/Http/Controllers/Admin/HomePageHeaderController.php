<?php
// app/Http/Controllers/HomepageHeaderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HomePageHeaderController extends Controller
{
    public function getHeader()
    {
        // Fetch the first homepage header record
        $header = HomepageHeader::first();

        if ($header) {
            return response()->json($header);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Header not found.',
            ], 404);
        }
    }

    public function update(Request $request)
{
    $request->validate([
        'title' => 'sometimes|string|max:255', // Validate only if present
        'subtitle' => 'sometimes|string|max:255',
        'buttonText' => 'sometimes|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image is nullable for updates
    ]);

    // Log the request data
    Log::debug('Request Data:', $request->all());

    try {
        // Find the existing homepage header record
        $header = HomepageHeader::first();
        if (!$header) {
            return response()->json(['message' => 'Header not found.'], 404);
        }

        // Update only fields that are present in the request
        if ($request->has('title')) {
            $header->title = $request->title;
        }

        if ($request->has('subtitle')) {
            $header->subtitle = $request->subtitle;
        }

        if ($request->has('buttonText')) {
            $header->buttonText = $request->buttonText;
        }

        // If there's a new image, upload it and delete the old one
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($header->image && Storage::disk('public')->exists($header->image)) {
                Storage::disk('public')->delete($header->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('images/homepage_headers', 'public');
            $header->image = $imagePath;
        }

        // Save the updated header
        $header->save();

        return response()->json($header, 200);
    } catch (\Exception $e) {
        // Log the exception
        Log::error('Error updating homepage header:', ['error' => $e->getMessage()]);
        return response()->json(['message' => 'An error occurred while updating the header.'], 500);
    }
}

}
