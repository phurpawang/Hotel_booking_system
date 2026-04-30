@extends('layouts.app')

@section('title', 'Your Review - ' . $review->hotel->name)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <!-- Page Header -->
    <div class="mb-8">
        <a href="{{ route('guest.booking-history') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Bookings
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Your Review</h1>
        <p class="text-gray-600 mt-2">{{ $review->hotel->name }}</p>
    </div>

    <!-- Review Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6 pb-6 border-b">
            <div>
                <h3 class="text-lg font-bold">{{ $review->guest_name }}</h3>
                <p class="text-gray-600 text-sm">{{ $review->review_date->format('F d, Y') }}</p>
            </div>
            <div class="text-right">
                <div class="text-4xl font-bold text-blue-600">{{ $review->overall_rating }}<span class="text-lg text-gray-500">/10</span></div>
                @if($review->manager_reply)
                    <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                        <i class="fas fa-reply mr-1"></i>Hotel Replied
                    </span>
                @endif
            </div>
        </div>

        <!-- Ratings -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 pb-8 border-b">
            <div>
                <p class="text-sm text-gray-600 font-semibold">Cleanliness</p>
                <p class="text-2xl font-bold text-blue-600">{{ $review->cleanliness_rating }}<span class="text-xs text-gray-500">/10</span></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-semibold">Staff</p>
                <p class="text-2xl font-bold text-blue-600">{{ $review->staff_rating }}<span class="text-xs text-gray-500">/10</span></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-semibold">Comfort</p>
                <p class="text-2xl font-bold text-blue-600">{{ $review->comfort_rating }}<span class="text-xs text-gray-500">/10</span></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-semibold">Facilities</p>
                <p class="text-2xl font-bold text-blue-600">{{ $review->facilities_rating }}<span class="text-xs text-gray-500">/10</span></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-semibold">Value</p>
                <p class="text-2xl font-bold text-blue-600">{{ $review->value_for_money_rating }}<span class="text-xs text-gray-500">/10</span></p>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-semibold">Location</p>
                <p class="text-2xl font-bold text-blue-600">{{ $review->location_rating }}<span class="text-xs text-gray-500">/10</span></p>
            </div>
        </div>

        <!-- Comment -->
        @if($review->comment)
        <div class="mb-8 pb-8 border-b">
            <h4 class="font-bold text-gray-900 mb-3">Your Comments</h4>
            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
        </div>
        @endif

        <!-- Manager Reply -->
        @if($review->manager_reply)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <div class="flex items-start gap-4">
                <i class="fas fa-hotel text-blue-600 text-2xl mt-1"></i>
                <div class="flex-1">
                    <h4 class="font-bold text-gray-900">Hotel Response</h4>
                    <p class="text-sm text-gray-600 mb-2">{{ $review->manager->name ?? 'Hotel Manager' }} - {{ optional($review->reply_date)->format('F d, Y') }}</p>
                    <p class="text-gray-700">{{ $review->manager_reply }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Booking Info -->
        <div class="grid grid-cols-2 gap-4 mb-8 pb-8 border-b">
            <div>
                <p class="text-sm text-gray-600">Booking ID</p>
                <p class="font-semibold text-gray-900">#{{ $review->booking->booking_id ?? $review->booking->id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Stay</p>
                <p class="font-semibold text-gray-900">{{ $review->booking->check_in_date->format('M d') }} - {{ $review->booking->check_out_date->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <a href="{{ route('guest.booking-history') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition text-center">
                <i class="fas fa-arrow-left mr-2"></i>Back to Bookings
            </a>
            <form action="{{ route('guest.review.destroy', $review->booking_id) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-bold py-3 px-6 rounded-lg transition" onclick="return confirm('Are you sure you want to delete your review?')">
                    <i class="fas fa-trash mr-2"></i>Delete Review
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
