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
<body class="bg-gradient-to-br from-purple-400 via-purple-500 to-pink-500 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Go Back Button -->
        <a href="{{ route('guest.hotel', ['id' => $hotel->id]) }}?check_in={{ $validated['check_in'] }}&check_out={{ $validated['check_out'] }}&adults={{ $validated['adults'] ?? 1 }}&children={{ $validated['children'] ?? 0 }}&rooms={{ $validated['num_rooms'] }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition mb-6 shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i> Go Back
        </a>

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
                <input type="hidden" name="room_id" value="{{ $room->id }}">
                <input type="hidden" name="num_rooms" value="{{ $validated['num_rooms'] }}">

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
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Price summary</h3>
                    <div class="space-y-2">
                        <p class="text-gray-700">
                            <span class="font-semibold">Nightly rate:</span> Nu. {{ number_format($room->price_per_night, 2) }}
                        </p>
                        <p class="text-gray-700" id="nightsDisplay">
                            <span class="font-semibold">Number of nights:</span> <span id="nightsCount">{{ $nights }}</span>
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold">Number of rooms:</span> {{ $validated['num_rooms'] }}
                        </p>
                        <div class="border-t border-blue-200 pt-2 mt-2">
                            <p class="text-xl font-bold text-gray-900">
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

    <script>
        // Calculate total amount based on dates
        const checkInInput = document.getElementById('checkInDate');
        const checkOutInput = document.getElementById('checkOutDate');
        const nightsCountSpan = document.getElementById('nightsCount');
        const totalAmountSpan = document.getElementById('totalAmount');
        const nightlyRate = {{ $room->price_per_night }};
        const numRooms = {{ $validated['num_rooms'] }};

        function calculateTotal() {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            
            if (checkIn && checkOut && checkOut > checkIn) {
                const timeDiff = checkOut - checkIn;
                const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                const total = nightlyRate * nights * numRooms;
                
                nightsCountSpan.textContent = nights;
                totalAmountSpan.textContent = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
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
