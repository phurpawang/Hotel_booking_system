<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Booking Details</h1>
                <div class="flex gap-4">
                    <a href="{{ route('guest.manage-booking') }}" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                        <i class="fas fa-search mr-2"></i> Search Another Booking
                    </a>
                    <a href="{{ route('home') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                        <i class="fas fa-home mr-2"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif

        <div class="max-w-4xl mx-auto">
            <!-- Booking Status Banner -->
            <div class="mb-6 bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">
                            Booking ID: <span class="text-blue-600">{{ $booking->booking_id }}</span>
                        </h2>
                        <p class="text-gray-600">Booked on {{ $booking->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-6 py-3 rounded-full text-lg font-bold
                            @if($booking->status === 'CONFIRMED') bg-green-100 text-green-800
                            @elseif($booking->status === 'CHECKED_IN') bg-blue-100 text-blue-800
                            @elseif($booking->status === 'CHECKED_OUT') bg-gray-100 text-gray-800
                            @elseif($booking->status === 'CANCELLED') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $booking->status }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Booking Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Hotel Information -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-hotel text-blue-600 mr-2"></i> Hotel Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-800">{{ $booking->hotel->name }}</h4>
                                <p class="text-gray-600 mt-1">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $booking->hotel->address }}
                                </p>
                                @if($booking->hotel->star_rating)
                                <div class="mt-2">
                                    @for($i = 0; $i < $booking->hotel->star_rating; $i++)
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @endfor
                                </div>
                                @endif
                            </div>
                            <div class="pt-3 border-t">
                                <p class="text-gray-700">
                                    <i class="fas fa-phone text-blue-600 mr-2"></i>
                                    <strong>Phone:</strong> {{ $booking->hotel->phone }}
                                </p>
                                <p class="text-gray-700 mt-1">
                                    <i class="fas fa-envelope text-blue-600 mr-2"></i>
                                    <strong>Email:</strong> {{ $booking->hotel->email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Room Information -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-bed text-blue-600 mr-2"></i> Room Information
                        </h3>
                        <div class="space-y-2">
                            <p class="text-lg"><strong>Room Type:</strong> {{ $booking->room->room_type }}</p>
                            <p><strong>Number of Rooms:</strong> {{ $booking->num_rooms }}</p>
                            <p><strong>Capacity:</strong> {{ $booking->room->capacity }} guests per room</p>
                            <p><strong>Total Guests:</strong> {{ $booking->num_guests }}</p>
                        </div>
                    </div>

                    <!-- Guest Information -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-user text-blue-600 mr-2"></i> Guest Information
                        </h3>
                        <div class="space-y-2">
                            <p><strong>Name:</strong> {{ $booking->guest_name }}</p>
                            <p><strong>Email:</strong> {{ $booking->guest_email }}</p>
                            <p><strong>Phone:</strong> {{ $booking->guest_phone }}</p>
                        </div>
                        @if($booking->special_requests)
                        <div class="mt-4 pt-4 border-t">
                            <p class="font-semibold text-gray-700 mb-2">Special Requests:</p>
                            <p class="text-gray-600">{{ $booking->special_requests }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column - Summary & Actions -->
                <div class="space-y-6">
                    <!-- Booking Summary -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Booking Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-in:</span>
                                <span class="font-semibold">{{ $booking->check_in_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-out:</span>
                                <span class="font-semibold">{{ $booking->check_out_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nights:</span>
                                <span class="font-semibold">{{ $booking->check_in_date->diffInDays($booking->check_out_date) }}</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t">
                                <span class="text-gray-600">Total Amount:</span>
                                <span class="text-2xl font-bold text-blue-600">Nu. {{ number_format($booking->total_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status:</span>
                                <span class="font-semibold px-3 py-1 rounded-full text-sm
                                    @if($booking->payment_status === 'PAID') bg-green-100 text-green-800
                                    @elseif($booking->payment_status === 'PENDING') bg-yellow-100 text-yellow-800
                                    @elseif($booking->payment_status === 'REFUNDED') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $booking->payment_status }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-semibold">{{ $booking->payment_method === 'ONLINE' ? 'Online Payment' : 'Pay at Hotel' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($booking->status !== 'CANCELLED' && $booking->status !== 'CHECKED_OUT')
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Actions</h3>
                        <button onclick="showCancelModal()" class="w-full bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition font-semibold">
                            <i class="fas fa-times-circle mr-2"></i> Cancel Booking
                        </button>
                        <p class="text-xs text-gray-600 mt-3 text-center">
                            Cancellation charges may apply as per policy
                        </p>
                    </div>
                    @endif

                    @if($booking->status === 'CANCELLED')
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-red-800 mb-2">
                            <i class="fas fa-ban mr-2"></i> Booking Cancelled
                        </h3>
                        <p class="text-sm text-red-700 mb-3">
                            Cancelled on {{ $booking->cancelled_at->format('M d, Y \a\t g:i A') }}
                        </p>
                        @if($booking->cancellation_reason)
                        <p class="text-sm text-red-600">
                            <strong>Reason:</strong> {{ $booking->cancellation_reason }}
                        </p>
                        @endif
                        @if($booking->refund_amount > 0)
                        <p class="text-sm text-red-600 mt-2">
                            <strong>Refund Amount:</strong> Nu. {{ number_format($booking->refund_amount, 2) }}
                        </p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Cancel Booking</h3>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <p class="text-yellow-800 text-sm">
                    <strong>Cancellation Policy:</strong><br>
                    - More than 7 days before check-in: Full refund<br>
                    - 3-7 days before: 50% refund<br>
                    - Less than 3 days: No refund
                </p>
            </div>
            <form method="POST" action="{{ route('guest.booking.cancel', $booking->booking_id) }}">
                @csrf
                <input type="hidden" name="identifier" value="{{ $booking->guest_email }}">
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Cancellation Reason (Optional)</label>
                    <textarea name="cancellation_reason" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" placeholder="Please let us know why you're cancelling..."></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition font-semibold">
                        Confirm Cancellation
                    </button>
                    <button type="button" onclick="hideCancelModal()" class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 transition font-semibold">
                        Keep Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function hideCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    </script>
</body>
</html>
