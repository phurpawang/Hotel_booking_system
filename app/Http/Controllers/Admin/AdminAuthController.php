<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\BookingCommission;
use App\Models\HotelPayout;
use App\Services\CommissionService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Show admin login form
     */
    public function showLogin()
    {
        // Redirect to dashboard if already logged in
        if (Session::has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Process admin login
     */
    public function login(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find admin by username
        $admin = Admin::where('username', $validated['username'])->first();

        // Check if admin exists and password matches
        if ($admin && Hash::check($validated['password'], $admin->password)) {
            // Store admin session
            Session::put('admin_id', $admin->id);
            Session::put('admin_username', $admin->username);

            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $admin->username . '!');
        }

        // Invalid credentials
        return redirect()->back()->withErrors(['error' => 'Invalid username or password'])->withInput();
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $adminUsername = Session::get('admin_username', 'Admin');

        // Get statistics
        $totalHotels = Hotel::count();
        $totalBookings = Booking::count();
        $totalReservations = Booking::count(); // Keep for backward compatibility
        $pendingPayments = Booking::where('payment_status', 'PENDING')->count();
        $totalRevenue = Booking::where('payment_status', 'PAID')->sum('total_price');

        // Platform commission statistics (current month)
        $platformStats = $this->commissionService->getPlatformStatistics(now()->year, now()->month);
        $totalPlatformRevenue = BookingCommission::sum('commission_amount');
        
        // Get pending payouts
        $pendingPayouts = HotelPayout::with('hotel')
            ->where('payout_status', 'pending')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Get recent reservations with commission
        $recentBookings = Booking::with(['hotel', 'room', 'commission'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent hotel registrations
        $recentHotels = Hotel::with('owner')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Chart data for 6 months
        $chartData = [
            'labels' => [],
            'commissions' => [],
            'bookings' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartData['labels'][] = $date->format('M Y');
            
            $monthCommissions = BookingCommission::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('commission_amount');
            $chartData['commissions'][] = $monthCommissions;
            
            $monthBookings = Booking::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $chartData['bookings'][] = $monthBookings;
        }

        return view('admin.dashboard', compact(
            'adminUsername',
            'totalHotels',
            'totalBookings',
            'totalReservations',
            'pendingPayments',
            'totalRevenue',
            'platformStats',
            'totalPlatformRevenue',
            'pendingPayouts',
            'recentBookings',
            'recentHotels',
            'chartData'
        ));
    }

    /**
     * Logout admin
     */
    public function logout()
    {
        Session::forget('admin_id');
        Session::forget('admin_username');
        Session::flush();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully');
    }
}


