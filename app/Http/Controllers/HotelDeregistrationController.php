<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelDeregistrationRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelDeregistrationController extends Controller
{
    /**
     * Show deregistration request form
     */
    public function showDeregistrationForm()
    {
        $hotel = Auth::user()->hotel;
        
        if (!$hotel) {
            return redirect()->route('dashboard')->with('error', 'No hotel found for your account.');
        }

        // Check for active bookings
        $activeBookings = Booking::where('hotel_id', $hotel->id)
            ->whereIn('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN'])
            ->whereDate('check_out_date', '>=', now())
            ->count();

        return view('hotel.deregistration-request', compact('hotel', 'activeBookings'));
    }

    /**
     * Submit deregistration request
     */
    public function submitDeregistrationRequest(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|in:BUSINESS_CLOSED,RENOVATION,SEASONAL_CLOSURE,SWITCHING_PLATFORM,OTHER',
            'reason_details' => 'nullable|string|max:1000',
            'declaration' => 'required|accepted',
        ]);

        $hotel = Auth::user()->hotel;

        if (!$hotel) {
            return redirect()->route('dashboard')->with('error', 'No hotel found for your account.');
        }

        // Check for active bookings
        $activeBookings = Booking::where('hotel_id', $hotel->id)
            ->whereIn('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN'])
            ->whereDate('check_out_date', '>=', now())
            ->count();

        if ($activeBookings > 0) {
            return back()->with('error', 'You have active bookings. Please complete or cancel all bookings before deregistration.');
        }

        // Create deregistration request
        HotelDeregistrationRequest::create([
            'hotel_id' => $hotel->id,
            'requested_by' => Auth::id(),
            'reason' => $validated['reason'],
            'reason_details' => $validated['reason_details'],
            'status' => 'PENDING',
        ]);

        // Update hotel status
        $hotel->update(['status' => 'DEREGISTRATION_REQUESTED']);

        return redirect()->route('dashboard')
                       ->with('success', 'Deregistration request submitted successfully. Admin will review your request.');
    }

    /**
     * Cancel deregistration request
     */
    public function cancelDeregistrationRequest()
    {
        $hotel = Auth::user()->hotel;

        if (!$hotel) {
            return redirect()->route('dashboard')->with('error', 'No hotel found for your account.');
        }

        // Find pending deregistration request
        $request = HotelDeregistrationRequest::where('hotel_id', $hotel->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($request) {
            $request->update(['status' => 'cancelled']);
            $hotel->update(['status' => 'approved']);
        }

        return redirect()->route('dashboard')
                       ->with('success', 'Deregistration request cancelled successfully.');
    }
}
