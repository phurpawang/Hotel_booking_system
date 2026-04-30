<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bhutan Hotel Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Ensure date inputs are visible */
        input[type="date"],
        input[type="number"],
        select {
            color: #1f2937 !important;
            background-color: white !important;
        }
        
        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 1;
            filter: invert(0);
        }
        
        /* Style for number input arrows */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
        }
        
        /* Center text in number inputs */
        input[type="number"] {
            text-align: center;
            font-weight: 600;
        }
        
        /* Ensure select dropdown arrow is visible */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        
        /* Remove number input spinners for cleaner look in guest inputs */
        .guest-input::-webkit-inner-spin-button,
        .guest-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .guest-input {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bhutan Hotel Booking System</h1>
                    <p class="text-sm text-blue-100">Your Gateway to Bhutan's Hospitality</p>
                </div>
                <nav class="flex gap-4">
                    <a href="{{ route('hotel.login') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition">Hotel Login</a>
                    <a href="{{ route('guest.manage-booking') }}" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">Manage Booking</a>
                    <a href="{{ route('hotel.register') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">Register Your Hotel</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section with Search -->
    <section class="bg-gradient-to-r from-blue-500 to-blue-700 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold mb-4">Find Your Perfect Stay in Bhutan</h2>
                <p class="text-xl text-blue-100">Explore rated hotels across all Dzongkhags</p>
            </div>

            <!-- Search Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-7xl mx-auto">
                <form action="{{ route('guest.search') }}" method="GET" class="flex flex-wrap items-start gap-4">
                    <!-- Dzongkhag -->
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-gray-900 font-semibold mb-2 text-sm h-5">Dzongkhag</label>
                        <select name="dzongkhag_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white text-base h-[50px]">
                            <option value="">Select Dzongkhag</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Check-in -->
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-gray-900 font-semibold mb-2 text-sm h-5">Check-in</label>
                        <input type="date" name="check_in" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white h-[50px]">
                    </div>

                    <!-- Check-out -->
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-gray-900 font-semibold mb-2 text-sm h-5">Check-out</label>
                        <input type="date" name="check_out" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white h-[50px]">
                    </div>

                    <!-- Guests & Rooms Section -->
                    <div class="flex-1 min-w-[300px]">
                        <label class="block text-gray-900 font-semibold mb-2 text-sm h-5">Guests & rooms</label>
                        <div class="flex gap-2 h-[50px]">
                            <div class="flex-1 relative">
                                <input type="number" name="adults" min="1" value="2" required class="guest-input w-full h-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white text-center font-semibold">
                                <span class="absolute -bottom-5 left-0 right-0 text-xs text-gray-600 text-center">Adults</span>
                            </div>
                            <div class="flex-1 relative">
                                <input type="number" name="children" min="0" value="0" class="guest-input w-full h-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white text-center font-semibold">
                                <span class="absolute -bottom-5 left-0 right-0 text-xs text-gray-600 text-center">Children</span>
                            </div>
                            <div class="flex-1 relative">
                                <input type="number" name="rooms" min="1" value="1" required class="guest-input w-full h-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white text-center font-semibold">
                                <span class="absolute -bottom-5 left-0 right-0 text-xs text-gray-600 text-center">Rooms</span>
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="pt-7">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-base font-semibold hover:bg-blue-700 transition shadow-lg whitespace-nowrap h-[50px]">
                            <i class="fas fa-search mr-2"></i> Search Hotels
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Top Rated Hotels -->
    <section class="container mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h3 class="text-3xl font-bold text-gray-800">Top Rated Hotels</h3>
                <p class="text-gray-600">Highly rated by our guests</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredHotels as $hotel)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                <div class="h-48 relative">
                    @if($hotel->property_image)
                        <img src="{{ asset('storage/' . $hotel->property_image) }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-blue-200 flex items-center justify-center">
                            <i class="fas fa-hotel text-white text-5xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-semibold text-gray-700">
                        @if($hotel->star_rating)
                            @for($i = 0; $i < $hotel->star_rating; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                        @else
                            <span class="text-gray-500">Not Rated</span>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $hotel->name }}</h4>
                    <p class="text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt text-blue-600"></i> 
                        {{ $hotel->dzongkhag ? $hotel->dzongkhag . ', ' : '' }}Bhutan
                    </p>
                    <p class="text-gray-700 mb-4 text-sm line-clamp-2">
                        {{ $hotel->description ?? 'A wonderful place to stay in Bhutan' }}
                    </p>
                    <div class="flex justify-between items-center">
                        <div class="text-gray-700 font-semibold">
                            From Nu. {{ number_format($hotel->rooms->min('price_per_night') ?? 0, 2) }}/night
                        </div>
                        <a href="{{ route('guest.hotel', $hotel->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-600 text-lg">No hotels available at the moment.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- All Registered Hotels -->
    <section class="container mx-auto px-4 py-12 bg-gray-100 rounded-xl">
        <div class="mb-8">
            <h3 class="text-3xl font-bold text-gray-800 mb-2">All Registered Hotels</h3>
            <p class="text-gray-600">Browse every active listing</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredHotels as $hotel)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                <div class="h-48 relative">
                    @if($hotel->property_image)
                        <img src="{{ asset('storage/' . $hotel->property_image) }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-hotel text-gray-400 text-5xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full">
                        @if($hotel->star_rating)
                            @for($i = 0; $i < $hotel->star_rating; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $hotel->name }}</h4>
                    <p class="text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt text-blue-600"></i> 
                        {{ $hotel->dzongkhag ? $hotel->dzongkhag . ', ' : '' }}Bhutan
                    </p>
                    <p class="text-gray-700 mb-4 text-sm line-clamp-2">
                        {{ $hotel->description ?? 'Experience authentic Bhutanese hospitality' }}
                    </p>
                    <a href="{{ route('guest.hotel', $hotel->id) }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition w-full text-center">
                        View Details
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-600 text-lg">No hotels registered yet.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Support -->
                <div>
                    <h5 class="text-lg font-bold mb-4">Support</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Manage your login</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contact Customer Service</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Safety Resource Center</a></li>
                    </ul>
                </div>

                <!-- Discover -->
                <div>
                    <h5 class="text-lg font-bold mb-4">Discover</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Getting loyalty program</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Seasonal and holiday deals</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Travel articles</a></li>
                    </ul>
                </div>

                <!-- Terms and Settings -->
                <div>
                    <h5 class="text-lg font-bold mb-4">Terms and settings</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Privacy Notice</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Accessibility Statement</a></li>
                    </ul>
                </div>

                <!-- Partners -->
                <div>
                    <h5 class="text-lg font-bold mb-4">Partners</h5>
                    <ul class="space-y-2">
                        <li><a href="{{ route('hotel.register') }}" class="text-gray-300 hover:text-white">Extranet login</a></li>
                        <li><a href="{{ route('hotel.register') }}" class="text-gray-300 hover:text-white">Partner help</a></li>
                        <li><a href="{{ route('hotel.register') }}" class="text-gray-300 hover:text-white">List your property</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; 2026 Bhutan Hotel Booking System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
