<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark a single notification as read.
     * Only the notification owner can mark their own notifications.
     */
    public function markAsRead(Notification $notification): RedirectResponse
    {
        // Verify ownership
        abort_if($notification->user_id !== Auth::id(), 403, 'You can only mark your own notifications.');

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications for the current user as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        Auth::user()->notifications()->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
