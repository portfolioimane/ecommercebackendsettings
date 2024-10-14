<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Make sure to import the Log facade


class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'review' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'review' => $request->review,
            'rating' => $request->rating,
            'status' => 'pending', // Change to 'approved' if you want instant visibility
        ]);

        return response()->json($review, 201);
    }


public function index($productId)
{
    // Log the product ID being queried
    Log::info('Fetching reviews for product ID: ' . $productId);

    // Fetch approved reviews for the product and eager-load the user relationship
    $reviews = Review::where('product_id', $productId)
        ->where('status', 'approved')
        ->with('user')  // Eager load the user
        ->get();

    // Log the number of reviews found
    Log::info('Number of reviews found: ' . $reviews->count());

    // Log the reviews data for debugging
    Log::info('Reviews data:', $reviews->toArray());

    return response()->json($reviews);
}


    // Method to fetch featured reviews
  public function getFeaturedReviews()
{
    // Fetch the latest three reviews where is_featured is true and eager load the associated user
    $featuredReviews = Review::where('is_featured', true)
                             ->with('user') // Eager load the user relation
                             ->latest() // Order by created_at descending
                             ->take(3) // Limit to the latest 3 reviews
                             ->get();

    // Return the featured reviews with user details as JSON response
    return response()->json($featuredReviews);
}





}
