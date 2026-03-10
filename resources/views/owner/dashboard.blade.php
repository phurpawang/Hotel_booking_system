<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - {{ $hotel->name ?? 'BHBS' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-900 text-white flex-shrink-0">
            <div class="p-4 border-b border-blue-800">
                <a href="{{ route('owner.dashboard') }}" class="block">
                    <h1 class="text-2xl font-bold hover:text-blue-300 transition cursor-pointer">
                        <i class="fas fa-building mr-2"></i>BHBS
                    </h1>
                </a>
                <p class="text-sm text-blue-200 mt-1">{{ $hotel->name ?? 'Hotel Name' }}</p>
            </div>
            
            <nav class="p-3">
                <a href="{{ route('owner.dashboard') }}" class="flex items-center px-3 py-2 bg-blue-800 rounded-lg mb-1">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('owner.reservations.index') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="{{ route('owner.rooms.index') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-bed mr-3"></i>
                    <span>Rooms</span>
                </a>
                <a href="{{ route('owner.rates') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-dollar-sign mr-3"></i>
                    <span>Rates & Availability</span>
                </a>
                <a href="{{ route('owner.guests.index') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-users-cog mr-3"></i>
                    <span>Guests</span>
                </a>
                <a href="{{ route('owner.payments.index') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-credit-card mr-3"></i>
                    <span>Payments</span>
                </a>
                <a href="{{ route('owner.revenue.index') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-money-bill-trend-up mr-3"></i>
                    <span>Revenue & Commission</span>
                </a>
                <a href="{{ route('owner.reviews.index') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-star mr-3"></i>
                    <span>Reviews</span>
                </a>
                <a href="{{ route('owner.reports') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
                <a href="{{ route('owner.staff.index') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-user-tie mr-3"></i>
                    <span>Staff Management</span>
                </a>
                <a href="{{ route('owner.amenities.index') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-concierge-bell mr-3"></i>
                    <span>Amenities</span>
                </a>
                <a href="{{ route('owner.promotions.index') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-tags mr-3"></i>
                    <span>Promotions</span>
                </a>
                <a href="{{ route('owner.property.edit') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Property Settings</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-user-circle mr-3"></i>
                    <span>Profile</span>
                </a>
                <a href="{{ route('owner.notifications.index') }}" class="flex items-center px-3 py-2 hover:bg-blue-800 rounded-lg mb-1 transition">
                    <i class="fas fa-bell mr-3"></i>
                    <span>Notifications</span>
                </a>
                
                <div class="border-t border-blue-800 mt-2 pt-2">
                    <a href="{{ route('owner.deregistration.index') }}" class="flex items-center px-3 py-2 hover:bg-red-700 rounded-lg mb-1 transition text-red-200">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        <span>Deregistration</span>
                    </a>
                </div>
                
                <div class="border-t border-blue-800 mt-2 pt-2">
                    <form method="POST" action="{{ route('hotel.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center px-3 py-2 hover:bg-red-600 rounded-lg w-full transition">
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
                        <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview</h2>
                        <p class="text-gray-600 text-sm">Welcome back, {{ Auth::user()->name }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->format('l, F d, Y') }}</span>
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
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

                <!-- Deregistration Request Alert -->
                @if(isset($deregistrationRequest) && $deregistrationRequest)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
                            <div>
                                <p class="font-bold text-lg">Pending Deregistration Request</p>
                                <p class="text-sm">Your hotel deregistration request is under review. Submitted on {{ $deregistrationRequest->created_at->format('F d, Y') }}.</p>
                            </div>
                        </div>
                        <a href="{{ route('owner.deregistration.index') }}" class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded transition">
                            View Details
                        </a>
                    </div>
                </div>
                @endif

                <!-- Colorful Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Bookings -->
                    <div class="rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm opacity-90">Total Bookings</div>
                            <i class="fas fa-calendar-check text-3xl opacity-90"></i>
                        </div>
                        <div class="text-4xl font-bold mb-2">{{ $totalReservations ?? 0 }}</div>
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
                        <div class="text-4xl font-bold mb-2">{{ $todayCheckIns ?? 0 }}</div>
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
                        <div class="text-4xl font-bold mb-2">{{ $todayCheckOuts ?? 0 }}</div>
                        <div class="text-xs opacity-90">
                            <i class="fas fa-arrow-up"></i> Departing
                        </div>
                    </div>

                    <!-- Monthly Revenue -->
                    <div class="rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm opacity-90">Monthly Revenue</div>
                            <i class="fas fa-chart-line text-3xl opacity-90"></i>
                        </div>
                        <div class="text-4xl font-bold mb-2">Nu. {{ number_format($monthlyRevenue ?? 0, 2) }}</div>
                        <div class="text-xs opacity-90">
                            <i class="fas fa-dollar-sign"></i> This month
                        </div>
                    </div>
                </div>

                <!-- Commission & Payout Section -->
                <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 mb-8 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-money-bill-wave text-3xl"></i>
                            <div>
                                <h3 class="text-xl font-bold">This Month's Revenue & Commission</h3>
                                <p class="text-sm opacity-90">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('owner.revenue.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition">
                            <i class="fas fa-chart-bar mr-2"></i>View Full Report
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                        <!-- Total Guest Payments -->
                        <div class="bg-white bg-opacity-10 backdrop-blur rounded-lg p-4">
                            <div class="text-xs opacity-80 mb-1">Total Guest Payments</div>
                            <div class="text-2xl font-bold">Nu. {{ number_format($monthlyGuestPayments ?? 0, 2) }}</div>
                            <div class="text-xs opacity-70 mt-1"><i class="fas fa-users"></i> Final price paid by guests</div>
                        </div>

                        <!-- Platform Commission -->
                        <div class="bg-red-500 bg-opacity-30 backdrop-blur rounded-lg p-4">
                            <div class="text-xs opacity-80 mb-1">Platform Commission (10%)</div>
                            <div class="text-2xl font-bold">Nu. {{ number_format($monthlyCommissionTotal ?? 0, 2) }}</div>
                            <div class="text-xs opacity-70 mt-1"><i class="fas fa-percentage"></i> Platform fee deducted</div>
                        </div>

                        <!-- Your Earning -->
                        <div class="bg-green-500 bg-opacity-30 backdrop-blur rounded-lg p-4">
                            <div class="text-xs opacity-80 mb-1">Your Net Earning</div>
                            <div class="text-2xl font-bold">Nu. {{ number_format($hotelPayout ?? 0, 2) }}</div>
                            <div class="text-xs opacity-70 mt-1"><i class="fas fa-wallet"></i> Amount you receive</div>
                        </div>

                        <!-- Payout Status -->
                        <div class="bg-yellow-500 bg-opacity-30 backdrop-blur rounded-lg p-4">
                            <div class="text-xs opacity-80 mb-1">Payout Status</div>
                            <div class="text-lg font-bold">
                                @if($currentPayout)
                                    @if($currentPayout->payout_status == 'paid')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-500">
                                            <i class="fas fa-check-circle mr-1"></i> Paid
                                        </span>
                                    @elseif($currentPayout->payout_status == 'processing')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-500">
                                            <i class="fas fa-sync fa-spin mr-1"></i> Processing
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-500">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-500">
                                        <i class="fas fa-calendar mr-1"></i> Not Generated Yet
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs opacity-70 mt-1"><i class="fas fa-info-circle"></i> Monthly payout</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Payouts Table -->
                @if(isset($recentPayouts) && $recentPayouts->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-history mr-2"></i>Recent Monthly Payouts</h3>
                        <a href="{{ route('owner.revenue.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest Payments</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Your Payout</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentPayouts as $payout)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::create($payout->year, $payout->month, 1)->format('F Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payout->total_bookings }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Nu. {{ number_format($payout->total_guest_payments, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                        Nu. {{ number_format($payout->total_commission, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold">
                                        Nu. {{ number_format($payout->hotel_payout_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payout->payout_status == 'paid')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Paid
                                            </span>
                                        @elseif($payout->payout_status == 'processing')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-sync mr-1"></i> Processing
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Room Status Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Confirmed -->
                    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <i class="fas fa-check-circle text-5xl mb-3 opacity-90"></i>
                        <div class="text-4xl font-bold mb-2">{{ $totalRooms ?? 0 }}</div>
                        <div class="text-sm opacity-90">Total Rooms</div>
                    </div>

                    <!-- Available Rooms -->
                    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-door-open text-5xl mb-3 opacity-90"></i>
                        <div class="text-4xl font-bold mb-2">{{ $availableRooms ?? 0 }}</div>
                        <div class="text-sm opacity-90">Available Rooms</div>
                    </div>

                    <!-- Occupied Rooms -->
                    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%);">
                        <i class="fas fa-bed text-5xl mb-3 opacity-90"></i>
                        <div class="text-4xl font-bold mb-2">{{ $totalRooms - $availableRooms ?? 0 }}</div>
                        <div class="text-sm opacity-90">Occupied Rooms</div>
                    </div>

                    <!-- Occupancy Rate -->
                    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
                        <i class="fas fa-percent text-5xl mb-3 opacity-90"></i>
                        <div class="text-4xl font-bold mb-2">{{ $totalRooms > 0 ? round((($totalRooms - $availableRooms) / $totalRooms) * 100) : 0 }}%</div>
                        <div class="text-sm opacity-90">Occupancy Rate</div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Booking Trends Chart -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Booking Trends (Last 6 Months)</h3>
                        <div style="height: 300px;">
                            <canvas id="bookingTrendsChart"></canvas>
                        </div>
                    </div>

                    <!-- Revenue Trends Chart -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Revenue Trends (Last 6 Months)</h3>
                        <div style="height: 300px;">
                            <canvas id="revenueTrendsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">Recent Bookings</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentBookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->guest_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $booking->room->room_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->commission)
                                            @if($booking->commission->payment_method == 'pay_online')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    <i class="fas fa-credit-card mr-1"></i> Online
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    <i class="fas fa-money-bill mr-1"></i> At Hotel
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->status == 'CONFIRMED')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Confirmed
                                            </span>
                                        @elseif($booking->status == 'PENDING')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($booking->status == 'CHECKED_IN')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Checked In
                                            </span>
                                        @elseif($booking->status == 'CHECKED_OUT')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Checked Out
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        Nu. {{ number_format($booking->total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->commission)
                                            <div class="text-xs text-gray-600">
                                                Commission: <span class="text-red-600 font-semibold">Nu. {{ number_format($booking->commission->commission_amount, 2) }}</span>
                                            </div>
                                            <div class="text-xs text-gray-600">
                                                Your Earning: <span class="text-green-600 font-semibold">Nu. {{ number_format($booking->commission->base_amount, 2) }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-2"></i>
                                        <p>No recent bookings found</p>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Booking Trends Chart
        const bookingCtx = document.getElementById('bookingTrendsChart').getContext('2d');
        new Chart(bookingCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($bookingTrends['labels']) !!},
                datasets: [{
                    label: 'Number of Bookings',
                    data: {!! json_encode($bookingTrends['data']) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Revenue Trends Chart
        const revenueCtx = document.getElementById('revenueTrendsChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueTrends['labels']) !!},
                datasets: [{
                    label: 'Revenue (Nu.)',
                    data: {!! json_encode($revenueTrends['data']) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Nu. ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
