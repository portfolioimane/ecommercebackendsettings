<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
public function getUserOrders()
{
    $userId = Auth::id();

    // Fetch orders for the authenticated user with product, product variant, and shipping area details
    $orders = Order::where('user_id', $userId)
        ->with(['items.product', 'items.productVariant', 'shippingArea']) // Include shipping area
        ->get();

    return response()->json([
        'orders' => $orders,
    ]);
}


public function show($id)
{
    // Retrieve the order by ID with product, product variant details, and shipping area
    $order = Order::with(['items.product', 'items.productVariant', 'shippingArea'])
                  ->find($id);

    // Check if the order exists
    if (!$order) {
        return response()->json([
            'message' => 'Order not found.'
        ], 404);
    }

    // Optionally, you might want to check if the user is authorized to view the order
    if ($order->user_id !== Auth::id()) {
        return response()->json([
            'message' => 'Unauthorized access to this order.'
        ], 403);
    }

    return response()->json([
        'order' => $order,
    ], 200);
}


public function store(Request $request)
{
    // Validate incoming request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'total_price' => 'required|numeric',
        'payment_method' => 'required|string',
        'payment_status' => 'required|string|in:paid,pending', // Ensure valid payment status
        'shipping_area_id' => 'sometimes|nullable|exists:shipping_areas,id', // Ensure valid shipping area ID
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.product_variant_id' => 'sometimes|nullable|exists:product_variants,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric',
        'items.*.image' => 'sometimes|string|max:255',
        'discount' => 'sometimes|nullable|numeric|min:0', // Optional discount
    ]);
     
         Log::info('Shipping Area ID:', ['shipping_area_id' => $request->shipping_area_id]);
    // Create the order
    $order = Order::create([
        'user_id' => Auth::id(), // Only if user is logged in
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'total_price' => $request->total_price, // Use the total price sent, which includes the shipping cost
        'discount' => $request->discount ?? 0, // Store discount if provided, otherwise default to 0
        'payment_method' => $request->payment_method,
        'status' => 'pending',
        'shipping_area_id' => $request->shipping_area_id, // Store shipping area ID if provided
    ]);

    // Create order items
    $orderItems = array_map(function ($item) use ($order) {
        return [
            'order_id' => $order->id,
            'product_id' => $item['product_id'],
            'product_variant_id' => $item['product_variant_id'] ?? null,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'image' => $item['image'] ?? null,
        ];
    }, $request->items);

    // Bulk insert order items
    OrderItem::insert($orderItems);

    // Clear the user's cart
    $cart = Cart::where('user_id', Auth::id())->first();
    if ($cart) {
        $cart->items()->delete(); // Delete all cart items
        $cart->delete(); // Optionally delete the cart itself
    }

    // Handle payment status logic
    switch ($request->input('payment_status')) {
        case 'paid':
            // Handle successful payment logic here
            return response()->json(['order' => $order], 201);
        case 'pending':
            // Handle cash on delivery logic here
            return response()->json(['order' => $order], 201);
        default:
            return response()->json(['error' => 'Invalid payment status'], 400);
    }
}


    public function payment(Request $request)
    {
        $request->validate([
            'total_price' => 'required|numeric|min:0',
        ]);

         Stripe::setApiKey(config('services.stripe.secret'));
        $paymentIntent = PaymentIntent::create([
            'amount' => $request->input('total_price') * 100, // Convert to cents
            'currency' => 'mad',
        ]);

        return response()->json(['paymentIntent' => $paymentIntent]);
    }
}