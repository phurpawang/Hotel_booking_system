<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;

/**
 * Availability Service - Handles room availability checks
 * Used to generate disabled dates for the calendar
 */
class AvailabilityService
{
    /**
     * Get all booked/disabled dates
     * Used by the date picker to gray out unavailable dates
     */
    public function getDisabledDates($roomId = null, $hotelId = null, $format = 'Y-m-d')
    {
        $disabledDates = [];
        $today = Carbon::today();
        $endDate = $today->copy()->addDays(730); // Check 2 years ahead

        // Get all confirmed or checked-in bookings
        $bookings = Booking::whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
            ->where('check_out_date', '>', $today)
            ->when($hotelId, function ($query) use ($hotelId) {
                return $query->where('hotel_id', $hotelId);
            })
            ->when($roomId, function ($query) use ($roomId) {
                return $query->where('room_id', $roomId);
            })
            ->get();

        foreach ($bookings as $booking) {
            $current = $booking->check_in_date->copy();
            $checkOut = $booking->check_out_date->copy();

            // Add each booked date
            while ($current < $checkOut && $current <= $endDate) {
                $disabledDates[] = $current->format($format);
                $current->addDay();
            }
        }

        return array_unique($disabledDates);
    }

    /**
     * Check if a room is available for dates
     */
    public function isAvailable($roomId, $checkInDate, $checkOutDate)
    {
        $checkIn = $checkInDate instanceof Carbon ? $checkInDate : Carbon::parse($checkInDate);
        $checkOut = $checkOutDate instanceof Carbon ? $checkOutDate : Carbon::parse($checkOutDate);

        $exists = Booking::where('room_id', $roomId)
            ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in_date', '<', $checkOut)
                  ->where('check_out_date', '>', $checkIn);
            })
            ->exists();

        return !$exists;
    }
}
