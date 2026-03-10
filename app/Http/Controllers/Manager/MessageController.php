<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display the messages for the hotel
     */
    public function index()
    {
        $user = Auth::user();
        $hotel = $user->hotel;
        
        $messages = Message::where('hotel_id', $user->hotel_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('manager.messages.index', compact('messages', 'hotel'));
    }

    /**
     * Mark a message as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $message = Message::where('id', $id)
            ->where('hotel_id', $user->hotel_id)
            ->firstOrFail();
        
        $message->update(['status' => 'READ']);
        
        return redirect()->route('manager.messages.index')
            ->with('success', 'Message marked as read.');
    }

    /**
     * Delete a message
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $message = Message::where('id', $id)
            ->where('hotel_id', $user->hotel_id)
            ->firstOrFail();
        
        $message->delete();
        
        return redirect()->route('manager.messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
