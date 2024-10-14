<?php
// app/Http/Controllers/LogoController.php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoController extends Controller
{
    public function index()
    {
        $logo = Logo::first();
        return response()->json($logo);
    }

    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image
        ]);

        // Delete the old logo if it exists
        $logo = Logo::first();
        if ($logo) {
            Storage::delete($logo->image_path); // Remove the old logo from storage
            $logo->delete(); // Remove old logo record from database
        }

        // Store the new logo
        $path = $request->file('logo')->store('logos', 'public');
        $newLogo = Logo::create(['image_path' => $path]);

        return response()->json($newLogo, 201);
    }
}
