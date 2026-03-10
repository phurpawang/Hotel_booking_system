<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelDeregistrationRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DeregistrationRequestController extends Controller
{
    /**
     * Display the deregistration request page
     */
    public function index()
    {
        $user = Auth::user();
        $hotel = Hotel::find($user->hotel_id);
        
        // Get existing deregistration request if any
        $deregistrationRequest = HotelDeregistrationRequest::where('hotel_id', $user->hotel_id)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->first();
        
        // Check for future confirmed bookings
        $futureBookingsCount = Booking::where('hotel_id', $user->hotel_id)
            ->where('status', 'CONFIRMED')
            ->where('check_in_date', '>', Carbon::now())
            ->count();
        
        return view('manager.deregistration.index', compact('hotel', 'deregistrationRequest', 'futureBookingsCount'));
    }

    /**
     * Submit a deregistration request
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check for existing pending request
        $existingRequest = HotelDeregistrationRequest::where('hotel_id', $user->hotel_id)
            ->where('status', 'PENDING')
            ->first();
        
        if ($existingRequest) {
            return redirect()->route('manager.deregistration.index')
                ->with('error', 'You already have a pending deregistration request.');
        }
        
        // Check for future confirmed bookings
        $futureBookings = Booking::where('hotel_id', $user->hotel_id)
            ->where('status', 'CONFIRMED')
            ->where('check_in_date', '>', Carbon::now())
            ->count();
        
        if ($futureBookings > 0) {
            return redirect()->route('manager.deregistration.index')
                ->with('error', "Cannot submit deregistration request. You have {$futureBookings} future confirmed booking(s). Please complete or cancel them first.");
        }
        
        $validated = $request->validate([
            'reason' => 'required|in:BUSINESS_CLOSURE,PROPERTY_SOLD,RENOVATION,OTHER',
            'reason_details' => 'required|string|max:1000',
        ]);
        
        // Create deregistration request
        HotelDeregistrationRequest::create([
            'hotel_id' => $user->hotel_id,
            'requested_by' => $user->id,
            'reason' => $validated['reason'],
            'reason_details' => $validated['reason_details'],
            'status' => 'PENDING',
        ]);
        
        // Update hotel status
        Hotel::where('id', $user->hotel_id)->update(['status' => 'PENDING_CLOSURE']);
        
        return redirect()->route('manager.deregistration.index')
            ->with('success', 'Deregistration request submitted successfully. An administrator will review your request.');
    }

    /**
     * Cancel a deregistration request
     */
    public function cancel($id)
    {
        $user = Auth::user();
        
        $request = HotelDeregistrationRequest::where('id', $id)
            ->where('hotel_id', $user->hotel_id)
            ->where('status', 'PENDING')
            ->firstOrFail();
        
        // Update request status
        $request->update(['status' => 'CANCELLED']);
        
        // Restore hotel status to APPROVED
        Hotel::where('id', $user->hotel_id)->update(['status' => 'APPROVED']);
        
        return redirect()->route('manager.deregistration.index')
            ->with('success', 'Deregistration request cancelled successfully.');
    }
}
