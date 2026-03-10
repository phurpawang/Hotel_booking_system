<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - {{ $booking->booking_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Main Content -->
        <div class="w-full">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Booking Details</h2>
                        <p class="text-gray-600 text-sm">{{ $booking->booking_id }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if($booking->status == 'CONFIRMED')
                            <span class="px-4 py-2 rounded-full bg-green-100 text-green-800 font-semibold">
                                Confirmed
                            </span>
                        @elseif($booking->status == 'CHECKED_IN')
                            <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-800 font-semibold">
                                Checked In
                            </span>
                        @elseif($booking->status == 'CHECKED_OUT')
                            <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-800 font-semibold">
                                Checked Out
                            </span>
                        @elseif($booking->status == 'CANCELLED')
                            <span class="px-4 py-2 rounded-full bg-red-100 text-red-800 font-semibold">
                                Cancelled
                            </span>
                        @endif
                        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                        <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                <div class="mb-6">
                    <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.index') }}" 
                       class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Reservations
                    </a>
                </div>

                <!-- Main Content -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Left Column - Details -->
                    <div class="md:col-span-2 space-y-6">
                        <!-- Guest Information -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Guest Information
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="font-semibold text-gray-800">{{ $booking->guest_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-semibold text-gray-800">{{ $booking->guest_phone }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold text-gray-800">{{ $booking->guest_email }}</p>
                        </div>
                    </div>
                        </div>

                        <!-- Booking Information -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar text-blue-600 mr-2"></i>
                        Booking Information
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Room Number</p>
                            <p class="font-semibold text-gray-800">{{ $booking->room->room_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Room Type</p>
                            <p class="font-semibold text-gray-800">{{ $booking->room->room_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Check-in Date</p>
                            <p class="font-semibold text-gray-800">{{ $booking->check_in_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Check-out Date</p>
                            <p class="font-semibold text-gray-800">{{ $booking->check_out_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Number of Nights</p>
                            <p class="font-semibold text-gray-800">{{ $booking->nights }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Number of Guests</p>
                            <p class="font-semibold text-gray-800">{{ $booking->num_guests }}</p>
                        </div>
                        @if($booking->actual_check_in)
                        <div>
                            <p class="text-sm text-gray-500">Actual Check-in</p>
                            <p class="font-semibold text-gray-800">{{ $booking->actual_check_in->format('M d, Y H:i') }}</p>
                        </div>
                        @endif
                        @if($booking->actual_check_out)
                        <div>
                            <p class="text-sm text-gray-500">Actual Check-out</p>
                            <p class="font-semibold text-gray-800">{{ $booking->actual_check_out->format('M d, Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                        </div>

                        <!-- Special Requests -->
                        @if($booking->special_requests)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-comment text-blue-600 mr-2"></i>
                        Special Requests
                    </h3>
                            <p class="text-gray-700">{{ $booking->special_requests }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Right Column - Summary & Actions -->
                    <div class="space-y-6">
                        <!-- Payment Summary -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Payment Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Room Rate</span>
                            <span class="font-semibold">Nu. {{ number_format($booking->room->price_per_night ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nights</span>
                            <span class="font-semibold">{{ $booking->nights }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rooms</span>
                            <span class="font-semibold">{{ $booking->num_rooms }}</span>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between text-lg">
                                <span class="font-bold text-gray-800">Total Amount</span>
                                <span class="font-bold text-blue-600">Nu. {{ number_format($booking->total_price, 2) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Method</span>
                            <span class="font-semibold">{{ $booking->payment_method }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Status</span>
                            @if($booking->payment_status == 'PAID')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-sm font-semibold">Paid</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-semibold">Pending</span>
                            @endif
                        </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($booking->status == 'CONFIRMED' && in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
                            <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.edit', $booking->id) }}" 
                               class="block w-full bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded-lg transition">
                                <i class="fas fa-edit mr-2"></i>Edit Booking
                            </a>
                        @endif

                        @if($booking->status == 'CONFIRMED')
                            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.reservations.checkin', $booking->id) }}">
                                @csrf
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Check In
                                </button>
                            </form>
                        @endif

                        @if($booking->status == 'CHECKED_IN')
                            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.reservations.checkout', $booking->id) }}">
                                @csrf
                                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Check Out
                                </button>
                            </form>
                        @endif

                        @if(in_array($booking->status, ['CONFIRMED', 'CHECKED_IN']))
                            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.reservations.cancel', $booking->id) }}">
                                @csrf
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition"
                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    <i class="fas fa-times-circle mr-2"></i>Cancel Booking
                                </button>
                            </form>
                        @endif

                        @if(strtoupper(Auth::user()->role) == 'OWNER' && $booking->status == 'CANCELLED')
                            <form method="POST" action="{{ route('owner.reservations.destroy', $booking->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition"
                                        onclick="return confirm('Are you sure you want to permanently delete this booking?')">
                                    <i class="fas fa-trash mr-2"></i>Delete Booking
                                </button>
                            </form>
                        @endif
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div class="bg-gray-50 rounded-lg p-4 text-sm border border-gray-200">
                    <div class="space-y-2">
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <span class="text-gray-800">{{ $booking->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        @if($booking->creator)
                        <div>
                            <span class="text-gray-500">Created By:</span>
                            <span class="text-gray-800">{{ $booking->creator->name }}</span>
                        </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
