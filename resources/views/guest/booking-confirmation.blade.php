<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-green-400 via-blue-500 to-purple-600 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Success Card -->
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-2xl p-8">
            <!-- Success Icon -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                    <i class="fas fa-check-circle text-green-600 text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Confirmed!</h1>
                <p class="text-gray-600">Your reservation has been successfully processed</p>
            </div>

            <!-- Booking Details -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Booking Details</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Booking ID</p>
                        <p class="font-bold text-gray-900">{{ $booking->booking_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <p class="font-bold text-green-600">{{ $booking->status }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Hotel</p>
                        <p class="font-semibold text-gray-900">{{ $booking->hotel->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Room Type</p>
                        <p class="font-semibold text-gray-900">{{ $booking->room->room_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Check-in</p>
                        <p class="font-semibold text-gray-900">{{ $booking->check_in_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Check-out</p>
                        <p class="font-semibold text-gray-900">{{ $booking->check_out_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Number of Rooms</p>
                        <p class="font-semibold text-gray-900">{{ $booking->num_rooms }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                        <p class="font-bold text-gray-900">Nu. {{ number_format($booking->total_price, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Guest Information -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Guest Information</h3>
                <div class="space-y-2">
                    <p class="text-gray-700"><span class="font-semibold">Name:</span> {{ $booking->guest_name }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Email:</span> {{ $booking->guest_email }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Phone:</span> {{ $booking->guest_phone }}</p>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Payment Information</h3>
                <p class="text-gray-700 mb-2">
                    <span class="font-semibold">Payment Method:</span> 
                    {{ $booking->payment_method === 'ONLINE' ? 'Pay Now (QR/Account)' : 'Pay at Hotel' }}
                </p>
                <p class="text-gray-700 mb-2">
                    <span class="font-semibold">Payment Status:</span> 
                    <span class="text-yellow-600 font-semibold">{{ $booking->payment_status }}</span>
                </p>
                @if($booking->payment_method === 'ONLINE' && !$booking->payment_screenshot)
                    <p class="text-sm text-orange-700 mt-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Please upload your payment screenshot from <strong>Manage Booking</strong> section using your Booking ID.
                    </p>
                @endif
            </div>

            <!-- Important Note -->
            <div class="bg-blue-50 border border-blue-300 rounded-lg p-4 mb-6">
                <p class="text-blue-800 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Important:</strong> Please save your Booking ID <strong>{{ $booking->booking_id }}</strong> for future reference. You can manage your booking anytime using this ID.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center">
                <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition shadow-md">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
                <a href="{{ route('guest.manage-booking') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg transition shadow-md">
                    <i class="fas fa-tasks mr-2"></i> Manage Booking
                </a>
            </div>

            <!-- Email Confirmation Note -->
            @if(session('email_sent'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-6 text-center">
                    <i class="fas fa-check-circle mr-1"></i>
                    A confirmation email has been successfully sent to <strong>{{ $booking->guest_email }}</strong>
                </div>
            @else
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mt-6 text-center">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <strong>Note:</strong> We were unable to send a confirmation email at this time. Your booking is confirmed, but please save your booking ID: <strong>{{ $booking->booking_id }}</strong>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
