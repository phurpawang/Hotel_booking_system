<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /**
     * Display all reviews in the system for admin
     */
    public function index(Request $request)
    {
        $reviews = Review::query();

        // Filter by hotel if specified
        if ($request->filled('hotel_id')) {
            $reviews->where('hotel_id', $request->hotel_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $reviews->where('status', $request->status);
        }

        // Search by guest name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $reviews->where(function($q) use ($search) {
                $q->where('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%");
            });
        }

        $reviews = $reviews->with('hotel', 'booking', 'guest')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        $hotels = Hotel::where('status', 'APPROVED')->get();

        return view('admin.reviews.index', compact('reviews', 'hotels'));
    }

    /**
     * Display specific review details
     */
    public function show($reviewId)
    {
        $review = Review::with('hotel', 'booking', 'guest', 'manager')->findOrFail($reviewId);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Update review status
     */
    public function updateStatus(Request $request, $reviewId)
    {
        $review = Review::findOrFail($reviewId);

        $validated = $request->validate([
            'status' => 'required|in:PENDING,APPROVED,REJECTED'
        ]);

        $review->update(['status' => $validated['status']]);

        return redirect()->route('admin.reviews.show', $review->id)
                        ->with('success', 'Review status updated successfully');
    }

    /**
     * Delete inappropriate review
     */
    public function destroy($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();

        return redirect()->route('admin.reviews.index')
                        ->with('success', 'Review has been removed from the system');
    }

    /**
     * Get system-wide review statistics
     */
    public function statistics()
    {
        $stats = [
            'total_reviews' => Review::count(),
            'pending_reviews' => Review::where('status', 'PENDING')->count(),
            'approved_reviews' => Review::where('status', 'APPROVED')->count(),
            'rejected_reviews' => Review::where('status', 'REJECTED')->count(),
            'average_overall' => round(Review::avg('overall_rating'), 1),
            'average_cleanliness' => round(Review::avg('cleanliness_rating'), 1),
            'average_staff' => round(Review::avg('staff_rating'), 1),
            'average_comfort' => round(Review::avg('comfort_rating'), 1),
            'hotels_with_reviews' => Hotel::whereHas('reviews')->count(),
        ];

        return view('admin.reviews.statistics', compact('stats'));
    }
}
