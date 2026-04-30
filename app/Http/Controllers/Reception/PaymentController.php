<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display payment records
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Payment::where('hotel_id', $user->hotel_id)
            ->with(['booking.user', 'booking.room']);

        if ($search) {
            $query->whereHas('booking', function ($q) use ($search) {
                $q->where('booking_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where(DB::raw('UPPER(status)'), strtoupper($status));
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate statistics
        $stats = [
            'total_revenue' => Payment::where('hotel_id', $user->hotel_id)
                ->where(DB::raw('UPPER(status)'), 'PAID')
                ->sum('amount'),
            'pending' => Payment::where('hotel_id', $user->hotel_id)
                ->where(DB::raw('UPPER(status)'), 'PENDING')
                ->sum('amount'),
            'paid_count' => Payment::where('hotel_id', $user->hotel_id)
                ->where(DB::raw('UPPER(status)'), 'PAID')
                ->count(),
            'pending_count' => Payment::where('hotel_id', $user->hotel_id)
                ->where(DB::raw('UPPER(status)'), 'PENDING')
                ->count(),
        ];

        return view('reception.payments.index', compact('payments', 'stats', 'search', 'status'));
    }

    /**
     * Show payment details
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $payment = Payment::where('hotel_id', $user->hotel_id)
            ->with(['booking.user', 'booking.room'])
            ->findOrFail($id);

        return view('reception.payments.show', compact('payment'));
    }

    /**
     * Show form to record a new payment
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get bookings that need payment with their relationships
        $bookings = Booking::where('hotel_id', $user->hotel_id)
            ->whereIn(DB::raw('UPPER(status)'), ['CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT'])
            ->with('user', 'room')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($booking) {
                // Only include bookings with valid user and room relationships
                return $booking->user && $booking->room;
            })
            ->values(); // Reset array keys

        return view('reception.payments.create', compact('bookings'));
    }

    /**
     * Store a new payment record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:CASH,CARD,ONLINE,BANK_TRANSFER',
            'transaction_id' => 'nullable|string|max:255',
            'status' => 'required|in:PENDING,PAID,REFUNDED,FAILED',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $booking = Booking::where('hotel_id', $user->hotel_id)
            ->findOrFail($validated['booking_id']);

        $validated['hotel_id'] = $user->hotel_id;
        $validated['user_id'] = $user->id;

        Payment::create($validated);

        // Update booking payment status if paid
        if ($validated['status'] === 'PAID') {
            $booking->update(['payment_status' => 'PAID']);
        }

        return redirect()->route('reception.payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Update payment status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:PENDING,PAID,REFUNDED,FAILED',
        ]);

        $user = Auth::user();
        $payment = Payment::where('hotel_id', $user->hotel_id)
            ->with('booking')
            ->findOrFail($id);

        $payment->update($validated);

        // Update booking payment status
        if ($validated['status'] === 'PAID') {
            $payment->booking->update(['payment_status' => 'PAID']);
        } elseif ($validated['status'] === 'REFUNDED') {
            $payment->booking->update(['payment_status' => 'REFUNDED']);
        }

        return redirect()->back()
            ->with('success', 'Payment status updated successfully.');
    }
}
