<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Booking - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .step-active {
            background: linear-gradient(to right, #3b82f6, #10b981);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Complete Your Booking</h1>
                    <p class="text-sm text-blue-100">Secure Reservation Form</p>
                </div>
                <a href="{{ route('guest.hotel', ['id' => $hotel->id]) }}?check_in={{ $validated['check_in'] }}&check_out={{ $validated['check_out'] }}&adults={{ $validated['adults'] ?? 1 }}&children={{ $validated['children'] ?? 0 }}&rooms={{ $validated['num_rooms'] }}" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded-lg transition">
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
        <!-- Booking Form Card -->
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-2xl p-8">
            <!-- Step Indicator -->
            <div class="grid grid-cols-4 gap-4 mb-8">
                <div class="text-center">
                    <div class="bg-blue-100 rounded-lg p-4 border-2 border-blue-500">
                        <span class="font-semibold text-blue-700"><span class="mr-2">1</span>Guest Details</span>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-blue-500 to-green-500 mt-2"></div>
                </div>
                <div class="text-center">
                    <div class="bg-gray-100 rounded-lg p-4">
                        <span class="text-gray-600"><span class="mr-2">2</span>Dates</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-gray-100 rounded-lg p-4">
                        <span class="text-gray-600"><span class="mr-2">3</span>Payment</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-gray-100 rounded-lg p-4">
                        <span class="text-gray-600"><span class="mr-2">4</span>Confirmation</span>
                    </div>
                </div>
            </div>

            <!-- Form Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Booking</h1>
            <p class="text-gray-600 mb-6">Enter your guest details and stay dates. Payment is offline via QR/account.</p>

            <!-- Booking Form -->
            <form action="{{ route('guest.booking.confirm') }}" method="POST" enctype="multipart/form-data" id="bookingForm" autocomplete="off">
                @csrf
                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                <input type="hidden" name="room_type" value="{{ $validated['room_type'] }}">
                <input type="hidden" name="price" value="{{ $validated['price'] }}">
                <input type="hidden" name="num_rooms" value="{{ $validated['num_rooms'] }}">
                <input type="hidden" name="check_in" value="{{ $validated['check_in'] }}">
                <input type="hidden" name="check_out" value="{{ $validated['check_out'] }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Full Name</label>
                        <input type="text" name="guest_name" required autocomplete="off" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tashi Dorji" value="{{ old('guest_name') }}">
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Email Address</label>
                        <input type="email" name="guest_email" required autocomplete="off" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="you@example.com" value="{{ old('guest_email') }}">
                    </div>

                    <!-- Mobile Number -->
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Mobile Number</label>
                        <input type="tel" name="guest_mobile" required autocomplete="off" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="+975 17xxxxxxx" value="{{ old('guest_mobile') }}">
                    </div>

                    <!-- Check-in Date -->
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Check-in Date</label>
                        <input type="date" name="check_in" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $validated['check_in'] }}" id="checkInDate">
                    </div>

                    <!-- Check-out Date -->
                    <div class="md:col-start-2">
                        <label class="block text-gray-800 font-semibold mb-2">Check-out Date</label>
                        <input type="date" name="check_out" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $validated['check_out'] }}" id="checkOutDate">
                    </div>
                </div>

                <!-- Payment Option -->
                <div class="mb-6">
                    <label class="block text-gray-800 font-semibold mb-3">Payment option</label>
                    <div class="flex gap-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="payment_method" value="pay_now" class="w-5 h-5 text-blue-600" checked>
                            <span class="ml-3 text-gray-700">Pay now (upload proof)</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="payment_method" value="pay_at_hotel" class="w-5 h-5 text-blue-600">
                            <span class="ml-3 text-gray-700">Pay at hotel</span>
                        </label>
                    </div>
                </div>

                <!-- Upload Payment Screenshot -->
                <div class="mb-6">
                    <label class="block text-gray-800 font-semibold mb-2">Upload Payment Screenshot (optional)</label>
                    <input type="file" name="payment_screenshot" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-sm text-gray-600 mt-2">If you pay now, upload the screenshot so we can review faster.</p>
                </div>

                <!-- Price Summary -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Price summary</h3>
                        @if($pricingInfo['promotion'])
                        <span class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-2 rounded-full text-sm font-bold">
                            {{ $pricingInfo['discount_display'] }}
                        </span>
                        @endif
                    </div>
                    
                    <div class="space-y-2">
                        <p class="text-gray-700">
                            <span class="font-semibold">Room Type:</span> {{ $validated['room_type'] }}
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold">Nightly rate:</span> Nu. {{ number_format($validated['price'], 2) }}
                        </p>
                        <p class="text-gray-700" id="nightsDisplay">
                            <span class="font-semibold">Number of nights:</span> <span id="nightsCount">{{ $nights }}</span>
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold">Number of rooms:</span> {{ $validated['num_rooms'] }}
                        </p>
                        
                        @if($pricingInfo['promotion'])
                        <div class="bg-white border border-orange-300 rounded p-3 my-3">
                            <p class="text-gray-700 font-semibold text-sm mb-2">
                                <i class="fas fa-tag text-orange-600 mr-2"></i>{{ $pricingInfo['promotion']->title }}
                            </p>
                            <div class="space-y-1 text-sm">
                                <p class="text-gray-600">
                                    <span class="font-semibold">Original price:</span> 
                                    <span class="text-lg font-bold text-gray-900" data-original-price>Nu. {{ number_format($pricingInfo['original_price'], 2) }}</span>
                                </p>
                                <p class="text-red-600">
                                    <span class="font-semibold">Discount:</span> 
                                    <span class="text-lg font-bold" data-discount-amount>-Nu. {{ number_format($pricingInfo['discount_applied'], 2) }}</span>
                                </p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="border-t border-blue-200 pt-2 mt-2">
                            <p class="text-xl font-bold {{ $pricingInfo['promotion'] ? 'text-green-600' : 'text-gray-900' }}">
                                Total: Nu. <span id="totalAmount">{{ number_format($totalPrice, 2) }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Warning Message -->
                <div class="bg-orange-50 border border-orange-300 rounded-lg p-4 mb-6">
                    <p class="text-orange-800 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        Choose pay now (QR/account <strong>215718279</strong>) or pay at hotel. Upload your screenshot now or later from Manage Booking.
                    </p>
                </div>

                <!-- Confirm Booking Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition shadow-lg">
                        Confirm Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data attributes for JavaScript -->
    <div id="scriptData" 
        data-nightly-rate="{{ $validated['price'] }}"
        data-num-rooms="{{ $validated['num_rooms'] }}"
        data-has-promotion="{{ isset($pricingInfo['promotion']) && $pricingInfo['promotion'] ? 'true' : 'false' }}"
        data-promo-type="{{ isset($pricingInfo['promotion']) && $pricingInfo['promotion'] ? $pricingInfo['promotion']->discount_type : '' }}"
        data-promo-value="{{ isset($pricingInfo['promotion']) && $pricingInfo['promotion'] ? $pricingInfo['promotion']->discount_value : 0 }}"
        style="display: none;">
    </div>

    <script>
        // Load data from HTML data attributes
        const scriptDataElement = document.getElementById('scriptData');
        const bookingData = {
            nightlyRate: parseFloat(scriptDataElement.dataset.nightlyRate),
            numRooms: parseInt(scriptDataElement.dataset.numRooms),
            promotionData: {
                exists: scriptDataElement.dataset.hasPromotion === 'true',
                type: scriptDataElement.dataset.promoType,
                value: parseFloat(scriptDataElement.dataset.promoValue || 0)
            }
        };

        // Calculate total amount based on dates
        const checkInInput = document.getElementById('checkInDate');
        const checkOutInput = document.getElementById('checkOutDate');
        const nightsCountSpan = document.getElementById('nightsCount');
        const totalAmountSpan = document.getElementById('totalAmount');
        const nightlyRate = bookingData.nightlyRate;
        const numRooms = bookingData.numRooms;

        function calculateDiscount(originalPrice) {
            if (!bookingData.promotionData.exists) {
                return 0;
            }

            const promoType = bookingData.promotionData.type;
            const promoValue = bookingData.promotionData.value;

            if (promoType === 'percentage') {
                return (originalPrice * promoValue) / 100;
            } else if (promoType === 'fixed') {
                // Fixed discount should not exceed original price
                return Math.min(promoValue, originalPrice);
            }

            return 0;
        }

        function calculateTotal() {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            
            if (checkIn && checkOut && checkOut > checkIn) {
                const timeDiff = checkOut - checkIn;
                const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                const originalPrice = nightlyRate * nights * numRooms;
                const discount = calculateDiscount(originalPrice);
                const finalTotal = Math.max(0, originalPrice - discount);
                
                nightsCountSpan.textContent = nights;
                totalAmountSpan.textContent = finalTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                // Update discount display if promotion exists
                if (bookingData.promotionData.exists) {
                    const discountDisplay = document.querySelector('[data-discount-amount]');
                    const originalPriceDisplay = document.querySelector('[data-original-price]');
                    if (discountDisplay) {
                        discountDisplay.textContent = '-Nu. ' + discount.toFixed(2);
                    }
                    if (originalPriceDisplay) {
                        originalPriceDisplay.textContent = 'Nu. ' + originalPrice.toFixed(2);
                    }
                }
            }
        }

        checkInInput.addEventListener('change', calculateTotal);
        checkOutInput.addEventListener('change', calculateTotal);

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        checkInInput.setAttribute('min', today);
        
        // Update check-out minimum when check-in changes
        checkInInput.addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            checkInDate.setDate(checkInDate.getDate() + 1);
            checkOutInput.setAttribute('min', checkInDate.toISOString().split('T')[0]);
        });

        // Initial calculation
        calculateTotal();
    </script>
</body>
</html>
