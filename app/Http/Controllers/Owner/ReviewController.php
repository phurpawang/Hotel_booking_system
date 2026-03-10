<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    private function getOwnerHotel()
    {
        return Auth::user()->hotel;
    }

    public function index()
    {
        $hotel = $this->getOwnerHotel();
        $reviews = Review::where('hotel_id', $hotel->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $averageRating = Review::where('hotel_id', $hotel->id)->avg('rating');

        return view('owner.reviews.index', compact('hotel', 'reviews', 'averageRating'));
    }

    public function reply(Request $request, $id)
    {
        $hotel = $this->getOwnerHotel();
        $review = Review::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        $validated = $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'reply' => $validated['reply'],
            'replied_at' => now(),
        ]);

        return redirect()->route('owner.reviews.index')
            ->with('success', 'Reply posted successfully!');
    }
}
