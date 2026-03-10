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

        $totalRevenue = Payment::where('hotel_id', $hotel->id)
            ->where('status', 'PAID')
            ->sum('amount');

        $pendingPayments = Payment::where('hotel_id', $hotel->id)
            ->where('status', 'PENDING')
            ->sum('amount');

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
