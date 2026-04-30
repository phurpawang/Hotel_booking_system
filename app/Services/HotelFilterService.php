<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Dzongkhag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * HotelFilterService
 * 
 * Handles all hotel filtering logic in a clean, scalable way
 * Builds dynamic queries based on filter parameters
 */
class HotelFilterService
{
    private Builder $query;
    private RoomInventoryService $inventoryService;
    private array $filterParams = [];
    private array $appliedFilters = [];

    public function __construct()
    {
        $this->inventoryService = new RoomInventoryService();
        $this->initializeQuery();
    }

    /**
     * Initialize base query with eager loading
     */
    private function initializeQuery(): void
    {
        $this->query = Hotel::where('status', 'approved')
            ->with([
                'promotions' => function($q) {
                    $q->where('is_active', true)
                      ->whereDate('start_date', '<=', now())
                      ->whereDate('end_date', '>=', now())
                      ->orderBy('created_at', 'desc');
                },
                'reviews' => function($q) {
                    $q->where('status', 'APPROVED');
                },
                'rooms',
                'dzongkhag'
            ]);
    }

    /**
     * Build filter query from request parameters
     */
    public function fromRequest(Request $request, array $bookingCriteria = []): self
    {
        $this->filterParams = [
            'dzongkhag_id' => $request->input('dzongkhag_id'),
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'rating_min' => $request->input('rating_min'),
            'amenities' => $request->input('amenities'),
            'property_types' => $request->input('property_types'),
            'free_cancellation' => $request->input('free_cancellation'),
            'breakfast' => $request->input('breakfast'),
            'wifi' => $request->input('wifi'),
            'spa' => $request->input('spa'),
            'parking' => $request->input('parking'),
            'sort' => $request->input('sort', 'rating'),
            'booking_criteria' => $bookingCriteria,
        ];

        return $this;
    }

    /**
     * Apply location filter
     */
    public function filterByLocation(): self
    {
        if (!empty($this->filterParams['dzongkhag_id'])) {
            $this->query->where('dzongkhag_id', $this->filterParams['dzongkhag_id']);
            $this->appliedFilters['location'] = $this->filterParams['dzongkhag_id'];
        }

        return $this;
    }

    /**
     * Apply amenity filters
     *
     * Hotels table has no 'amenities' column — amenities are stored
     * on the rooms table as JSON. We use whereHas('rooms') to find
     * hotels that have at least one room offering any selected amenity.
     */
    public function filterByAmenities(): self
    {
        $selectedAmenities = [];

        // Collect all selected amenities from various filters
        if ($this->filterParams['breakfast']) {
            $selectedAmenities[] = 'breakfast';
            $this->appliedFilters['breakfast'] = true;
        }
        if ($this->filterParams['wifi']) {
            $selectedAmenities[] = 'wifi';
            $this->appliedFilters['wifi'] = true;
        }
        if ($this->filterParams['spa']) {
            $selectedAmenities[] = 'spa';
            $this->appliedFilters['spa'] = true;
        }
        if ($this->filterParams['parking']) {
            $selectedAmenities[] = 'parking';
            $this->appliedFilters['parking'] = true;
        }

        // Also check if amenities string/array is provided
        if (!empty($this->filterParams['amenities'])) {
            $amenitiesInput = $this->filterParams['amenities'];
            if (is_array($amenitiesInput)) {
                $amenitiesArray = $amenitiesInput;
            } else {
                $amenitiesArray = explode(',', $amenitiesInput);
            }
            $selectedAmenities = array_merge($selectedAmenities, array_filter($amenitiesArray));
        }

        // Filter hotels that have rooms with at least one of the selected amenities
        if (!empty($selectedAmenities)) {
            $this->query->whereHas('rooms', function ($query) use ($selectedAmenities) {
                foreach ($selectedAmenities as $amenity) {
                    $jsonVal = json_encode($amenity);
                    $query->orWhereRaw(
                        "JSON_CONTAINS(COALESCE(amenities, '[]'), ?)",
                        [$jsonVal]
                    );
                }
            });
            $this->appliedFilters['amenities'] = $selectedAmenities;
        }

        return $this;
    }

    /**
     * Apply property type filter
     */
    public function filterByPropertyType(): self
    {
        $propertyTypes = $this->filterParams['property_types'];

        if (!empty($propertyTypes)) {
            $typesArray = is_string($propertyTypes) 
                ? explode(',', $propertyTypes) 
                : (array)$propertyTypes;

            $typesArray = array_filter($typesArray);

            if (!empty($typesArray)) {
                $this->query->whereIn('property_type', $typesArray);
                $this->appliedFilters['property_types'] = $typesArray;
            }
        }

        return $this;
    }

    /**
     * Apply cancellation policy filter
     *
     * cancellation_policy lives on the rooms table, not hotels.
     */
    public function filterByFreeCancellation(): self
    {
        if ($this->filterParams['free_cancellation']) {
            $this->query->whereHas('rooms', function ($query) {
                $query->where('cancellation_policy', 'like', '%free%');
            });
            $this->appliedFilters['free_cancellation'] = true;
        }

        return $this;
    }

