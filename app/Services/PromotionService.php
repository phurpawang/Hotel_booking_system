<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\Hotel;
use App\Models\Room;
use Carbon\Carbon;

class PromotionService
{
    /**
     * Find the best active promotion for a given hotel and room
     * Prioritizes room-type specific promotions over hotel-wide promotions
     *
     * @param int $hotelId
     * @param int $roomId
     * @return Promotion|null
     */
    public function findActivePromotionForRoom($hotelId, $roomId)
    {
        $now = now();

        // Get the room to find its type
        $room = Room::find($roomId);
        if (!$room) {
            return null;
        }

        // First, check for room-type specific promotion (highest priority)
        // NEW APPROACH: Check room_type field
        $roomTypePromotion = Promotion::where('hotel_id', $hotelId)
            ->where('room_type', $room->room_type)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($roomTypePromotion) {
            return $roomTypePromotion;
        }

        // If no room-type promotion, check for hotel-wide promotion
        // Hotel-wide: both room_type and room_id are NULL
        $hotelWidePromotion = Promotion::where('hotel_id', $hotelId)
            ->whereNull('room_id')
            ->whereNull('room_type')
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->orderBy('created_at', 'desc')
            ->first();

        return $hotelWidePromotion;
    }

    /**
     * Find active promotions for a hotel (for display purposes)
     *
     * @param int $hotelId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findActivePromotionsForHotel($hotelId)
    {
        $now = now();

        return Promotion::where('hotel_id', $hotelId)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->orderBy('room_id', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Calculate discount for a given promotion and original price
     *
     * @param Promotion $promotion
     * @param float $originalPrice
     * @return float
     */
    public function calculateDiscount(Promotion $promotion, $originalPrice)
    {
        if ($promotion->discount_type === 'percentage') {
            return ($originalPrice * $promotion->discount_value) / 100;
        } else {
            // Fixed discount - ensure it doesn't exceed original price
            return min($promotion->discount_value, $originalPrice);
        }
    }

    /**
     * Calculate final price after applying promotion
     *
     * @param Promotion $promotion
     * @param float $originalPrice
     * @return float
     */
    public function calculateFinalPrice(Promotion $promotion, $originalPrice)
    {
        $discount = $this->calculateDiscount($promotion, $originalPrice);
        return max(0, $originalPrice - $discount);
    }

    /**
     * Apply promotion to a booking (calculate prices)
     * Returns array with pricing information
     *
     * @param Promotion|null $promotion
     * @param float $originalPrice
     * @return array
     */
    public function applyPromotion(?Promotion $promotion, $originalPrice)
    {
        $result = [
            'original_price' => $originalPrice,
            'discount_applied' => 0,
            'final_price' => $originalPrice,
            'promotion_id' => null,
            'promotion' => null,
            'discount_percentage' => 0,
            'discount_display' => null,
        ];

        if (!$promotion || !$promotion->isActive()) {
            return $result;
        }

        $discount = $this->calculateDiscount($promotion, $originalPrice);
        $finalPrice = max(0, $originalPrice - $discount);

        $result['promotion_id'] = $promotion->id;
        $result['promotion'] = $promotion;
        $result['discount_applied'] = $discount;
        $result['final_price'] = $finalPrice;

        // Format display text
        if ($promotion->discount_type === 'percentage') {
            $result['discount_percentage'] = $promotion->discount_value;
            $result['discount_display'] = "{$promotion->discount_value}% OFF";
        } else {
            $result['discount_display'] = "Nu. " . number_format($promotion->discount_value, 2) . " OFF";
        }

        return $result;
    }

    /**
     * Format promotion for display
     *
     * @param Promotion $promotion
     * @return array
     */
    public function formatPromotion(Promotion $promotion)
    {
        if ($promotion->discount_type === 'percentage') {
            $badge = "{$promotion->discount_value}% OFF";
        } else {
            $badge = "Nu. " . number_format($promotion->discount_value, 2) . " OFF";
        }

        return [
            'id' => $promotion->id,
            'title' => $promotion->title,
            'description' => $promotion->description,
            'badge' => $badge,
            'discount_type' => $promotion->discount_type,
            'discount_value' => $promotion->discount_value,
            'start_date' => $promotion->start_date->format('M d, Y'),
            'end_date' => $promotion->end_date->format('M d, Y'),
            'applies_to' => $promotion->appliesToSpecificRoom() ? 'Specific Room' : 'Entire Hotel',
            'room_id' => $promotion->room_id,
        ];
    }

    /**
     * Validate if promotion applies to given room and hotel
     *
     * @param Promotion $promotion
     * @param int $roomId
     * @param int $hotelId
     * @return bool
     */
    public function validatePromotionApplicability(Promotion $promotion, $roomId, $hotelId)
    {
        // Check hotel match
        if ($promotion->hotel_id != $hotelId) {
            return false;
        }

        // If room-specific, check room match
        if ($promotion->appliesToSpecificRoom()) {
            return $promotion->room_id == $roomId;
        }

        // Hotel-wide promotion applies to all rooms
        return true;
    }
}
