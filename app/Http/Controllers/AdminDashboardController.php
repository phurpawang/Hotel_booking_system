<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingCommission;
use App\Models\HotelPayout;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Show admin dashboard
     */
    public function index()
    {
        $pendingHotels = Hotel::where('status', 'pending')->count();
        $approvedHotels = Hotel::where('status', 'approved')->count();
        $rejectedHotels = Hotel::where('status', 'rejected')->count();
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalHotels = Hotel::count();

        // Platform commission statistics
        $currentMonth = Carbon::now();
        $platformStats = $this->commissionService->getPlatformStatistics($currentMonth->year, $currentMonth->month);

        // Recent pending payouts
        $pendingPayouts = HotelPayout::with('hotel')
            ->where('payout_status', 'pending')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(10)
            ->get();

        // All-time platform revenue
        $totalPlatformRevenue = BookingCommission::sum('commission_amount');

        // Monthly trends for charts (last 6 months)
        $months = [];
        $commissionData = [];
        $bookingData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $monthlyCommission = BookingCommission::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('commission_amount');
            
            $commissionData[] = $monthlyCommission;

            $monthlyBookings = Booking::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $bookingData[] = $monthlyBookings;
        }

        $chartData = [
            'labels' => $months,
            'commissions' => $commissionData,
            'bookings' => $bookingData
        ];

        // Recent bookings
        $recentBookings = Booking::with(['hotel', 'room', 'commission'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent hotels
        $recentHotels = Hotel::with('owner')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'pendingHotels',
            'approvedHotels',
            'rejectedHotels',
            'totalUsers',
            'totalBookings',
            'totalHotels',
            'platformStats',
            'totalPlatformRevenue',
            'pendingPayouts',
            'chartData',
            'recentBookings',
            'recentHotels'
        ));
    }

    /**
     * Show pending hotel registrations
     */
    public function pendingHotels()
    {
        $hotels = Hotel::where('status', 'pending')
                      ->with('owner')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('admin.hotels.pending', compact('hotels'));
    }

    /**
     * Show all hotels
     */
    public function allHotels()
    {
        $hotels = Hotel::with('owner')
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);

        return view('admin.hotels.index', compact('hotels'));
    }

    /**
     * Show hotel details
     */
    public function showHotelDetails($id)
    {
        $hotel = Hotel::with(['owner', 'users', 'rooms', 'bookings'])->findOrFail($id);

        return view('admin.hotels.details', compact('hotel'));
    }

    /**
     * Approve hotel
     */
    public function approveHotel($id)
    {
        try {
            $hotel = Hotel::findOrFail($id);

            if ($hotel->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Only pending hotels can be approved.');
            }

            $hotel->update([
                'status' => 'approved',
                'rejection_reason' => null,
            ]);

            return redirect()->back()
                ->with('success', "Hotel '{$hotel->hotel_name}' has been approved successfully.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve hotel. Please try again.');
        }
    }

    /**
     * Reject hotel
     */
    public function rejectHotel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $hotel = Hotel::findOrFail($id);

            if ($hotel->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Only pending hotels can be rejected.');
            }

            $hotel->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            return redirect()->back()
                ->with('success', "Hotel '{$hotel->hotel_name}' has been rejected.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject hotel. Please try again.');
        }
    }

    /**
     * View all users
     */
    public function allUsers()
    {
        $users = User::with('hotel')
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * View all bookings
     */
    public function allBookings()
    {
        $bookings = Booking::with(['hotel', 'room'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }
}
