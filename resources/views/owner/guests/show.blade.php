@extends('owner.layouts.app')

@section('title', 'Guest Details')

@section('header')
    <div class="flex items-center mb-2">
        <a href="{{ route('owner.guests.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Guest Profile</h2>
    </div>
    <p class="text-gray-600 text-sm">Guest information and booking history</p>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Guest Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
        <div class="w-24 h-24 rounded-full bg-purple-600 flex items-center justify-center text-white text-4xl font-bold mx-auto mb-4">
            {{ substr($guest['name'], 0, 1) }}
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $guest['name'] }}</h3>
        <p class="text-gray-600 mb-1">{{ $guest['email'] }}</p>
        <p class="text-gray-600">{{ $guest['phone'] ?? 'No phone' }}</p>
    </div>

    <!-- Statistics -->
    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <i class="fas fa-calendar-check text-3xl mb-3 opacity-80"></i>
            <p class="text-2xl font-bold">{{ $bookings->count() }}</p>
            <p class="text-sm opacity-90">Total Bookings</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <i class="fas fa-chart-line text-3xl mb-3 opacity-80"></i>
            <p class="text-2xl font-bold">Nu. {{ number_format($bookings->sum('total_price'), 2) }}</p>
            <p class="text-sm opacity-90">Total Spent</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <i class="fas fa-clock text-3xl mb-3 opacity-80"></i>
            <p class="text-sm opacity-90 mb-1">Last Visit</p>
            <p class="font-semibold">{{ $bookings->first() ? \Carbon\Carbon::parse($bookings->first()->check_in_date)->format('M d, Y') : 'N/A' }}</p>
        </div>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #{{ $booking->id }}
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
                        @if($booking->status == 'CONFIRMED')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Confirmed
                            </span>
                        @elseif($booking->status == 'PENDING')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @elseif($booking->status == 'CHECKED_IN')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Checked In
                            </span>
                        @elseif($booking->status == 'CHECKED_OUT')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                Checked Out
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Cancelled
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
                        No bookings found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
