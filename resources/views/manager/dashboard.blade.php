@extends('manager.layouts.app')

@section('title', 'Dashboard')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview</h2>
    <p class="text-gray-600 text-sm">Welcome back, {{ Auth::user()->name }}</p>
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
        <a href="{{ route('manager.deregistration.index') }}" class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded transition">
            View Details
        </a>
    </div>
</div>
@endif

<!-- Colorful Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="flex items-center justify-between mb-3">
            <div class="text-sm opacity-90">Total Bookings</div>
            <i class="fas fa-calendar-check text-3xl opacity-90"></i>
        </div>
        <div class="text-4xl font-bold mb-2">{{ $totalBookings ?? 0 }}</div>
        <div class="text-xs opacity-90"><i class="fas fa-chart-line"></i> All time</div>
    </div>

    <div class="rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="flex items-center justify-between mb-3">
            <div class="text-sm opacity-90">Today Check-ins</div>
            <i class="fas fa-door-open text-3xl opacity-90"></i>
        </div>
        <div class="text-4xl font-bold mb-2">{{ $todayCheckIns ?? 0 }}</div>
        <div class="text-xs opacity-90"><i class="fas fa-arrow-down"></i> Arriving</div>
    </div>

    <div class="rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <div class="flex items-center justify-between mb-3">
            <div class="text-sm opacity-90">Today Check-outs</div>
            <i class="fas fa-door-closed text-3xl opacity-90"></i>
        </div>
        <div class="text-4xl font-bold mb-2">{{ $todayCheckOuts ?? 0 }}</div>
        <div class="text-xs opacity-90"><i class="fas fa-arrow-up"></i> Departing</div>
    </div>

    <div class="rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
        <div class="flex items-center justify-between mb-3">
            <div class="text-sm opacity-90">Pending Bookings</div>
            <i class="fas fa-clock text-3xl opacity-90"></i>
        </div>
        <div class="text-4xl font-bold mb-2">{{ $stats['pending'] ?? $pendingBookings ?? 0 }}</div>
        <div class="text-xs opacity-90"><i class="fas fa-hourglass-half"></i> Awaiting confirmation</div>
    </div>
</div>

<!-- Room Status Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
        <i class="fas fa-check-circle text-5xl mb-3 opacity-90"></i>
        <div class="text-4xl font-bold mb-2">{{ ($availableRooms ?? 0) + ($stats['checked_in'] ?? 0) }}</div>
        <div class="text-sm opacity-90">Total Rooms</div>
    </div>

    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <i class="fas fa-door-open text-5xl mb-3 opacity-90"></i>
        <div class="text-4xl font-bold mb-2">{{ $availableRooms ?? 0 }}</div>
        <div class="text-sm opacity-90">Available Rooms</div>
    </div>

    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%);">
        <i class="fas fa-bed text-5xl mb-3 opacity-90"></i>
        <div class="text-4xl font-bold mb-2">{{ $stats['checked_in'] ?? 0 }}</div>
        <div class="text-sm opacity-90">Occupied Rooms</div>
    </div>

    <div class="rounded-xl shadow-lg p-8 text-white text-center transform hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
        <i class="fas fa-percent text-5xl mb-3 opacity-90"></i>
        <div class="text-4xl font-bold mb-2">{{ round(($occupancyRate ?? 0) * 100) }}%</div>
        <div class="text-sm opacity-90">Occupancy Rate</div>
    </div>
</div>

<!-- Quick Actions - Guest Questions -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-comments text-green-600 mr-2"></i>Guest Questions
        </h3>
        <a href="{{ route('manager.inquiries.index') }}" class="text-xs font-semibold text-green-600 hover:text-green-800">All →</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-4">
        <div class="bg-green-50 p-2 rounded-lg text-center">
            <div class="text-xl font-bold text-green-600">{{ $totalInquiries ?? 0 }}</div>
            <div class="text-xs text-gray-600">Total</div>
        </div>
        <div class="bg-orange-50 p-2 rounded-lg text-center">
            <div class="text-xl font-bold text-orange-600">{{ $pendingInquiries ?? 0 }}</div>
            <div class="text-xs text-gray-600">Pending</div>
        </div>
        <div class="bg-blue-50 p-2 rounded-lg text-center">
            <div class="text-xl font-bold text-blue-600">{{ $answeredInquiries ?? 0 }}</div>
            <div class="text-xs text-gray-600">Answered</div>
        </div>
        <div class="bg-gray-100 p-2 rounded-lg text-center">
            <div class="text-xl font-bold text-gray-600">{{ $closedInquiries ?? 0 }}</div>
            <div class="text-xs text-gray-600">Closed</div>
        </div>
    </div>

    @if($recentInquiries && $recentInquiries->count() > 0)
        <div class="space-y-2 mb-4 max-h-64 overflow-y-auto">
            @foreach($recentInquiries as $inquiry)
            <div class="bg-gray-50 p-2 rounded-lg border-l-4 @if($inquiry->status === 'PENDING') border-orange-500 @elseif($inquiry->status === 'ANSWERED') border-green-500 @else border-gray-400 @endif hover:bg-gray-100 transition">
                <div class="flex justify-between items-start gap-1">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-xs text-gray-800 truncate">{{ $inquiry->guest_name }}</p>
                        <p class="text-xs text-gray-600 truncate">{{ Str::limit($inquiry->question, 40) }}</p>
                    </div>
                    <span class="px-1 py-0.5 text-xs rounded whitespace-nowrap @if($inquiry->status === 'PENDING') bg-orange-100 text-orange-800 @elseif($inquiry->status === 'ANSWERED') bg-green-100 text-green-800 @else bg-gray-200 text-gray-800 @endif">
                        {{ $inquiry->status }}
                    </span>
                </div>
                <a href="{{ route('manager.inquiries.show', $inquiry->id) }}" class="text-green-600 hover:text-green-800 text-xs font-semibold inline-block mt-1">
                    Reply <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center py-6 text-sm">No guest questions yet</p>
    @endif

    <a href="{{ route('manager.inquiries.index') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg text-center transition text-sm">
        <i class="fas fa-envelope mr-1"></i>Manage Questions
    </a>
