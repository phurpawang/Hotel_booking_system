<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Search Results - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
    <style>
        .noui-target { background: #e5e7eb; border: 1px solid #d1d5db; }
        .noui-connects { background: #3b82f6; }
        .noui-handle { background: #2563eb; border: 2px solid white; }
        .noui-handle:hover { background: #1d4ed8; }
        .hotel-card { transition: transform 0.2s, box-shadow 0.2s; }
        .hotel-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px rgba(0,0,0,0.15); }
        .review-badge { font-size: 12px; padding: 4px 8px; font-weight: bold; }
        .facility-badge { display: inline-block; padding: 4px 8px; background: #dbeafe; color: #1e40af; border-radius: 4px; font-size: 12px; margin: 2px; }
        .loading-spinner { text-align: center; padding: 3rem 0; }
        .filter-badge { display: inline-block; background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 20px; font-size: 12px; margin: 4px 4px 4px 0; }
        .filter-badge .close { cursor: pointer; margin-left: 6px; font-weight: bold; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Bhutan Hotel Booking System</h1>
                    <p class="text-sm text-blue-100">Search Results</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Reusable Search Bar Component -->
    @include('components.search-bar', [
        'dzongkhags' => \App\Models\Dzongkhag::all(),
        'sticky' => true,
        'check_in' => $validated['check_in'] ?? null,
        'check_out' => $validated['check_out'] ?? null,
        'adults' => $validated['adults'] ?? 1,
        'children' => $validated['children'] ?? 0,
        'rooms' => $validated['rooms'] ?? 1,
        'dzongkhag_id' => $validated['dzongkhag_id'] ?? null
    ])

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-4 mb-6 border-l-4 border-blue-600">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-gray-600">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                        <span class="font-semibold">{{ $dzongkhagName ?? 'All Dzongkhags' }}</span>
                    </p>
                    <p class="text-gray-600 text-sm mt-1">
                        <i class="fas fa-calendar text-blue-600 mr-2"></i>
                        {{ \Carbon\Carbon::parse($validated['check_in'])->format('D, M d') }} - {{ \Carbon\Carbon::parse($validated['check_out'])->format('D, M d') }}
                        ({{ $nights ?? \Carbon\Carbon::parse($validated['check_out'])->diffInDays($validated['check_in']) }} nights)
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        <span class="font-semibold">{{ $validated['adults'] }} adult(s), {{ $validated['children'] ?? 0 }} child(ren)</span>
                    </p>
                    <p class="text-gray-600 text-sm mt-1">
                        <i class="fas fa-door-open text-blue-600 mr-2"></i>
                        <span class="font-semibold">{{ $validated['rooms'] }} room(s)</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Sidebar - Filters -->
            <aside class="lg:col-span-1">
                <div class="sticky top-4">
                    <!-- Filter Header -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-bold text-gray-900">Filters</h2>
                            <button id="clearAllFiltersBtn" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                <i class="fas fa-times mr-1"></i> Clear All
                            </button>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-money-bill text-blue-600 mr-2"></i> Your Budget
                            </h3>
                            <div id="priceSlider" class="mb-3"></div>
                            <div class="flex gap-2">
                                <input type="number" id="minPrice" placeholder="Min" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" value="0">
                                <span class="flex items-center text-gray-600">to</span>
                                <input type="number" id="maxPrice" placeholder="Max" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" value="10000">
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Nu. <span id="priceDisplay">0 - 10,000</span></p>
                        </div>

                        <!-- Rating Filter -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-2"></i> Review Score
                            </h3>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                                <input type="radio" name="rating" class="filter-radio" value="9" data-filter="rating"> 
                                <span class="text-sm text-gray-700">Excellent (9+)</span>
                            </label>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                                <input type="radio" name="rating" class="filter-radio" value="8" data-filter="rating"> 
                                <span class="text-sm text-gray-700">Very Good (8+)</span>
                            </label>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                                <input type="radio" name="rating" class="filter-radio" value="7" data-filter="rating"> 
                                <span class="text-sm text-gray-700">Good (7+)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="rating" class="filter-radio" value="0" data-filter="rating" checked> 
                                <span class="text-sm text-gray-700">All Ratings</span>
                            </label>
                        </div>

                        <!-- Popular Amenities -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i> Popular Features
                            </h3>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                                <input type="checkbox" class="filter-checkbox" value="breakfast" data-filter="amenity"> 
                                <span class="text-sm text-gray-700">Breakfast included</span>
                            </label>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                                <input type="checkbox" class="filter-checkbox" value="wifi" data-filter="amenity"> 
                                <span class="text-sm text-gray-700">Free WiFi</span>
                            </label>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                                <input type="checkbox" class="filter-checkbox" value="parking" data-filter="amenity"> 
                                <span class="text-sm text-gray-700">Free Parking</span>
                            </label>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                                <input type="checkbox" class="filter-checkbox" value="spa" data-filter="amenity"> 
                                <span class="text-sm text-gray-700">Spa</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="filter-checkbox" value="free_cancellation" data-filter="amenity"> 
                                <span class="text-sm text-gray-700">Free cancellation</span>
                            </label>
                        </div>

                        <!-- Property Type -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-building text-blue-600 mr-2"></i> Property Type
                            </h3>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                                <input type="checkbox" class="filter-checkbox" value="hotel" data-filter="property_type"> 
                                <span class="text-sm text-gray-700">Hotel</span>
                            </label>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                                <input type="checkbox" class="filter-checkbox" value="resort" data-filter="property_type"> 
                                <span class="text-sm text-gray-700">Resort</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="filter-checkbox" value="apartment" data-filter="property_type"> 
                                <span class="text-sm text-gray-700">Apartment</span>
                            </label>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Results Section -->
            <main class="lg:col-span-3">
                <!-- Results Header with Sort -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6 sticky top-24 z-10">
                    <div class="flex justify-between items-center flex-wrap gap-4">
                        <div>
                            <p class="text-gray-600">
                                Showing <span id="resultsCount" class="font-bold text-gray-900">{{ count($availableHotels ?? []) }}</span> properties
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-700 mr-2">Sort by:</label>
                            <select id="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                <option value="rating">Highest Rated</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="name">Name: A to Z</option>
                            </select>
                        </div>
                    </div>

                    <!-- Active Filters Display (Chips) -->
                    <div id="activeFiltersContainer" class="mt-4 flex flex-wrap gap-2 hidden">
                        <span class="text-xs font-semibold text-gray-600">Active filters:</span>
                        <div id="filterChips" class="flex flex-wrap gap-2"></div>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center py-12 hidden">
                    <div class="inline-block">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-blue-600"></div>
                        <p class="text-gray-600 mt-3">Finding best deals for you...</p>
                    </div>
                </div>

                <!-- Hotels Grid (AJAX Updated) -->
                <div id="hotelListings" class="space-y-4">
                    @if($availableHotels && count($availableHotels) > 0)
                        @foreach($availableHotels as $hotel)
                            @include('partials.hotel-card-ajax', ['hotel' => $hotel, 'nights' => $nights ?? 1])
                        @endforeach
                    @else
                        <div class="text-center py-12 bg-white rounded-lg">
                            <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">No hotels found. Try adjusting your filters or dates.</p>
                        </div>
                    @endif
                </div>

                <!-- Pagination (if applicable) -->
                <div id="paginationContainer" class="mt-6"></div>
            </main>
        </div>
    </div>

    <!-- Load noUiSlider JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>

    @php
    $filterConfigJson = json_encode([
        'apiEndpoint' => route('api.hotels.filter'),
        'checkIn' => $validated['check_in'],
        'checkOut' => $validated['check_out'],
        'adults' => $validated['adults'] ?? 1,
        'children' => $validated['children'] ?? 0,
        'rooms' => $validated['rooms'] ?? 1,
        'dzongkhagId' => $validated['dzongkhag_id'] ?? ''
    ]);
    @endphp

    <input type="hidden" id="hotelFilterConfigData" value="{{ $filterConfigJson }}">

    <script>
        // ==================== Configuration ====================
        const config = JSON.parse(document.getElementById('hotelFilterConfigData').value);

        // ==================== Filter State ====================
        const filterState = {
            priceMin: 0,
            priceMax: 10000,
            ratingMin: 0,
            amenities: [],
            propertyTypes: [],
            sort: 'rating',
            page: 1,
            perPage: 20
        };

        let priceSliderElement = null;

        // ==================== Initialize Price Slider ====================
        function initPriceSlider() {
            priceSliderElement = document.getElementById('priceSlider');
            if (!priceSliderElement) return;

            noUiSlider.create(priceSliderElement, {
                start: [filterState.priceMin, filterState.priceMax],
                connect: true,
                range: {
                    'min': 0,
                    'max': 10000
                },
                step: 100,
                format: {
                    to: (value) => Math.round(value),
                    from: (value) => Math.round(value)
                }
            });

            priceSliderElement.noUiSlider.on('update', (values) => {
                document.getElementById('minPrice').value = values[0];
                document.getElementById('maxPrice').value = values[1];
                document.getElementById('priceDisplay').textContent = values[0] + ' - ' + values[1];
                filterState.priceMin = parseInt(values[0]);
                filterState.priceMax = parseInt(values[1]);
            });
        }

        // ==================== Event Listeners ====================
        function setupEventListeners() {
            // Price input fields
            const minPriceInput = document.getElementById('minPrice');
            const maxPriceInput = document.getElementById('maxPrice');
            
            if (minPriceInput) {
                minPriceInput.addEventListener('change', (e) => {
                    filterState.priceMin = parseInt(e.target.value) || 0;
                    applyFilters();
                });
            }

            if (maxPriceInput) {
                maxPriceInput.addEventListener('change', (e) => {
                    filterState.priceMax = parseInt(e.target.value) || 10000;
                    applyFilters();
                });
            }

            // Checkboxes (Amenities & Property Types)
            document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', (e) => {
                    const filterType = e.target.dataset.filter;
                    const value = e.target.value;

                    if (filterType === 'amenity') {
                        if (e.target.checked) {
                            if (!filterState.amenities.includes(value)) {
                                filterState.amenities.push(value);
                            }
                        } else {
                            filterState.amenities = filterState.amenities.filter(a => a !== value);
                        }
                    } else if (filterType === 'property_type') {
                        if (e.target.checked) {
                            if (!filterState.propertyTypes.includes(value)) {
                                filterState.propertyTypes.push(value);
                            }
                        } else {
                            filterState.propertyTypes = filterState.propertyTypes.filter(p => p !== value);
                        }
                    }

                    applyFilters();
                });
            });

            // Radio buttons (Rating)
            document.querySelectorAll('.filter-radio').forEach(radio => {
                radio.addEventListener('change', (e) => {
                    filterState.ratingMin = parseInt(e.target.value) || 0;
                    applyFilters();
                });
            });

            // Sort dropdown
            const sortBy = document.getElementById('sortBy');
            if (sortBy) {
                sortBy.addEventListener('change', (e) => {
                    filterState.sort = e.target.value;
                    applyFilters();
                });
            }

            // Clear all filters
            const clearBtn = document.getElementById('clearAllFiltersBtn');
            if (clearBtn) {
                clearBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    resetFilters();
                });
            }
        }

        // ==================== Filter Reset ====================
        function resetFilters() {
            filterState.priceMin = 0;
            filterState.priceMax = 10000;
            filterState.ratingMin = 0;
            filterState.amenities = [];
            filterState.propertyTypes = [];
            filterState.sort = 'rating';
            filterState.page = 1;

            // Reset UI elements
            document.querySelectorAll('.filter-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.filter-radio').forEach(r => r.checked = r.value === '0');
            
            const sortBy = document.getElementById('sortBy');
            if (sortBy) sortBy.value = 'rating';
            
            const minPrice = document.getElementById('minPrice');
            const maxPrice = document.getElementById('maxPrice');
            if (minPrice) minPrice.value = 0;
            if (maxPrice) maxPrice.value = 10000;
            
            if (priceSliderElement && priceSliderElement.noUiSlider) {
                priceSliderElement.noUiSlider.set([0, 10000]);
            }

            window.history.replaceState({}, '', window.location.pathname);
            sessionStorage.removeItem('hotelFilters');

            applyFilters();
        }

        // ==================== AJAX Filter Function ====================
        async function applyFilters() {
            showLoadingSpinner();

            const requestData = {
                check_in: document.getElementById('checkIn').value || config.checkIn,
                check_out: document.getElementById('checkOut').value || config.checkOut,
                adults: parseInt(document.querySelector('input[name="adults"]')?.value || config.adults || 1),
                children: parseInt(document.querySelector('input[name="children"]')?.value || config.children || 0),
                rooms: parseInt(document.querySelector('input[name="rooms"]')?.value || config.rooms || 1),
                dzongkhag_id: document.querySelector('select[name="dzongkhag_id"]')?.value || config.dzongkhagId || null,
                price_min: filterState.priceMin || null,
                price_max: filterState.priceMax || null,
                rating_min: filterState.ratingMin || null,
                amenities: filterState.amenities.length > 0 ? filterState.amenities.join(',') : null,
                property_types: filterState.propertyTypes.length > 0 ? filterState.propertyTypes.join(',') : null,
                sort: filterState.sort,
                page: filterState.page
            };

            try {
                const response = await fetch(config.apiEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(requestData)
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('hotelListings').innerHTML = data.html || '<div class="text-center py-12 bg-white rounded-lg"><p class="text-gray-500">No hotels found.</p></div>';
                    document.getElementById('resultsCount').textContent = data.total_results;
                    updateActiveFiltersDisplay(data.active_filters);
                    updateURL(requestData);
                    sessionStorage.setItem('hotelFilters', JSON.stringify(filterState));
                    document.getElementById('hotelListings').scrollIntoView({ behavior: 'smooth' });
                } else {
                    console.error('Server error:', data.error || data.message);
                    document.getElementById('hotelListings').innerHTML = '<div class="text-center py-12 bg-white rounded-lg"><p class="text-red-500">Error loading hotels: ' + (data.error || data.message || 'Unknown error') + '</p></div>';
                }
            } catch (error) {
                console.error('Filter error:', error);
                document.getElementById('hotelListings').innerHTML = '<div class="text-center py-12 bg-white rounded-lg"><p class="text-red-500">An error occurred: ' + error.message + '</p></div>';
            } finally {
                hideLoadingSpinner();
            }
        }

        // ==================== Helper Functions ====================
        function showLoadingSpinner() {
            const spinner = document.getElementById('loadingSpinner');
            if (spinner) spinner.classList.remove('hidden');
        }

        function hideLoadingSpinner() {
            const spinner = document.getElementById('loadingSpinner');
            if (spinner) spinner.classList.add('hidden');
        }

        function updateActiveFiltersDisplay(activeFilters) {
            const container = document.getElementById('activeFiltersContainer');
            const chipsContainer = document.getElementById('filterChips');
            
            if (!container || !chipsContainer) return;

            if (!activeFilters || Object.keys(activeFilters).length === 0) {
                container.classList.add('hidden');
                return;
            }

            container.classList.remove('hidden');
            chipsContainer.innerHTML = '';

            for (const [key, value] of Object.entries(activeFilters)) {
                if (value === true || (Array.isArray(value) && value.length > 0)) {
                    const display = Array.isArray(value) ? value.join(', ') : key;
                    const chip = document.createElement('span');
                    chip.className = 'inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs';
                    chip.innerHTML = `
                        ${display}
                        <button class="ml-1 hover:text-blue-900 font-bold" onclick="removeFilter('${key}', '${Array.isArray(value) ? value[0] : ''}')">×</button>
                    `;
                    chipsContainer.appendChild(chip);
                }
            }
        }

        function removeFilter(filterType, filterValue) {
            if (filterType === 'price_min' || filterType === 'price_max') {
                filterState.priceMin = 0;
                filterState.priceMax = 10000;
                document.getElementById('minPrice').value = 0;
                document.getElementById('maxPrice').value = 10000;
                if (priceSliderElement && priceSliderElement.noUiSlider) {
                    priceSliderElement.noUiSlider.set([0, 10000]);
                }
            } else if (filterType === 'rating_min') {
                filterState.ratingMin = 0;
                document.querySelectorAll('.filter-radio').forEach(r => r.checked = r.value === '0');
            } else if (filterType === 'amenities') {
                filterState.amenities = [];
                document.querySelectorAll('.filter-checkbox[data-filter="amenity"]').forEach(cb => cb.checked = false);
            } else if (filterType === 'property_types') {
                filterState.propertyTypes = [];
                document.querySelectorAll('.filter-checkbox[data-filter="property_type"]').forEach(cb => cb.checked = false);
            }
            applyFilters();
        }

        function updateURL(filters) {
            const params = new URLSearchParams();
            params.set('dzongkhag_id', filters.dzongkhag_id || '');
            params.set('check_in', filters.check_in);
            params.set('check_out', filters.check_out);
            params.set('adults', filters.adults);
            params.set('children', filters.children);
            params.set('rooms', filters.rooms);

            if (filters.price_min > 0) params.set('price_min', filters.price_min);
            if (filters.price_max < 10000) params.set('price_max', filters.price_max);
            if (filters.rating_min > 0) params.set('rating_min', filters.rating_min);

            // Handle amenities (could be array, string, or null)
            let amenitiesVal = filters.amenities;
            if (Array.isArray(amenitiesVal) && amenitiesVal.length > 0) {
                params.set('amenities', amenitiesVal.join(','));
            } else if (typeof amenitiesVal === 'string' && amenitiesVal.length > 0) {
                params.set('amenities', amenitiesVal);
            }

            // Handle property_types (could be array, string, or null)
            let typesVal = filters.property_types;
            if (Array.isArray(typesVal) && typesVal.length > 0) {
                params.set('property_types', typesVal.join(','));
            } else if (typeof typesVal === 'string' && typesVal.length > 0) {
                params.set('property_types', typesVal);
            }

            if (filters.sort !== 'rating') params.set('sort', filters.sort);

            window.history.replaceState({}, '', `?${params.toString()}`);
        }

        function loadFromURL() {
            const params = new URLSearchParams(window.location.search);
            
            if (params.has('price_min')) filterState.priceMin = parseInt(params.get('price_min'));
            if (params.has('price_max')) filterState.priceMax = parseInt(params.get('price_max'));
            if (params.has('rating_min')) filterState.ratingMin = parseInt(params.get('rating_min'));
            if (params.has('amenities')) filterState.amenities = params.get('amenities').split(',').filter(a => a);
            if (params.has('property_types')) filterState.propertyTypes = params.get('property_types').split(',').filter(p => p);
            if (params.has('sort')) filterState.sort = params.get('sort');
        }

        // ==================== Initialize on Page Load ====================
        document.addEventListener('DOMContentLoaded', () => {
            initPriceSlider();
            setupEventListeners();
            loadFromURL();
            
            // Re-check elements based on loaded state
            filterState.amenities.forEach(amenity => {
                const checkbox = document.querySelector(`.filter-checkbox[value="${amenity}"]`);
                if (checkbox) checkbox.checked = true;
            });

            filterState.propertyTypes.forEach(type => {
                const checkbox = document.querySelector(`.filter-checkbox[value="${type}"]`);
                if (checkbox) checkbox.checked = true;
            });

            if (filterState.ratingMin > 0) {
                const radio = document.querySelector(`.filter-radio[value="${filterState.ratingMin}"]`);
                if (radio) radio.checked = true;
            }

            const sortBy = document.getElementById('sortBy');
            if (sortBy && filterState.sort !== 'rating') {
                sortBy.value = filterState.sort;
            }

            if (filterState.priceMin > 0 || filterState.priceMax < 10000) {
                if (priceSliderElement && priceSliderElement.noUiSlider) {
                    priceSliderElement.noUiSlider.set([filterState.priceMin, filterState.priceMax]);
                }
            }

            // Setup favorite buttons
            setupFavoriteButtons();
        });

        function setupFavoriteButtons() {
            document.querySelectorAll('.favorite-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.querySelector('i').classList.toggle('fas');
                    this.querySelector('i').classList.toggle('far');
                    this.querySelector('i').classList.toggle('text-red-500');
                });
            });
        }
    </script>
</body>
</html>
