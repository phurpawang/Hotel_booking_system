<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelPayout;
use App\Models\Hotel;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CommissionController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Display commission dashboard
     */
    public function index(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month');

        // Get platform statistics
        $stats = $this->commissionService->getPlatformStatistics($year, $month);

        // Get all payouts for the period
        $query = HotelPayout::with('hotel')
            ->where('year', $year);

        if ($month) {
            $query->where('month', $month);
        }

        $payouts = $query->orderBy('year', 'desc')
                        ->orderBy('month', 'desc')
                        ->paginate(20);

        return view('admin.commissions.index', compact('payouts', 'stats', 'year', 'month'));
    }

    /**
     * Show monthly commission report for a specific hotel
     */
    public function show($payoutId)
    {
        $payout = HotelPayout::with(['hotel', 'processor'])
            ->findOrFail($payoutId);

        $commissions = $payout->commissions()->with(['booking', 'room'])->get();

        return view('admin.commissions.show', compact('payout', 'commissions'));
    }

    /**
     * Generate payouts for a specific month
     */
    public function generatePayouts(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $payouts = $this->commissionService->generateAllHotelPayouts(
            $request->year,
            $request->month
        );

        return redirect()->route('admin.commissions.index', [
            'year' => $request->year,
            'month' => $request->month
        ])->with('success', 'Generated payouts for ' . count($payouts) . ' hotels.');
    }

    /**
     * Show payout payment form
     */
    public function payoutForm($payoutId)
    {
        $payout = HotelPayout::with('hotel')->findOrFail($payoutId);

        if ($payout->payout_status === 'paid') {
            return redirect()->back()
                ->with('error', 'This payout has already been marked as paid.');
        }

        return view('admin.commissions.payout-form', compact('payout'));
    }

    /**
     * Mark payout as paid
     */
    public function markAsPaid(Request $request, $payoutId)
    {
        $request->validate([
            'payout_reference' => 'required|string|max:255',
            'payout_notes' => 'nullable|string|max:1000',
        ]);

        $payout = HotelPayout::findOrFail($payoutId);

        if ($payout->payout_status === 'paid') {
            return redirect()->back()
                ->with('error', 'This payout has already been marked as paid.');
        }

        $success = $this->commissionService->markPayoutAsPaid(
            $payout,
            Auth::id(),
            $request->payout_reference,
            $request->payout_notes
        );

        if ($success) {
            return redirect()->route('admin.commissions.show', $payout->id)
                ->with('success', 'Payout marked as paid successfully.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to mark payout as paid.');
        }
    }

    /**
     * Platform earnings report
     */
    public function earnings(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        $monthlyStats = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyStats[$month] = $this->commissionService->getPlatformStatistics($year, $month);
        }

        $yearlyStats = $this->commissionService->getPlatformStatistics($year);

        return view('admin.commissions.earnings', compact('monthlyStats', 'yearlyStats', 'year'));
    }

    /**
     * Hotel-wise commission report
     */
    public function hotelReport(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month');

        $hotels = Hotel::where('status', 'APPROVED')
            ->with(['commissions' => function($query) use ($year, $month) {
                $query->whereYear('booking_date', $year);
                if ($month) {
                    $query->whereMonth('booking_date', $month);
                }
            }])
            ->get()
            ->map(function($hotel) {
                return [
                    'hotel' => $hotel,
                    'total_bookings' => $hotel->commissions->count(),
                    'total_commission' => $hotel->commissions->sum('commission_amount'),
                    'total_payout' => $hotel->commissions->sum('base_amount'),
                    'pending_commission' => $hotel->commissions->where('commission_status', 'pending')->sum('commission_amount'),
                ];
            })
            ->sortByDesc('total_commission');

        return view('admin.commissions.hotel-report', compact('hotels', 'year', 'month'));
    }
}
