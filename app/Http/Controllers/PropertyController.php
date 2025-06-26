<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{   
    public function index()
    {
        $properties = Property::all();

        return inertia('Properties', [
            'properties' => $properties,
        ]);
    }

    public function create()
    {
        return inertia('Upload');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price_range' => 'required|string|max:255',
            'area' => 'required|numeric',
            'address' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'purok' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|in:available,pending,sold',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        if (!$user || $user->role !== 'Seller') {
            abort(403, 'Only sellers can upload properties.');
        }

        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('property_images', 'public');
            $imagePaths[] = $path;
        }

        Property::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price_range' => $validated['price_range'],
            'area' => $validated['area'],
            'address' => $validated['address'],
            'street' => $validated['street'],
            'purok' => $validated['purok'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'country' => $validated['country'],
            'postal_code' => $validated['postal_code'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'status' => $validated['status'],
            'images' => json_encode($imagePaths)
        ]);

        return redirect()->route('properties')->with('success', 'Property created successfully!');
    }

    public function show(Property $property)
    {
        return inertia('Show', [
            'property' => $property,
            'images' => json_decode($property->images, true)
        ]);
    }

    public function map()
    {
        return response()->json(
            Property::select('id', 'title', 'address', 'latitude', 'longitude')->get()
        );
    }
}
