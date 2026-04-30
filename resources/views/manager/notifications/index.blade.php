@extends('manager.layouts.app')

@section('title', 'Notifications')

@section('header')
    <div class="flex items-center justify-between w-full">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
            <p class="text-gray-600 text-sm">Stay updated with system notifications</p>
        </div>
        @if($unreadCount > 0)
        <form action="{{ route('manager.notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                <i class="fas fa-check-double mr-1"></i>
                Mark all as read
            </button>
        </form>
        @endif
    </div>
@endsection

@section('content')
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    <i class="fas fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
@endif

<!-- Unread Count Badge -->
@if($unreadCount > 0)
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <p class="text-blue-800">
        <i class="fas fa-info-circle mr-2"></i>
        You have <strong>{{ $unreadCount }}</strong> unread {{ $unreadCount == 1 ? 'notification' : 'notifications' }}
    </p>
</div>
@endif

<!-- Notifications List -->
<div class="space-y-4">
    @forelse($notifications as $notification)
    <div class="bg-white rounded-xl shadow-sm border {{ $notification->is_read ? 'border-gray-100' : 'border-blue-200 bg-blue-50' }} p-6 hover:shadow-md transition">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    @if(!$notification->is_read)
                    <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                    @endif
                    
                    <!-- Notification Icon -->
                    <div class="flex-shrink-0 mr-3">
                        @if(stripos($notification->type, 'booking') !== false)
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-calendar-check text-purple-600 text-sm"></i>
                            </div>
                        @elseif(stripos($notification->type, 'question') !== false)
                            <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center">
                                <i class="fas fa-question-circle text-orange-600 text-sm"></i>
                            </div>
                        @elseif(stripos($notification->type, 'alert') !== false)
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-red-600 text-sm"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-bell text-blue-600 text-sm"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="text-lg font-semibold {{ $notification->is_read ? 'text-gray-900' : 'text-blue-900' }}">
                        {{ $notification->title }}
                    </h4>
                    <span class="ml-auto text-xs text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $notification->created_at->diffForHumans() }}
                    </span>
                </div>
                <p class="{{ $notification->is_read ? 'text-gray-600' : 'text-blue-800' }} mb-3">
                    {{ $notification->message }}
                </p>
                
                <!-- Notification Data -->
                @if($notification->data)
                    @php
                        $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                    @endphp
                    @if(is_array($data) && !empty($data))
                    <div class="bg-gray-50 rounded-lg p-3 mb-3 text-sm">
                        @foreach($data as $key => $value)
                            <p class="text-gray-600">
                                <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> {{ $value }}
                            </p>
                        @endforeach
                    </div>
                    @endif
                @endif
                
                <div class="mt-3 flex items-center space-x-4">
                    <span class="text-xs {{ $notification->is_read ? 'text-gray-500' : 'text-blue-600' }}">
                        <i class="fas fa-tag mr-1"></i>
                        {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                    </span>
                    @if(!$notification->is_read)
                    <form action="{{ route('manager.notifications.read', $notification->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-check mr-1"></i>
                            Mark as read
                        </button>
                    </form>
                    @endif
                    
                    @if($notification->link)
                    <a href="{{ $notification->link }}" class="text-xs text-green-600 hover:text-green-800 font-medium">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        View Details
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Notifications</h3>
        <p class="text-gray-500">You're all caught up! Notifications will appear here.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($notifications->hasPages())
<div class="mt-8">
    {{ $notifications->links() }}
</div>
@endif
@endsection
