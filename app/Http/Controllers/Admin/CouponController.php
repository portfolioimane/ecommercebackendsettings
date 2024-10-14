<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon; // Make sure the Coupon model is imported

class CouponController extends Controller
{
    // Get a list of all coupons
    public function index()
    {
        $coupons = Coupon::all();
        return response()->json($coupons);
    }

    // Create a new coupon
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons|max:255',
            'discount' => 'required|numeric',
            'discount_type' => 'required|in:fixed,percentage',
            'expiration_date' => 'required|date_format:Y-m-d H:i:s', // Update validation for dateTime
        ]);

        $coupon = Coupon::create($request->all());
        return response()->json($coupon, 201);
    }

    // Get a specific coupon by ID
    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);
        return response()->json($coupon);
    }

    // Update a specific coupon
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'sometimes|string|unique:coupons,code,' . $coupon->id . '|max:255',
            'discount' => 'sometimes|numeric',
            'discount_type' => 'sometimes|in:fixed,percentage',
            'expiration_date' => 'sometimes|date_format:Y-m-d H:i:s', // Update validation for dateTime
        ]);

        $coupon->update($request->all());
        return response()->json($coupon);
    }

    // Delete a specific coupon
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return response()->json(null, 204);
    }
}
