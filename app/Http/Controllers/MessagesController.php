<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessagesController extends Controller
{
    public function index()
{
    $userId = Auth::id();

    $conversations = Messages::with(['sender', 'receiver', 'property'])
        ->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        })
        ->selectRaw('MAX(id) as id')
        ->groupBy('property_id', 'sender_id', 'receiver_id')
        ->orderByDesc('id')
        ->get()
        ->map(fn ($msg) => Messages::with(['sender', 'receiver', 'property'])->find($msg->id));

    return Inertia::render('Messages', [
        'messages' => $conversations, // ✅ Fix: use $conversations instead of undefined $messages
    ]);
}

    /**
     * Start a new conversation with an initial message.
     */
    public function start(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|exists:users,id',
        'property_id' => 'required|exists:properties,id',
    ]);

    $user = Auth::user();
    $property = Property::findOrFail($request->property_id);

    // Combine the predefined options into one message
    $initialMessage = "I am interested in this land property \"{$property->title}\" and am willing to negotiate. "
                    . "I would like to know the full details including the documents.";

    // Avoid duplicate conversation if already exists
    $existing = Messages::where('sender_id', $user->id)
        ->where('receiver_id', $request->receiver_id)
        ->where('property_id', $property->id)
        ->first();

    if (!$existing) {
        Messages::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'property_id' => $property->id,
            'message' => $initialMessage,
        ]);
    }

    return redirect()->route('messages.conversation', ['property_id' => $property->id]);
}
    /**
     * Send a message in an existing conversation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:properties,id',
            'content' => 'required|string',
        ]);

        Messages::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'property_id' => $request->property_id,
            'message' => $request->content,
        ]);

        return back();
    }

    /**
     * Show a single message thread.
     */
    public function show($id)
    {
        $message = Messages::with(['sender', 'receiver', 'property'])->findOrFail($id);

        if (Auth::id() !== $message->sender_id && Auth::id() !== $message->receiver_id) {
            abort(403);
        }

        return Inertia::render('MessagesIndex', [
            'messages' => [$message],
            'property_id' => $message->property_id,
        ]);
    }

    /**
     * View full conversation related to a property.
     */
    public function conversation($property_id)
{
    $userId = Auth::id();

    $messages = Messages::with(['sender', 'receiver', 'property'])
        ->where('property_id', $property_id)
        ->where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })
        ->orderBy('created_at')
        ->get();

    $property = Property::find($property_id);

    return Inertia::render('MessagesIndex', [
        'messages' => $messages,
        'property_id' => $property_id,
        'presetMessage' => request()->presetMessage ?? null,
        'propertyOwnerId' => $property?->user_id,
    ]);
}
}