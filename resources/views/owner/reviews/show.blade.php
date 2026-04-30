@extends('owner.layouts.app')

@section('title', 'Review Details')

@section('content')
<div class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 min-h-screen p-6">
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('owner.reviews.index') }}" 
           class="inline-flex items-center text-white hover:text-blue-300 transition font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>Back to Reviews
        </a>
        <div class="text-white text-sm">
            {{ now()->format('l, F d, Y') }}
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl shadow-lg p-4 flex items-center">
        <i class="fas fa-check-circle mr-3 text-2xl"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (2 columns) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Guest Review Card -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl shadow-lg border-l-4 border-amber-500 overflow-hidden hover:shadow-xl transition">
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $review->guest_name }}'s Review</h3>
                        <p class="text-amber-100 text-sm">{{ $review->review_date->format('F d, Y') }}</p>
                    </div>
                    <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center shadow-lg">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-amber-600">{{ $review->overall_rating }}</p>
                            <p class="text-xs text-gray-600">/10</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Detailed Ratings -->
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-star text-amber-500 mr-2"></i>Detailed Ratings
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Cleanliness -->
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-semibold text-gray-800">Cleanliness</p>
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm font-bold">{{ $review->cleanliness_rating }}/10</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" style="--bar-width: {{ ($review->cleanliness_rating/10)*100 }}%; width: var(--bar-width)"></div>
                                </div>
                            </div>
                            
                            <!-- Staff & Service -->
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-semibold text-gray-800">Staff & Service</p>
                                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-sm font-bold">{{ $review->staff_rating }}/10</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-2 rounded-full" style="--bar-width: {{ ($review->staff_rating/10)*100 }}%; width: var(--bar-width)"></div>
                                </div>
                            </div>
                            
                            <!-- Comfort -->
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-semibold text-gray-800">Comfort</p>
                                    <span class="bg-pink-100 text-pink-700 px-2 py-1 rounded text-sm font-bold">{{ $review->comfort_rating }}/10</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-pink-400 to-pink-600 h-2 rounded-full" style="--bar-width: {{ ($review->comfort_rating/10)*100 }}%; width: var(--bar-width)"></div>
                                </div>
                            </div>
                            
                            <!-- Facilities -->
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-semibold text-gray-800">Facilities</p>
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm font-bold">{{ $review->facilities_rating }}/10</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="--bar-width: {{ ($review->facilities_rating/10)*100 }}%; width: var(--bar-width)"></div>
                                </div>
                            </div>
                            
                            <!-- Value for Money -->
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-semibold text-gray-800">Value for Money</p>
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm font-bold">{{ $review->value_for_money_rating }}/10</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 h-2 rounded-full" style="--bar-width: {{ ($review->value_for_money_rating/10)*100 }}%; width: var(--bar-width)"></div>
                                </div>
                            </div>
                            
                            <!-- Location -->
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-semibold text-gray-800">Location</p>
                                    <span class="bg-cyan-100 text-cyan-700 px-2 py-1 rounded text-sm font-bold">{{ $review->location_rating }}/10</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-cyan-400 to-cyan-600 h-2 rounded-full" style="--bar-width: {{ ($review->location_rating/10)*100 }}%; width: var(--bar-width)"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Comments -->
                    @if($review->comment)
                    <div class="border-t pt-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-quote-left text-orange-500 mr-2"></i>Guest Comments
                        </h4>
                        <div class="bg-gradient-to-br from-orange-50 to-yellow-50 border-l-4 border-orange-500 rounded-lg p-4">
                            <p class="text-gray-700 leading-relaxed text-lg italic">{{ $review->comment }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Booking Info -->
                    <div class="border-t pt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">Booking ID</p>
                            <p class="text-lg font-bold text-gray-800">#{{ $review->booking->booking_id ?? $review->booking->id }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide mb-1">Guest Email</p>
                            <p class="text-sm font-bold text-gray-800"><a href="mailto:{{ $review->guest_email }}" class="text-purple-600 hover:underline">{{ $review->guest_email }}</a></p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-1">Stay Dates</p>
                            <p class="text-sm font-bold text-gray-800">{{ $review->booking->check_in_date->format('M d') }} - {{ $review->booking->check_out_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Response Form Card -->
            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl shadow-lg border-l-4 border-blue-500 overflow-hidden hover:shadow-xl transition">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-4">
                    <h4 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-reply bg-white bg-opacity-20 rounded-full p-2 mr-3 w-10 h-10 flex items-center justify-center"></i>
                        {{ $review->manager_reply ? 'Edit Your Response' : 'Write a Response' }}
                    </h4>
                </div>

                <div class="p-6">
                    @if($review->manager_reply)
                    <div class="bg-gradient-to-r from-blue-100 to-cyan-100 border-l-4 border-blue-500 rounded-lg p-4 mb-6">
                        <p class="text-sm font-semibold text-blue-900 mb-2">
                            <i class="fas fa-history mr-2"></i>Your previous response ({{ optional($review->reply_date)->format('M d, Y') }}):
                        </p>
                        <p class="text-gray-800">{{ $review->manager_reply }}</p>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('owner.reviews.reply', $review->id) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="manager_reply" class="block text-sm font-bold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-pen-fancy text-blue-500 mr-2"></i>Your Response *
                            </label>
                            <textarea name="manager_reply" id="manager_reply" 
                                class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:border-blue-500 @error('manager_reply') border-red-500 @enderror" 
                                rows="6" placeholder="Thank the guest for their feedback and address any concerns..." required>{{ old('manager_reply', $review->manager_reply) }}</textarea>
                            <p class="text-xs text-gray-600 mt-2">Provide a thoughtful response to the guest's feedback</p>
                            @error('manager_reply')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold px-6 py-3 rounded-lg transition shadow-md">
                                <i class="fas fa-paper-plane mr-2"></i>{{ $review->manager_reply ? 'Update Response' : 'Send Response' }}
                            </button>
                            @if($review->manager_reply)
                            <button type="button" onclick="document.getElementById('deleteForm').submit()" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold px-6 py-3 rounded-lg transition shadow-md">
                                <i class="fas fa-trash mr-2"></i>Delete Review
                            </button>
                            @endif
                        </div>
                    </form>

                    @if($review->manager_reply)
                    <form id="deleteForm" method="POST" action="{{ route('owner.reviews.destroy', $review->id) }}" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Information Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl shadow-lg border-l-4 border-indigo-500 overflow-hidden hover:shadow-xl transition">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-4">
                    <h4 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-info-circle bg-white bg-opacity-20 rounded-full p-2 mr-3 w-10 h-10 flex items-center justify-center"></i>
                        Information
                    </h4>
                </div>

                <div class="p-6 space-y-4">
                    <div class="bg-white rounded-lg p-4">
                        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-2">Hotel</p>
                        <p class="text-lg font-bold text-gray-800">{{ $review->hotel->name }}</p>
                    </div>

                    <div class="bg-white rounded-lg p-4">
                        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-2">Guest</p>
                        <p class="text-lg font-bold text-gray-800 mb-2">{{ $review->guest_name }}</p>
                        <a href="mailto:{{ $review->guest_email }}" class="block w-full bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white text-center font-semibold px-3 py-2 rounded transition">
                            <i class="fas fa-envelope mr-1"></i>Send Email
                        </a>
                    </div>

                    <div class="bg-white rounded-lg p-4">
                        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-3">Status</p>
                        @if($review->manager_reply)
                            <span class="inline-flex items-center bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-bold px-3 py-2 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i>Response Sent
                            </span>
                        @else
                            <span class="inline-flex items-center bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-bold px-3 py-2 rounded-full">
                                <i class="fas fa-clock mr-1"></i>Pending Response
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tip Card -->
            <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-2xl shadow-lg border-l-4 border-yellow-500 overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-amber-500 p-4">
                    <h4 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-lightbulb bg-white bg-opacity-20 rounded-full p-2 mr-3 w-10 h-10 flex items-center justify-center"></i>
                        Pro Tip
                    </h4>
                </div>

                <div class="p-6">
                    <p class="text-gray-700 leading-relaxed">
                        Always respond to reviews professionally and politely. This helps build trust with potential guests and shows your commitment to excellent service.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    textarea:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>
@endsection
