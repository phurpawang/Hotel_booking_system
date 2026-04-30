<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private function getOwnerHotel()
    {
        return Auth::user()->hotel;
    }

    public function index()
    {
        $hotel = $this->getOwnerHotel();
        $payments = Payment::where('hotel_id', $hotel->id)
            ->with('booking')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate total revenue from PAID bookings
        $totalRevenue = Booking::where('hotel_id', $hotel->id)
            ->where('payment_status', 'PAID')
            ->sum('total_price');

        // Calculate pending payments from PENDING bookings (CONFIRMED or CHECKED_IN)
        $pendingPayments = Booking::where('hotel_id', $hotel->id)
            ->where('payment_status', 'PENDING')
            ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
            ->sum('total_price');

        return view('owner.payments.index', compact('hotel', 'payments', 'totalRevenue', 'pendingPayments'));
    }

    public function show($id)
    {
        $hotel = $this->getOwnerHotel();
        $payment = Payment::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->with('booking')
            ->firstOrFail();

        return view('owner.payments.show', compact('hotel', 'payment'));
    }
}
