<?php

namespace App\Http\Controllers;

use App\Services\HotelFilterService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * HotelFilterController
 * 
 * Handles AJAX filter requests for real-time hotel filtering
 * Returns JSON responses with filtered results
 */
class HotelFilterController extends Controller
{
    /**
     * Apply filters and return filtered results as JSON
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function applyFilters(Request $request): JsonResponse
    {
        try {
            // Validate booking criteria
            $validated = $request->validate([
                'dzongkhag_id' => 'nullable',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'adults' => 'required|integer|min:1',
                'children' => 'nullable|integer|min:0',
                'rooms' => 'required|integer|min:1',

                // Filter parameters
                'price_min' => 'nullable|numeric|min:0',
                'price_max' => 'nullable|numeric',
                'rating_min' => 'nullable|numeric|min:0|max:10',
                'amenities' => 'nullable',
                'property_types' => 'nullable',
                'free_cancellation' => 'nullable|boolean',
                'breakfast' => 'nullable|boolean',
                'wifi' => 'nullable|boolean',
                'spa' => 'nullable|boolean',
                'parking' => 'nullable|boolean',
                'sort' => 'nullable|in:rating,price_low,price_high,name',
                'page' => 'nullable|integer|min:1',
            ]);

            // Prepare booking criteria for availability check
            $bookingCriteria = [
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'adults' => $validated['adults'],
                'children' => $validated['children'] ?? 0,
                'rooms' => $validated['rooms'],
            ];

            // Create filter service and apply filters
            $filterService = new HotelFilterService();
            $filterService->fromRequest($request, $bookingCriteria);

            // Apply all filters
            $filterService
                ->filterByLocation()
                ->filterByAmenities()
                ->filterByPropertyType()
                ->filterByFreeCancellation();

            // Get filtered hotels
            $hotels = $filterService->getFiltered();

            // Sort results
            $filterService->sortResults($hotels);

            // Pagination
            $perPage = 20;
            $page = $request->get('page', 1);
            $total = count($hotels);
            $paginatedHotels = array_slice($hotels, ($page - 1) * $perPage, $perPage);

            // Calculate number of nights for price display
            $nights = \Carbon\Carbon::parse($validated['check_out'] ?? now()->addDay())
                ->diffInDays(\Carbon\Carbon::parse($validated['check_in'] ?? now()));

            return response()->json([
                'success' => true,
                'html' => $this->renderHotelsHtml($paginatedHotels, $validated, $nights),
                'total_results' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage),
                'active_filters' => $filterService->getAppliedFilters(),
                'filter_stats' => $filterService->getFilterStats(),
            ], 200);

        } catch (\Exception $e) {
            Log::error('Hotel filter error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error applying filters',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Render hotels HTML for display
     * 
     * @param array $hotels
     * @param array $validated
     * @param int $nights
     * @return string
     */
    private function renderHotelsHtml(array $hotels, array $validated, int $nights = 1): string
    {
        if (empty($hotels)) {
            return '<div class="col-span-3 text-center py-12">
                        <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No properties found</h3>
                        <p class="text-gray-600">Try adjusting your filters or search criteria</p>
                    </div>';
        }

        $html = '';

        $inventoryService = new \App\Services\RoomInventoryService();

        foreach ($hotels as $hotel) {
            // Convert array to Hotel model instance if needed
            $hotelInventory = null;
            if (is_array($hotel)) {
                $hotelData = $hotel;
                $hotelInventory = $hotelData['inventory'] ?? null;
                // Get fresh hotel instance to ensure all relationships are loaded
                $hotel = \App\Models\Hotel::with('dzongkhag', 'reviews', 'promotions', 'rooms')
                    ->findOrFail($hotelData['id']);
            } else {
                $hotelInventory = $hotel->inventory ?? null;
            }

            // Recompute inventory if lost during model reload
            if (empty($hotelInventory)) {
                $hotelInventory = $inventoryService->getAvailableInventory($hotel->id);
            }
            $hotel->inventory = $hotelInventory;

            // Get minimum price
            $minPrice = 0;
            if (!empty($hotel->inventory)) {
                $prices = [];
                foreach ($hotel->inventory as $variants) {
                    foreach ($variants as $variant) {
                        if (is_array($variant) && isset($variant['price'])) {
                            $prices[] = $variant['price'];
                        }
                    }
                }
                $minPrice = empty($prices) ? 0 : min($prices);
            }

            // Get average rating
            $avgRating = 0;
            $reviewCount = 0;
            if ($hotel->reviews && $hotel->reviews->count() > 0) {
                $ratings = $hotel->reviews->pluck('overall_rating')->toArray();
                $avgRating = empty($ratings) ? ($hotel->star_rating ?? 0) : array_sum($ratings) / count($ratings);
                $reviewCount = count($ratings);
            }

            // Get available rooms count
            $availableCount = 0;
            if (!empty($hotel->inventory)) {
                foreach ($hotel->inventory as $variants) {
                    $availableCount += count($variants ?? []);
                }
            }

            $html .= view('partials.hotel-card-ajax', [
                'hotel' => $hotel,
                'minPrice' => $minPrice,
                'avgRating' => $avgRating,
                'reviewCount' => $reviewCount,
                'availableCount' => $availableCount,
                'validated' => $validated,
                'nights' => $nights,
            ])->render();
        }

        return $html;
    }
}
