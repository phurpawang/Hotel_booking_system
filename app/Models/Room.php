<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_number',
        'room_type',
        'quantity',
        'capacity',
        'price_per_night',
        'base_price',
        'commission_rate',
        'commission_amount',
        'final_price',
        'description',
        'amenities',
        'photos',
        'cancellation_policy',
        'status',
        'is_available'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'base_price' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'amenities' => 'array',
        'photos' => 'array',
    ];

    /**
     * Get the hotel that owns the room
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the bookings for the room
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope for available rooms
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'AVAILABLE')
                     ->where('is_available', true);
    }

    /**
     * Scope for occupied rooms
     */
    public function scopeOccupied($query)
    {
        return $query->where('status', 'OCCUPIED');
    }

    /**
     * Check if room is available for dates
     */
    public function isAvailableForDates($checkIn, $checkOut)
    {
        if (!$this->is_available) {
            return false;
        }

        return !Booking::hasOverlap($this->id, $checkIn, $checkOut);
    }

    /**
     * Get active bookings (confirmed or checked in)
     */
    public function activeBookings()
    {
        return $this->bookings()->whereIn('status', ['CONFIRMED', 'CHECKED_IN']);
    }

    /**
     * Calculate commission based on base price
     * 
     * @param float $basePrice
     * @param float $commissionRate
     * @return array
     */
    public static function calculateCommission($basePrice, $commissionRate = 10.00)
    {
        $basePrice = floatval($basePrice);
        $commissionRate = floatval($commissionRate);
        
        $commissionAmount = round(($basePrice * $commissionRate) / 100, 2);
        $finalPrice = round($basePrice + $commissionAmount, 2);

        return [
            'base_price' => $basePrice,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'final_price' => $finalPrice,
        ];
    }

    /**
     * Update commission fields based on base price
     */
    public function updateCommission($basePrice = null, $commissionRate = null)
    {
        $basePrice = $basePrice ?? $this->base_price ?? $this->price_per_night;
        $commissionRate = $commissionRate ?? $this->commission_rate ?? 10.00;

        $calculated = self::calculateCommission($basePrice, $commissionRate);

        $this->update([
            'base_price' => $calculated['base_price'],
            'commission_rate' => $calculated['commission_rate'],
            'commission_amount' => $calculated['commission_amount'],
            'final_price' => $calculated['final_price'],
            'price_per_night' => $calculated['final_price'], // Keep price_per_night synced with final_price
        ]);
    }

    /**
     * Get the display price for guests (final price)
     */
    public function getDisplayPriceAttribute()
    {
        return $this->final_price ?? $this->price_per_night;
    }

    /**
     * Get the owner's earning per night (base price)
     */
    public function getOwnerEarningAttribute()
    {
        return $this->base_price ?? $this->price_per_night;
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically calculate commission when base_price is set
        static::creating(function ($room) {
            if ($room->base_price && !$room->final_price) {
                $calculated = self::calculateCommission($room->base_price, $room->commission_rate ?? 10.00);
                $room->commission_amount = $calculated['commission_amount'];
                $room->final_price = $calculated['final_price'];
                $room->price_per_night = $calculated['final_price'];
            }
        });

        static::updating(function ($room) {
            // If base_price changed, recalculate commission
            if ($room->isDirty('base_price') || $room->isDirty('commission_rate')) {
                $calculated = self::calculateCommission(
                    $room->base_price ?? $room->price_per_night,
                    $room->commission_rate ?? 10.00
                );
                $room->commission_amount = $calculated['commission_amount'];
                $room->final_price = $calculated['final_price'];
                $room->price_per_night = $calculated['final_price'];
            }
        });
    }
}
