<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\BookingCommission;
use App\Models\HotelInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManagerDashboardController extends Controller
{
    /**
     * Show manager dashboard with Chart.js data
     */
    public function index()
    {
        $user = Auth::user();
        $hotel = $user->hotel;

        // Room statistics (using sum of quantities)
        $totalRooms = Room::where('hotel_id', $user->hotel_id)->count();
        $occupiedRooms = Room::where('hotel_id', $user->hotel_id)
                            ->where(DB::raw('UPPER(status)'), 'OCCUPIED')
                            ->count();
        $availableRooms = $totalRooms - $occupiedRooms;
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

        // Booking statistics
        $totalBookings = Booking::where('hotel_id', $user->hotel_id)->count();
        $pendingBookings = Booking::where('hotel_id', $user->hotel_id)
                                  ->where(DB::raw('UPPER(status)'), 'CONFIRMED')
                                  ->count();
        
        $todayCheckIns = Booking::where('hotel_id', $user->hotel_id)
                               ->whereDate('check_in_date', today())
                               ->whereIn(DB::raw('UPPER(status)'), ['CONFIRMED', 'CHECKED_IN'])
                               ->count();
        
        $todayCheckOuts = Booking::where('hotel_id', $user->hotel_id)
                                ->whereDate('check_out_date', today())
                                ->where(DB::raw('UPPER(status)'), 'CHECKED_IN')
                                ->count();

        // Commission data (readonly for managers)
        $currentMonth = Carbon::now();
        $monthlyCommissions = BookingCommission::where('hotel_id', $user->hotel_id)
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->get();

        $commissionInfo = [
            'total_bookings' => $monthlyCommissions->count(),
            'total_guest_payments' => $monthlyCommissions->sum('final_amount'),
            'total_commission' => $monthlyCommissions->sum('commission_amount'),
            'hotel_revenue' => $monthlyCommissions->sum('base_amount'),
            'pay_online_count' => $monthlyCommissions->where('payment_method', 'pay_online')->count(),
            'pay_at_hotel_count' => $monthlyCommissions->where('payment_method', 'pay_at_hotel')->count(),
        ];

        // Recent bookings with commission
        $recentBookings = Booking::where('hotel_id', $user->hotel_id)
                                ->with(['room', 'commission'])
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();

        // 6-Month Booking Trend for Chart.js (Manager doesn't see revenue)
        $bookingData = [];
        $bookingLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $bookingLabels[] = $month->format('M Y');
            $count = Booking::where('hotel_id', $user->hotel_id)
                ->whereYear('check_in_date', $month->year)
                ->whereMonth('check_in_date', $month->month)
                ->count();
            $bookingData[] = $count;
        }

        // 6-Month Occupancy Trend
        $occupancyData = [];
        $occupancyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $occupancyLabels[] = $month->format('M Y');
            
            // Calculate days in month
            $daysInMonth = $month->daysInMonth;
            $totalRoomNights = $totalRooms * $daysInMonth;
            
            // Calculate booked room nights
            $bookedNights = Booking::where('hotel_id', $user->hotel_id)
                ->whereYear('check_in_date', $month->year)
                ->whereMonth('check_in_date', $month->month)
                ->whereIn(DB::raw('UPPER(status)'), ['CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT'])
                ->get()
                ->sum(function($booking) {
                    return $booking->check_in_date->diffInDays($booking->check_out_date);
                });
            
            $rate = $totalRoomNights > 0 ? round(($bookedNights / $totalRoomNights) * 100, 2) : 0;
            $occupancyData[] = $rate;
        }

        // Enhanced Statistics (like ReservationController, but NO revenue for managers)
        $stats = [
            'total' => $totalBookings,
            'today_checkins' => $todayCheckIns,
            'today_checkouts' => $todayCheckOuts,
            'pending' => Booking::where('hotel_id', $user->hotel_id)
                ->where(DB::raw('UPPER(status)'), 'CONFIRMED')
                ->where('payment_status', 'PENDING')
                ->count(),
            'monthly_revenue' => 0, // Manager doesn't see revenue
            'confirmed' => Booking::where('hotel_id', $user->hotel_id)->where(DB::raw('UPPER(status)'), 'CONFIRMED')->count(),
            'checked_in' => Booking::where('hotel_id', $user->hotel_id)->where(DB::raw('UPPER(status)'), 'CHECKED_IN')->count(),
            'checked_out' => Booking::where('hotel_id', $user->hotel_id)->where(DB::raw('UPPER(status)'), 'CHECKED_OUT')->count(),
            'cancelled' => Booking::where('hotel_id', $user->hotel_id)->where(DB::raw('UPPER(status)'), 'CANCELLED')->count(),
        ];

        // Guest inquiries statistics
        $totalInquiries = HotelInquiry::where('hotel_id', $user->hotel_id)->count();
        $pendingInquiries = HotelInquiry::where('hotel_id', $user->hotel_id)->where('status', 'PENDING')->count();
        $answeredInquiries = HotelInquiry::where('hotel_id', $user->hotel_id)->where('status', 'ANSWERED')->count();
        $closedInquiries = HotelInquiry::where('hotel_id', $user->hotel_id)->where('status', 'CLOSED')->count();
        
        // Recent inquiries (last 5)
        $recentInquiries = HotelInquiry::where('hotel_id', $user->hotel_id)
                                       ->latest()
                                       ->limit(5)
                                       ->get();

        return view('manager.dashboard', compact(
            'hotel',
            'totalBookings',
            'pendingBookings',
            'occupancyRate',
            'availableRooms',
            'todayCheckIns',
            'todayCheckOuts',
            'recentBookings',
            'bookingData',
            'bookingLabels',
            'occupancyData',
            'occupancyLabels',
            'stats',
            'commissionInfo',
            'totalInquiries',
            'pendingInquiries',
            'answeredInquiries',
            'closedInquiries',
            'recentInquiries'
        ));
    }

    /**
     * Manage rooms
     */
    public function manageRooms()
    {
        $user = Auth::user();
        $rooms = Room::where('hotel_id', $user->hotel_id)
                    ->orderBy('room_number', 'asc')
                    ->get();

        return view('manager.rooms', compact('rooms'));
    }

    /**
     * Update room pricing
     */
    public function updateRoomPricing(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'price_per_night' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $user = Auth::user();
            $room = Room::where('id', $id)
                       ->where('hotel_id', $user->hotel_id)
                       ->firstOrFail();

            $room->update([
                'price_per_night' => $request->price_per_night,
            ]);

            return redirect()->back()
                ->with('success', 'Room pricing updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update room pricing.');
        }
    }

    /**
     * Update room availability
     */
    public function updateRoomAvailability($id)
    {
        try {
            $user = Auth::user();
            $room = Room::where('id', $id)
                       ->where('hotel_id', $user->hotel_id)
                       ->firstOrFail();

            $room->update([
                'is_available' => !$room->is_available,
            ]);

            $status = $room->is_available ? 'available' : 'unavailable';

            return redirect()->back()
                ->with('success', "Room marked as {$status}.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update room availability.');
        }
    }

    /**
     * View bookings
     */
    public function viewBookings()
    {
        $user = Auth::user();
        $bookings = Booking::where('hotel_id', $user->hotel_id)
                          ->with('room')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('manager.bookings', compact('bookings'));
    }

    /**
     * Update booking status
     */
    public function updateBookingStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:confirmed,cancelled,completed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $user = Auth::user();
            $booking = Booking::where('id', $id)
                             ->where('hotel_id', $user->hotel_id)
                             ->firstOrFail();

            $booking->update([
                'status' => $request->status,
            ]);

            return redirect()->back()
                ->with('success', 'Booking status updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update booking status.');
        }
    }

    /**
     * Bookings page (alias for viewBookings)
     */
    public function bookings()
    {
        return $this->viewBookings();
    }

    /**
     * Rooms page (alias for manageRooms)
     */
    public function rooms()
    {
        return $this->manageRooms();
    }

    /**
     * Rates page
     */
    public function rates()
    {
        $user = Auth::user();
        $hotel = $user->hotel;
        $rooms = Room::where('hotel_id', $user->hotel_id)->get();

        return view('manager.rates', compact('hotel', 'rooms'));
    }

    /**
     * Reports page (limited access - no revenue data)
     */
    public function reports()
    {
        $user = Auth::user();
        $hotel = $user->hotel;

        $totalBookings = Booking::where('hotel_id', $user->hotel_id)->count();
        $completedBookings = Booking::where('hotel_id', $user->hotel_id)
                                    ->where('status', 'completed')
                                    ->count();

        return view('manager.reports', compact(
            'hotel',
            'totalBookings',
            'completedBookings'
        ));
    }
}
