<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-cyan-400 via-blue-400 to-purple-500 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Go Back Button with Parameters -->
        <a href="{{ route('home') }}?dzongkhag_id={{ $validated['dzongkhag_id'] ?? '' }}&check_in={{ $validated['check_in'] }}&check_out={{ $validated['check_out'] }}&adults={{ $validated['adults'] }}&children={{ $validated['children'] ?? 0 }}&rooms={{ $validated['rooms'] }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition mb-6 shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i> Go Back
        </a>

        <!-- Header with Search Info -->
        <div class="mb-6">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                Hotels in {{ $dzongkhagName ?? 'All Dzongkhags' }}
            </h1>
            <p class="text-lg text-gray-700">
                Check-in: <span class="font-semibold">{{ $validated['check_in'] }}</span> 
                Check-out: <span class="font-semibold">{{ $validated['check_out'] }}</span> | 
                <span class="font-semibold">{{ $validated['adults'] }} adult(s), {{ $validated['children'] ?? 0 }} child(ren), {{ $validated['rooms'] }} room(s)</span>
            </p>
        </div>

        <!-- Sort By Section -->
        <div class="bg-white rounded-xl shadow-md p-4 mb-6">
            <form method="GET" action="{{ route('guest.search') }}" class="flex items-center gap-4" id="filterForm">
                <input type="hidden" name="dzongkhag_id" value="{{ $validated['dzongkhag_id'] ?? '' }}">
                <input type="hidden" name="check_in" value="{{ $validated['check_in'] }}">
                <input type="hidden" name="check_out" value="{{ $validated['check_out'] }}">
                <input type="hidden" name="adults" value="{{ $validated['adults'] }}">
                <input type="hidden" name="children" value="{{ $validated['children'] ?? 0 }}">
                <input type="hidden" name="rooms" value="{{ $validated['rooms'] }}">
                
                <label class="text-gray-700 font-semibold">Sort by</label>
                <select name="sort" class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="rating" {{ request('sort') == 'rating' || !request('sort') ? 'selected' : '' }}>Rating</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition shadow-md">
                    Apply
                </button>
            </form>
        </div>

        <!-- Active Promotions Section -->
        @php
            $hasPromotions = false;
            foreach($availableHotels as $hotel) {
                if($hotel->promotions && count($hotel->promotions) > 0) {
                    $hasPromotions = true;
                    break;
                }
            }
        @endphp
        
        @if($availableHotels->count() > 0 && $hasPromotions)
        <div class="mb-8 p-6 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl shadow-md border-2 border-orange-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-tag text-orange-600 mr-2"></i> Active Promotions
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($availableHotels as $hotel)
                    @if($hotel->promotions && count($hotel->promotions) > 0)
                        @foreach($hotel->promotions as $promotion)
                            <div class="bg-white rounded-lg shadow-md border-l-4 border-orange-500 p-4 hover:shadow-lg transition">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $promotion->title }}</h3>
                                    <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-full px-3 py-1">
                                        <span class="text-2xl font-bold text-white">
                                            @if($promotion->discount_type === 'percentage')
                                                {{ $promotion->discount_value }}%
                                            @else
                                                Nu.{{ number_format($promotion->discount_value, 0) }}
                                            @endif
                                        </span>
                                        <span class="text-xs text-white block">OFF</span>
                                    </div>
                                </div>
                                <p class="text-sm text-blue-600 font-semibold mb-2">
                                    <i class="fas fa-hotel mr-1"></i> {{ $hotel->name }}
                                </p>
                                @if($promotion->description)
                                    <p class="text-gray-700 text-sm mb-3">{{ $promotion->description }}</p>
                                @endif
                                <div class="space-y-2 text-sm text-gray-600">
                                    <p><i class="fas fa-bed mr-2 text-blue-500"></i><span class="font-semibold">Applies to:</span> {{ $promotion->getAppliesTo() }}</p>
                                    <p><i class="fas fa-calendar mr-2 text-green-500"></i><span class="font-semibold">Valid until:</span> {{ $promotion->end_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Results -->
        @if($availableHotels->count() > 0)
            <div class="grid grid-cols-1 gap-8">
                @foreach($availableHotels as $hotel)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                        <!-- Hotel Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <h2 class="text-2xl font-bold text-white mb-1">{{ $hotel->name }}</h2>
                            <p class="text-blue-100">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                @if($hotel->dzongkhag_relation)
                                    {{ $hotel->dzongkhag_relation->name }}, Bhutan
                                @else
                                    Bhutan
                                @endif
                            </p>
                        </div>

                        <!-- Room Options (Aggregated by Room Type + Price) -->
                        <div class="p-6">
                            @if($hotel->inventory && count($hotel->inventory) > 0)
                                <h3 class="text-lg font-bold text-gray-800 mb-4">
                                    <i class="fas fa-door-open mr-2 text-blue-600"></i>Available Room Options
                                </h3>
                                
                                <div class="space-y-4">
                                    @foreach($hotel->inventory as $roomType => $variants)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-blue-50 transition">
                                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                                <!-- Room Image -->
                                                @php
                                                    $firstVariant = $variants[0] ?? null;
                                                    $firstPhoto = $firstVariant['firstPhoto'] ?? null;
                                                @endphp
                                                <div class="w-full md:w-40 h-40 flex-shrink-0 rounded-lg overflow-hidden bg-gradient-to-br from-gray-200 to-gray-300">
                                                    @if($firstPhoto)
                                                        <img src="{{ asset('storage/' . $firstPhoto) }}" 
                                                             alt="{{ $roomType }}" 
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-300 to-orange-500">
                                                            <i class="fas fa-bed text-white text-5xl"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Room Info -->
                                                <div class="flex-1">
                                                    <h4 class="text-lg font-bold text-gray-900 mb-3">{{ $roomType }}</h4>
                                                    
                                                    <!-- Price Variants for this Room Type -->
                                                    <div class="space-y-2">
                                                        @foreach($variants as $variant)
                                                            @php
                                                                $available = $variant['available'];
                                                                $isAvailable = $available > 0;
                                                            @endphp
                                                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                                                <div>
                                                                    <p class="text-gray-700">
                                                                        <span class="font-semibold">Price:</span>
                                                                        <span class="text-2xl font-bold text-green-600">Nu. {{ number_format($variant['price'], 2) }}</span>
                                                                        <span class="text-gray-600 text-sm"> per night</span>
                                                                    </p>
                                                                    <p class="text-gray-600 text-sm mt-1">
                                                                        <i class="fas fa-check-circle {{ $isAvailable ? 'text-green-600' : 'text-gray-400' }} mr-2"></i>
                                                                        <span class="font-semibold">Available:</span>
                                                                        <span class="text-lg font-bold {{ $isAvailable ? 'text-green-600' : 'text-gray-500' }}">{{ $available }}</span>
                                                                    </p>
                                                                </div>

                                                                @if($isAvailable)
                                                                    <form action="{{ route('guest.booking.form') }}" method="GET">
                                                                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                                                        <input type="hidden" name="room_type" value="{{ $roomType }}">
                                                                        <input type="hidden" name="price" value="{{ $variant['price'] }}">
                                                                        <input type="hidden" name="check_in" value="{{ $validated['check_in'] }}">
                                                                        <input type="hidden" name="check_out" value="{{ $validated['check_out'] }}">
                                                                        <input type="hidden" name="adults" value="{{ $validated['adults'] }}">
                                                                        <input type="hidden" name="children" value="{{ $validated['children'] ?? 0 }}">
                                                                        <input type="hidden" name="guests" value="{{ $validated['guests'] }}">
                                                                        <input type="hidden" name="num_rooms" value="{{ $validated['rooms'] }}">
                                                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded-lg transition shadow-md whitespace-nowrap">
                                                                            <i class="fas fa-check-circle mr-2"></i>Book Now
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button disabled class="bg-gray-400 cursor-not-allowed text-white font-bold px-6 py-2 rounded-lg opacity-60 whitespace-nowrap">
                                                                        <i class="fas fa-times-circle mr-2"></i>Sold Out
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-600 text-center py-6">No rooms available for selected dates.</p>
                            @endif
                        </div>

                        <!-- View Hotel Details Link -->
                        <div class="px-6 py-4 border-t bg-gray-50">
                            <a href="{{ route('guest.hotel', ['id' => $hotel->id, 'check_in' => $validated['check_in'], 'check_out' => $validated['check_out'], 'adults' => $validated['adults'], 'children' => $validated['children'] ?? 0, 'rooms' => $validated['rooms']]) }}" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold">
                                <i class="fas fa-arrow-right mr-2"></i>View Hotel Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Results Message -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="mb-6">
                    <i class="fas fa-search text-gray-300 text-7xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">No Rooms Available</h2>
                <p class="text-gray-600 text-lg mb-6">
                    We couldn't find any available rooms matching your search criteria.
                </p>
                <div class="space-y-2 text-gray-600 mb-8">
                    <p><i class="fas fa-info-circle text-blue-600 mr-2"></i> Try adjusting your dates</p>
                    <p><i class="fas fa-info-circle text-blue-600 mr-2"></i> Search in a different Dzongkhag</p>
                    <p><i class="fas fa-info-circle text-blue-600 mr-2"></i> Reduce the number of rooms</p>
                </div>
                <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition shadow-md">
                    <i class="fas fa-search mr-2"></i> Try New Search
                </a>
            </div>
        @endif
    </div>
</body>
</html>
