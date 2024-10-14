<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Subscriber;
use App\Models\Contact;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch total counts
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalReviews = Review::count();
        $totalSubscribers = Subscriber::count();
        $totalContactMessages = Contact::count();

        // Fetch data for charts
        $ordersOverTime = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Ensure that all 12 months are represented, defaulting to zero if there are no orders for a month
        $ordersData = [];
        for ($i = 1; $i <= 12; $i++) {
            $ordersData[$i] = 0; // Default to 0
        }
        foreach ($ordersOverTime as $order) {
            $ordersData[$order->month] = $order->total;
        }

        // Prepare response data
        $data = [
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'totalReviews' => $totalReviews,
            'totalSubscribers' => $totalSubscribers,
            'totalContactMessages' => $totalContactMessages,
            'ordersOverTime' => array_values($ordersData), // Get values to send to the frontend
        ];

        return response()->json($data);
    }
}
