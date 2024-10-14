<?php
// app/Http/Controllers/PromotionController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::where('is_active', true)
            ->where(function($query) {
                $query->where('expires_at', '>', now())
                      ->orWhereNull('expires_at');
            })
            ->orderBy('created_at', 'desc') // Order by creation date
            ->take(2) // Limit to 2 promotions
            ->get();

        return response()->json($promotions);
    }
}
