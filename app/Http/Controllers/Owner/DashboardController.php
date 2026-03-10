<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Payment;
use App\Models\BookingCommission;
use App\Models\HotelPayout;
use App\Models\HotelDeregistrationRequest;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    private function getOwnerHotel()
    {
        $user = Auth::user();
        return $user->hotel;
    }

    public function index()
    {
        $hotel = $this->getOwnerHotel();
        
        if (!$hotel) {
            abort(404, 'Hotel not found');
        }

        // Statistics
        $totalReservations = Booking::where('hotel_id', $hotel->id)->count();
        $todayCheckIns = Booking::where('hotel_id', $hotel->id)
            ->whereDate('check_in_date', today())
            ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
            ->count();
        $todayCheckOuts = Booking::where('hotel_id', $hotel->id)
            ->whereDate('check_out_date', today())
            ->where('status', 'CHECKED_IN')
            ->count();
        
        $totalRooms = Room::where('hotel_id', $hotel->id)->sum('quantity');
        $availableRooms = Room::where('hotel_id', $hotel->id)
            ->where('status', 'AVAILABLE')
            ->sum('quantity');
        
        // Get monthly revenue and commission data
        $currentMonth = Carbon::now();
        $monthlyRevenue = Payment::where('hotel_id', $hotel->id)
            ->where('status', 'PAID')
            ->whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->sum('amount');

        // Commission statistics for current month
        $monthlyCommissions = BookingCommission::where('hotel_id', $hotel->id)
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->get();

        $monthlyCommissionTotal = $monthlyCommissions->sum('commission_amount');
        $monthlyBaseRevenue = $monthlyCommissions->sum('base_amount');
        $monthlyGuestPayments = $monthlyCommissions->sum('final_amount');
        
        // Hotel payout for current month
        $hotelPayout = $monthlyBaseRevenue; // Hotel receives base amount after commission
        
        // Current month payout record
        $currentPayout = HotelPayout::where('hotel_id', $hotel->id)
            ->where('year', $currentMonth->year)
            ->where('month', $currentMonth->month)
            ->first();

        // Get commission summary
        $commissionSummary = $this->commissionService->getHotelRevenueSummary($hotel->id, $currentMonth->year, $currentMonth->month);

        // Chart data - Booking trends (last 6 months)
        $months = [];
        $bookingData = [];
        $revenueData = [];
        $commissionData = [];
        $payoutData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $bookingData[] = Booking::where('hotel_id', $hotel->id)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $revenueData[] = Payment::where('hotel_id', $hotel->id)
                ->where('status', 'PAID')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            // Commission data for the month
            $monthCommissions = BookingCommission::where('hotel_id', $hotel->id)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->get();
            
            $commissionData[] = $monthCommissions->sum('commission_amount');
            $payoutData[] = $monthCommissions->sum('base_amount');
        }
        
        // Structure data for charts
        $bookingTrends = [
            'labels' => $months,
            'data' => $bookingData
        ];
        
        $revenueTrends = [
            'labels' => $months,
            'data' => $revenueData
        ];

        $commissionTrends = [
            'labels' => $months,
            'commission' => $commissionData,
            'payout' => $payoutData
        ];

        // Recent bookings with commission info
        $recentBookings = Booking::where('hotel_id', $hotel->id)
            ->with(['room', 'user', 'commission'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent payouts
        $recentPayouts = HotelPayout::where('hotel_id', $hotel->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        // Check for pending deregistration request
        $deregistrationRequest = HotelDeregistrationRequest::where('hotel_id', $hotel->id)
            ->where('status', 'PENDING')
            ->first();

        return view('owner.dashboard', compact(
            'hotel',
            'totalReservations',
            'todayCheckIns',
            'todayCheckOuts',
            'totalRooms',
            'availableRooms',
            'monthlyRevenue',
            'monthlyCommissionTotal',
            'monthlyBaseRevenue',
            'monthlyGuestPayments',
            'hotelPayout',
            'currentPayout',
            'commissionSummary',
            'bookingTrends',
            'revenueTrends',
            'commissionTrends',
            'recentBookings',
            'recentPayouts',
            'deregistrationRequest'
        ));
    }
}
