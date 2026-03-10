<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Dzongkhag;
use App\Models\Guest;
use App\Mail\BookingConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GuestBookingController extends Controller
{
    /**
     * Show home page with search form
     */
    public function index()
    {
        $dzongkhags = Dzongkhag::all();
        $featuredHotels = Hotel::where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        return view('guest.home', compact('dzongkhags', 'featuredHotels'));
    }

    /**
     * Search for available hotels
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'dzongkhag_id' => 'nullable|exists:dzongkhags,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'rooms' => 'required|integer|min:1',
        ]);

        // Calculate total guests
        $validated['guests'] = $validated['adults'] + ($validated['children'] ?? 0);

        $query = Hotel::where('status', 'approved')
            ->with(['rooms' => function($q) {
                $q->where('status', 'AVAILABLE');
            }], 'dzongkhag');

        // Filter by dzongkhag if specified
        if ($request->filled('dzongkhag_id') && $request->dzongkhag_id != '' && $request->dzongkhag_id > 0) {
            $query->where('dzongkhag_id', $request->dzongkhag_id);
        }

        $hotels = $query->get();

        // Filter hotels with available rooms for the dates
        $availableHotels = $hotels->filter(function($hotel) use ($validated) {
            if ($hotel->rooms->count() === 0) {
                return false;
            }
            
            // Filter rooms to only include those available for the requested dates
            $hotel->availableRooms = $hotel->rooms->filter(function($room) use ($validated) {
                // Check if room has enough quantity
                if ($room->quantity <= 0) {
                    return false;
                }
                
                // Check for booking conflicts
                $conflictingBookings = Booking::where('room_id', $room->id)
                    ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
                    ->where(function($q) use ($validated) {
                        $checkIn = $validated['check_in'];
                        $checkOut = $validated['check_out'];
                        
                        // Check for date overlaps
                        $q->whereBetween('check_in_date', [$checkIn, $checkOut])
                          ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                          ->orWhere(function($q2) use ($checkIn, $checkOut) {
                              $q2->where('check_in_date', '<=', $checkIn)
                                 ->where('check_out_date', '>=', $checkOut);
                          });
                    })
                    ->sum('num_rooms');
                
                // Calculate available quantity for the dates
                $availableQuantity = $room->quantity - $conflictingBookings;
                
                // Room is available if we have enough rooms for the request
                return $availableQuantity >= $validated['rooms'];
            });
            
            // Replace the rooms collection with only available rooms
            $hotel->setRelation('rooms', $hotel->availableRooms);
            
            return $hotel->rooms->count() > 0;
        });

        // Get dzongkhag name for display
        $dzongkhagName = null;
        if (!empty($validated['dzongkhag_id'])) {
            $dzongkhag = Dzongkhag::find($validated['dzongkhag_id']);
            $dzongkhagName = $dzongkhag ? $dzongkhag->name : null;
        }

        return view('guest.search-results', compact('availableHotels', 'validated', 'dzongkhagName'));
    }

    /**
     * Show hotel details
     */
    public function showHotel($id, Request $request)
    {
        $hotel = Hotel::where('status', 'approved')
            ->with(['rooms' => function($q) {
                $q->where('status', 'AVAILABLE');
            }])
            ->findOrFail($id);

        $checkIn = $request->query('check_in');
        $checkOut = $request->query('check_out');
        $adults = $request->query('adults', 1);
        $children = $request->query('children', 0);
        $guests = $adults + $children;
        $numRooms = $request->query('rooms', 1);

        return view('guest.hotel-details', compact('hotel', 'checkIn', 'checkOut', 'adults', 'children', 'guests', 'numRooms'));
    }

    /**
     * Show booking form
     */
    public function showBookingForm(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after:check_in',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'guests' => 'nullable|integer|min:1',
            'num_rooms' => 'required|integer|min:1',
        ]);

        // Calculate total guests (support both new and old format)
        if (isset($validated['adults'])) {
            $validated['guests'] = $validated['adults'] + ($validated['children'] ?? 0);
        } elseif (!isset($validated['guests'])) {
            $validated['guests'] = 1;
        }

        $hotel = Hotel::findOrFail($validated['hotel_id']);
        $room = Room::findOrFail($validated['room_id']);

        // Calculate total price
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $room->price_per_night * $nights * $validated['num_rooms'];

        return view('guest.booking-form', compact('hotel', 'room', 'validated', 'nights', 'totalPrice'));
    }

    /**
     * Confirm booking
     */
    public function confirmBooking(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'num_rooms' => 'required|integer|min:1',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_mobile' => 'required|string|max:20',
            'payment_method' => 'required|in:pay_now,pay_at_hotel',
            'payment_screenshot' => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $room = Room::findOrFail($validated['room_id']);

        // Calculate total price
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $room->price_per_night * $nights * $validated['num_rooms'];

        // Generate unique booking ID
        $bookingId = Booking::generateBookingId();

        // Handle payment screenshot upload
        $paymentScreenshotPath = null;
        if ($request->hasFile('payment_screenshot')) {
            $file = $request->file('payment_screenshot');
            $filename = 'payment_' . $bookingId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $paymentScreenshotPath = $file->storeAs('payment_screenshots', $filename, 'public');
        }

        // Map payment method to database format
        $paymentMethodDb = $validated['payment_method'] === 'pay_now' ? 'ONLINE' : 'PAY_AT_HOTEL';

        // Create or find guest
        $guest = Guest::updateOrCreate(
            ['email' => $validated['guest_email']],
            [
                'name' => $validated['guest_name'],
                'mobile' => $validated['guest_mobile'],
                'status' => 'active',
            ]
        );

        // Create booking
        $booking = Booking::create([
            'booking_id' => $bookingId,
            'hotel_id' => $validated['hotel_id'],
            'room_id' => $validated['room_id'],
            'guest_id' => $guest->id, // Link to guest
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_mobile'],
            'check_in_date' => $validated['check_in'],
            'check_out_date' => $validated['check_out'],
            'num_guests' => 1, // Default to 1 for now
            'num_rooms' => $validated['num_rooms'],
            'total_price' => $totalPrice,
            'payment_method' => $paymentMethodDb,
            'payment_screenshot' => $paymentScreenshotPath,
            'payment_status' => $paymentScreenshotPath ? 'PENDING' : 'PENDING',
            'status' => 'CONFIRMED',
            'special_requests' => $validated['special_requests'] ?? null,
        ]);

        // Load relationships for email
        $booking->load(['hotel', 'room']);

        // Send confirmation email
        $emailSent = false;
        try {
            Mail::to($booking->guest_email)->send(new BookingConfirmation($booking));
            $emailSent = true;
            Log::info('Booking confirmation email sent successfully', [
                'booking_id' => $bookingId,
                'guest_email' => $booking->guest_email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $bookingId,
                'guest_email' => $booking->guest_email,
                'error' => $e->getMessage()
            ]);
        }

        // Show confirmation page with email status
        return redirect()->route('guest.booking.confirmation', ['booking_id' => $bookingId])
            ->with('email_sent', $emailSent);
    }

    /**
     * Show booking confirmation
     */
    public function showConfirmation($bookingId)
    {
        $booking = Booking::where('booking_id', $bookingId)
            ->with(['hotel', 'room'])
            ->firstOrFail();

        return view('guest.booking-confirmation', compact('booking'));
    }

    /**
     * Show manage booking form
     */
    public function showManageBookingForm()
    {
        return view('guest.manage-booking-form');
    }

    /**
     * View booking details
     */
    public function viewBooking(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|string',
            'identifier' => 'required|string', // Email or Phone
        ]);

        $booking = Booking::where('booking_id', $validated['booking_id'])
            ->where(function($query) use ($validated) {
                $query->where('guest_email', $validated['identifier'])
                      ->orWhere('guest_phone', $validated['identifier']);
            })
            ->with(['hotel', 'room'])
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found. Please check your Booking ID and Email/Phone.');
        }

        return view('guest.view-booking', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancelBooking(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'identifier' => 'required|string', // Email or Phone
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $booking = Booking::where('booking_id', $bookingId)
            ->where(function($query) use ($validated) {
                $query->where('guest_email', $validated['identifier'])
                      ->orWhere('guest_phone', $validated['identifier']);
            })
            ->firstOrFail();

        // Check if booking can be cancelled
        if (in_array($booking->status, ['CANCELLED', 'CHECKED_OUT'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        // Update booking status
        $booking->update([
            'status' => 'CANCELLED',
            'cancelled_at' => now(),
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);

        // Calculate refund (simple logic - can be enhanced)
        if ($booking->payment_status === 'PAID') {
            $daysUntilCheckIn = Carbon::parse($booking->check_in_date)->diffInDays(now(), false);
            
            if ($daysUntilCheckIn > 7) {
                $refundAmount = $booking->total_price; // Full refund
            } elseif ($daysUntilCheckIn > 3) {
                $refundAmount = $booking->total_price * 0.5; // 50% refund
            } else {
                $refundAmount = 0; // No refund
            }

            $booking->update([
                'refund_amount' => $refundAmount,
                'payment_status' => 'REFUNDED',
            ]);
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
