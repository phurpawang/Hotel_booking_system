@extends('owner.layouts.app')

@section('title', 'View Inquiry')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Guest Inquiry Details</h2>
            <p class="text-gray-600 text-sm">From <strong>{{ $inquiry->guest_name }}</strong></p>
        </div>
        <a href="{{ route('owner.inquiries.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to Inquiries
        </a>
    </div>
@endsection

@section('content')
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <!-- Guest Information Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-circle mr-2 text-blue-600"></i>Guest Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-600 text-sm font-semibold">Guest Name</label>
                    <p class="text-gray-800 font-semibold mt-1">{{ $inquiry->guest_name }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm font-semibold">Email</label>
                    <p class="text-gray-800 mt-1">
                        <a href="mailto:{{ $inquiry->guest_email }}" class="text-blue-600 hover:text-blue-800">
                            {{ $inquiry->guest_email }}
                        </a>
                    </p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm font-semibold">Phone</label>
                    <p class="text-gray-800 font-semibold mt-1">
                        {{ $inquiry->guest_phone ?? 'Not provided' }}
                    </p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm font-semibold">Submitted</label>
                    <p class="text-gray-800 mt-1">{{ $inquiry->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-question-circle mr-2 text-blue-600"></i>Question
            </h3>
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <p class="text-gray-800 whitespace-pre-wrap">{{ $inquiry->question }}</p>
            </div>
        </div>

        <!-- Answer Section -->
        @if($inquiry->status === 'ANSWERED')
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-reply mr-2 text-green-600"></i>Your Answer
                </h3>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $inquiry->answer }}</p>
                </div>
                @if($inquiry->answered_at)
                    <p class="text-gray-600 text-sm mt-4">
                        <i class="fas fa-calendar mr-1"></i>Answered on {{ $inquiry->answered_at->format('M d, Y \a\t h:i A') }}
                    </p>
                @endif
            </div>
        @else
            <!-- Answer Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-pen-square mr-2 text-blue-600"></i>Provide Answer
                </h3>
                <form action="{{ route('owner.inquiries.answer', $inquiry->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Your Response</label>
                        <textarea name="answer" rows="8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 @error('answer') border-red-500 @enderror" required placeholder="Type your answer here..."></textarea>
                        @error('answer')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>Send Answer
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>Status
            </h3>
            <div class="mb-4">
                @if($inquiry->status === 'PENDING')
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-800 w-full text-center">
                        <i class="fas fa-hourglass-half mr-2"></i>Pending Review
                    </span>
                @elseif($inquiry->status === 'ANSWERED')
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 w-full text-center">
                        <i class="fas fa-check-circle mr-2"></i>Answered
                    </span>
                @else
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-gray-200 text-gray-800 w-full text-center">
                        <i class="fas fa-times-circle mr-2"></i>Closed
                    </span>
                @endif
            </div>

            @if($inquiry->status !== 'CLOSED')
                <form action="{{ route('owner.inquiries.close', $inquiry->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-semibold flex items-center justify-center">
                        <i class="fas fa-lock mr-2"></i>Close Inquiry
                    </button>
                </form>
            @endif
        </div>

        <!-- Quick Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clock mr-2 text-blue-600"></i>Timeline
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start">
                    <span class="text-gray-600 font-semibold min-w-24">Submitted:</span>
                    <span class="text-gray-700 ml-2">{{ $inquiry->created_at->format('M d, Y') }}</span>
                </div>
                @if($inquiry->answered_at)
                    <div class="flex items-start">
                        <span class="text-gray-600 font-semibold min-w-24">Answered:</span>
                        <span class="text-gray-700 ml-2">{{ $inquiry->answered_at->format('M d, Y') }}</span>
                    </div>
                @endif
                @if($inquiry->closed_at)
                    <div class="flex items-start">
                        <span class="text-gray-600 font-semibold min-w-24">Closed:</span>
                        <span class="text-gray-700 ml-2">{{ $inquiry->closed_at->format('M d, Y') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
