<!-- Hotel Card - Booking.com Style AJAX Rendering -->
@php
    // Calculate values if not provided (for initial page load)
    if (!isset($avgRating) || !isset($reviewCount)) {
        $avgRating = 0;
        $reviewCount = 0;
        if (!empty($hotel->reviews)) {
            $ratings = $hotel->reviews->pluck('overall_rating')->toArray();
            $avgRating = empty($ratings) ? ($hotel->star_rating ?? 0) : array_sum($ratings) / count($ratings);
            $reviewCount = count($ratings);
        }
    }

    if (!isset($minPrice)) {
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
    }

    if (!isset($availableCount)) {
        $availableCount = 0;
        if (!empty($hotel->inventory)) {
            foreach ($hotel->inventory as $variants) {
                $availableCount += count($variants ?? []);
            }
        }
    }

    // Review score label
    $reviewLabel = 'Okay';
    if ($avgRating >= 9.0) $reviewLabel = 'Excellent';
    elseif ($avgRating >= 8.0) $reviewLabel = 'Very Good';
    elseif ($avgRating >= 7.0) $reviewLabel = 'Good';
    elseif ($avgRating >= 6.0) $reviewLabel = 'Pleasant';

    // Total price for stay
    $nights = $nights ?? 1;
    $totalPrice = $minPrice * $nights;

    // Free cancellation check (rooms table)
    $hasFreeCancellation = false;
    if (!empty($hotel->rooms)) {
        foreach ($hotel->rooms as $room) {
            if (!empty($room->cancellation_policy) && stripos($room->cancellation_policy, 'free') !== false) {
                $hasFreeCancellation = true;
                break;
            }
        }
    }

    // Urgency level
    $urgencyLevel = 'normal';
    if ($availableCount <= 3) $urgencyLevel = 'critical';
    elseif ($availableCount <= 5) $urgencyLevel = 'high';
    elseif ($availableCount <= 10) $urgencyLevel = 'medium';
@endphp

