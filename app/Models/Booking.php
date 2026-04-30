<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ManagesNotifications;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory, ManagesNotifications;

    protected $fillable = [
        'booking_id',
        'room_id',
        'hotel_id',
        'user_id',
        'guest_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'check_in_date',
        'check_out_date',
        'actual_check_in',
        'actual_check_out',
        'num_guests',
        'num_rooms',
        'total_price',
        'base_price',
        'original_price',
        'discount_applied',
        'promotion_id',
        'commission_amount',
        'payment_status',
        'payment_method',
        'payment_method_type',
        'payment_screenshot',
        'status',
        'special_requests',
        'cancelled_at',
        'cancellation_reason',
        'refund_amount',
        'created_by'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'actual_check_in' => 'datetime',
        'actual_check_out' => 'datetime',
        'total_price' => 'decimal:2',
        'base_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_applied' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the room that owns the booking
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the hotel for the booking
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the user who made the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the guest who made the booking
     */
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Get the staff who created the booking
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the commission record for this booking
     */
    public function commission()
    {
        return $this->hasOne(BookingCommission::class);
    }

    /**
     * Get the promotion applied to this booking
     */
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Get the review for this booking
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Scope for today's arrivals
     */
    public function scopeTodayArrivals($query)
    {
        return $query->whereDate('check_in_date', Carbon::today())
                     ->where('status', 'CONFIRMED');
    }

    /**
     * Scope for confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'CONFIRMED');
    }

    /**
     * Scope for checked in bookings
     */
    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'CHECKED_IN');
    }

    /**
     * Scope for checked out bookings
     */
    public function scopeCheckedOut($query)
    {
        return $query->where('status', 'CHECKED_OUT');
    }

    /**
     * Scope for completed bookings (checked out)
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'CHECKED_OUT');
    }

    /**
     * Scope for today's departures
     */
    public function scopeTodayDepartures($query)
    {
        return $query->whereDate('check_out_date', Carbon::today())
                     ->where('status', 'CHECKED_IN');
    }

    /**
     * Check if booking is active
     */
    public function isActive()
    {
        return in_array($this->status, ['CONFIRMED', 'CHECKED_IN']);
    }

    /**
     * Calculate number of nights
     */
    public function getNightsAttribute()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    /**
     * Check if dates overlap with existing bookings
     */
    public static function hasOverlap($roomId, $checkIn, $checkOut, $excludeBookingId = null)
    {
        $query = self::where('room_id', $roomId)
            ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
            ->where(function($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in_date', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                  ->orWhere(function($q2) use ($checkIn, $checkOut) {
                      $q2->where('check_in_date', '<=', $checkIn)
                         ->where('check_out_date', '>=', $checkOut);
                  });
            });
        
        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->exists();
    }

    /**
     * Generate unique booking ID in format BK-XXXX (4-digit number between 1000-9999)
     */
    public static function generateBookingId()
    {
        do {
            // Generate random 4-digit number between 1000 and 9999
            $number = rand(1000, 9999);
            $bookingId = 'BK-' . $number;
            
            // Check if this booking ID already exists
            $exists = self::where('booking_id', $bookingId)->exists();
        } while ($exists);
        
        return $bookingId;
    }
}
