@extends('owner.layouts.app')

@section('title', 'Dashboard')

@section('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
@endsection

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Dashboard Overview</h2>
            <p class="text-gray-600 text-sm mt-1">Welcome back, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span> 👋</p>
        </div>
        <div class="text-right text-gray-600">
            <p class="text-sm">{{ now()->format('l, F d, Y') }}</p>
        </div>
    </div>
@endsection

@section('content')
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
    <!-- Total Bookings Card -->
    <div class="rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 hover:shadow-2xl transition-all duration-300" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm font-medium opacity-90">Total Bookings</div>
                <div class="text-4xl font-bold mt-2">{{ $totalReservations ?? 0 }}</div>
            </div>
            <div class="bg-white bg-opacity-20 p-4 rounded-xl">
                <i class="fas fa-calendar-check text-3xl opacity-90"></i>
            </div>
        </div>
        <div class="text-xs opacity-80 pt-3 border-t border-white border-opacity-20"><i class="fas fa-chart-line mr-1"></i> All-time bookings</div>
    </div>

    <!-- Today Check-ins Card -->
    <div class="rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 hover:shadow-2xl transition-all duration-300" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm font-medium opacity-90">Today Check-ins</div>
                <div class="text-4xl font-bold mt-2">{{ $todayCheckIns ?? 0 }}</div>
            </div>
            <div class="bg-white bg-opacity-20 p-4 rounded-xl">
                <i class="fas fa-door-open text-3xl opacity-90"></i>
            </div>
        </div>
        <div class="text-xs opacity-80 pt-3 border-t border-white border-opacity-20"><i class="fas fa-arrow-down mr-1"></i> Guests arriving</div>
    </div>

    <!-- Today Check-outs Card -->
    <div class="rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 hover:shadow-2xl transition-all duration-300" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm font-medium opacity-90">Today Check-outs</div>
                <div class="text-4xl font-bold mt-2">{{ $todayCheckOuts ?? 0 }}</div>
            </div>
            <div class="bg-white bg-opacity-20 p-4 rounded-xl">
                <i class="fas fa-door-closed text-3xl opacity-90"></i>
            </div>
        </div>
        <div class="text-xs opacity-80 pt-3 border-t border-white border-opacity-20"><i class="fas fa-arrow-up mr-1"></i> Guests departing</div>
    </div>

    <!-- Monthly Revenue Card -->
    <div class="rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 hover:shadow-2xl transition-all duration-300" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm font-medium opacity-90">Monthly Revenue</div>
                <div class="text-3xl font-bold mt-2">Nu. {{ number_format($monthlyRevenue ?? 0, 0) }}</div>
            </div>
            <div class="bg-white bg-opacity-20 p-4 rounded-xl">
                <i class="fas fa-chart-line text-3xl opacity-90"></i>
            </div>
        </div>
        <div class="text-xs opacity-80 pt-3 border-t border-white border-opacity-20"><i class="fas fa-calendar-alt mr-1"></i> This month</div>
    </div>
</div>

<!-- Commission & Payout Section -->
<div class="bg-gradient-to-br from-purple-600 via-pink-500 to-indigo-700 rounded-2xl shadow-2xl p-8 mb-8 text-white transform hover:shadow-3xl transition-all duration-300">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <div class="bg-white bg-opacity-20 p-4 rounded-xl">
                <i class="fas fa-money-bill-wave text-3xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold">Revenue & Commission</h3>
                <p class="text-sm opacity-90">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
            </div>
        </div>
        <a href="{{ route('owner.revenue.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-5 py-2 rounded-xl transition font-semibold flex items-center space-x-2 transform hover:scale-105 duration-200">
            <i class="fas fa-chart-bar"></i><span>Full Report</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <!-- Total Guest Payments -->
        <div class="bg-white bg-opacity-10 backdrop-blur-md rounded-xl p-5 border border-white border-opacity-20 hover:bg-opacity-20 transition">
            <div class="flex items-center justify-between mb-2">
                <div class="text-xs opacity-80 font-medium">Total Guest Payments</div>
                <i class="fas fa-users text-lg opacity-70"></i>
            </div>
            <div class="text-3xl font-bold mt-2">Nu. {{ number_format($monthlyGuestPayments ?? 0, 0) }}</div>
            <div class="text-xs opacity-70 mt-3 pt-3 border-t border-white border-opacity-20">Final price paid by guests</div>
        </div>

        <!-- Platform Commission -->
        <div class="bg-red-500 bg-opacity-20 backdrop-blur-md rounded-xl p-5 border border-red-300 border-opacity-30 hover:bg-opacity-30 transition">
            <div class="flex items-center justify-between mb-2">
                <div class="text-xs opacity-90 font-medium">Platform Commission</div>
                <i class="fas fa-percentage text-lg opacity-70"></i>
            </div>
            <div class="text-3xl font-bold mt-2">Nu. {{ number_format($monthlyCommissionTotal ?? 0, 0) }}</div>
            <div class="text-xs opacity-80 mt-3 pt-3 border-t border-red-300 border-opacity-20">10% platform fee deducted</div>
        </div>

        <!-- Your Net Earning -->
        <div class="bg-emerald-500 bg-opacity-20 backdrop-blur-md rounded-xl p-5 border border-emerald-300 border-opacity-30 hover:bg-opacity-30 transition">
            <div class="flex items-center justify-between mb-2">
                <div class="text-xs opacity-90 font-medium">Your Net Earning</div>
                <i class="fas fa-wallet text-lg opacity-70"></i>
            </div>
            <div class="text-3xl font-bold mt-2">Nu. {{ number_format($hotelPayout ?? 0, 0) }}</div>
            <div class="text-xs opacity-80 mt-3 pt-3 border-t border-emerald-300 border-opacity-20">Amount you receive</div>
        </div>

        <!-- Payout Status -->
        <div class="bg-amber-500 bg-opacity-20 backdrop-blur-md rounded-xl p-5 border border-amber-300 border-opacity-30 hover:bg-opacity-30 transition">
            <div class="flex items-center justify-between mb-2">
                <div class="text-xs opacity-90 font-medium">Payout Status</div>
                <i class="fas fa-info-circle text-lg opacity-70"></i>
            </div>
            <div class="mt-3">
                @if($currentPayout)
                    @if($currentPayout->payout_status == 'paid')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm bg-white bg-opacity-30 text-white font-semibold">
                            <i class="fas fa-check-circle mr-2 text-lg"></i> Paid
                        </span>
                    @elseif($currentPayout->payout_status == 'processing')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm bg-blue-400 bg-opacity-40 text-white font-semibold">
                            <i class="fas fa-sync fa-spin mr-2 text-lg"></i> Processing
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm bg-yellow-400 bg-opacity-40 text-white font-semibold">
                            <i class="fas fa-clock mr-2 text-lg"></i> Pending
                        </span>
                    @endif
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm bg-gray-400 bg-opacity-40 text-white font-semibold">
                        <i class="fas fa-calendar mr-2 text-lg"></i> Not Generated
                    </span>
                @endif
            </div>
            <div class="text-xs opacity-70 mt-3 pt-3 border-t border-amber-300 border-opacity-20">Monthly payout status</div>
        </div>
    </div>
</div>

<!-- Recent Payouts Table -->
@if(isset($recentPayouts) && $recentPayouts->count() > 0)
<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 mb-8">
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 flex justify-between items-center">
        <h3 class="text-lg font-bold text-white flex items-center space-x-2">
            <i class="fas fa-history"></i>
            <span>Recent Monthly Payouts</span>
        </h3>
        <a href="{{ route('owner.revenue.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg text-xs font-semibold text-white transition flex items-center space-x-1">
            <span>View All</span> <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Month</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Bookings</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Guest Payments</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Commission</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Your Payout</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($recentPayouts as $index => $payout)
                <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-all duration-200 @if($index % 2 == 0) bg-gray-50 @endif">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            {{ \Carbon\Carbon::create($payout->year, $payout->month, 1)->format('F Y') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold text-gray-900">
                        {{ $payout->total_bookings }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900">
                        Nu. {{ number_format($payout->total_guest_payments, 0) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-red-600">
                        -Nu. {{ number_format($payout->total_commission, 0) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600 text-lg">
                        Nu. {{ number_format($payout->hotel_payout_amount, 0) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($payout->payout_status == 'paid')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs leading-5 font-semibold bg-green-100 text-green-800 space-x-1">
                                <i class="fas fa-check-circle"></i> <span>Paid</span>
                            </span>
                        @elseif($payout->payout_status == 'processing')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs leading-5 font-semibold bg-blue-100 text-blue-800 space-x-1">
                                <i class="fas fa-sync fa-spin"></i> <span>Processing</span>
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs leading-5 font-semibold bg-yellow-100 text-yellow-800 space-x-1">
                                <i class="fas fa-clock"></i> <span>Pending</span>
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
    <div class="rounded-2xl shadow-xl p-8 text-white text-center transform hover:scale-105 hover:shadow-2xl transition-all duration-300" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
        <div class="bg-white bg-opacity-20 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-4xl"></i>
        </div>
        <div class="text-5xl font-bold mb-1">{{ $totalRooms ?? 0 }}</div>
        <div class="text-sm font-medium opacity-90">Total Rooms</div>
        <div class="text-xs opacity-75 mt-2">Available & Occupied</div>
    </div>

    <div class="rounded-2xl shadow-xl p-8 text-white text-center transform hover:scale-105 hover:shadow-2xl transition-all duration-300" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="bg-white bg-opacity-20 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-door-open text-4xl"></i>
        </div>
        <div class="text-5xl font-bold mb-1">{{ $availableRooms ?? 0 }}</div>
        <div class="text-sm font-medium opacity-90">Available Rooms</div>
        <div class="text-xs opacity-75 mt-2">Ready for booking</div>
    </div>

    <div class="rounded-2xl shadow-xl p-8 text-white text-center transform hover:scale-105 hover:shadow-2xl transition-all duration-300" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
        <div class="bg-white bg-opacity-20 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-bed text-4xl"></i>
        </div>
        <div class="text-5xl font-bold mb-1">{{ $totalRooms - $availableRooms ?? 0 }}</div>
        <div class="text-sm font-medium opacity-90">Occupied Rooms</div>
        <div class="text-xs opacity-75 mt-2">Currently booked</div>
    </div>

    <div class="rounded-2xl shadow-xl p-8 text-white text-center transform hover:scale-105 hover:shadow-2xl transition-all duration-300" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
        <div class="bg-white bg-opacity-20 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-chart-pie text-4xl"></i>
        </div>
        <div class="text-5xl font-bold mb-1">{{ $totalRooms > 0 ? round((($totalRooms - $availableRooms) / $totalRooms) * 100) : 0 }}%</div>
        <div class="text-sm font-medium opacity-90">Occupancy Rate</div>
        <div class="text-xs opacity-75 mt-2">Room utilization</div>
    </div>
</div>



<!-- Recent Bookings Table -->
<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                <i class="fas fa-history"></i>
                <span>Recent Bookings</span>
            </h3>
            <span class="bg-white bg-opacity-20 px-3 py-1 rounded-lg text-xs font-semibold text-white">Latest</span>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Guest</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Room</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Check-in</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Check-out</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Commission</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentBookings as $index => $booking)
                <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200 @if($index % 2 == 0) bg-gray-50 @endif">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-400 flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($booking->guest_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-sm text-gray-900">{{ $booking->guest_name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            #{{ $booking->room->room_number ?? 'N/A' }}
                        </span>
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
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs leading-5 font-semibold bg-blue-100 text-blue-800 space-x-1">
                                    <i class="fas fa-credit-card"></i> <span>Online</span>
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs leading-5 font-semibold bg-purple-100 text-purple-800 space-x-1">
                                    <i class="fas fa-money-bill"></i> <span>At Hotel</span>
                                </span>
                            @endif
                        @else
                            <span class="text-xs text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($booking->status == 'CONFIRMED')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs leading-5 font-semibold bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Confirmed</span>
                        @elseif($booking->status == 'PENDING')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs leading-5 font-semibold bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>Pending</span>
                        @elseif($booking->status == 'CHECKED_IN')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs leading-5 font-semibold bg-blue-100 text-blue-800"><i class="fas fa-sign-in-alt mr-1"></i>Checked In</span>
                        @elseif($booking->status == 'CHECKED_OUT')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs leading-5 font-semibold bg-gray-100 text-gray-800"><i class="fas fa-sign-out-alt mr-1"></i>Checked Out</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs leading-5 font-semibold bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Cancelled</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-center">
                        <span class="text-lg font-bold text-gray-900">Nu. {{ number_format($booking->total_price, 0) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($booking->commission)
                            <div class="text-xs space-y-1">
                                <div class="text-red-600 font-semibold">-Nu. {{ number_format($booking->commission->commission_amount, 0) }}</div>
                                <div class="text-green-600 font-bold">+Nu. {{ number_format($booking->commission->base_amount, 0) }}</div>
                            </div>
                        @else
                            <span class="text-xs text-gray-400">N/A</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-500 text-sm font-medium">No recent bookings found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Hidden data container for chart data -->
    <div id="chartData" 
         data-booking-labels="{!! json_encode($bookingTrends['labels']) !!}"
         data-booking-data="{!! json_encode($bookingTrends['data']) !!}"
         data-revenue-labels="{!! json_encode($revenueTrends['labels']) !!}"
         data-revenue-data="{!! json_encode($revenueTrends['data']) !!}"
         style="display: none;"></div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartDataEl = document.getElementById('chartData');
        const bookingLabels = JSON.parse(chartDataEl.dataset.bookingLabels);
        const bookingData = JSON.parse(chartDataEl.dataset.bookingData);
        const revenueLabels = JSON.parse(chartDataEl.dataset.revenueLabels);
        const revenueData = JSON.parse(chartDataEl.dataset.revenueData);

        const bookingCtx = document.getElementById('bookingTrendsChart').getContext('2d');
        new Chart(bookingCtx, {
            type: 'line',
            data: {
                labels: bookingLabels,
                datasets: [{
                    label: 'Number of Bookings',
                    data: bookingData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: 'rgb(59, 130, 246)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            font: { size: 12, weight: 'bold' },
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1,
                            font: { size: 11 },
                            color: '#6b7280'
                        },
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 },
                            color: '#6b7280'
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });

        const revenueCtx = document.getElementById('revenueTrendsChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue (Nu.)',
                    data: revenueData,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(74, 222, 128, 0.8)',
                        'rgba(132, 204, 22, 0.8)',
                        'rgba(163, 230, 53, 0.8)',
                        'rgba(190, 242, 100, 0.8)'
                    ],
                    borderColor: [
                        'rgb(16, 185, 129)',
                        'rgb(34, 197, 94)',
                        'rgb(74, 222, 128)',
                        'rgb(132, 204, 22)',
                        'rgb(163, 230, 53)',
                        'rgb(190, 242, 100)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            font: { size: 12, weight: 'bold' },
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Nu. ' + Math.round(value/1000) + 'K';
                            },
                            font: { size: 11 },
                            color: '#6b7280'
                        },
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 },
                            color: '#6b7280'
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    </script>
@endsection
