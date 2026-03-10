<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\Guest;
use App\Models\BookingCommission;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $hotel = $user->hotel;

        $query = Booking::where('hotel_id', $user->hotel_id)->with(['room', 'creator']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->status));
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('check_in_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_out_date', '<=', $request->end_date);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', strtoupper($request->payment_status));
        }

        // Search by guest name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_phone', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%")
                  ->orWhere('booking_id', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        // Enhanced Statistics
        $stats = [
            'total' => Booking::where('hotel_id', $user->hotel_id)->count(),
            'today_checkins' => Booking::where('hotel_id', $user->hotel_id)
                ->whereDate('check_in_date', Carbon::today())
                ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
                ->count(),
            'today_checkouts' => Booking::where('hotel_id', $user->hotel_id)
                ->whereDate('check_out_date', Carbon::today())
                ->where('status', 'CHECKED_IN')
                ->count(),
            'pending' => Booking::where('hotel_id', $user->hotel_id)
                ->where('status', 'CONFIRMED')
                ->where('payment_status', 'PENDING')
                ->count(),
            'monthly_revenue' => Booking::where('hotel_id', $user->hotel_id)
                ->where('payment_status', 'PAID')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_price'),
            'confirmed' => Booking::where('hotel_id', $user->hotel_id)->where('status', 'CONFIRMED')->count(),
            'checked_in' => Booking::where('hotel_id', $user->hotel_id)->where('status', 'CHECKED_IN')->count(),
            'checked_out' => Booking::where('hotel_id', $user->hotel_id)->where('status', 'CHECKED_OUT')->count(),
            'cancelled' => Booking::where('hotel_id', $user->hotel_id)->where('status', 'CANCELLED')->count(),
        ];

        // Chart Data - Last 6 Months Booking Trend
        $chartData = $this->getBookingChartData($user->hotel_id);

        // Occupancy Rate
        $totalRooms = Room::where('hotel_id', $user->hotel_id)->sum('quantity');
        $occupiedRooms = Booking::where('hotel_id', $user->hotel_id)
            ->where('status', 'CHECKED_IN')
            ->whereDate('check_in_date', '<=', Carbon::today())
            ->whereDate('check_out_date', '>=', Carbon::today())
            ->sum('num_rooms');
        $stats['occupancy'] = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        return view('bookings.index', compact('bookings', 'hotel', 'stats', 'chartData'));
    }

    /**
     * Get booking chart data for last 6 months
     */
    private function getBookingChartData($hotelId)
    {
        $months = [];
        $bookingCounts = [];
        $revenueCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $bookingCounts[] = Booking::where('hotel_id', $hotelId)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $revenueCounts[] = Booking::where('hotel_id', $hotelId)
                ->where('payment_status', 'PAID')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_price');
        }

        return [
            'months' => $months,
            'bookings' => $bookingCounts,
            'revenue' => $revenueCounts,
        ];
    }

    /**
     * Show the form for creating a new booking
     */
    public function create()
    {
        $user = Auth::user();
        $hotel = $user->hotel;
        
        // Get unique room types with available rooms
        $roomTypes = Room::where('hotel_id', $user->hotel_id)
                    ->where('status', 'AVAILABLE')
                    ->whereNotIn('status', ['OCCUPIED', 'MAINTENANCE', 'CLEANING'])
                    ->select('room_type', DB::raw('MIN(price_per_night) as min_price'), DB::raw('COUNT(*) as available_count'))
                    ->groupBy('room_type')
                    ->get();

        return view('bookings.create', compact('hotel', 'roomTypes'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'room_type' => 'required|string',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'num_guests' => 'required|integer|min:1',
            'num_rooms' => 'required|integer|min:1',
            'payment_status' => 'required|in:PENDING,PAID',
            'payment_method' => 'required|in:CASH,CARD,BANK_TRANSFER,ONLINE',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Auto-assign first available room of selected type
        $room = Room::where('hotel_id', $user->hotel_id)
                   ->where('room_type', $request->room_type)
                   ->where('status', 'AVAILABLE')
                   ->whereNotIn('status', ['OCCUPIED', 'MAINTENANCE', 'CLEANING'])
                   ->first();

        if (!$room) {
            return redirect()->back()
                ->with('error', 'No available rooms of type "' . $request->room_type . '". Please select a different room type.')
                ->withInput();
        }

        // Check for overlapping bookings  on this specific room
        if (Booking::hasOverlap($room->id, $request->check_in_date, $request->check_out_date)) {
            return redirect()->back()
                ->with('error', 'Room is not available for the selected dates. Please choose different dates.')
                ->withInput();
        }

        // Calculate total price
        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $room->price_per_night * $nights * $request->num_rooms;

        // Generate booking ID
        $bookingId = Booking::generateBookingId();

        try {
            DB::beginTransaction();

            // Create or find guest
            $guest = Guest::updateOrCreate(
                ['email' => $request->guest_email],
                [
                    'name' => $request->guest_name,
                    'mobile' => $request->guest_phone,
                    'status' => 'active',
                ]
            );

            $booking = Booking::create([
                'booking_id' => $bookingId,
                'hotel_id' => $user->hotel_id,
                'room_id' => $room->id,
                'guest_id' => $guest->id,
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
                'guest_phone' => $request->guest_phone,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'num_guests' => $request->num_guests,
                'num_rooms' => $request->num_rooms,
                'total_price' => $totalPrice,
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'status' => 'CONFIRMED',
                'special_requests' => $request->special_requests,
                'created_by' => $user->id,
            ]);

            // Auto-update room status to OCCUPIED
            $room->update(['status' => 'OCCUPIED']);

            DB::commit();

            return redirect()->route(strtolower($user->role) . '.reservations.index')
                ->with('success', 'Booking created successfully. Booking ID: ' . $bookingId . ' - Assigned to Room ' . $room->room_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create booking. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified booking
     */
    public function show($id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
                         ->where('hotel_id', $user->hotel_id)
                         ->with(['room', 'creator'])
                         ->firstOrFail();

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking
     */
    public function edit($id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
                         ->where('hotel_id', $user->hotel_id)
                         ->firstOrFail();

        // Only allow editing if not checked in or checked out
        if (in_array($booking->status, ['CHECKED_IN', 'CHECKED_OUT', 'CANCELLED'])) {
            return redirect()->back()
                ->with('error', 'Cannot edit booking with status: ' . $booking->status);
        }

        $rooms = Room::where('hotel_id', $user->hotel_id)
                    ->where('is_available', true)
                    ->get();

        return view('bookings.edit', compact('booking', 'rooms'));
    }

    /**
     * Update the specified booking
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
                         ->where('hotel_id', $user->hotel_id)
                         ->firstOrFail();

        // Only allow editing if confirmed
        if ($booking->status !== 'CONFIRMED') {
            return redirect()->back()
                ->with('error', 'Cannot edit booking with status: ' . $booking->status);
        }

        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'num_guests' => 'required|integer|min:1',
            'num_rooms' => 'required|integer|min:1',
            'payment_status' => 'required|in:PENDING,PAID',
            'payment_method' => 'required|in:CASH,CARD,BANK_TRANSFER,ONLINE',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for overlapping bookings (exclude current booking)
        if (Booking::hasOverlap($request->room_id, $request->check_in_date, $request->check_out_date, $id)) {
            return redirect()->back()
                ->with('error', 'Room is not available for the selected dates.')
                ->withInput();
        }

        // Recalculate total price
        $room = Room::findOrFail($request->room_id);
        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $room->price_per_night * $nights * $request->num_rooms;

        try {
            $booking->update([
                'room_id' => $request->room_id,
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
                'guest_phone' => $request->guest_phone,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'num_guests' => $request->num_guests,
                'num_rooms' => $request->num_rooms,
                'total_price' => $totalPrice,
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'special_requests' => $request->special_requests,
            ]);

            return redirect()->route(strtolower($user->role) . '.reservations.index')
                ->with('success', 'Booking updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update booking.')
                ->withInput();
        }
    }

    /**
     * Check-in a booking
     */
    public function checkIn($id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
                         ->where('hotel_id', $user->hotel_id)
                         ->firstOrFail();

        if ($booking->status !== 'CONFIRMED') {
            return redirect()->back()
                ->with('error', 'Only confirmed bookings can be checked in.');
        }

        try {
            DB::transaction(function() use ($booking) {
                // Update booking status
                $booking->update([
                    'status' => 'CHECKED_IN',
                    'actual_check_in' => now(),
                ]);

                // Update room status
                $booking->room->update([
                    'status' => 'OCCUPIED'
                ]);
            });

            return redirect()->back()
                ->with('success', 'Guest checked in successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to check in guest.');
        }
    }

    /**
     * Check-out a booking
     */
    public function checkOut($id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
                         ->where('hotel_id', $user->hotel_id)
                         ->firstOrFail();

        if ($booking->status !== 'CHECKED_IN') {
            return redirect()->back()
                ->with('error', 'Only checked-in bookings can be checked out.');
        }

        try {
            DB::transaction(function() use ($booking) {
                // Update booking status
                $booking->update([
                    'status' => 'CHECKED_OUT',
                    'actual_check_out' => now(),
                ]);

                // Update room status to available
                $booking->room->update([
                    'status' => 'AVAILABLE'
                ]);

                // Create commission record if not already exists
                if (!BookingCommission::where('booking_id', $booking->id)->exists()) {
                    $commissionService = app(CommissionService::class);
                    $commissionService->createBookingCommission($booking);
                }
            });

            return redirect()->back()
                ->with('success', 'Guest checked out successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to check out guest.');
        }
    }

    /**
     * Cancel a booking
     */
    public function cancel($id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
                         ->where('hotel_id', $user->hotel_id)
                         ->firstOrFail();

        if (in_array($booking->status, ['CHECKED_OUT', 'CANCELLED'])) {
            return redirect()->back()
                ->with('error', 'Cannot cancel booking with status: ' . $booking->status);
        }

        try {
            $booking->update([
                'status' => 'CANCELLED',
                'cancelled_at' => now(),
            ]);

            // If room is occupied, make it available
            if ($booking->room->status === 'OCCUPIED') {
                $booking->room->update(['status' => 'AVAILABLE']);
            }

            return redirect()->back()
                ->with('success', 'Booking cancelled successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel booking.');
        }
    }

    /**
     * Mark booking payment as paid
     */
    public function markAsPaid(Request $request, $id)
    {
        $user = Auth::user();

        $booking = Booking::where('id', $id)
                         ->where('hotel_id', $user->hotel_id)
                         ->firstOrFail();

        // Validate payment method
        $validated = $request->validate([
            'payment_method' => 'required|in:CASH,CARD,BANK_TRANSFER,ONLINE',
            'payment_notes' => 'nullable|string|max:500'
        ]);

        try {
            // Determine payment method type for commission tracking
            $paymentMethodType = 'pay_at_hotel'; // Default for Cash, Card, Bank Transfer
            if ($validated['payment_method'] == 'ONLINE') {
                $paymentMethodType = 'pay_online'; // Platform receives payment
            }

            $updateData = [
                'payment_status' => 'PAID',
                'payment_method' => $validated['payment_method'],
                'payment_method_type' => $paymentMethodType,
                'commission_status' => 'Pending', // Mark for commission accounting
            ];

            // Only add payment_notes if the column exists in the table
            if (Schema::hasColumn('bookings', 'payment_notes')) {
                $updateData['payment_notes'] = $validated['payment_notes'] ?? null;
            }

            $booking->update($updateData);

            return redirect()->back()
                ->with('success', 'Payment marked as PAID successfully for booking ' . $booking->booking_id);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update payment status: ' . $e->getMessage());
        }
    }

    /**
     * Delete a booking (soft delete or actual delete based on user role)
     */
    public function destroy($id)
    {
        $user = Auth::user();

        // Only owners can delete bookings
        if (strtoupper($user->role) !== 'OWNER') {
            return redirect()->back()
                ->with('error', 'Unauthorized. Only owners can delete bookings.');
        }

        $booking = Booking::where('id', $id)
                         ->where('hotel_id', $user->hotel_id)
                         ->firstOrFail();

        try {
            $booking->delete();

            return redirect()->back()
                ->with('success', 'Booking deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete booking.');
        }
    }

    /**
     * Get available rooms for booking (AJAX)
     */
    public function getAvailableRooms(Request $request)
    {
        $user = Auth::user();
        
        $query = Room::where('hotel_id', $user->hotel_id)
                    ->where('is_available', true);
        
        // If dates are provided, check for availability
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $query->whereDoesntHave('bookings', function($q) use ($request) {
                $q->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
                  ->where(function($q2) use ($request) {
                      $q2->whereBetween('check_in_date', [$request->check_in, $request->check_out])
                         ->orWhereBetween('check_out_date', [$request->check_in, $request->check_out])
                         ->orWhere(function($q3) use ($request) {
                             $q3->where('check_in_date', '<=', $request->check_in)
                                ->where('check_out_date', '>=', $request->check_out);
                         });
                  });
            });
        }
        
        $rooms = $query->get(['id', 'room_number', 'room_type', 'price_per_night']);
        
        return response()->json([
            'success' => true,
            'rooms' => $rooms
        ]);
    }
}
