<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuestReviewController extends Controller
{
    /**
     * Show booking history with review eligibility
     */
    public function bookingHistory()
    {
        $user = auth()->user();
        
        // Get bookings from the users table guest_email field or from booking guest_email
        $bookings = Booking::where(function($query) use ($user) {
                        $query->where('guest_email', $user->email)
                              ->orWhere('guest_name', $user->name);
                    })
                    ->where('status', 'CONFIRMED')
                    ->orderBy('check_out_date', 'desc')
                    ->paginate(10);

        return view('guest.booking-history', compact('bookings'));
    }

    /**
     * Show review form for a booking
     */
    public function create($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $user = auth()->user();

        // Verify user owns this booking
        if ($booking->guest_email !== $user->email && $booking->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Check if booking is completed (check-out date passed)
        if (Carbon::now()->lessThan($booking->check_out_date)) {
            return redirect()->back()->with('error', 'You can only review after your check-out date');
        }

        // Check if review already exists
        if ($booking->review()->exists()) {
            return redirect()->back()->with('info', 'You have already reviewed this booking');
        }

        return view('guest.review-form', compact('booking'));
    }

    /**
     * Store the review
     */
    public function store(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $user = auth()->user();

        // Verify user owns this booking
        if ($booking->guest_email !== $user->email && $booking->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Check if booking is completed
        if (Carbon::now()->lessThan($booking->check_out_date)) {
            return redirect()->back()->with('error', 'You can only review after your check-out date');
        }

        // Check if review already exists
        if ($booking->review()->exists()) {
            return redirect()->back()->with('error', 'You have already reviewed this booking');
        }

        // Validate input
        $validated = $request->validate([
            'overall_rating' => 'required|integer|between:1,10',
            'cleanliness_rating' => 'required|integer|between:1,10',
            'staff_rating' => 'required|integer|between:1,10',
            'comfort_rating' => 'required|integer|between:1,10',
            'facilities_rating' => 'required|integer|between:1,10',
            'value_for_money_rating' => 'required|integer|between:1,10',
            'location_rating' => 'required|integer|between:1,10',
            'comment' => 'nullable|string|max:2000'
        ]);

        // Create review
        Review::create([
            'booking_id' => $bookingId,
            'hotel_id' => $booking->hotel_id,
            'guest_id' => $user->id,
            'guest_name' => $booking->guest_name,
            'guest_email' => $booking->guest_email,
            'overall_rating' => $validated['overall_rating'],
            'cleanliness_rating' => $validated['cleanliness_rating'],
            'staff_rating' => $validated['staff_rating'],
            'comfort_rating' => $validated['comfort_rating'],
            'facilities_rating' => $validated['facilities_rating'],
            'value_for_money_rating' => $validated['value_for_money_rating'],
            'location_rating' => $validated['location_rating'],
            'comment' => $validated['comment'] ?? null,
            'review_date' => Carbon::now()->toDateString(),
            'status' => 'APPROVED'
        ]);

        return redirect()->route('guest.booking-history')->with('success', 'Your review has been submitted successfully!');
    }

    /**
     * View a specific review
     */
    public function show($bookingId)
    {
        $review = Review::where('booking_id', $bookingId)->firstOrFail();
        $user = auth()->user();

        // Verify user owns this review
        if ($review->guest_email !== $user->email && $review->guest_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        return view('guest.review-view', compact('review'));
    }

    /**
     * Delete own review
     */
    public function destroy($bookingId)
    {
        $review = Review::where('booking_id', $bookingId)->firstOrFail();
        $user = auth()->user();

        // Verify user owns this review
        if ($review->guest_email !== $user->email && $review->guest_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $review->delete();

        return redirect()->route('guest.booking-history')->with('success', 'Your review has been deleted');
    }
}
