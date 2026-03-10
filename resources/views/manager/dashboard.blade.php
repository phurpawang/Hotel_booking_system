<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - {{ $hotel->name ?? 'BHBS' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-green-900 text-white flex-shrink-0">
            <div class="p-6 border-b border-green-800">
                <a href="{{ route('manager.dashboard') }}" class="block">
                    <h1 class="text-2xl font-bold hover:text-green-300 transition cursor-pointer">
                        <i class="fas fa-building mr-2"></i>BHBS
                    </h1>
                </a>
                <p class="text-sm text-green-200 mt-1">{{ $hotel->name ?? 'Hotel Name' }}</p>
                <span class="text-xs bg-green-700 px-2 py-1 rounded mt-2 inline-block">Manager</span>
            </div>
            
            <nav class="p-4">
                <a href="{{ route('manager.reservations.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="{{ route('manager.rooms.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-bed mr-3"></i>
                    <span>Rooms</span>
                </a>
                <a href="{{ route('manager.room-status.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-door-open mr-3"></i>
                    <span>Room Status</span>
                </a>
                <a href="{{ route('manager.rates') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-dollar-sign mr-3"></i>
                    <span>Rates</span>
                </a>
                <a href="{{ route('manager.reports') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
                <a href="{{ route('manager.property.edit') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Property Settings</span>
                </a>
                <a href="{{ route('manager.messages.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-envelope mr-3"></i>
                    <span>Messages</span>
                </a>
                <a href="{{ route('manager.deregistration.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Deregistration</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-user-circle mr-3"></i>
                    <span>Profile</span>
                </a>
                
                <div class="border-t border-green-800 mt-4 pt-4">
                    <form method="POST" action="{{ route('hotel.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center px-4 py-3 hover:bg-red-600 rounded-lg w-full transition">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Manager Dashboard</h2>
                        <p class="text-gray-600 text-sm">Welcome back, {{ Auth::user()->name }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->format('l, F d, Y') }}</span>
                        <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold">
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

                <!-- Colorful Statistics Cards (No Revenue for Manager) -->
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

                <!-- Commission Information (Readonly) -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-8 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-info-circle text-2xl"></i>
                            <div>
                                <h3 class="text-lg font-bold">This Month's Commission Info (View Only)</h3>
                                <p class="text-xs opacity-90">{{ \Carbon\Carbon::now()->format('F Y') }} - Managers can view but not modify</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Total Bookings -->
                        <div class="bg-white bg-opacity-10 backdrop-blur rounded-lg p-4">
                            <div class="text-xs opacity-80 mb-1">Total Bookings</div>
                            <div class="text-2xl font-bold">{{ $commissionInfo['total_bookings'] ?? 0 }}</div>
                            <div class="text-xs opacity-70 mt-1">
                                <i class="fas fa-credit-card mr-1"></i>Online: {{ $commissionInfo['pay_online_count'] ?? 0 }} 
                                <i class="fas fa-money-bill ml-2 mr-1"></i>At Hotel: {{ $commissionInfo['pay_at_hotel_count'] ?? 0 }}
                            </div>
                        </div>

                        <!-- Guest Payments -->
                        <div class="bg-white bg-opacity-10 backdrop-blur rounded-lg p-4">
                            <div class="text-xs opacity-80 mb-1">Total Guest Payments</div>
                            <div class="text-2xl font-bold">Nu. {{ number_format($commissionInfo['total_guest_payments'] ?? 0, 2) }}</div>
                            <div class="text-xs opacity-70 mt-1"><i class="fas fa-users"></i> Final price paid</div>
                        </div>

                        <!-- Commission Deducted -->
                        <div class="bg-white bg-opacity-10 backdrop-blur rounded-lg p-4">
                            <div class="text-xs opacity-80 mb-1">Platform Commission (10%)</div>
                            <div class="text-2xl font-bold">Nu. {{ number_format($commissionInfo['total_commission'] ?? 0, 2) }}</div>
                            <div class="text-xs opacity-70 mt-1"><i class="fas fa-percentage"></i> Deducted by platform</div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-yellow-500 bg-opacity-20 rounded-lg">
                        <p class="text-xs">
                            <i class="fas fa-lock mr-1"></i> 
                            <strong>Note:</strong> Commission details are managed by the Owner. Managers can view this information for reference only.
                        </p>
                    </div>
                </div>

                <!-- Recent Bookings & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Bookings -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Bookings</h3>
                        <div class="space-y-4">
                            @forelse($recentBookings ?? [] as $booking)
                            <div class="border-l-4 border-green-600 pl-4 py-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $booking->guest_name }}</p>
                                        <p class="text-sm text-gray-600">Room: {{ $booking->room->room_number ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d') }}</p>
                                    </div>
                                    <div class="text-right">
                                        @if($booking->commission)
                                            @if($booking->commission->payment_method == 'pay_online')
                                                <span class="inline-block px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                    <i class="fas fa-credit-card"></i> Online
                                                </span>
                                            @else
                                                <span class="inline-block px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                                    <i class="fas fa-money-bill"></i> At Hotel
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <span class="inline-block px-2 py-1 text-xs rounded-full mt-1
                                    @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No recent bookings</p>
                            @endforelse
                        </div>
                        <a href="{{ route('manager.reservations.index') }}" class="block text-center text-green-600 hover:text-green-800 font-semibold mt-4">
                            View All Bookings →
                        </a>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('manager.reservations.create') }}" class="block bg-green-50 hover:bg-green-100 p-4 rounded-lg transition">
                                <i class="fas fa-calendar-plus text-green-600 mr-2"></i>
                                <span class="font-semibold text-gray-800">New Booking</span>
                            </a>
                            <a href="{{ route('manager.rooms.index') }}" class="block bg-blue-50 hover:bg-blue-100 p-4 rounded-lg transition">
                                <i class="fas fa-bed text-blue-600 mr-2"></i>
                                <span class="font-semibold text-gray-800">Manage Rooms</span>
                            </a>
                            <a href="{{ route('manager.rates') }}" class="block bg-purple-50 hover:bg-purple-100 p-4 rounded-lg transition">
                                <i class="fas fa-tags text-purple-600 mr-2"></i>
                                <span class="font-semibold text-gray-800">Update Rates</span>
                            </a>
                            <a href="{{ route('manager.reports') }}" class="block bg-orange-50 hover:bg-orange-100 p-4 rounded-lg transition">
                                <i class="fas fa-file-alt text-orange-600 mr-2"></i>
                                <span class="font-semibold text-gray-800">View Reports</span>
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // No charts needed
    </script>
</body>
</html>
