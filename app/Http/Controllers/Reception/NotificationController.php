<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications
     */
    public function index()
    {
        $user = Auth::user();
        
        $notifications = Notification::where('hotel_id', $user->hotel_id)
            ->where(function ($q) use ($user) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::where('hotel_id', $user->hotel_id)
            ->where(function ($q) use ($user) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->id);
            })
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
        
        $notification = Notification::where('hotel_id', $user->hotel_id)
            ->where(function ($q) use ($user) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->id);
            })
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect()->back()
            ->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::where('hotel_id', $user->hotel_id)
            ->where(function ($q) use ($user) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->id);
            })
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()
            ->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('hotel_id', $user->hotel_id)
            ->where(function ($q) use ($user) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->id);
            })
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()
            ->with('success', 'Notification deleted successfully.');
    }
}
