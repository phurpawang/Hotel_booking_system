<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\User;
use App\Models\BookingCommission;
use App\Services\CommissionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role
     */
    public function index()
    {
        $user = auth()->user();

        // Redirect to role-specific dashboards
        switch ($user->role) {
            case 'ADMIN':
                return redirect()->route('admin.dashboard');
            case 'OWNER':
                return redirect()->route('owner.dashboard');
            case 'MANAGER':
                return redirect()->route('manager.dashboard');
            case 'RECEPTION':
                return redirect()->route('reception.dashboard');
            default:
                abort(403, 'Unauthorized access');
        }
    }

    /**
     * Update booking status (for Manager and Reception)
     */
    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:CONFIRMED,CHECKED_IN,CHECKED_OUT,CANCELLED'
        ]);

        $booking->update(['status' => $request->status]);

        // Update room status based on booking status
        if ($request->status == 'CHECKED_IN') {
            $booking->room->update(['status' => 'OCCUPIED']);
        } elseif ($request->status == 'CHECKED_OUT') {
            $booking->room->update(['status' => 'AVAILABLE']);
            
            // Create commission record if not already exists
            if (!BookingCommission::where('booking_id', $booking->id)->exists()) {
                $commissionService = app(CommissionService::class);
                $commissionService->createBookingCommission($booking);
            }
        }

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    /**
     * Approve hotel registration (Admin only)
     */
    public function approveHotel($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Hotel approved successfully.');
    }

    /**
     * Reject hotel registration (Admin only)
     */
    public function rejectHotel($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Hotel rejected.');
    }
}
