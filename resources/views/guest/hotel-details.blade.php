<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hotel->name }} - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-purple-400 via-purple-500 to-purple-600 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Go Back Button -->
        <a href="{{ url()->previous() }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition mb-6 shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i> Go Back
        </a>

        <!-- Hotel Details Card -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Hotel Image -->
            <div class="w-full h-96 bg-gradient-to-br from-gray-200 to-gray-300">
                @if($hotel->property_image)
                    <img src="{{ asset('storage/' . $hotel->property_image) }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-hotel text-gray-400 text-8xl"></i>
                    </div>
                @endif
            </div>

            <!-- Hotel Information -->
            <div class="p-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $hotel->name }}</h1>
                
                <div class="mb-4">
                    <p class="text-lg text-blue-600 mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span class="font-semibold">Location:</span> Bhutan
                    </p>
                    <p class="text-gray-700 mb-2">
                        <span class="font-semibold">Address:</span> {{ $hotel->address }}
                    </p>
                    @if($hotel->star_rating)
                        <div class="flex items-center mb-2">
                            <span class="font-semibold text-gray-700 mr-2">Rating:</span>
                            @for($i = 0; $i < $hotel->star_rating; $i++)
                                <i class="fas fa-star text-yellow-500"></i>
                            @endfor
                        </div>
                    @endif
                </div>

                @if($hotel->description)
                    <div class="mb-6">
                        <p class="text-gray-700 leading-relaxed">{{ $hotel->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Available Rooms Section -->
            <div class="p-8 pt-0">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Rooms</h2>

                @if($hotel->rooms->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b-2 border-gray-300">
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Photo</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Room Type</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Room #</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Price (Nu.)</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Available</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hotel->rooms as $room)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                        <td class="py-4 px-4">
                                            @php
                                                $photos = is_array($room->photos) ? $room->photos : json_decode($room->photos, true);
                                                $firstPhoto = !empty($photos) ? $photos[0] : null;
                                            @endphp
                                            @if($firstPhoto)
                                                <img src="{{ asset('storage/' . $firstPhoto) }}" alt="{{ $room->room_type }}" class="w-20 h-16 object-cover rounded-lg shadow">
                                            @else
                                                <div class="w-20 h-16 bg-gradient-to-br from-orange-300 to-orange-500 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-bed text-white text-xl"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-semibold text-gray-900">{{ $room->room_type }}</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-gray-700">{{ $room->room_number }}</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-bold text-gray-900">{{ number_format($room->price_per_night, 2) }}</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-green-600 font-semibold">{{ $room->quantity }}</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex flex-col gap-2">
                                                <span class="text-sm text-blue-600">
                                                    Available: {{ $room->quantity }} {{ $room->room_type }}(s)
                                                </span>
                                                <form action="{{ route('guest.booking.form') }}" method="GET" class="inline">
                                                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                    <input type="hidden" name="check_in" value="{{ $checkIn ?? '' }}">
                                                    <input type="hidden" name="check_out" value="{{ $checkOut ?? '' }}">
                                                    <input type="hidden" name="adults" value="{{ $adults ?? 1 }}">
                                                    <input type="hidden" name="children" value="{{ $children ?? 0 }}">
                                                    <input type="hidden" name="guests" value="{{ $guests ?? 1 }}">
                                                    <input type="hidden" name="num_rooms" value="{{ $numRooms ?? 1 }}">
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition shadow-md">
                                                        Book Now
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="fas fa-bed text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-600 text-lg">No rooms available at this property.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
