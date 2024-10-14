<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\Product; // Import Product model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\DB; // Import DB facade

class CartController extends Controller
{
    // Get the cart items for the logged-in user
    public function index()
{
    $userId = Auth::id();
    Log::info("User ID: $userId fetching cart items."); // Log user ID

    // Eager load both product and productVariant information
    $cart = Cart::where('user_id', $userId)
        ->with(['items.product', 'items.productVariant']) // Eager load product and productVariant
        ->first();

    Log::info("Cart retrieved: ", [$cart]); // Log the retrieved cart

    return response()->json($cart);
}


    // Add an item to the cart
public function store(Request $request, $id) // Receive product ID from the URL
{
    Log::info("Attempting to add product ID: $id to cart."); // Log the attempt

    // Validate the incoming request
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'product_variant_id' => 'nullable|exists:product_variants,id', // Validate product_variant_id if provided
        'price' => 'required|numeric|min:0', // Ensure price is provided and valid
        'image' => 'required|string', // Ensure image is just a string (image name)
    ]);

    Log::info("Price: $request->price"); // Log cart information

    $userId = Auth::id(); // Get the authenticated user's ID
    Log::info("User ID: $userId."); // Log cart information

    // Create or retrieve the cart for the user
    $cart = Cart::firstOrCreate(['user_id' => $userId]);

    // Check if the item already exists in the cart
    $cartItem = CartItem::where('cart_id', $cart->id)
        ->where('product_id', $id)
        ->where('product_variant_id', $request->product_variant_id) // Check for variant
        ->first();

    if ($cartItem) {
        // If the item already exists, update the quantity
        $cartItem->quantity += $request->quantity; // Merge quantities
        $cartItem->save(); // Save the updated cart item
        Log::info("Updated cart item ID: {$cartItem->id} with new quantity: {$cartItem->quantity}");
    } else {
        // If it doesn't exist, create a new cart item
        $product = Product::findOrFail($id); // Fetch the product by ID
        Log::info("Product retrieved: ", [$product]); // Log the product details

        // Create a new CartItem with product or variant information
        $cartItem = new CartItem([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => $request->product_variant_id ?? null, // Use the product_variant_id from the request
            'quantity' => $request->quantity,
            'price' => $request->price, // Use the price from the request
            'image' => $request->image, // Store just the image name
        ]);
        $cartItem->save(); // Save the new cart item
        Log::info("Added new cart item: ", [$cartItem]); // Log the added cart item
    }

    return response()->json($cartItem, 201);
}




    // Remove an item from the cart
    public function destroy($id)
    {
        Log::info("Attempting to remove cart item ID: $id."); // Log the removal attempt

        $cartItem = CartItem::findOrFail($id); // Find cart item by its ID
        $cartItem->delete();

        Log::info("Cart item ID: $id deleted."); // Log the successful deletion

        return response()->json(null, 204);
    }




}