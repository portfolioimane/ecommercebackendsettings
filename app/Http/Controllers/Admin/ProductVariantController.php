<?php
// app/Http/Controllers/Admin/ProductVariantController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index()
    {
        // Fetch all products with their variants
        $products = Product::with('variants')->get();
        
        // Return a JSON response
        return response()->json($products);
    }


public function show($id)
{
    // Find the variant by ID with the associated product
    $variant = ProductVariant::with('product')->find($id); // Assuming 'product' is the relationship name

    // Check if the variant exists
    if (!$variant) {
        return response()->json(['message' => 'Variant not found.'], 404);
    }

    // Return the variant with the associated product
    return response()->json($variant);
}


    public function destroy($id)
    {
        // Find the variant by ID
        $variant = ProductVariant::find($id);

        // Check if the variant exists
        if (!$variant) {
            return response()->json(['message' => 'Variant not found.'], 404);
        }

        // Delete the variant
        $variant->delete();

        // Respond with a success message
        return response()->json(['message' => 'Variant deleted successfully.'], 200);
    }


    public function update(Request $request, $id)
{
    // Validate incoming request
    $request->validate([
        'color' => 'required|string',
        'size' => 'required|string',
        'price_adjustment' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for image
    ]);

    // Find the variant by ID
    $variant = ProductVariant::find($id);
    if (!$variant) {
        return response()->json(['message' => 'Variant not found.'], 404);
    }

    // Update variant details
    $variant->color = $request->input('color');
    $variant->size = $request->input('size');
    $variant->price_adjustment = $request->input('price_adjustment');

    // Handle image upload if a new image is provided
    if ($request->hasFile('image')) {
        // Delete old image if necessary
        // $this->deleteOldImage($variant->image); // Implement this function based on your logic
        $path = $request->file('image')->store('variant_images', 'public'); // Store the new image
        $variant->image_url = $path;
    }

    // Save updated variant
    $variant->save();

    return response()->json(['message' => 'Variant updated successfully.'], 200);
}

// app/Http/Controllers/Admin/ProductVariantController.php

public function create(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'color' => 'required|string',
        'size' => 'required|string',
        'price_adjustment' => 'required|numeric',
        'product_id' => 'required|exists:products,id', // Ensure the product exists
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for image
    ]);

    // Create a new product variant
    $variant = new ProductVariant();
    $variant->color = $request->input('color');
    $variant->size = $request->input('size');
    $variant->price_adjustment = $request->input('price_adjustment');
    $variant->product_id = $request->input('product_id'); // Link variant to the selected product

    // Handle image upload if provided
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('variant_images', 'public'); // Store the image
        $variant->image_url = $path;
    }

    // Save the new variant
    $variant->save();

    // Respond with a success message
    return response()->json(['message' => 'Variant created successfully.', 'variant' => $variant], 201);
}



}
