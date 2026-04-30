<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write a Review - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Write a Review</h1>
                <a href="{{ route('guest.manage-booking') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif

        <!-- Booking Summary Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Booking Summary</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 text-sm">Hotel</p>
                    <p class="text-xl font-semibold text-gray-800">{{ $booking->hotel->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Booking ID</p>
                    <p class="text-xl font-semibold text-blue-600">{{ $booking->booking_id }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Room Type</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $booking->room->room_type ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Check-in to Check-out</p>
                    <p class="text-lg font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Review Form -->
        <form method="POST" action="{{ route('guest.review.submit') }}" class="bg-white rounded-xl shadow-lg p-8">
            @csrf
            
            <!-- Hidden Fields -->
            <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
            <input type="hidden" name="identifier" value="{{ $identifier }}">

            <!-- Instructions -->
            <div class="mb-8 bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
                <p class="text-gray-700">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Please share your honest feedback by rating different aspects of your stay. Your reviews help us improve our services.
                </p>
            </div>

            <!-- Overall Rating -->
            <div class="mb-8">
                <label class="block text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>Overall Rating
                    <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-4">
                    <input type="range" name="overall_rating" min="1" max="10" value="5" id="overall_rating" 
                        class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-yellow-500" required>
                    <div class="flex items-center justify-center w-16 h-16 bg-yellow-50 rounded-lg border-2 border-yellow-300">
                        <span id="overall_rating_display" class="text-3xl font-bold text-yellow-600">5</span>
                        <span class="text-lg text-gray-600">/10</span>
                    </div>
                </div>
                @error('overall_rating')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
            </div>

            <!-- Category Ratings Grid -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Rate Each Aspect</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Cleanliness -->
                    <div>
                        <label class="block text-base font-semibold text-gray-700 mb-3">
                            <i class="fas fa-soap text-teal-500 mr-2"></i>Cleanliness & Hygiene <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="range" name="cleanliness_rating" min="1" max="10" value="5" class="rating-range cleanliness_rating w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-teal-500" required>
                            <span class="cleanliness_rating_display text-lg font-bold text-teal-600 w-12 text-right">5/10</span>
                        </div>
                        @error('cleanliness_rating')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Staff Service -->
                    <div>
                        <label class="block text-base font-semibold text-gray-700 mb-3">
                            <i class="fas fa-handshake text-purple-500 mr-2"></i>Staff & Service <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="range" name="staff_rating" min="1" max="10" value="5" class="rating-range staff_rating w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-purple-500" required>
                            <span class="staff_rating_display text-lg font-bold text-purple-600 w-12 text-right">5/10</span>
                        </div>
                        @error('staff_rating')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Comfort -->
                    <div>
                        <label class="block text-base font-semibold text-gray-700 mb-3">
                            <i class="fas fa-bed text-pink-500 mr-2"></i>Comfort & Amenities <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="range" name="comfort_rating" min="1" max="10" value="5" class="rating-range comfort_rating w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-pink-500" required>
                            <span class="comfort_rating_display text-lg font-bold text-pink-600 w-12 text-right">5/10</span>
                        </div>
                        @error('comfort_rating')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Facilities -->
                    <div>
                        <label class="block text-base font-semibold text-gray-700 mb-3">
                            <i class="fas fa-building text-orange-500 mr-2"></i>Facilities <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="range" name="facilities_rating" min="1" max="10" value="5" class="rating-range facilities_rating w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-orange-500" required>
                            <span class="facilities_rating_display text-lg font-bold text-orange-600 w-12 text-right">5/10</span>
                        </div>
                        @error('facilities_rating')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Value for Money -->
                    <div>
                        <label class="block text-base font-semibold text-gray-700 mb-3">
                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>Value for Money <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="range" name="value_for_money_rating" min="1" max="10" value="5" class="rating-range value_for_money_rating w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-green-500" required>
                            <span class="value_for_money_rating_display text-lg font-bold text-green-600 w-12 text-right">5/10</span>
                        </div>
                        @error('value_for_money_rating')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-base font-semibold text-gray-700 mb-3">
                            <i class="fas fa-map-pin text-red-500 mr-2"></i>Location & Accessibility <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="range" name="location_rating" min="1" max="10" value="5" class="rating-range location_rating w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-red-500" required>
                            <span class="location_rating_display text-lg font-bold text-red-600 w-12 text-right">5/10</span>
                        </div>
                        @error('location_rating')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Comment Section -->
            <div class="mb-8">
                <label for="comment" class="block text-lg font-semibold text-gray-800 mb-3">
                    <i class="fas fa-comment text-blue-500 mr-2"></i>Additional Comments (Optional)
                </label>
                <textarea name="comment" id="comment" rows="6" placeholder="Share your experience... What did you like? What could be improved? (Max 2000 characters)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                    maxlength="2000"></textarea>
                <p class="text-xs text-gray-500 mt-2">
                    <span id="char_count">0</span> / 2000 characters
                </p>
                @error('comment')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 justify-center">
                <a href="{{ route('guest.booking.view', ['booking_id' => $booking->booking_id, 'identifier' => $identifier]) }}" 
                    class="px-8 py-3 border-2 border-gray-400 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-check mr-2"></i>Submit Review
                </button>
            </div>
        </form>
    </div>

    <script>
        // Update overall rating display
        document.getElementById('overall_rating').addEventListener('input', function(e) {
            document.getElementById('overall_rating_display').textContent = e.target.value;
        });

        // Update category rating displays
        document.querySelectorAll('.rating-range').forEach(slider => {
            function updateDisplay() {
                const name = slider.name;
                const value = slider.value;
                const display = document.querySelector('.' + name + '_display');
                if (display) {
                    display.textContent = value + '/10';
                }
            }
            
            slider.addEventListener('input', updateDisplay);
            updateDisplay(); // Initial call
        });

        // Character counter
        document.getElementById('comment').addEventListener('input', function(e) {
            document.getElementById('char_count').textContent = e.target.value.length;
        });
    </script>
</body>
</html>
