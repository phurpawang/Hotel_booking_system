<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Show reports page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $hotel = $user->hotel;

        // Default date range: current month
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        // Revenue Report (Owner only)
        $revenueData = null;
        if (strtoupper($user->role) === 'OWNER') {
            $revenueData = $this->getRevenueReport($user->hotel_id, $startDate, $endDate);
        }

        // Booking Report
        $bookingData = $this->getBookingReport($user->hotel_id, $startDate, $endDate);

        // Occupancy Report
        $occupancyData = $this->getOccupancyReport($user->hotel_id, $startDate, $endDate);

        return view('reports.index', compact('hotel', 'revenueData', 'bookingData', 'occupancyData', 'startDate', 'endDate'));
    }

    /**
     * Get revenue report data
     */
    private function getRevenueReport($hotelId, $startDate, $endDate)
    {
        $totalRevenue = Booking::where('hotel_id', $hotelId)
            ->where('payment_status', 'PAID')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->sum('total_price');

        $pendingRevenue = Booking::where('hotel_id', $hotelId)
            ->where('payment_status', 'PENDING')
            ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->sum('total_price');

        $refundedAmount = Booking::where('hotel_id', $hotelId)
            ->where('status', 'CANCELLED')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->sum('refund_amount');

        // Monthly breakdown
        $monthlyRevenue = Booking::where('hotel_id', $hotelId)
            ->where('payment_status', 'PAID')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->selectRaw('YEAR(check_in_date) as year, MONTH(check_in_date) as month, SUM(total_price) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return [
            'total_revenue' => $totalRevenue,
            'pending_revenue' => $pendingRevenue,
            'refunded_amount' => $refundedAmount,
            'net_revenue' => $totalRevenue - $refundedAmount,
            'monthly_revenue' => $monthlyRevenue,
        ];
    }

    /**
     * Get booking report data
     */
    private function getBookingReport($hotelId, $startDate, $endDate)
    {
        $totalBookings = Booking::where('hotel_id', $hotelId)
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->count();

        $confirmedBookings = Booking::where('hotel_id', $hotelId)
            ->where('status', 'CONFIRMED')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->count();

        $checkedInBookings = Booking::where('hotel_id', $hotelId)
            ->where('status', 'CHECKED_IN')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->count();

        $checkedOutBookings = Booking::where('hotel_id', $hotelId)
            ->where('status', 'CHECKED_OUT')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->count();

        $cancelledBookings = Booking::where('hotel_id', $hotelId)
            ->where('status', 'CANCELLED')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->count();

        // Daily breakdown
        $dailyBookings = Booking::where('hotel_id', $hotelId)
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->selectRaw('DATE(check_in_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total' => $totalBookings,
            'confirmed' => $confirmedBookings,
            'checked_in' => $checkedInBookings,
            'checked_out' => $checkedOutBookings,
            'cancelled' => $cancelledBookings,
            'cancellation_rate' => $totalBookings > 0 ? round(($cancelledBookings / $totalBookings) * 100, 2) : 0,
            'daily_bookings' => $dailyBookings,
        ];
    }

    /**
     * Get occupancy report data
     */
    private function getOccupancyReport($hotelId, $startDate, $endDate)
    {
        $totalRooms = Room::where('hotel_id', $hotelId)->sum('quantity');
        $occupiedRooms = Room::where('hotel_id', $hotelId)
            ->where('status', 'OCCUPIED')
            ->sum('quantity');

        $availableRooms = $totalRooms - $occupiedRooms;
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;

        // Calculate average occupancy for the period
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $start->diffInDays($end) + 1;

        $totalRoomNights = $totalRooms * $days;
        $bookedRoomNights = Booking::where('hotel_id', $hotelId)
            ->whereIn('status', ['CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT'])
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in_date', [$startDate, $endDate])
                      ->orWhereBetween('check_out_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('check_in_date', '<=', $startDate)
                            ->where('check_out_date', '>=', $endDate);
                      });
            })
            ->get()
            ->sum(function($booking) use ($startDate, $endDate) {
                $checkIn = max($booking->check_in_date, Carbon::parse($startDate));
                $checkOut = min($booking->check_out_date, Carbon::parse($endDate));
                return $checkIn->diffInDays($checkOut);
            });

        $averageOccupancyRate = $totalRoomNights > 0 ? round(($bookedRoomNights / $totalRoomNights) * 100, 2) : 0;

        return [
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'available_rooms' => $availableRooms,
            'current_occupancy_rate' => $occupancyRate,
            'average_occupancy_rate' => $averageOccupancyRate,
            'total_room_nights' => $totalRoomNights,
            'booked_room_nights' => $bookedRoomNights,
        ];
    }

    /**
     * Export revenue report to CSV
     */
    public function exportRevenue(Request $request)
    {
        $user = Auth::user();

        // Only owners can export revenue reports
        if (strtoupper($user->role) !== 'OWNER') {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $bookings = Booking::where('hotel_id', $user->hotel_id)
            ->where('payment_status', 'PAID')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->with('room')
            ->orderBy('check_in_date')
            ->get();

        $filename = 'revenue_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Booking ID', 'Guest Name', 'Room', 'Check-in', 'Check-out', 'Nights', 'Amount', 'Payment Method', 'Status']);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_id,
                    $booking->guest_name,
                    $booking->room->room_number ?? 'N/A',
                    $booking->check_in_date->format('Y-m-d'),
                    $booking->check_out_date->format('Y-m-d'),
                    $booking->nights,
                    $booking->total_price,
                    $booking->payment_method,
                    $booking->status,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export booking report to CSV
     */
    public function exportBookings(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $bookings = Booking::where('hotel_id', $user->hotel_id)
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->with(['room', 'creator'])
            ->orderBy('check_in_date')
            ->get();

        $filename = 'booking_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Booking ID', 'Guest Name', 'Phone', 'Email', 'Room', 'Check-in', 'Check-out', 'Guests', 'Amount', 'Payment Status', 'Booking Status', 'Created By', 'Created At']);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_id,
                    $booking->guest_name,
                    $booking->guest_phone,
                    $booking->guest_email,
                    $booking->room->room_number ?? 'N/A',
                    $booking->check_in_date->format('Y-m-d'),
                    $booking->check_out_date->format('Y-m-d'),
                    $booking->num_guests,
                    $booking->total_price,
                    $booking->payment_status,
                    $booking->status,
                    $booking->creator->name ?? 'Guest',
                    $booking->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export occupancy report to CSV
     */
    public function exportOccupancy(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $rooms = Room::where('hotel_id', $user->hotel_id)
            ->with(['bookings' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in_date', [$startDate, $endDate])
                      ->whereIn('status', ['CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT']);
            }])
            ->get();

        $filename = 'occupancy_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($rooms, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Room Number', 'Room Type', 'Status', 'Total Bookings', 'Total Nights Booked', 'Revenue']);

            foreach ($rooms as $room) {
                $totalNights = $room->bookings->sum('nights');
                $revenue = $room->bookings->where('payment_status', 'PAID')->sum('total_price');

                fputcsv($file, [
                    $room->room_number,
                    $room->room_type,
                    $room->status,
                    $room->bookings->count(),
                    $totalNights,
                    $revenue,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
