<?php
// app/Http/Controllers/Admin/ReviewController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    // List all reviews with user and product info
    public function index()
    {
        try {
            // Log a message before fetching the reviews
            Log::info('Fetching reviews from the database.');

            // Fetch reviews along with their associated user and product data
            $reviews = Review::with('user', 'product')->get();

            // Log the number of reviews fetched
            Log::info('Number of reviews fetched: ' . $reviews->count());

            // Return the data as a JSON response
            return response()->json($reviews, 200);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error fetching reviews: ' . $e->getMessage());

            // Return a JSON error response
            return response()->json(['error' => 'Failed to fetch reviews'], 500);
        }
    }

public function store(Request $request)
{
    try {
        // Validate the incoming data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'status' => 'in:pending,approved,rejected',
            'is_featured' => 'boolean',
        ]);

        // Create the review with the validated data
        $review = Review::create($validated);

        // Log the creation action
        Log::info("A new review has been created with ID {$review->id}.", ['data' => $validated]);

        return response()->json($review, 201);
    } catch (ValidationException $e) {
        Log::error('Validation error creating review:', $e->errors());
        return response()->json(['error' => $e->errors()], 422);
    } catch (\Exception $e) {
        Log::error('Error creating a review: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to create review'], 500);
    }
}


    public function update(Request $request, $id)
    {
        try {
            $review = Review::findOrFail($id);

            // Validate the incoming data (including status)
            $validated = $request->validate([
                'rating' => 'sometimes|required|integer|min:1|max:5',
                'review' => 'sometimes|nullable|string',
                'status' => 'sometimes|in:pending,approved,rejected', // Excluding 'featured' here for clarity
            ]);

            // Update the review with the validated data
            $review->update($validated);

            // Log the update action
            Log::info("Review with ID {$id} has been updated.", ['data' => $validated]);

            return response()->json($review, 200);
        } catch (\Exception $e) {
            // Log the error message
            Log::error("Error updating review with ID {$id}: " . $e->getMessage());

            // Return a JSON error response
            return response()->json(['error' => 'Failed to update review'], 500);
        }
    }

    // Update the featured status of a review
    public function feature(Request $request, $id)
    {
        try {
            $review = Review::findOrFail($id);

            // Validate the incoming data for featuring a review
            $validated = $request->validate([
                'is_featured' => 'required|boolean', // Ensure it's a boolean value
            ]);

            // Update the review's featured status
            $review->update(['is_featured' => $validated['is_featured']]);

            // Log the feature action
            Log::info("Review with ID {$id} has been updated to featured: " . $validated['is_featured']);

            return response()->json($review, 200);
        } catch (\Exception $e) {
            // Log the error message
            Log::error("Error updating featured status for review with ID {$id}: " . $e->getMessage());

            // Return a JSON error response
            return response()->json(['error' => 'Failed to update featured status'], 500);
        }
    }

    // Delete a review
    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            // Log the delete action
            Log::info("Review with ID {$id} has been deleted.");

            return response()->json(null, 204);
        } catch (\Exception $e) {
            // Log the error message
            Log::error("Error deleting review with ID {$id}: " . $e->getMessage());

            // Return a JSON error response
            return response()->json(['error' => 'Failed to delete review'], 500);
        }
    }
}
