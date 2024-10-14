<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\HomepageHeader; // Use your HomepageHeader model

class HomeController extends Controller
{
    public function index()
    {
        // Get featured products or any specific logic for the homepage
        $products = Product::with('category')->take(10)->get(); // Adjust the limit as needed
        return response()->json($products);
    }


    public function getHeader()
    {
        // Fetch the first homepage header record
        $header = HomepageHeader::first();

        // You can customize the response structure as needed
        if ($header) {
            return response()->json($header);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Header not found.',
            ], 404);
        }
    }

}