    /**
     * Get filtered hotels and apply post-query filters
     * Returns array of hotels with inventory data
     */
    public function getFiltered(): array
    {
        $hotels = $this->query->get();
        $bookingCriteria = $this->filterParams['booking_criteria'] ?? [];

        // Filter by availability and pricing
        $filtered = $hotels->filter(function($hotel) use ($bookingCriteria) {
            // Check availability
            if (!empty($bookingCriteria)) {
                $requiredRooms = $bookingCriteria['rooms'] ?? 1;
                $totalAvailable = $this->inventoryService->getTotalAvailableCount($hotel->id);

                if ($totalAvailable < $requiredRooms) {
                    return false;
                }
            }

            // Get inventory data
            $hotel->inventory = $this->inventoryService->getAvailableInventory($hotel->id);

            // Apply price filter
            $minPrice = $this->getMinPrice($hotel);
            $priceMin = (float)($this->filterParams['price_min'] ?? 0);
            $priceMax = (float)($this->filterParams['price_max'] ?? 999999);

            if ($minPrice !== null && ($minPrice < $priceMin || $minPrice > $priceMax)) {
                return false;
            }

            // Apply rating filter
            $avgRating = $this->getAverageRating($hotel);
            $ratingMin = (float)($this->filterParams['rating_min'] ?? 0);

            if ($ratingMin > 0 && $avgRating < $ratingMin) {
                return false;
            }

            return true;
        })->values();

        return $filtered->toArray();
    }

    /**
     * Apply sorting to filtered results
     */
    public function sortResults(array &$hotels): self
    {
        $sort = $this->filterParams['sort'] ?? 'rating';

        usort($hotels, function($a, $b) use ($sort) {
            if ($sort === 'price_low') {
                $priceA = $this->getMinPrice($a);
                $priceB = $this->getMinPrice($b);
                return ($priceA ?? 999999) <=> ($priceB ?? 999999);
            } elseif ($sort === 'price_high') {
                $priceA = $this->getMinPrice($a);
                $priceB = $this->getMinPrice($b);
                return ($priceB ?? 0) <=> ($priceA ?? 0);
            } elseif ($sort === 'name') {
                return strcasecmp($a['name'], $b['name']);
            } else {
                // Default: Rating (highest first)
                $ratingA = $this->getAverageRating($a);
                $ratingB = $this->getAverageRating($b);
                return $ratingB <=> $ratingA;
            }
        });

        return $this;
    }

    /**
     * Get minimum price from hotel inventory
     */
    private function getMinPrice($hotel): ?float
    {
        $hotel = is_array($hotel) ? (object)$hotel : $hotel;
        $inventory = $hotel->inventory ?? [];

        if (empty($inventory)) {
            return null;
        }

        $prices = [];
        foreach ($inventory as $variants) {
            if (is_array($variants)) {
                foreach ($variants as $variant) {
                    if (is_array($variant) && isset($variant['price'])) {
                        $prices[] = $variant['price'];
                    }
                }
            }
        }

        return empty($prices) ? null : (float)min($prices);
    }

    /**
     * Get average rating from hotel
     */
    private function getAverageRating($hotel): float
    {
        $hotel = is_array($hotel) ? (object)$hotel : $hotel;

        if (is_array($hotel)) {
            $reviews = $hotel['reviews'] ?? [];
            if (!empty($reviews) && is_array($reviews[0] ?? null)) {
                $ratings = array_column($reviews, 'overall_rating');
                return empty($ratings) ? ($hotel['star_rating'] ?? 0) : (float)array_sum($ratings) / count($ratings);
            }
            return $hotel['star_rating'] ?? 0;
        }

        if ($hotel->reviews && $hotel->reviews->isNotEmpty()) {
            return (float)$hotel->reviews->avg('overall_rating');
        }

        return (float)($hotel->star_rating ?? 0);
    }

    /**
     * Get applied filters for response
     */
    public function getAppliedFilters(): array
    {
        return $this->appliedFilters;
    }

    /**
     * Get filter suggestions/counts for UI
     */
    public function getFilterStats(): array
    {
        return [
            'total_results' => $this->query->count(),
            'amenities_count' => $this->getAmenitiesCount(),
            'price_range' => $this->getPriceRange(),
            'rating_distribution' => $this->getRatingDistribution(),
        ];
    }

    /**
     * Get available amenities and their counts
     */
    private function getAmenitiesCount(): array
    {
        // This would require more complex query logic
        // For now, return common amenities
        return [
            'breakfast' => 0,
            'wifi' => 0,
            'spa' => 0,
            'parking' => 0,
        ];
    }

    /**
     * Get price range from filtered hotels
     */
    private function getPriceRange(): array
    {
        $hotels = $this->query->get();
        $prices = [];

        foreach ($hotels as $hotel) {
            $inventory = $this->inventoryService->getAvailableInventory($hotel->id);
            if ($inventory) {
                foreach ($inventory as $variants) {
                    foreach ($variants as $variant) {
                        if (isset($variant['price'])) {
                            $prices[] = $variant['price'];
                        }
                    }
                }
            }
        }

        return empty($prices) ? [0, 10000] : [min($prices), max($prices)];
    }

    /**
     * Get rating distribution
     */
    private function getRatingDistribution(): array
    {
        return [
            '9+' => 0,
            '8+' => 0,
            '7+' => 0,
            '6+' => 0,
        ];
    }
}