<div class="hotel-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition border border-gray-100">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4">
        <!-- Hotel Image -->
        <div class="md:col-span-1">
            <div class="relative h-48 md:h-40 bg-gray-200 rounded-lg overflow-hidden">
                @if($hotel->property_image ?? false)
                    <img src="{{ asset('storage/' . $hotel->property_image) }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-blue-100">
                        <i class="fas fa-hotel text-blue-600 text-4xl"></i>
                    </div>
                @endif
                <div class="absolute top-2 right-2">
                    <button class="bg-white rounded-full p-2 shadow-md hover:shadow-lg transition favorite-btn" data-hotel-id="{{ $hotel->id }}">
                        <i class="far fa-heart text-gray-400 hover:text-red-500"></i>
                    </button>
                </div>
                @if($hasFreeCancellation)
                    <div class="absolute bottom-2 left-2 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">
                        Free cancellation
                    </div>
                @endif
            </div>
        </div>

        <!-- Hotel Details -->
        <div class="md:col-span-2">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-gray-900 hover:text-blue-600 cursor-pointer truncate">
                        {{ $hotel->name ?? 'Hotel Name' }}
                    </h3>
                    <p class="text-sm text-blue-600 font-medium">
                        {{ $hotel->property_type ?? 'Hotel' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                        {{ $hotel->dzongkhag->name ?? 'Bhutan' }}
                    </p>
                </div>

                <!-- Booking.com Style Review Badge -->
                <div class="flex items-start gap-3 ml-3 flex-shrink-0">
                    <div class="text-right">
                        @if($reviewCount > 0)
                            <div class="text-sm font-semibold text-gray-900">{{ $reviewLabel }}</div>
                            <div class="text-xs text-gray-500">{{ $reviewCount }} reviews</div>
                        @else
                            <div class="text-sm font-semibold text-gray-400">No reviews</div>
                        @endif
                    </div>
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-white text-sm
                        {{ $avgRating >= 8 ? 'bg-blue-600' : ($avgRating >= 6 ? 'bg-blue-400' : 'bg-gray-400') }}">
                        {{ number_format($avgRating, 1) }}
                    </div>
                </div>
            </div>

            <!-- Description -->
            <p class="text-sm text-gray-700 mb-3 line-clamp-2">
                {{ $hotel->description ?? 'A wonderful place to stay in Bhutan' }}
            </p>

            <!-- Facilities -->
            <div class="flex flex-wrap gap-1 mb-2">
                @php
                    $amenities = [];
                    if (!empty($hotel->rooms)) {
                        foreach ($hotel->rooms as $room) {
                            $roomAmenities = is_array($room->amenities) ? $room->amenities : (json_decode($room->amenities, true) ?? []);
                            $amenities = array_merge($amenities, $roomAmenities);
                        }
                        $amenities = array_unique($amenities);
                    }
                @endphp
                @if(!empty($amenities))
                    @foreach(array_slice($amenities, 0, 4) as $amenity)
                        <span class="inline-flex items-center bg-gray-50 text-gray-600 rounded px-2 py-0.5 text-xs border border-gray-200">
                            <i class="fas fa-check text-green-500 mr-1 text-[10px]"></i>{{ ucfirst($amenity) }}
                        </span>
                    @endforeach
                    @if(count($amenities) > 4)
                        <span class="text-gray-500 text-xs px-1">+{{ count($amenities) - 4 }} more</span>
                    @endif
                @endif
            </div>

            <!-- Urgency / Availability Indicator -->
            @if($urgencyLevel === 'critical')
                <p class="text-sm font-bold text-red-600 animate-pulse">
                    <i class="fas fa-fire mr-1"></i>
                    Only {{ $availableCount }} room{{ $availableCount > 1 ? 's' : '' }} left at this price!
                </p>
            @elseif($urgencyLevel === 'high')
                <p class="text-sm font-semibold text-orange-600">
                    <i class="fas fa-clock mr-1"></i>
                    Only {{ $availableCount }} rooms left
                </p>
            @elseif($urgencyLevel === 'medium')
                <p class="text-sm text-yellow-600">
                    <i class="fas fa-bolt mr-1"></i>
                    In high demand — booked {{ rand(3, 8) }} times today
                </p>
            @else
                <p class="text-sm text-green-600">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ $availableCount }} rooms available
                </p>
            @endif
        </div>

        <!-- Price & CTA - Booking.com Style -->
        <div class="md:col-span-1 flex flex-col justify-between items-end">
            <div class="text-right w-full">
                <!-- Per night price -->
                <p class="text-gray-500 text-sm line-through {{ $minPrice > 0 ? '' : 'hidden' }}">
                    Nu. {{ number_format($minPrice * 1.2, 0) }}
                </p>
                <p class="text-2xl font-bold text-gray-900">
                    Nu. {{ number_format($minPrice, 0) }}
                </p>
                <p class="text-xs text-gray-500 mb-1">per night</p>

                <!-- Total for stay -->
                <div class="bg-blue-50 rounded-lg px-3 py-2 mb-2 inline-block">
                    <p class="text-xs text-blue-700 font-semibold">
                        Total: Nu. {{ number_format($totalPrice, 0) }}
                    </p>
                    <p class="text-[11px] text-blue-600">
                        for {{ $nights }} night{{ $nights > 1 ? 's' : '' }}
                    </p>
                </div>

                @if($hasFreeCancellation)
                    <p class="text-xs text-green-600 font-medium">
                        <i class="fas fa-check mr-1"></i>Free cancellation
                    </p>
                @endif
                <p class="text-xs text-gray-400 mt-1">+ taxes & charges</p>
            </div>

            <a href="{{ route('guest.hotel', $hotel->id) }}?check_in={{ $validated['check_in'] }}&check_out={{ $validated['check_out'] }}&adults={{ $validated['adults'] }}&children={{ $validated['children'] ?? 0 }}&rooms={{ $validated['rooms'] }}"
               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition text-center text-sm mt-3">
                See availability
            </a>
        </div>
    </div>
</div>
