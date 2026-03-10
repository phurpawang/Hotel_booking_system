<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoomStatusController extends Controller
{
    /**
     * Display room status overview
     */
    public function index()
    {
        $user = Auth::user();
        $hotel = $user->hotel;

        $rooms = Room::where('hotel_id', $user->hotel_id)
            ->with(['bookings' => function ($q) {
                $q->whereIn(DB::raw('UPPER(status)'), ['CONFIRMED', 'CHECKED_IN'])
                  ->where(function ($query) {
                      $query->where('check_in_date', '<=', now())
                            ->where('check_out_date', '>=', now());
                  });
            }])
            ->orderBy('room_number')
            ->get();

        // Calculate statistics (count individual rooms)
        $stats = [
            'total' => $rooms->count(),
            'available' => $rooms->where('status', 'AVAILABLE')->count(),
            'occupied' => $rooms->where('status', 'OCCUPIED')->count(),
            'cleaning' => $rooms->where('status', 'CLEANING')->count(),
            'maintenance' => $rooms->where('status', 'MAINTENANCE')->count(),
        ];

        return view('manager.room-status.index', compact('rooms', 'stats', 'hotel'));
    }

    /**
     * Update room status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:AVAILABLE,OCCUPIED,CLEANING,MAINTENANCE'
        ]);

        $user = Auth::user();
        $room = Room::where('hotel_id', $user->hotel_id)
            ->findOrFail($id);

        $room->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Room status updated successfully.');
    }

    /**
     * Show room details
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $room = Room::where('hotel_id', $user->hotel_id)
            ->with(['bookings' => function ($q) {
                $q->whereIn(DB::raw('UPPER(status)'), ['CONFIRMED', 'CHECKED_IN'])
                  ->orderBy('check_in_date', 'desc')
                  ->limit(5);
            }])
            ->findOrFail($id);

        return view('manager.room-status.show', compact('room'));
    }
}
