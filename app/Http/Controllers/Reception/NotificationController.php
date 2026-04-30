<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display receptionist notifications
     */
    public function index()
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->where('target_role', 'reception')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('target_role', 'reception')
            ->where('is_read', false)
            ->count();

        return view('reception.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->where('target_role', 'reception')
            ->firstOrFail();

        NotificationService::markAsRead($notification);

        return redirect()->route('reception.notifications.index')->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $count = NotificationService::markAllAsRead($user, 'reception');

        return redirect()->back()->with('success', "Marked {$count} notification(s) as read");
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->where('target_role', 'reception')
            ->firstOrFail();

        NotificationService::delete($notification);

        return redirect()->back()->with('success', 'Notification deleted successfully');
    }

    /**
     * Get unread notification count (for AJAX)
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = NotificationService::getUnreadCount($user, 'reception');
        
        return response()->json(['unreadCount' => $count]);
    }

    /**
     * Get recent unread notifications (for AJAX dropdown)
     */
    public function getRecent()
    {
        $user = Auth::user();
        $notifications = NotificationService::getUnreadNotifications($user, 'reception', 5);
        
        return response()->json(['notifications' => $notifications]);
    }
}
