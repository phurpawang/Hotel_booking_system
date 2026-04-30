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
        <!-- Go Back Button -->
        <a href="{{ route('home') }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition mb-6 shadow-lg">
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
            <form method="GET" action="{{ route('guest.search') }}" class="flex items-center gap-4">
                <input type="hidden" name="dzongkhag_id" value="{{ $validated['dzongkhag_id'] ?? '' }}">
                <input type="hidden" name="check_in" value="{{ $validated['check_in'] }}">
                <input type="hidden" name="check_out" value="{{ $validated['check_out'] }}">
                <input type="hidden" name="adults" value="{{ $validated['adults'] }}">
                <input type="hidden" name="children" value="{{ $validated['children'] ?? 0 }}">
                <input type="hidden" name="rooms" value="{{ $validated['rooms'] }}">
                
                <label class="text-gray-700 font-semibold">Sort by</label>
                <select name="sort" class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="rating">Rating</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="name">Name (A-Z)</option>
                </select>
                <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                    Apply
                </button>
            </form>
        </div>

        <!-- Results -->
        @if($availableHotels->count() > 0)
            <div class="grid grid-cols-1 gap-6">
                @foreach($availableHotels as $hotel)
                    @foreach($hotel->rooms as $room)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                            <div class="flex flex-col md:flex-row">
                                <!-- Room Image -->
                                <div class="md:w-80 h-64 md:h-auto">
                                    @php
                                        $photos = is_array($room->photos) ? $room->photos : json_decode($room->photos, true);
                                        $firstPhoto = !empty($photos) ? $photos[0] : null;
                                    @endphp
                                    @if($firstPhoto)
                                        <img src="{{ asset('storage/' . $firstPhoto) }}" alt="{{ $room->room_type }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-orange-300 to-orange-500 flex items-center justify-center">
                                            <i class="fas fa-bed text-white text-6xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Hotel & Room Details -->
                                <div class="flex-1 p-6">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $hotel->name }}</h2>
                                    <p class="text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt text-blue-600 mr-1"></i>
                                        @if($hotel->dzongkhag)
                                            <span class="font-semibold">{{ $hotel->dzongkhag }}, Bhutan</span>
                                        @elseif($hotel->dzongkhag_relation)
                                            <span class="font-semibold">{{ $hotel->dzongkhag_relation->name }}, Bhutan</span>
                                        @else
                                            Bhutan
                                        @endif
                                    </p>
                                    
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $room->room_type }}</h3>
                                    
                                    <div class="mb-4">
                                        <p class="text-gray-700">
                                            <i class="fas fa-door-open text-blue-600 mr-2"></i>
                                            <span class="font-semibold">Available rooms: {{ $room->quantity }}</span>
                                        </p>
                                        
                                        @if($room->amenities)
                                            @php
                                                $amenities = is_array($room->amenities) ? $room->amenities : json_decode($room->amenities, true);
                                            @endphp
                                            @if(is_array($amenities) && count($amenities) > 0)
                                                <p class="text-gray-600 text-sm mt-2">
                                                    <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                                    {{ implode(', ', array_slice($amenities, 0, 3)) }}
                                                    @if(count($amenities) > 3)
                                                        <span class="text-blue-600">+{{ count($amenities) - 3 }} more</span>
                                                    @endif
                                                </p>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-3xl font-bold text-gray-900">
                                                Nu. {{ number_format($room->price_per_night, 2) }}
                                                <span class="text-lg font-normal text-gray-600">/ night</span>
                                            </p>
                                        </div>

                                        <div class="flex gap-3">
                                            <form action="{{ route('guest.booking.form') }}" method="GET">
                                                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                <input type="hidden" name="check_in" value="{{ $validated['check_in'] }}">
                                                <input type="hidden" name="check_out" value="{{ $validated['check_out'] }}">
                                                <input type="hidden" name="adults" value="{{ $validated['adults'] }}">
                                                <input type="hidden" name="children" value="{{ $validated['children'] ?? 0 }}">
                                                <input type="hidden" name="guests" value="{{ $validated['guests'] }}">
                                                <input type="hidden" name="num_rooms" value="{{ $validated['rooms'] }}">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition shadow-md">
                                                    Book Now
                                                </button>
                                            </form>

                                            <a href="{{ route('guest.hotel', ['id' => $hotel->id, 'check_in' => $validated['check_in'], 'check_out' => $validated['check_out'], 'adults' => $validated['adults'], 'children' => $validated['children'] ?? 0, 'rooms' => $validated['rooms']]) }}" 
                                               class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-3 rounded-lg transition shadow-md">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
