<?php

namespace App\Http\Controllers;

use App\Models\SavedProperty;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SavedPropertyController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $saved = SavedProperty::with('property')->where('user_id', auth()->id())->get();
        // Only return the related property objects, filter out nulls
        $properties = $saved->map(function ($sp) {
            return $sp->property;
        })->filter()->values();
        \Log::info('SavedPropertyController@index', ['saved' => $properties->toArray()]);
        return Inertia::render('Saved', ['savedProperties' => $properties]);
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            \Log::warning('SaveProperty: Not authenticated');
            abort(403, 'Not authenticated');
        }

        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'notes' => 'nullable|string',
        ]);

        \Log::info('SaveProperty: Saving', [
            'user_id' => auth()->id(),
            'property_id' => $request->property_id,
        ]);

        $saved = SavedProperty::firstOrCreate([
            'user_id' => auth()->id(),
            'property_id' => $request->property_id,
        ], [
            'notes' => $request->notes,
        ]);

        \Log::info('SaveProperty: Saved', ['saved' => $saved]);

        return response()->noContent();
    }

    public function destroy($propertyId)
    {
        $saved = SavedProperty::where('user_id', auth()->id())
            ->where('property_id', $propertyId)
            ->firstOrFail();
        $saved->delete();
        return response()->noContent();
    }
}
