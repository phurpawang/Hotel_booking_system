<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    /**
     * Display payments
     */
    public function index(Request $request)
    {
        $query = Booking::with(['hotel'])->whereNotNull('total_price');
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }
        
        $payments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Mark as paid
     */
    public function markPaid($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['payment_status' => 'PAID']);
        
        return redirect()->back()->with('success', 'Payment marked as paid');
    }

    /**
     * Issue refund
     */
    public function refund(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'refund_amount' => 'required|numeric|min:0'
        ]);
        
        $booking->update([
            'payment_status' => 'REFUNDED',
            'refund_amount' => $validated['refund_amount']
        ]);
        
        return redirect()->back()->with('success', 'Refund processed successfully');
    }
}
