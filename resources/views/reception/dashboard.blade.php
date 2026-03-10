<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception Dashboard - {{ $hotel->name ?? 'BHBS' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'dashboard'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Reception Dashboard</h2>
                        <p class="text-gray-600 text-sm">Welcome back, {{ Auth::user()->name }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->format('l, F d, Y') }}</span>
                        <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Colorful Statistics Cards (No Revenue for Reception) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Total Bookings -->
                    <div class="rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm opacity-90">Total Bookings</div>
                            <i class="fas fa-calendar-check text-3xl opacity-90"></i>
                        </div>
                        <div class="text-4xl font-bold mb-2">{{ $stats['total'] ?? 0 }}</div>
                        <div class="text-xs opacity-90">
                            <i class="fas fa-chart-line"></i> All time
                        </div>
                    </div>

                    <!-- Today Check-ins -->
                    <div class="rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm opacity-90">Today Check-ins</div>
                            <i class="fas fa-door-open text-3xl opacity-90"></i>
                        </div>
                        <div class="text-4xl font-bold mb-2">{{ $stats['today_checkins'] ?? 0 }}</div>
                        <div class="text-xs opacity-90">
                            <i class="fas fa-arrow-down"></i> Arriving
                        </div>
                    </div>

                    <!-- Today Check-outs -->
                    <div class="rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm opacity-90">Today Check-outs</div>
                            <i class="fas fa-door-closed text-3xl opacity-90"></i>
                        </div>
                        <div class="text-4xl font-bold mb-2">{{ $stats['today_checkouts'] ?? 0 }}</div>
                        <div class="text-xs opacity-90">
                            <i class="fas fa-arrow-up"></i> Departing
                        </div>
                    </div>

                    <!-- Pending Bookings -->
                    <div class="rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm opacity-90">Pending Bookings</div>
                            <i class="fas fa-clock text-3xl opacity-90"></i>
                        </div>
                        <div class="text-4xl font-bold mb-2">{{ $stats['pending'] ?? 0 }}</div>
                        <div class="text-xs opacity-90">
                            <i class="fas fa-hourglass-half"></i> Awaiting payment
                        </div>
                    </div>
                </div>

                <!-- Additional Status Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Confirmed -->
                    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <i class="fas fa-check-circle text-5xl mb-3 opacity-90"></i>
                        <div class="text-4xl font-bold mb-2">{{ $stats['confirmed'] ?? 0 }}</div>
                        <div class="text-sm opacity-90">Confirmed</div>
                    </div>

                    <!-- Checked In -->
                    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-user-check text-5xl mb-3 opacity-90"></i>
                        <div class="text-4xl font-bold mb-2">{{ $stats['checked_in'] ?? 0 }}</div>
                        <div class="text-sm opacity-90">Checked In</div>
                    </div>

                    <!-- Checked Out -->
                    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%);">
                        <i class="fas fa-sign-out-alt text-5xl mb-3 opacity-90"></i>
                        <div class="text-4xl font-bold mb-2">{{ $stats['checked_out'] ?? 0 }}</div>
                        <div class="text-sm opacity-90">Checked Out</div>
                    </div>

                    <!-- Cancelled -->
                    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
                        <i class="fas fa-times-circle text-5xl mb-3 opacity-90"></i>
                        <div class="text-4xl font-bold mb-2">{{ $stats['cancelled'] ?? 0 }}</div>
                        <div class="text-sm opacity-90">Cancelled</div>
                    </div>
                </div>

                <!-- Today's Schedule -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Check-ins Today -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800">Check-ins Today</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @forelse($todayCheckInsList ?? [] as $booking)
                                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $booking->guest_name }}</p>
                                        <p class="text-sm text-gray-600">Room {{ $booking->room->room_number ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('h:i A') }}</p>
                                    </div>
                                    @if($booking->status != 'checked_in')
                                    <a href="{{ route('reception.reservations.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                        View Booking
                                    </a>
                                    @else
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        Checked In
                                    </span>
                                    @endif
                                </div>
                                @empty
                                <p class="text-center text-gray-500 py-8">No check-ins scheduled for today</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Check-outs Today -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800">Check-outs Today</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @forelse($todayCheckOutsList ?? [] as $booking)
                                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $booking->guest_name }}</p>
                                        <p class="text-sm text-gray-600">Room {{ $booking->room->room_number ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('h:i A') }}</p>
                                    </div>
                                    @if($booking->status != 'checked_out')
                                    <a href="{{ route('reception.reservations.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                        View Booking
                                    </a>
                                    @else
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        Checked Out
                                    </span>
                                    @endif
                                </div>
                                @empty
                                <p class="text-center text-gray-500 py-8">No check-outs scheduled for today</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">Recent Bookings</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentBookings ?? [] as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $booking->guest_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $booking->room->room_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status == 'checked_in') bg-blue-100 text-blue-800
                                            @elseif($booking->status == 'checked_out') bg-gray-100 text-gray-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No recent bookings
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
