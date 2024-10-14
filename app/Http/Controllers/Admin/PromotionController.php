<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    // Get all promotions
    public function index()
    {
        $promotions = Promotion::all();
        return response()->json($promotions);
    }

    // Create a new promotion
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expires_at' => 'nullable|date|after:today', // Ensure the date is after today
        ]);

        // Store the promotion
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promotions', 'public');
        }

        $promotion = Promotion::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => isset($path) ? $path : null,
            'expires_at' => $request->expires_at,
        ]);

        return response()->json($promotion, 201);
    }

    // Get a single promotion
    public function show($id)
    {
        $promotion = Promotion::findOrFail($id);
        return response()->json($promotion);
    }

    // Update a promotion
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expires_at' => 'nullable|date|after:today', // Ensure the date is after today
        ]);

        if ($request->hasFile('image')) {
            // Delete the old image
            if ($promotion->image) {
                Storage::delete($promotion->image);
            }
            $path = $request->file('image')->store('promotions', 'public');
            $promotion->image = $path; // Update image path
        }

        $promotion->update($request->only('title', 'description', 'expires_at'));

        return response()->json($promotion);
    }

    // Toggle promotion status
    public function toggleStatus($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->is_active = !$promotion->is_active; // Toggle status
        $promotion->save();

        return response()->json($promotion);
    }

    // Delete a promotion
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);

        // Delete the image from storage
        if ($promotion->image) {
            Storage::delete($promotion->image);
        }

        $promotion->delete();

        return response()->json(['message' => 'Promotion deleted successfully.']);
    }
}
