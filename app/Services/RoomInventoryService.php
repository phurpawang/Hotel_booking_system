<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

/**
 * RoomInventoryService
 * 
 * Manages aggregated room inventory grouped by (room_type + price)
 * Calculates availability dynamically from actual room records
 * Provides inventory structure similar to Booking.com
 * 
 * OPTIMIZED: Single-query aggregates, no N+1, no PHP collection loading
 */
class RoomInventoryService
{
    /**
     * Get available rooms grouped by (room_type + price)
     * 
     * Structure:
     * {
     *   "Single": [
     *     { "price": 2700, "available": 2, "currency": "Nu." },
     *     { "price": 2500, "available": 3, "currency": "Nu." }
     *   ],
     *   "Double": [
     *     { "price": 2750, "available": 2 }
     *   ]
     * }
     * 
     * OPTIMIZED: Uses MIN() aggregates instead of N+1 sample room queries
     * 
     * @param int $hotelId
     * @param \DateTime $checkInDate (optional - for future use)
     * @param \DateTime $checkOutDate (optional - for future use)
     * @return array Grouped inventory structure
     */
    public function getAvailableInventory($hotelId, $checkInDate = null, $checkOutDate = null)
    {
        $rows = DB::table('rooms')
            ->where('hotel_id', $hotelId)
            ->where('status', 'AVAILABLE')
            ->select(
                'room_type',
                'price_per_night as price',
                DB::raw('COUNT(*) as available_count'),
                DB::raw('MIN(description) as description'),
                DB::raw('MIN(amenities) as amenities'),
                DB::raw('MIN(photos) as photos')
            )
            ->groupBy('room_type', 'price_per_night')
            ->orderBy('room_type')
            ->orderBy('price_per_night')
            ->get();

        $inventory = [];
        foreach ($rows as $row) {
            $photos = json_decode($row->photos, true) ?? [];
            $amenities = json_decode($row->amenities, true) ?? [];

            $inventory[$row->room_type][] = [
                'price'       => (float) $row->price,
                'available'   => (int) $row->available_count,
                'currency'    => 'Nu.',
                'photos'      => $photos,
                'firstPhoto'  => $photos[0] ?? null,
                'description' => $row->description,
                'amenities'   => $amenities,
            ];
        }

        return $inventory;
    }

    /**
     * Get detailed room variants for a specific room type
     * 
     * Used for booking flow to show all price options for a room type
     * 
     * @param int $hotelId
     * @param string $roomType
     * @return array Array of price variants with availability
     */
    public function getRoomTypeVariants($hotelId, $roomType)
    {
        return Room::where('hotel_id', $hotelId)
            ->where('room_type', $roomType)
            ->where('status', 'AVAILABLE')
            ->select('price_per_night as price', DB::raw('COUNT(*) as available'))
            ->groupBy('price_per_night')
            ->orderBy('price_per_night')
            ->get()
            ->map(fn($v) => ['price' => (float) $v->price, 'available' => (int) $v->available])
            ->toArray();
    }

    /**
     * Get a single available room matching specific criteria
     * Used during booking confirmation to assign actual room
     * 
     * @param int $hotelId
     * @param string $roomType
     * @param float $price
     * @return Room|null The actual room to be assigned to booking
     */
    public function findAvailableRoom($hotelId, $roomType, $price)
    {
        return Room::where('hotel_id', $hotelId)
            ->where('room_type', $roomType)
            ->where('price_per_night', $price)
            ->where('status', 'AVAILABLE')
            ->first();
    }

    /**
     * Check availability for a specific (room_type + price) combination
     * 
     * @param int $hotelId
     * @param string $roomType
     * @param float $price
     * @return int Number of available rooms
     */
    public function getAvailabilityCount($hotelId, $roomType, $price)
    {
        return Room::where('hotel_id', $hotelId)
            ->where('room_type', $roomType)
            ->where('price_per_night', $price)
            ->where('status', 'AVAILABLE')
            ->count();
    }

    /**
     * Get all room type options for a hotel
     * 
     * @param int $hotelId
     * @return array Distinct room types
     */
    public function getHotelRoomTypes($hotelId)
    {
        return Room::where('hotel_id', $hotelId)
            ->distinct()
            ->pluck('room_type')
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Get all price points for a hotel
     * 
     * @param int $hotelId
     * @return array Distinct prices
     */
    public function getHotelPricePoints($hotelId)
    {
        return Room::where('hotel_id', $hotelId)
            ->distinct()
            ->pluck('price_per_night')
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Calculate total available rooms for a hotel
     * 
     * @param int $hotelId
     * @return int Total count of available rooms
     */
    public function getTotalAvailableCount($hotelId)
    {
        return Room::where('hotel_id', $hotelId)
            ->where('status', 'AVAILABLE')
            ->count();
    }

    /**
     * Get room stats for dashboard display
     * Shows inventory breakdown by room type
     * 
     * OPTIMIZED: Uses DB SUM(CASE WHEN) aggregates instead of loading all rooms into PHP Collection
     * 
     * @param int $hotelId
     * @return array Stats including total available, by type, occupancy
     */
    public function getInventoryStats($hotelId)
    {
        $stats = DB::table('rooms')
            ->where('hotel_id', $hotelId)
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status='AVAILABLE' THEN 1 ELSE 0 END) as available"),
                DB::raw("SUM(CASE WHEN status='OCCUPIED' THEN 1 ELSE 0 END) as occupied"),
                DB::raw("SUM(CASE WHEN status='MAINTENANCE' THEN 1 ELSE 0 END) as maintenance")
            )
            ->first();

        $byType = DB::table('rooms')
            ->where('hotel_id', $hotelId)
            ->select(
                'room_type',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status='AVAILABLE' THEN 1 ELSE 0 END) as available"),
                DB::raw("SUM(CASE WHEN status='OCCUPIED' THEN 1 ELSE 0 END) as occupied"),
                DB::raw("SUM(CASE WHEN status='MAINTENANCE' THEN 1 ELSE 0 END) as maintenance")
            )
            ->groupBy('room_type')
            ->get()
            ->keyBy('room_type')
            ->toArray();

        $total = (int) $stats->total;

        return [
            'total_rooms'          => $total,
            'available_rooms'      => (int) $stats->available,
            'occupied_rooms'       => (int) $stats->occupied,
            'maintenance_rooms'    => (int) $stats->maintenance,
            'occupancy_percentage' => $total > 0
                ? round(($stats->occupied / $total) * 100, 2)
                : 0,
            'by_room_type'         => $byType,
        ];
    }
}
