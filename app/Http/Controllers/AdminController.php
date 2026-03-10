<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelDeregistrationRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        $pendingHotels = Hotel::where('status', 'pending')->count();
        $pendingDeregistrations = HotelDeregistrationRequest::where('status', 'pending')->count();
        $approvedHotels = Hotel::where('status', 'approved')->count();
        $totalBookings = Booking::count();

        return view('admin.dashboard', compact('pendingHotels', 'pendingDeregistrations', 'approvedHotels', 'totalBookings'));
    }

    /**
     * Show pending hotel registrations
     */
    public function pendingRegistrations()
    {
        $pendingHotels = Hotel::where('status', 'pending')
            ->with(['owner'])
            ->latest()
            ->paginate(10);

        return view('admin.pending-registrations', compact('pendingHotels'));
    }

    /**
     * Show hotel registration details
     */
    public function viewHotelDetails($id)
    {
        $hotel = Hotel::with(['owner'])
            ->findOrFail($id);

        return view('admin.hotel-details', compact('hotel'));
    }

    /**
     * Approve hotel registration
     */
    public function approveHotel($id)
    {
        $hotel = Hotel::findOrFail($id);

        if ($hotel->status !== 'PENDING') {
            return back()->with('error', 'Hotel is not pending approval.');
        }

        // Generate unique Hotel ID
        $hotelId = 'HT' . strtoupper(Str::random(6)) . sprintf('%04d', $hotel->id);

        $hotel->update([
            'hotel_id' => $hotelId,
            'status' => 'APPROVED',
        ]);

        // TODO: Send email/SMS notification to hotel owner with Hotel ID

        return redirect()->route('admin.pending-registrations')
            ->with('success', "Hotel approved successfully! Hotel ID: {$hotelId}");
    }

    /**
     * Reject hotel registration
     */
    public function rejectHotel(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $hotel = Hotel::findOrFail($id);

        if ($hotel->status !== 'PENDING') {
            return back()->with('error', 'Hotel is not pending approval.');
        }

        $hotel->update([
            'status' => 'REJECTED',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // TODO: Send email/SMS notification to hotel owner with rejection reason

        return redirect()->route('admin.pending-registrations')
            ->with('success', 'Hotel registration rejected.');
    }

    /**
     * Show all hotels
     */
    public function allHotels(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Hotel::with(['owner', 'dzongkhag']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $hotels = $query->latest()->paginate(15);

        return view('admin.all-hotels', compact('hotels', 'status'));
    }

    /**
     * Show pending deregistration requests
     */
    public function pendingDeregistrations()
    {
        $pendingRequests = HotelDeregistrationRequest::where('status', 'PENDING')
            ->with(['hotel', 'requester'])
            ->latest()
            ->paginate(10);

        return view('admin.pending-deregistrations', compact('pendingRequests'));
    }

    /**
     * Show deregistration request details
     */
    public function viewDeregistrationDetails($id)
    {
        $request = HotelDeregistrationRequest::with(['hotel', 'requester'])
            ->findOrFail($id);

        // Get hotel's active bookings
        $activeBookings = Booking::where('hotel_id', $request->hotel_id)
            ->whereIn('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN'])
            ->whereDate('check_out_date', '>=', now())
            ->count();

        // Get pending payments (bookings with pending payment)
        $pendingPayments = Booking::where('hotel_id', $request->hotel_id)
            ->where('payment_status', 'PENDING')
            ->count();

        return view('admin.deregistration-details', compact('request', 'activeBookings', 'pendingPayments'));
    }

    /**
     * Approve deregistration request
     */
    public function approveDeregistration(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $deregRequest = HotelDeregistrationRequest::findOrFail($id);

        if ($deregRequest->status !== 'PENDING') {
            return back()->with('error', 'Request is not pending approval.');
        }

        // Check for active bookings
        $activeBookings = Booking::where('hotel_id', $deregRequest->hotel_id)
            ->whereIn('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN'])
            ->whereDate('check_out_date', '>=', now())
            ->count();

        if ($activeBookings > 0) {
            return back()->with('error', 'Cannot approve deregistration. Hotel has active bookings.');
        }

        // Update deregistration request
        $deregRequest->update([
            'status' => 'APPROVED',
            'admin_notes' => $validated['admin_notes'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Update hotel status
        $deregRequest->hotel->update([
            'status' => 'DEREGISTERED',
        ]);

        // TODO: Send email/SMS notification to hotel

        return redirect()->route('admin.pending-deregistrations')
            ->with('success', 'Deregistration approved successfully.');
    }

    /**
     * Reject deregistration request
     */
    public function rejectDeregistration(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $deregRequest = HotelDeregistrationRequest::findOrFail($id);

        if ($deregRequest->status !== 'PENDING') {
            return back()->with('error', 'Request is not pending approval.');
        }

        // Update deregistration request
        $deregRequest->update([
            'status' => 'REJECTED',
            'admin_notes' => $validated['admin_notes'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Revert hotel status to APPROVED
        $deregRequest->hotel->update([
            'status' => 'APPROVED',
        ]);

        // TODO: Send email/SMS notification to hotel

        return redirect()->route('admin.pending-deregistrations')
            ->with('success', 'Deregistration rejected. Hotel remains active.');
    }

    /**
     * Force deregister a hotel (emergency)
     */
    public function forceDeregister(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $hotel = Hotel::findOrFail($id);

        $hotel->update([
            'status' => 'FORCE_DEREGISTERED',
            'rejection_reason' => $validated['reason'],
        ]);

        // TODO: Send email/SMS notification to hotel

        return back()->with('success', 'Hotel force deregistered successfully.');
    }

    /**
     * Suspend a hotel
     */
    public function suspendHotel(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $hotel = Hotel::findOrFail($id);

        $hotel->update([
            'status' => 'SUSPENDED',
            'rejection_reason' => $validated['reason'],
        ]);

        // TODO: Send email/SMS notification to hotel

        return back()->with('success', 'Hotel suspended successfully.');
    }

    /**
     * View all bookings
     */
    public function allBookings(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Booking::with(['hotel', 'room']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.all-bookings', compact('bookings', 'status'));
    }
}
