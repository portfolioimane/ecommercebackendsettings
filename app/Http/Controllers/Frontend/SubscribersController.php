<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscribersController extends Controller
{
    /**
     * Display a listing of the subscribers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Fetch all subscribers from the database
            $subscribers = Subscriber::all();

            // Return the subscribers as a JSON response
            return response()->json($subscribers, 200);
        } catch (\Exception $e) {
            // Handle errors and return an appropriate message
            return response()->json(['error' => 'Failed to retrieve subscribers'], 500);
        }
    }

    
}
