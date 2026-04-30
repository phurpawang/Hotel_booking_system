@extends('owner.layouts.app')

@section('title', 'Reviews')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800">Guest Reviews</h2>
    <p class="text-gray-600 text-sm">View and respond to guest feedback</p>
@endsection

@section('content')
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

<!-- Average Rating Card -->
<div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg shadow-lg p-8 text-white mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold mb-2">Overall Rating</h3>
            <div class="flex items-center">
                <span class="text-5xl font-bold">{{ number_format($averageRating, 1) }}</span>
                <div class="ml-4">
                    <div class="flex items-center mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating))
                                <i class="fas fa-star text-2xl"></i>
                            @elseif($i - $averageRating < 1)
                                <i class="fas fa-star-half-alt text-2xl"></i>
                            @else
                                <i class="far fa-star text-2xl"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-sm opacity-90">Based on {{ $reviews->total() }} reviews</p>
                </div>
            </div>
        </div>
        <div class="text-right">
            <i class="fas fa-star text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Reviews List -->
<div class="space-y-6">
    @forelse($reviews as $review)
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
        <!-- Review Header -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-lg">
                    {{ substr($review->guest_name, 0, 1) }}
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">{{ $review->guest_name }}</h4>
                    <p class="text-sm text-gray-500">{{ $review->guest_email }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $review->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        <i class="fas fa-star text-yellow-400"></i>
                    @else
                        <i class="far fa-star text-yellow-400"></i>
                    @endif
                @endfor
                <span class="ml-2 text-sm font-semibold text-gray-700">{{ $review->rating }}/5</span>
            </div>
        </div>

        <!-- Review Comment -->
        <div class="mb-4 pl-16">
            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
        </div>

        <!-- Owner Reply -->
        @if($review->reply)
        <div class="ml-16 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
            <div class="flex items-start">
                <i class="fas fa-reply text-blue-600 mt-1 mr-3"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-900 mb-1">Your Reply:</p>
                    <p class="text-gray-700">{{ $review->reply }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        Replied on {{ \Carbon\Carbon::parse($review->replied_at)->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>
        @else
        <!-- Reply Form -->
        <div class="ml-16 border-t border-gray-200 pt-4">
            <form action="{{ route('owner.reviews.reply', $review->id) }}" method="POST">
                @csrf
                <textarea name="reply" rows="3" required placeholder="Write your reply to this guest..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                <div class="flex justify-end mt-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold flex items-center">
                        <i class="fas fa-paper-plane mr-1"></i>Send Reply
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-12 text-center">
        <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Reviews Yet</h3>
        <p class="text-gray-500">Guest reviews will appear here once they submit feedback.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($reviews->hasPages())
<div class="mt-8">
    {{ $reviews->links() }}
</div>
@endif
@endsection
