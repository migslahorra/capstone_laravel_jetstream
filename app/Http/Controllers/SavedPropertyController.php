<?php

namespace App\Http\Controllers;

use App\Models\SavedProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SavedPropertyController extends Controller
{
    public function index()
    {
        $savedProperties = SavedProperty::with('property')
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($saved) {
                return $saved->property;
            })
            ->filter()
            ->values();

        return Inertia::render('Saved', [
            'savedProperties' => $savedProperties,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $saved = SavedProperty::firstOrCreate([
            'user_id' => Auth::id(),
            'property_id' => $request->property_id,
        ]);

        return response()->json([
            'message' => 'Property saved successfully',
            'saved' => $saved,
        ]);
    }

    public function destroy($propertyId)
    {
        $saved = SavedProperty::where('user_id', Auth::id())
            ->where('property_id', $propertyId)
            ->first();

        if ($saved) {
            $saved->delete();
        }

        // ✅ Inertia expects a redirect or Inertia response, not plain JSON
        return redirect()->back()->with('success', 'Saved property removed');
    }

    public function ids()
    {
        $ids = SavedProperty::where('user_id', Auth::id())->pluck('property_id');

        return response()->json([
            'savedIds' => $ids,
        ]);
    }
}
