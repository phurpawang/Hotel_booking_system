<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Details - Reception Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'guests'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Guest Details</h2>
                        <p class="text-gray-600 text-sm">View guest information and booking history</p>
                    </div>
                    <a href="{{ route('reception.guests.index') }}" class="text-purple-600 hover:text-purple-900">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Guests
                    </a>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                <!-- Guest Profile Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                    <div class="flex items-center space-x-6 mb-6">
                        <div class="w-20 h-20 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-3xl">
                            {{ substr($guest->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $guest->name }}</h3>
                            <p class="text-gray-600">{{ $guest->email }}</p>
                        </div>
                        <a href="{{ route('reception.guests.edit', $guest->id) }}" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                            <i class="fas fa-edit mr-2"></i>Edit Guest
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Phone Number</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $guest->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Email Address</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $guest->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Member Since</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $guest->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm opacity-90">Total Bookings</span>
                            <i class="fas fa-calendar-check text-2xl opacity-90"></i>
                        </div>
                        <div class="text-3xl font-bold">{{ $stats['total_bookings'] }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm opacity-90">Completed Stays</span>
                            <i class="fas fa-check-circle text-2xl opacity-90"></i>
                        </div>
                        <div class="text-3xl font-bold">{{ $stats['completed'] }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm opacity-90">Upcoming Bookings</span>
                            <i class="fas fa-clock text-2xl opacity-90"></i>
                        </div>
                        <div class="text-3xl font-bold">{{ $stats['upcoming'] }}</div>
                    </div>
                </div>

                <!-- Booking History -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">Booking History</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-600">
                                        #{{ $booking->booking_id ?? $booking->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $booking->room->room_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $booking->check_in_date?->format('M d, Y') ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $booking->check_out_date?->format('M d, Y') ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(strtoupper($booking->status) == 'CONFIRMED')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Confirmed
                                            </span>
                                        @elseif(strtoupper($booking->status) == 'CHECKED_IN')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Checked In
                                            </span>
                                        @elseif(strtoupper($booking->status) == 'CHECKED_OUT')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Checked Out
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        Nu. {{ number_format($booking->total_price, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                        <p>No booking history found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($bookings->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $bookings->links() }}
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
