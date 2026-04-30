<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'hotel_id',
        'guest_id',
        'guest_name',
        'guest_email',
        'overall_rating',
        'cleanliness_rating',
        'staff_rating',
        'comfort_rating',
        'facilities_rating',
        'value_for_money_rating',
        'location_rating',
        'comment',
        'review_date',
        'manager_reply',
        'manager_id',
        'reply_date',
        'status'
    ];

    protected $casts = [
        'review_date' => 'date',
        'reply_date' => 'datetime',
    ];

    /**
     * Get the booking this review belongs to
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the hotel this review is for
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the guest who wrote the review
     */
    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    /**
     * Get the manager who replied to the review
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Calculate average rating from all individual ratings
     */
    public function getAverageRatingAttribute()
    {
        $ratings = [
            $this->overall_rating,
            $this->cleanliness_rating,
            $this->staff_rating,
            $this->comfort_rating,
            $this->facilities_rating,
            $this->value_for_money_rating,
            $this->location_rating
        ];
        
        return round(array_sum($ratings) / count($ratings), 1);
    }

    /**
     * Scope: Only approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'APPROVED');
    }

    /**
     * Scope: Get reviews for a specific hotel
     */
    public function scopeForHotel($query, $hotelId)
    {
        return $query->where('hotel_id', $hotelId)->approved();
    }

    /**
     * Scope: Only reviews pending a manager reply
     */
    public function scopePending($query)
    {
        return $query->whereNull('manager_reply');
    }

    /**
     * Scope: Only reviews with manager replies
     */
    public function scopeWithReplies($query)
    {
        return $query->whereNotNull('manager_reply');
    }

    /**
     * Scope: Order by newest reviews first
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('review_date', 'desc');
    }

    /**
     * Scope: Order by recently replied/updated
     */
    public function scopeRecentlyUpdated($query)
    {
        return $query->orderBy('reply_date', 'desc');
    }

    /**
     * Scope: Filter by minimum rating
     */
    public function scopeWithMinimumRating($query, $rating)
    {
        return $query->where('overall_rating', '>=', $rating);
    }

    /**
     * Scope: Filter by maximum rating
     */
    public function scopeWithMaximumRating($query, $rating)
    {
        return $query->where('overall_rating', '<=', $rating);
    }

    /**
     * Calculate average rating for a hotel
     */
    public static function getHotelAverageRating($hotelId)
    {
        return static::where('hotel_id', $hotelId)
                    ->approved()
                    ->avg('overall_rating') ?? 0;
    }

    /**
     * Get rating distribution for a hotel
     */
    public static function getHotelRatingDistribution($hotelId)
    {
        return [
            'overall' => round(static::where('hotel_id', $hotelId)->approved()->avg('overall_rating') ?? 0, 1),
            'cleanliness' => round(static::where('hotel_id', $hotelId)->approved()->avg('cleanliness_rating') ?? 0, 1),
            'staff' => round(static::where('hotel_id', $hotelId)->approved()->avg('staff_rating') ?? 0, 1),
            'comfort' => round(static::where('hotel_id', $hotelId)->approved()->avg('comfort_rating') ?? 0, 1),
            'facilities' => round(static::where('hotel_id', $hotelId)->approved()->avg('facilities_rating') ?? 0, 1),
            'value' => round(static::where('hotel_id', $hotelId)->approved()->avg('value_for_money_rating') ?? 0, 1),
            'location' => round(static::where('hotel_id', $hotelId)->approved()->avg('location_rating') ?? 0, 1),
        ];
    }

    /**
     * Get review count and statistics for a hotel
     */
    public static function getHotelReviewStats($hotelId)
    {
        $hotelReviews = static::where('hotel_id', $hotelId)->approved();
        
        return [
            'total' => $hotelReviews->count(),
            'average_rating' => round($hotelReviews->avg('overall_rating') ?? 0, 1),
            'pending_replies' => static::where('hotel_id', $hotelId)->pending()->count(),
            'with_replies' => static::where('hotel_id', $hotelId)->withReplies()->approved()->count(),
        ];
    }
}
