<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Get wishlist items for the authenticated user
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('product') // eager loading
            ->get();
        
        return response()->json($wishlistItems); // Return JSON response
    }

    // Add a product to the user's wishlist
    public function store($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404); // Return 404 for not found
        }

        // Check if the product is already in the user's wishlist
        $existingWishlistItem = Wishlist::where('user_id', Auth::id())
                                         ->where('product_id', $productId)
                                         ->first();

        if ($existingWishlistItem) {
            return response()->json(['error' => 'Product is already in your wishlist.'], 409); // Return 409 for conflict
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
        ]);

        return response()->json(['success' => 'Product added to wishlist.'], 201); // Return 201 for created
    }

    // Remove a product from the user's wishlist
    public function destroy($productId)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())
                                 ->where('product_id', $productId)
                                 ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return response()->json(['success' => 'Product removed from wishlist.']); // Return success message
        }

        return response()->json(['error' => 'Product not found in wishlist.'], 404); // Return 404 if not found
    }
}
