<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_id',
        'room_type',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'applicable_room_types' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the hotel this promotion belongs to
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the specific room this promotion applies to (if any)
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Check if promotion is currently active
     */
    public function isActive()
    {
        $now = now();
        return $this->is_active && 
               $this->start_date <= $now && 
               $this->end_date >= $now;
    }

    /**
     * Check if promotion applies to entire hotel
     */
    public function appliesToEntireHotel()
    {
        return is_null($this->room_id);
    }

    /**
     * Check if promotion applies to specific room
     */
    public function appliesToSpecificRoom()
    {
        return !is_null($this->room_id);
    }

    /**
     * Calculate discount amount based on original price
     */
    public function calculateDiscount($originalPrice)
    {
        if ($this->discount_type === 'percentage') {
            return ($originalPrice * $this->discount_value) / 100;
        } else {
            // Fixed discount
            return min($this->discount_value, $originalPrice);
        }
    }

    /**
     * Calculate final price after discount
     */
    public function calculateFinalPrice($originalPrice)
    {
        $discount = $this->calculateDiscount($originalPrice);
        return max(0, $originalPrice - $discount);
    }

    /**
     * Get the formatted discount display (e.g., "20%" or "Nu. 500")
     */
    public function getFormattedDiscount()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        } else {
            return 'Nu. ' . number_format($this->discount_value, 2);
        }
    }

    /**
     * Get what this promotion applies to
     */
    public function getAppliesTo()
    {
        // Check if it's a room-type specific promotion (new approach)
        if ($this->room_type) {
            return $this->room_type . ' room';
        }

        // Fall back to old per-room approach for backward compatibility
        if ($this->appliesToEntireHotel()) {
            return 'All room types';
        }

        // If room exists, return its type
        if ($this->room) {
            return $this->room->room_type . ' room';
        }

        return 'Specific rooms';
    }
}
