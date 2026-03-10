<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Dashboard - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-800 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">{{ $hotel->name }}</h1>
                <p class="text-sm text-blue-200">Hotel ID: {{ $hotel->hotel_id }}</p>
            </div>
            <nav class="mt-6">
                <a href="{{ route('hotel.dashboard') }}" class="block px-6 py-3 bg-blue-600 border-l-4 border-blue-400">
                    <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                </a>
                <a href="{{ route('hotel.manage-rooms') }}" class="block px-6 py-3 hover:bg-blue-700">
                    <i class="fas fa-bed mr-3"></i> Manage Rooms
                </a>
                <a href="{{ route('hotel.bookings') }}" class="block px-6 py-3 hover:bg-blue-700">
                    <i class="fas fa-calendar-check mr-3"></i> Bookings
                </a>
                <a href="{{ route('hotel.settings') }}" class="block px-6 py-3 hover:bg-blue-700">
                    <i class="fas fa-cog mr-3"></i> Settings
                </a>
                <a href="{{ route('hotel.deregistration') }}" class="block px-6 py-3 hover:bg-blue-700 text-red-300">
                    <i class="fas fa-sign-out-alt mr-3"></i> Deregistration
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="block w-full text-left px-6 py-3 hover:bg-blue-700">
                        <i class="fas fa-sign-out-alt mr-3"></i> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="px-8 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Hotel Dashboard</h2>
                    <p class="text-gray-600">Welcome back! Here's what's happening today.</p>
                </div>
            </header>

            <!-- Content -->
            <div class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
                @endif

                @if($hotel->status !== 'approved')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-6">
                    <p class="font-semibold text-yellow-800">Hotel Status: {{ $hotel->status }}</p>
                    @if($hotel->status === 'pending')
                        <p class="text-yellow-700 text-sm">Your hotel registration is pending admin approval.</p>
                    @elseif($hotel->status === 'deregistration_requested')
                        <p class="text-yellow-700 text-sm">Your deregistration request is being reviewed by admin.</p>
                    @endif
                </div>
                @endif

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Total Rooms</p>
                                <p class="text-3xl font-bold text-blue-600">{{ $totalRooms }}</p>
                            </div>
                            <div class="bg-blue-100 p-4 rounded-full">
                                <i class="fas fa-bed text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Available Rooms</p>
                                <p class="text-3xl font-bold text-green-600">{{ $availableRooms }}</p>
                            </div>
                            <div class="bg-green-100 p-4 rounded-full">
                                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Today's Check-ins</p>
                                <p class="text-3xl font-bold text-orange-600">{{ $todayCheckIns }}</p>
                            </div>
                            <div class="bg-orange-100 p-4 rounded-full">
                                <i class="fas fa-sign-in-alt text-orange-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Upcoming Bookings</p>
                                <p class="text-3xl font-bold text-purple-600">{{ $upcomingBookings }}</p>
                            </div>
                            <div class="bg-purple-100 p-4 rounded-full">
                                <i class="fas fa-calendar-alt text-purple-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-calendar-check text-blue-600 mr-2"></i> Recent Bookings
                        </h3>
                        <a href="{{ route('hotel.bookings') }}" class="text-blue-600 hover:text-blue-700">
                            View All →
                        </a>
                    </div>

                    @if($recentBookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Booking ID</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Guest Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Room</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Check-in</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Check-out</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($recentBookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-mono">{{ $booking->booking_id }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $booking->guest_name }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $booking->room->room_type ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $booking->check_in_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $booking->check_out_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($booking->status === 'CONFIRMED') bg-green-100 text-green-800
                                            @elseif($booking->status === 'CHECKED_IN') bg-blue-100 text-blue-800
                                            @elseif($booking->status === 'CANCELLED') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p>No bookings yet</p>
                    </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <a href="{{ route('hotel.manage-rooms') }}" class="block bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-4 rounded-full mr-4">
                                <i class="fas fa-bed text-blue-600 text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Manage Rooms</h4>
                                <p class="text-sm text-gray-600">Add, edit or remove rooms</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('hotel.bookings') }}" class="block bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-4 rounded-full mr-4">
                                <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">View Bookings</h4>
                                <p class="text-sm text-gray-600">Manage all bookings</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('hotel.settings') }}" class="block bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-4 rounded-full mr-4">
                                <i class="fas fa-cog text-purple-600 text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Settings</h4>
                                <p class="text-sm text-gray-600">Update hotel information</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