</div>

<!-- Recent Bookings Table - Enhanced Design -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="animation: slideInUp 0.6s ease-out;">
    <!-- Colorful Header -->
    <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 2rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-calendar-check" style="font-size: 2rem; opacity: 0.9;"></i>
            <div>
                <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Recent Bookings</h3>
                <p style="margin: 0.25rem 0 0 0; opacity: 0.9; font-size: 0.9rem;">Latest guest reservations</p>
            </div>
        </div>
        <a href="{{ route('manager.reservations.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'">
            <i class="fas fa-arrow-right mr-1"></i>View All
        </a>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background: linear-gradient(90deg, #0f7d6f 0%, #0d9d7c 100%); border-bottom: 2px solid #0d6b60;">
                <tr>
                    <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-user-circle mr-2" style="color: white; opacity: 0.95;"></i>Guest Name
                    </th>
                    <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-door-open mr-2" style="color: white; opacity: 0.95;"></i>Room
                    </th>
                    <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-calendar-plus mr-2" style="color: white; opacity: 0.95;"></i>Check-in
                    </th>
                    <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-calendar-minus mr-2" style="color: white; opacity: 0.95;"></i>Check-out
                    </th>
                    <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-flag mr-2" style="color: white; opacity: 0.95;"></i>Status
                    </th>
                    <th style="padding: 1.25rem 1.5rem; text-align: right; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-money-bill-wave mr-2" style="color: white; opacity: 0.95;"></i>Amount
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $booking)
                <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.background='linear-gradient(90deg, #f0f9f8 0%, #f5fffe 100%)'; this.style.boxShadow='inset 0 2px 6px rgba(17, 153, 142, 0.08)'" onmouseout="this.style.background='white'; this.style.boxShadow='none'">
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="font-weight: 600; color: #333; font-size: 0.95rem;">{{ $booking->guest_name }}</div>
                        <div style="font-size: 0.8rem; color: #999; margin-top: 0.25rem;">{{ $booking->email }}</div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-align: center; font-size: 0.9rem; display: inline-block;">
                            {{ $booking->room->room_number ?? 'N/A' }}
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; color: #555; font-size: 0.95rem;">
                        {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem; color: #555; font-size: 0.95rem;">
                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        @if(strtolower($booking->status ?? 'pending') === 'confirmed')
                            <span style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); color: #047857; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                <i class="fas fa-check-circle mr-1"></i>CONFIRMED
                            </span>
                        @elseif(strtolower($booking->status ?? 'pending') === 'checked_out' || strtolower($booking->status ?? 'pending') === 'completed')
                            <span style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #6b7280; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                <i class="fas fa-check-double mr-1"></i>CHECKED OUT
                            </span>
                        @elseif(strtolower($booking->status ?? 'pending') === 'pending')
                            <span style="background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%); color: #d97706; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                <i class="fas fa-clock mr-1"></i>PENDING
                            </span>
                        @elseif(strtolower($booking->status ?? 'pending') === 'cancelled')
                            <span style="background: linear-gradient(135deg, #fa8072 0%, #ff6b6b 100%); color: #7f1d1d; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                <i class="fas fa-times-circle mr-1"></i>CANCELLED
                            </span>
                        @else
                            <span style="background: #f3f4f6; color: #6b7280; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                {{ strtoupper($booking->status ?? 'UNKNOWN') }}
                            </span>
                        @endif
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: right;">
                        <div style="font-weight: 700; color: #11998e; font-size: 1.05rem;">Nu. {{ number_format($booking->total_price ?? 0, 2) }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 3rem 1.5rem; text-align: center;">
                        <div style="color: #999;">
                            <i class="fas fa-inbox" style="font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.5; display: block;"></i>
                            <p style="font-size: 1rem; margin: 0;">No recent bookings</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
