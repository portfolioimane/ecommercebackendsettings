<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function applyCoupon(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Retrieve the coupon from the database
        $coupon = Coupon::where('code', $request->input('coupon_code'))
            ->where('expiration_date', '>', now()) // Ensure the coupon is not expired
            ->first();

        if (!$coupon) {
            return response()->json(['message' => 'Invalid or expired coupon'], 404);
        }

        // Check if the user has already used this coupon
        if ($user->coupons()->where('coupon_id', $coupon->id)->exists()) {
            return response()->json(['message' => 'Coupon already used'], 403);
        }

        // Attach the coupon to the user
        $user->coupons()->attach($coupon->id);

        return response()->json([
            'message' => 'Coupon applied successfully',
            'discount' => $coupon->discount,
            'discount_type' => $coupon->discount_type,
        ]);
    }
}
