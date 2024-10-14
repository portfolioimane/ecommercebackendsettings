<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    // Fetch all orders with user and items relationships
   public function index()
{
    // Fetch all orders with related user, items, products, and product variants
    $orders = Order::with(['user', 'items.product', 'items.productVariant', 'shippingArea'])->get();

    return response()->json($orders);
}

// Show a specific order with user and items relationships, including shipping area
public function show($id)
{
    // Fetch the order by ID with related user, items, products, product variants, and shipping area
    $order = Order::with(['user', 'items.product', 'items.productVariant', 'shippingArea'])->findOrFail($id);

    return response()->json($order);
}


    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validate the incoming request
        $validated = $request->validate([
            'status' => 'sometimes|required|string',
            'total_price' => 'sometimes|required|numeric',
            'payment_method' => 'sometimes|required|string',
        ]);

        // Update the order with validated data
        $order->update($validated);
        return response()->json($order, 200);
    }

    // Delete an order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

    // Optional: Create a new order
   
}
