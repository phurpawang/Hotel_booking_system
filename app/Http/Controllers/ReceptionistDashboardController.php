<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\BookingCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceptionistDashboardController extends Controller
{
    /**
     * Show receptionist dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $hotel = $user->hotel;

        // Today's check-ins and check-outs count
        $todayCheckIns = Booking::where('hotel_id', $user->hotel_id)
                               ->whereDate('check_in_date', today())
                               ->whereIn(DB::raw('UPPER(status)'), ['CONFIRMED', 'CHECKED_IN'])
                               ->count();

        $todayCheckOuts = Booking::where('hotel_id', $user->hotel_id)
                                ->whereDate('check_out_date', today())
                                ->where(DB::raw('UPPER(status)'), 'CHECKED_IN')
                                ->count();

        // Pending operations count
        $pendingCheckIns = Booking::where('hotel_id', $user->hotel_id)
                                  ->whereDate('check_in_date', today())
                                  ->where(DB::raw('UPPER(status)'), 'CONFIRMED')
                                  ->count();
        
        $pendingCheckOuts = Booking::where('hotel_id', $user->hotel_id)
                                   ->whereDate('check_out_date', today())
                                   ->where(DB::raw('UPPER(status)'), 'CHECKED_IN')
                                   ->count();

        // Room statistics (using sum of quantities)
        $totalRooms = Room::where('hotel_id', $user->hotel_id)->count();
        $occupiedRooms = Room::where('hotel_id', $user->hotel_id)
                            ->where(DB::raw('UPPER(status)'), 'OCCUPIED')
                            ->count();
        $availableRooms = $totalRooms - $occupiedRooms;

        // Payment status counts (for receptionist to track)
        $paymentStats = [
            'paid_online' => BookingCommission::where('hotel_id', $user->hotel_id)
                ->where('payment_method', 'pay_online')
                ->where('commission_status', 'paid')
                ->count(),
            'pending_at_hotel' => BookingCommission::where('hotel_id', $user->hotel_id)
                ->where('payment_method', 'pay_at_hotel')
                ->where('commission_status', 'pending')
                ->whereHas('booking', function($query) {
                    $query->where(DB::raw('UPPER(status)'), 'CONFIRMED')
                          ->orWhere(DB::raw('UPPER(status)'), 'CHECKED_IN');
                })
                ->count(),
        ];

        // Today's arrivals and departures lists with payment info
        $todayCheckInsList = Booking::where('hotel_id', $user->hotel_id)
                                    ->whereDate('check_in_date', today())
                                    ->with(['room', 'commission'])
                                    ->orderBy('check_in_date', 'asc')
                                    ->get();

        $todayCheckOutsList = Booking::where('hotel_id', $user->hotel_id)
                                     ->whereDate('check_out_date', today())
                                     ->with(['room', 'commission'])
                                     ->orderBy('check_out_date', 'asc')
                                     ->get();

        // Recent bookings with payment status
        $recentBookings = Booking::where('hotel_id', $user->hotel_id)
                                ->with(['room', 'commission'])
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();

        // Enhanced Statistics (like ReservationController, but NO revenue for reception)
        $totalBookings = Booking::where('hotel_id', $user->hotel_id)->count();
        $stats = [
            'total' => $totalBookings,
            'today_checkins' => $todayCheckIns,
            'today_checkouts' => $todayCheckOuts,
            'pending' => Booking::where('hotel_id', $user->hotel_id)
                ->where(DB::raw('UPPER(status)'), 'CONFIRMED')
                ->where('payment_status', 'PENDING')
                ->count(),
            'monthly_revenue' => 0, // Reception doesn't see revenue
            'confirmed' => Booking::where('hotel_id', $user->hotel_id)->where(DB::raw('UPPER(status)'), 'CONFIRMED')->count(),
            'checked_in' => Booking::where('hotel_id', $user->hotel_id)->where(DB::raw('UPPER(status)'), 'CHECKED_IN')->count(),
            'checked_out' => Booking::where('hotel_id', $user->hotel_id)->where(DB::raw('UPPER(status)'), 'CHECKED_OUT')->count(),
            'cancelled' => Booking::where('hotel_id', $user->hotel_id)->where(DB::raw('UPPER(status)'), 'CANCELLED')->count(),
        ];

        return view('reception.dashboard', compact(
            'hotel',
            'todayCheckIns',
            'todayCheckOuts',
            'pendingCheckIns',
            'pendingCheckOuts',
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'todayCheckInsList',
            'todayCheckOutsList',
            'recentBookings',
            'stats',
            'paymentStats'
        ));
    }

    /**
     * View bookings
     */
    public function viewBookings()
    {
        $user = Auth::user();
        $bookings = Booking::where('hotel_id', $user->hotel_id)
                          ->with('room')
                          ->orderBy('check_in_date', 'desc')
                          ->paginate(15);

        return view('reception.bookings', compact('bookings'));
    }

    /**
     * Check-in guest
     */
    public function checkIn($id)
    {
        try {
            $user = Auth::user();
            $booking = Booking::where('id', $id)
                             ->where('hotel_id', $user->hotel_id)
                             ->where('status', 'confirmed')
                             ->firstOrFail();

            $booking->update([
                'status' => 'checked_in',
                'actual_check_in' => now(),
            ]);

            return redirect()->back()
                ->with('success', 'Guest checked in successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to check in guest.');
        }
    }

    /**
     * Check-out guest
     */
    public function checkOut($id)
    {
        try {
            $user = Auth::user();
            $booking = Booking::where('id', $id)
                             ->where('hotel_id', $user->hotel_id)
                             ->where('status', 'checked_in')
                             ->firstOrFail();

            $booking->update([
                'status' => 'completed',
                'actual_check_out' => now(),
            ]);

            return redirect()->back()
                ->with('success', 'Guest checked out successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to check out guest.');
        }
    }

    /**
     * Show today's arrivals
     */
    public function todayArrivals()
    {
        $user = Auth::user();
        $arrivals = Booking::where('hotel_id', $user->hotel_id)
                          ->whereDate('check_in_date', today())
                          ->with('room')
                          ->orderBy('check_in_date', 'asc')
                          ->get();

        return view('reception.arrivals', compact('arrivals'));
    }

    /**
     * Show today's departures
     */
    public function todayDepartures()
    {
        $user = Auth::user();
        $departures = Booking::where('hotel_id', $user->hotel_id)
                            ->whereDate('check_out_date', today())
                            ->with('room')
                            ->orderBy('check_out_date', 'asc')
                            ->get();

        return view('reception.departures', compact('departures'));
    }

    /**
     * Bookings page (alias for viewBookings)
     */
    public function bookings()
    {
        return $this->viewBookings();
    }

    /**
     * Check-in list page
     */
    public function checkinList()
    {
        return $this->todayArrivals();
    }

    /**
     * Check-out list page
     */
    public function checkoutList()
    {
        return $this->todayDepartures();
    }

    /**
     * Process check-in (alias for checkIn)
     */
    public function processCheckin($id)
    {
        return $this->checkIn($id);
    }

    /**
     * Process check-out (alias for checkOut)
     */
    public function processCheckout($id)
    {
        return $this->checkOut($id);
    }
}
