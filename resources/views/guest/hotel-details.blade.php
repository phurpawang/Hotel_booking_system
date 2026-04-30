<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hotel->name }} - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">{{ $hotel->name }}</h1>
                    <p class="text-sm text-blue-100">Hotel Details & Booking</p>
                </div>
                <a href="{{ url()->previous() }}" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i> Go Back
                </a>
            </div>
        </div>
    </header>

    <!-- Reusable Search Bar -->
    @include('components.search-bar', [
        'dzongkhags' => \App\Models\Dzongkhag::all(),
        'sticky' => true,
        'check_in' => request('check_in'),
        'check_out' => request('check_out'),
        'adults' => request('adults', 1),
        'children' => request('children', 0),
        'rooms' => request('rooms', 1),
        'dzongkhag_id' => request('dzongkhag_id')
    ])

    <div class="container mx-auto px-4 py-8">
        <!-- Hotel Details Card -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Hotel Information and Map/Image Section -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Side: Information and Map -->
                    <div class="lg:col-span-2">
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $hotel->name }}</h1>
                        
                        <div class="mb-6">
                            <p class="text-lg text-blue-600 mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span class="font-semibold">Location:</span> Bhutan
                            </p>
                            <p class="text-gray-700 mb-3">
                                <span class="font-semibold">Address:</span> {{ $hotel->address }}
                            </p>
                            @if($hotel->star_rating)
                                <div class="flex items-center mb-3">
                                    <span class="font-semibold text-gray-700 mr-2">Rating:</span>
                                    @for($i = 0; $i < $hotel->star_rating; $i++)
                                        <i class="fas fa-star text-yellow-500"></i>
                                    @endfor
                                </div>
                            @endif

                            <!-- Map Toggle Button -->
                            <button onclick="toggleMap()" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition shadow-md mt-2" id="mapToggleBtn">
                                <i class="fas fa-map mr-2"></i> Show on map
                            </button>
                        </div>

                        <!-- Map Container -->
                        <div id="mapContainer" class="hidden mb-6">
                            <div id="hotelMap" class="rounded-lg overflow-hidden shadow-lg" style="height: 400px;">
                            </div>
                            <button onclick="closeMap()" class="mt-4 inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                                <i class="fas fa-times mr-2"></i> Close map
                            </button>
                        </div>

                        @if($hotel->description)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">About</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $hotel->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Right Side: Hotel Image -->
                    <div class="lg:col-span-1">
                        <div class="h-64 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg overflow-hidden shadow-lg">
                            @if($hotel->property_image)
                                <img src="{{ asset('storage/' . $hotel->property_image) }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-hotel text-gray-400 text-6xl"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Promotions Section -->
            @if($hotel->promotions && count($hotel->promotions) > 0)
            <div class="p-8 border-t bg-gradient-to-r from-orange-50 to-yellow-50">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-tag text-orange-500 mr-3"></i>Current Promotions
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($hotel->promotions as $promotion)
                    <div class="bg-white rounded-xl shadow-md border-l-4 border-orange-500 overflow-hidden hover:shadow-lg transition">
                        <div class="bg-gradient-to-r from-orange-500 to-red-500 p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-white">{{ $promotion->title }}</h3>
                                <div class="bg-white rounded-full px-3 py-1 ml-2">
                                    <span class="text-2xl font-bold text-orange-600">
                                        @if($promotion->discount_type === 'percentage')
                                            {{ $promotion->discount_value }}%
                                        @else
                                            Nu.{{ number_format($promotion->discount_value, 0) }}
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-600 block">OFF</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            @if($promotion->description)
                                <p class="text-gray-700 text-sm mb-3">{{ $promotion->description }}</p>
                            @endif
                            <div class="space-y-2 text-sm text-gray-600">
                                <p><i class="fas fa-bed mr-2 text-blue-500"></i><span class="font-semibold">Applies to:</span> {{ $promotion->getAppliesTo() }}</p>
                                <p><i class="fas fa-calendar mr-2 text-green-500"></i><span class="font-semibold">Valid until:</span> {{ $promotion->end_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Available Rooms Section (Aggregated by Room Type + Price) -->
            <div class="p-8 {{ $hotel->promotions && count($hotel->promotions) > 0 ? '' : 'pt-0' }}">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-door-open mr-3 text-blue-600"></i>Available Room Options
                </h2>

                @if($inventory && count($inventory) > 0)
                    <div class="space-y-6">
                        @foreach($inventory as $roomType => $variants)
                            <div class="border rounded-lg overflow-hidden">
                                <!-- Room Type Header -->
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                                    <h3 class="text-xl font-bold text-white">
                                        <i class="fas fa-bed mr-2"></i>{{ $roomType }} Room
                                    </h3>
                                </div>

                                <!-- Price Variants List -->
                                <div class="divide-y">
                                    @foreach($variants as $variant)
                                        @php
                                            $available = $variant['available'];
                                            $isAvailable = $available > 0;
                                            $firstPhoto = $variant['firstPhoto'] ?? null;
                                        @endphp
                                        <div class="p-6 bg-white hover:bg-blue-50 transition flex items-center justify-between">
                                            <div class="flex items-center gap-6 flex-1">
                                                <!-- Room Photo -->
                                                <div class="hidden md:block">
                                                    @if($firstPhoto)
                                                        <img src="{{ asset('storage/' . $firstPhoto) }}" 
                                                             alt="{{ $roomType }}" 
                                                             class="w-24 h-20 object-cover rounded-lg shadow">
                                                    @else
                                                        <div class="w-24 h-20 bg-gradient-to-br from-orange-300 to-orange-500 rounded-lg flex items-center justify-center shadow">
                                                            <i class="fas fa-bed text-white text-2xl"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Room Info -->
                                                <div class="flex-1">
                                                    <h4 class="text-lg font-bold text-gray-900">{{ $roomType }}</h4>
                                                    <p class="text-gray-700">
                                                        <span class="font-semibold">Price:</span>
                                                        <span class="text-xl font-bold text-green-600">Nu. {{ number_format($variant['price'], 2) }}</span>
                                                        <span class="text-gray-600 text-sm"> per night</span>
                                                    </p>
                                                    <p class="text-gray-600 mt-2">
                                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                        <span class="font-semibold">Available:</span>
                                                        <span class="text-lg font-bold text-green-600">{{ $available }}</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Book Button -->
                                            <div class="ml-4">
                                                @if($isAvailable)
                                                    <form action="{{ route('guest.booking.form') }}" method="GET" class="inline">
                                                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                                        <input type="hidden" name="room_type" value="{{ $roomType }}">
                                                        <input type="hidden" name="price" value="{{ $variant['price'] }}">
                                                        <input type="hidden" name="check_in" value="{{ $checkIn ?? '' }}">
                                                        <input type="hidden" name="check_out" value="{{ $checkOut ?? '' }}">
                                                        <input type="hidden" name="adults" value="{{ $adults ?? 1 }}">
                                                        <input type="hidden" name="children" value="{{ $children ?? 0 }}">
                                                        <input type="hidden" name="guests" value="{{ $guests ?? 1 }}">
                                                        <input type="hidden" name="num_rooms" value="{{ $numRooms ?? 1 }}">
                                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-lg transition shadow-md whitespace-nowrap">
                                                            <i class="fas fa-check-circle mr-2"></i>Book Now
                                                        </button>
                                                    </form>
                                                @else
                                                    <button disabled class="bg-gray-400 cursor-not-allowed text-white font-bold px-8 py-3 rounded-lg whitespace-nowrap opacity-60">
                                                        <i class="fas fa-times-circle mr-2"></i>Sold Out
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-600 text-lg">No rooms available at this property right now.</p>
                    </div>
                @endif
            </div>

            <!-- Guest Reviews Section -->
            <div class="p-8 pt-0 border-t">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Guest Reviews</h2>
                        @php
                            $reviews = $hotel->reviews()->approved()->get();
                            $avgRating = $reviews->count() > 0 ? round($reviews->avg('overall_rating'), 1) : 0;
                        @endphp
                        <p class="text-gray-600 mt-1">
                            @if($reviews->count() > 0)
                                <span class="text-yellow-500">
                                    @for($i = 0; $i < 5; $i++)
                                        @if($i < round($avgRating/2))
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </span>
                                <span class="font-bold text-gray-900"> {{ $avgRating }}/10 </span>
                                <span class="text-gray-600">({{ $reviews->count() }} guest review{{ $reviews->count() !== 1 ? 's' : '' }})</span>
                            @else
                                <span class="text-gray-600">No reviews yet</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($reviews->count() > 0)
                    <!-- Rating Summary -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 p-6 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-600">{{ round($reviews->avg('overall_rating'), 1) }}</p>
                            <p class="text-sm text-gray-600">Overall</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-600">{{ round($reviews->avg('cleanliness_rating'), 1) }}</p>
                            <p class="text-sm text-gray-600">Cleanliness</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-600">{{ round($reviews->avg('staff_rating'), 1) }}</p>
                            <p class="text-sm text-gray-600">Staff</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-600">{{ round($reviews->avg('comfort_rating'), 1) }}</p>
                            <p class="text-sm text-gray-600">Comfort</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-600">{{ round($reviews->avg('facilities_rating'), 1) }}</p>
                            <p class="text-sm text-gray-600">Facilities</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-600">{{ round($reviews->avg('value_for_money_rating'), 1) }}</p>
                            <p class="text-sm text-gray-600">Value</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-600">{{ round($reviews->avg('location_rating'), 1) }}</p>
                            <p class="text-sm text-gray-600">Location</p>
                        </div>
                    </div>

                    <!-- Individual Reviews -->
                    <div class="space-y-6">
                        @foreach($reviews->take(5) as $review)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $review->guest_name }}</p>
                                    <p class="text-sm text-gray-600">
                                        @if($review->review_date instanceof \Carbon\Carbon)
                                            {{ $review->review_date->format('F d, Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($review->review_date)->format('F d, Y') }}
                                        @endif
                                    </p>
                                </div>
                                <span class="text-lg font-bold text-blue-600">{{ $review->overall_rating }}<span class="text-sm text-gray-500">/10</span></span>
                            </div>

                            @if($review->comment)
                            <p class="text-gray-700 mb-4">{{ Str::limit($review->comment, 200) }}</p>
                            @endif

                            @if($review->manager_reply)
                            <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                                <p class="text-sm font-bold text-blue-900 mb-1">
                                    <i class="fas fa-reply mr-2"></i>{{ $hotel->name }} replied:
                                </p>
                                <p class="text-sm text-blue-800">{{ Str::limit($review->manager_reply, 150) }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    @if($reviews->count() > 5)
                    <div class="text-center mt-6">
                        <button onclick="alert('More reviews coming soon!')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                            View All {{ $reviews->count() }} Reviews
                        </button>
                    </div>
                    @endif
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <i class="fas fa-star text-gray-300 text-5xl mb-3"></i>
                        <p class="text-gray-600">No guest reviews yet. Be the first to share your experience!</p>
                    </div>
                @endif
            </div>

            <!-- Ask a Question Section -->
            <div class="p-8 pt-0 border-t">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Still looking?</h3>
                        <p class="text-gray-600 mt-1">Have questions about this hotel? We have an instant answer to most questions</p>
                    </div>
                    <button onclick="openInquiryModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition shadow-md">
                        <i class="fas fa-question-circle mr-2"></i>Ask a question
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ask a Question Modal -->
    <div id="inquiryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900">Ask a question</h2>
                <button onclick="closeInquiryModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="p-6">
                <p class="text-sm font-semibold text-gray-700 mb-4">About: <span class="text-gray-900">{{ $hotel->name }}</span></p>

                <form id="inquiryForm" onsubmit="submitInquiry(event)">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your name *</label>
                        <input type="text" name="guest_name" required placeholder="Enter your name" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your email *</label>
                        <input type="email" name="guest_email" required placeholder="Enter your email" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your question <span class="text-gray-500">(0 / 300)</span></label>
                        <textarea name="question" id="questionText" required placeholder="e.g., do you offer room service?" 
                            maxlength="500" oninput="updateCharCount()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4"></textarea>
                        <p class="text-xs text-gray-600 mt-1">300 characters left</p>
                    </div>

                    <div class="mb-6 flex items-start gap-2 text-xs text-gray-600 bg-blue-50 p-3 rounded">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 flex-shrink-0"></i>
                        <p>If we can't answer your question right away, you can forward it to the property. Make sure not to include any personal info and to <a href="#" class="text-blue-600 hover:underline">follow our guidelines</a>.</p>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                        Submit question
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openInquiryModal() {
            document.getElementById('inquiryModal').classList.remove('hidden');
        }

        function closeInquiryModal() {
            document.getElementById('inquiryModal').classList.add('hidden');
            document.getElementById('inquiryForm').reset();
            updateCharCount();
        }

        function updateCharCount() {
            const textarea = document.getElementById('questionText');
            const charCount = textarea.value.length;
            const remaining = 300 - charCount;
            const label = document.querySelector('label[for="questionText"]').nextElementSibling;
            label.textContent = `(${charCount} / 300)`;
        }

        function submitInquiry(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            fetch(`{{ route('guest.inquiry.store', $hotel->id) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        closeInquiryModal();
                    }, 1500);
                } else {
                    showNotification('Error: ' + (data.message || 'Failed to submit question'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            });
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 max-w-md p-4 rounded-lg shadow-lg transition-all duration-300 z-[9999] ${
                type === 'success' 
                    ? 'bg-green-500 text-white' 
                    : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-start gap-3">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mt-0.5 text-lg"></i>
                    <div>
                        <p class="font-semibold">${type === 'success' ? 'Success!' : 'Error!'}</p>
                        <p class="text-sm opacity-90">${message}</p>
                    </div>
                    <button class="ml-auto text-white hover:opacity-75" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(notification);
            
            // Auto-remove after 4 seconds
            setTimeout(() => {
                notification.remove();
            }, 4000);
        }

        // Close modal when clicking outside
        document.getElementById('inquiryModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeInquiryModal();
            }
        });
    </script>

    <script>
        let map;
        let marker;
        let mapInitialized = false;

        function toggleMap() {
            const mapContainer = document.getElementById('mapContainer');
            const mapToggleBtn = document.getElementById('mapToggleBtn');
            
            if (mapContainer.classList.contains('hidden')) {
                mapContainer.classList.remove('hidden');
                mapToggleBtn.innerHTML = '<i class="fas fa-times mr-2"></i> Hide map';
                
                // Initialize map if not already done
                if (!mapInitialized) {
                    initializeMap();
                    mapInitialized = true;
                } else {
                    // Invalidate size to refresh map display
                    setTimeout(() => {
                        if (map) map.invalidateSize();
                    }, 100);
                }
            } else {
                closeMap();
            }
        }

        function closeMap() {
            const mapContainer = document.getElementById('mapContainer');
            const mapToggleBtn = document.getElementById('mapToggleBtn');
            
            mapContainer.classList.add('hidden');
            mapToggleBtn.innerHTML = '<i class="fas fa-map mr-2"></i> Show on map';
        }

        function parseLocationData() {
            const pinLocation = '{{ $hotel->pin_location }}';
            let latitude = 27.3081;  // Default Bhutan center
            let longitude = 89.6007;

            if (pinLocation) {
                // Try to parse as coordinates (latitude,longitude format)
                const coordMatch = pinLocation.match(/(-?\d+\.?\d*)\s*[,]\s*(-?\d+\.?\d*)/);
                if (coordMatch) {
                    latitude = parseFloat(coordMatch[1]);
                    longitude = parseFloat(coordMatch[2]);
                } else {
                    // Try to extract from Google Maps URL
                    // Format: https://maps.google.com/?q=latitude,longitude or https://maps.google.com/maps/place/@latitude,longitude
                    const urlMatch = pinLocation.match(/@(-?\d+\.?\d*),(-?\d+\.?\d*)/);
                    if (urlMatch) {
                        latitude = parseFloat(urlMatch[1]);
                        longitude = parseFloat(urlMatch[2]);
                    }
                }
            }

            return { latitude, longitude };
        }

        function initializeMap() {
            const { latitude, longitude } = parseLocationData();
            
            // Initialize map
            map = L.map('hotelMap').setView([latitude, longitude], 14);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
            
            // Add marker for hotel location
            marker = L.marker([latitude, longitude]).addTo(map);
            marker.bindPopup('<b>{{ $hotel->name }}</b><br>{{ $hotel->address }}').openPopup();
            
            // Invalidate size to ensure proper rendering
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        }
    </script>
</body>
</html>
