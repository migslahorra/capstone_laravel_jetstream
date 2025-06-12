<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NotificationsController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $notifications = Notifications::where('user_id', auth()->id())->latest()->get();
        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications
        ]);
    }

    public function markAsRead(Notifications $notification)
    {
        $this->authorize('view', $notification);
        $notification->update(['is_read' => 1]);
        return redirect()->back();
    }
}
