<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingArea;
use Illuminate\Http\Request;

class ShippingAreaController extends Controller
{
    public function index()
    {
        $shippingAreas = ShippingArea::all();
        return response()->json($shippingAreas, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric',
            'delivery_time' => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        $shippingArea = ShippingArea::create($validatedData);

        return response()->json($shippingArea, 201);
    }

    public function show($id)
    {
        $shippingArea = ShippingArea::findOrFail($id);
        return response()->json($shippingArea, 200);
    }

    public function update(Request $request, $id)
    {
        $shippingArea = ShippingArea::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric',
            'delivery_time' => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        $shippingArea->update($validatedData);

        return response()->json($shippingArea, 200);
    }

    public function destroy($id)
    {
        $shippingArea = ShippingArea::findOrFail($id);
        $shippingArea->delete();

        return response()->json(null, 204);
    }

    public function toggleStatus(Request $request, $id)
    {
        $shippingArea = ShippingArea::findOrFail($id);
        $shippingArea->active = !$shippingArea->active; // Toggle the status
        $shippingArea->save();

        return response()->json($shippingArea, 200);
    }
}
