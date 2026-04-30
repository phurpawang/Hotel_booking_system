<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display owner notifications
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get notifications for this owner
        $notifications = Notification::where('user_id', $user->id)
            ->where('target_role', 'owner')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('target_role', 'owner')
            ->where('is_read', false)
            ->count();

        return view('owner.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->where('target_role', 'owner')
            ->firstOrFail();

        NotificationService::markAsRead($notification);

        return redirect()->route('owner.notifications.index')->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $count = NotificationService::markAllAsRead($user, 'owner');

        return redirect()->back()->with('success', "Marked {$count} notification(s) as read");
    }

    /**
     * Get unread notification count (for AJAX)
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = NotificationService::getUnreadCount($user, 'owner');
        
        return response()->json(['unreadCount' => $count]);
    }

    /**
     * Get recent unread notifications (for AJAX dropdown)
     */
    public function getRecent()
    {
        $user = Auth::user();
        $notifications = NotificationService::getUnreadNotifications($user, 'owner', 5);
        
        return response()->json(['notifications' => $notifications]);
    }
}
