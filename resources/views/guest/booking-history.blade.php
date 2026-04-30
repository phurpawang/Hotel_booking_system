@extends('layouts.app')

@section('title', 'My Bookings & Reviews')

@section('content')
<!-- Reusable Search Bar -->
@include('components.search-bar', [
        'dzongkhags' => \App\Models\Dzongkhag::all(),
        'sticky' => true,
        'check_in' => request('check_in'),
        'check_out' => request('check_out'),
        'adults' => request('adults', 1),
        'children' => request('children', 0),
        'rooms' => request('rooms', 1),
        'dzongkhag_id' => request('dzongkhag_id')
    ])

<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
        <p class="text-gray-600 mt-2">View your booking history and write reviews</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
    @endif

    @if(session('info'))
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg">
        <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
    </div>
    @endif

    <!-- Bookings List -->
    <div class="space-y-4">
        @forelse($bookings as $booking)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Booking Info -->
                <div class="md:col-span-2">
                    <h3 class="text-xl font-bold text-gray-900">{{ $booking->hotel->name }}</h3>
                    <p class="text-gray-600 mt-1">Booking #{{ $booking->booking_id ?? $booking->id }}</p>
                    
                    <div class="mt-4 space-y-2 text-sm">
                        <p><strong>Check-in:</strong> {{ $booking->check_in_date->format('M d, Y') }}</p>
                        <p><strong>Check-out:</strong> {{ $booking->check_out_date->format('M d, Y') }}</p>
                        <p><strong>Guest Name:</strong> {{ $booking->guest_name }}</p>
                        <p><strong>Room:</strong> {{ $booking->room->room_type ?? 'N/A' }} #{{ $booking->room->room_number ?? 'N/A' }}</p>
                        <p><strong>Total Price:</strong> Nu. {{ number_format($booking->total_price, 2) }}</p>
                    </div>

                    <div class="mt-4">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                            @if($booking->status === 'CONFIRMED') bg-green-100 text-green-800
                            @elseif($booking->status === 'CANCELLED') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ $booking->status }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col justify-between">
                    @php
                        $checkoutPassed = now()->greaterThan($booking->check_out_date);
                        $hasReview = $booking->review()->exists();
                    @endphp

                    @if(!$checkoutPassed)
                        <button disabled class="bg-gray-300 text-gray-600 px-4 py-2 rounded-lg font-semibold cursor-not-allowed">
                            <i class="fas fa-star mr-2"></i>Write Review
                        </button>
                        <small class="text-gray-500 mt-2">Available after {{ $booking->check_out_date->format('M d, Y') }}</small>
                    @elseif($hasReview)
                        <div class="space-y-2">
                            <a href="{{ route('guest.review.show', $booking->id) }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                                <i class="fas fa-eye mr-2"></i>View Review
                            </a>
                            <form action="{{ route('guest.review.destroy', $booking->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg font-semibold transition" onclick="return confirm('Delete your review?')">
                                    <i class="fas fa-trash mr-2"></i>Delete Review
                                </button>
                            </form>
                        </div>
                        <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                            <i class="fas fa-check mr-1"></i>Review Submitted
                        </span>
                    @else
                        <a href="{{ route('guest.review.create', $booking->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition text-center">
                            <i class="fas fa-star mr-2"></i>Write Review
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-calendar text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-600 text-lg">No bookings found</p>
            <p class="text-gray-500">Once you make a booking, it will appear here</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
