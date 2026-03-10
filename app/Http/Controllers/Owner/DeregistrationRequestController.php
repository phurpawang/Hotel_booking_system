<?php

namespace App\Http\Controllers\Owner;

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
        
        if (!$hotel) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'Hotel not found.');
        }
        
        // Get existing deregistration request if any
        $deregistrationRequest = HotelDeregistrationRequest::where('hotel_id', $user->hotel_id)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->first();
        
        // Check for future confirmed bookings
        $futureBookingsCount = Booking::where('hotel_id', $user->hotel_id)
            ->where('status', 'CONFIRMED')
            ->where('check_in_date', '>', Carbon::now())
            ->count();
        
        // Get all deregistration requests history
        $requestHistory = HotelDeregistrationRequest::where('hotel_id', $user->hotel_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('owner.deregistration.index', compact('hotel', 'deregistrationRequest', 'futureBookingsCount', 'requestHistory'));
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
            return redirect()->route('owner.deregistration.index')
                ->with('error', 'You already have a pending deregistration request.');
        }
        
        // Check for future confirmed bookings
        $futureBookings = Booking::where('hotel_id', $user->hotel_id)
            ->where('status', 'CONFIRMED')
            ->where('check_in_date', '>', Carbon::now())
            ->count();
        
        if ($futureBookings > 0) {
            return redirect()->route('owner.deregistration.index')
                ->with('error', "Cannot submit deregistration request. You have {$futureBookings} future confirmed booking(s). Please complete or cancel them first.");
        }
        
        $validated = $request->validate([
            'reason' => 'required|in:BUSINESS_CLOSED,RENOVATION,SEASONAL_CLOSURE,SWITCHING_PLATFORM,OTHER',
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
        
        return redirect()->route('owner.deregistration.index')
            ->with('success', 'Deregistration request submitted successfully. An administrator will review your request.');
    }

    /**
     * Cancel a deregistration request
     */
    public function cancel($id)
    {
        $user = Auth::user();
        
        $deregRequest = HotelDeregistrationRequest::where('id', $id)
            ->where('hotel_id', $user->hotel_id)
            ->where('status', 'PENDING')
            ->firstOrFail();
        
        // Update request status
        $deregRequest->update(['status' => 'CANCELLED']);
        
        // Restore hotel status to APPROVED
        Hotel::where('id', $user->hotel_id)->update(['status' => 'APPROVED']);
        
        return redirect()->route('owner.deregistration.index')
            ->with('success', 'Deregistration request cancelled successfully.');
    }
}
