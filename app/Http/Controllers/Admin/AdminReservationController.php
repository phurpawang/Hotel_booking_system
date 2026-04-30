<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    /**
     * Display a listing of reservations
     */
    public function index(Request $request)
    {
        $query = Booking::with(['hotel', 'room']);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Search by guest name
        if ($request->has('search') && $request->search != '') {
            $query->where('guest_name', 'LIKE', '%' . $request->search . '%');
        }
        
        $reservations = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Display the specified reservation
     */
    public function show($id)
    {
        $reservation = Booking::with(['hotel', 'room'])->findOrFail($id);
        
        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Update reservation status
     */
    public function updateStatus(Request $request, $id)
    {
        $reservation = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:PENDING,CONFIRMED,CHECKED_IN,CHECKED_OUT,CANCELLED'
        ]);
        
        $reservation->update(['status' => $validated['status']]);
        
        return redirect()->back()->with('success', 'Reservation status updated successfully');
    }

    /**
     * Delete reservation
     */
    public function destroy($id)
    {
        $reservation = Booking::findOrFail($id);
        $reservation->delete();
        
        return redirect()->route('admin.reservations.index')->with('success', 'Reservation deleted successfully');
    }
}



