<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\HotelPayout;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RevenueController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Display revenue dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $hotel = $user->hotel;

        if (!$hotel) {
            return redirect()->back()->with('error', 'No hotel associated with your account.');
        }

        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month');

        // Get revenue summary
        $summary = $this->commissionService->getHotelRevenueSummary($hotel->id, $year, $month);

        // Get payouts
        $payoutsQuery = HotelPayout::where('hotel_id', $hotel->id)
            ->where('year', $year);

        if ($month) {
            $payoutsQuery->where('month', $month);
        }

        $payouts = $payoutsQuery->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();

        // Get current month status
        $currentMonth = Carbon::now();
        $currentPayout = HotelPayout::where('hotel_id', $hotel->id)
            ->where('year', $currentMonth->year)
            ->where('month', $currentMonth->month)
            ->first();

        return view('owner.revenue.index', compact('summary', 'payouts', 'currentPayout', 'year', 'month', 'hotel'));
    }

    /**
     * Show detailed payout information
     */
    public function show($payoutId)
    {
        $user = Auth::user();
        
        $payout = HotelPayout::where('hotel_id', $user->hotel_id)
            ->with(['hotel', 'processor'])
            ->findOrFail($payoutId);

        $commissions = $payout->commissions()->with(['booking', 'room'])->get();

        return view('owner.revenue.show', compact('payout', 'commissions'));
    }

    /**
     * Monthly revenue report
     */
    public function monthlyReport(Request $request)
    {
        $user = Auth::user();
        $hotel = $user->hotel;

        $year = $request->get('year', Carbon::now()->year);

        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $summary = $this->commissionService->getHotelRevenueSummary($hotel->id, $year, $month);
            $payout = HotelPayout::where('hotel_id', $hotel->id)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            $monthlyData[] = [
                'month' => $month,
                'month_name' => Carbon::create($year, $month, 1)->format('F'),
                'summary' => $summary,
                'payout' => $payout,
            ];
        }

        $yearlyTotal = $this->commissionService->getHotelRevenueSummary($hotel->id, $year);

        return view('owner.revenue.monthly-report', compact('monthlyData', 'yearlyTotal', 'year', 'hotel'));
    }
}
