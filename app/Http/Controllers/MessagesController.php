<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;
use Inertia\Inertia;
class MessagesController extends Controller
{
    public function index()
    {
        $messages = Messages::with('sender', 'receiver', 'property')
            ->where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->latest()
            ->get();

        return Inertia::render('Messages/Index', ['messages' => $messages]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:properties,id',
            'message' => 'required|string',
        ]);

        Messages::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'property_id' => $request->property_id,
            'message' => $request->message,
        ]);

        return redirect()->back();
    }
}
