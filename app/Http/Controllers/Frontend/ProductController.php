<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of products with their categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::with('category', 'variants')->get(); // Return all products with their categories and variants

        return response()->json($products);
    }

    /**
     * Display the specified product with its variants and their images.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        // Retrieve the product along with its variants and their images
        $product = Product::with(['category', 'variants'])->findOrFail($id);
        
        return response()->json($product);
    }
}

