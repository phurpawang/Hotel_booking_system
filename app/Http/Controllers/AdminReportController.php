<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    /**
     * Display reports page
     */
    public function index()
    {
        // Total Revenue
        $totalRevenue = Booking::where('payment_status', 'PAID')->sum('total_price');
        
        // Total Reservations
        $totalReservations = Booking::count();
        
        // Total Hotels
        $totalHotels = Hotel::where('status', 'approved')->count();
        
        // Monthly Revenue (last 12 months)
        $monthlyRevenue = Booking::where('payment_status', 'PAID')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        // Bookings by status
        $bookingsByStatus = Booking::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        
        // Revenue by hotel (top 10)
        $revenueByHotel = Booking::select('hotel_id', DB::raw('SUM(total_price) as total_revenue'))
            ->where('payment_status', 'PAID')
            ->groupBy('hotel_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->with('hotel')
            ->get();
        
        return view('admin.reports.index', compact(
            'totalRevenue',
            'totalReservations',
            'totalHotels',
            'monthlyRevenue',
            'bookingsByStatus',
            'revenueByHotel'
        ));
    }
}
