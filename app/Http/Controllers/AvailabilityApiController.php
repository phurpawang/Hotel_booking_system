<?php

namespace App\Http\Controllers;

use App\Services\AvailabilityService;
use Illuminate\Http\Request;

class AvailabilityApiController extends Controller
{
    protected $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Get disabled dates for the date picker calendar
     * API: GET /api/availability/disabled-dates
     */
    public function getDisabledDates(Request $request)
    {
        try {
            $roomId = $request->query('room_id');
            $hotelId = $request->query('hotel_id');

            $disabledDates = $this->availabilityService->getDisabledDates(
                $roomId ? (int) $roomId : null,
                $hotelId ? (int) $hotelId : null
            );

            return response()->json([
                'success' => true,
                'data' => $disabledDates,
                'count' => count($disabledDates)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching disabled dates',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
