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
        return Inertia::render('SavedProperties/Index', ['savedProperties' => $saved]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'notes' => 'nullable|string',
        ]);

        SavedProperty::firstOrCreate([
            'user_id' => auth()->id(),
            'property_id' => $request->property_id,
        ], [
            'notes' => $request->notes,
        ]);

        return redirect()->back();
    }

    public function destroy(SavedProperty $savedProperty)
    {
        $this->authorize('delete', $savedProperty);
        $savedProperty->delete();
        return redirect()->back();
    }
}
