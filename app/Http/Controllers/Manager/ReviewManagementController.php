<?php

namespace App\Http\Controllers\Manager;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ReviewManagementController extends Controller
{
    /**
     * Display all reviews for the manager's hotel
     */
    public function index()
    {
        $hotel = auth()->user()->hotel;
        
        if (!$hotel) {
            return redirect()->back()->with('error', 'Hotel not found');
        }
        
        $reviews = $hotel->reviews()
                         ->with('booking', 'guest')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        // Calculate statistics
        $stats = [
            'total_reviews' => $hotel->reviews()->count(),
            'pending_replies' => $hotel->reviews()->whereNull('manager_reply')->count(),
            'average_overall' => round($hotel->reviews()->avg('overall_rating'), 1),
            'average_cleanliness' => round($hotel->reviews()->avg('cleanliness_rating'), 1),
            'average_staff' => round($hotel->reviews()->avg('staff_rating'), 1),
            'average_comfort' => round($hotel->reviews()->avg('comfort_rating'), 1),
            'average_facilities' => round($hotel->reviews()->avg('facilities_rating'), 1),
            'average_value' => round($hotel->reviews()->avg('value_for_money_rating'), 1),
            'average_location' => round($hotel->reviews()->avg('location_rating'), 1),
        ];

        return view('manager.reviews.management', compact('reviews', 'stats'));
    }

    /**
     * Display a specific review with reply form
     */
    public function show($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        
        // Verify review belongs to authenticated manager's hotel
        if ($review->hotel_id !== auth()->user()->hotel_id) {
            abort(403, 'Unauthorized');
        }

        return view('manager.reviews.show', compact('review'));
    }

    /**
     * Save manager's reply to a review
     */
    public function reply(Request $request, $reviewId)
    {
        $review = Review::findOrFail($reviewId);
        
        // Verify review belongs to authenticated manager's hotel
        if ($review->hotel_id !== auth()->user()->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'manager_reply' => 'required|string|min:10|max:1000'
        ]);

        $review->update([
            'manager_reply' => $validated['manager_reply'],
            'manager_id' => auth()->user()->id,
            'reply_date' => Carbon::now()
        ]);

        return redirect()->route('manager.reviews.show', $review->id)
                        ->with('success', 'Your reply has been posted successfully!');
    }

    /**
     * Delete or moderate a review
     */
    public function destroy($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        
        // Verify review belongs to authenticated manager's hotel
        if ($review->hotel_id !== auth()->user()->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $review->delete();

        return redirect()->route('manager.reviews.index')
                        ->with('success', 'Review has been removed');
    }

    /**
     * Get hotel rating summary
     */
    public function getRatingSummary()
    {
        $hotel = auth()->user()->hotel;
        
        return [
            'overall' => round($hotel->reviews()->avg('overall_rating'), 1),
            'cleanliness' => round($hotel->reviews()->avg('cleanliness_rating'), 1),
            'staff' => round($hotel->reviews()->avg('staff_rating'), 1),
            'comfort' => round($hotel->reviews()->avg('comfort_rating'), 1),
            'facilities' => round($hotel->reviews()->avg('facilities_rating'), 1),
            'value' => round($hotel->reviews()->avg('value_for_money_rating'), 1),
            'location' => round($hotel->reviews()->avg('location_rating'), 1),
            'total_reviews' => $hotel->reviews()->count(),
        ];
    }
}
