<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Reception Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'notifications'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
                            <p class="text-gray-600 text-sm">Stay updated with system alerts and messages</p>
                        </div>
                        @if($unreadCount > 0)
                        <form method="POST" action="{{ route('reception.notifications.readAll') }}">
                            @csrf
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-check-double mr-2"></i>Mark All as Read
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Unread Count Badge -->
                @if($unreadCount > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-center">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ $unreadCount }}
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold text-blue-900">You have {{ $unreadCount }} unread notification{{ $unreadCount > 1 ? 's' : '' }}</p>
                        <p class="text-sm text-blue-700">Click on notifications to mark them as read</p>
                    </div>
                </div>
                @endif

                <!-- Notifications List -->
                <div class="space-y-4">
                    @forelse($notifications as $notification)
                    <div class="bg-white rounded-xl shadow-sm border {{ $notification->is_read ? 'border-gray-100' : 'border-blue-200 bg-blue-50' }} hover:shadow-md transition">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <!-- Notification Icon -->
                                <div class="flex-shrink-0">
                                    @if(stripos($notification->type, 'booking') !== false)
                                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                                        </div>
                                    @elseif(stripos($notification->type, 'payment') !== false)
                                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-credit-card text-green-600 text-xl"></i>
                                        </div>
                                    @elseif(stripos($notification->type, 'check') !== false)
                                        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                            <i class="fas fa-door-open text-yellow-600 text-xl"></i>
                                        </div>
                                    @elseif(stripos($notification->type, 'alert') !== false || stripos($notification->type, 'warning') !== false)
                                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-bell text-blue-600 text-xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Notification Content -->
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <h3 class="text-lg font-bold text-gray-900">
                                            {{ $notification->title }}
                                            @if(!$notification->is_read)
                                                <span class="inline-block w-2 h-2 bg-blue-600 rounded-full ml-2"></span>
                                            @endif
                                        </h3>
                                        <span class="text-sm text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 mb-4">{{ $notification->message }}</p>
                                    
                                    <!-- Notification Data -->
                                    @if($notification->data)
                                        @php
                                            $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                                        @endphp
                                        @if(is_array($data) && !empty($data))
                                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                            @foreach($data as $key => $value)
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> {{ $value }}
                                                </p>
                                            @endforeach
                                        </div>
                                        @endif
                                    @endif

                                    <!-- Actions -->
                                    <div class="flex gap-3">
                                        @if(!$notification->is_read)
                                        <form method="POST" action="{{ route('reception.notifications.read', $notification->id) }}">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                                <i class="fas fa-check mr-1"></i>Mark as Read
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('reception.notifications.destroy', $notification->id) }}" 
                                            onsubmit="return confirm('Are you sure you want to delete this notification?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                        <i class="fas fa-bell-slash text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">No Notifications</h3>
                        <p class="text-gray-500">You're all caught up! Check back later for new updates.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
                @endif
            </main>
        </div>
    </div>
</body>
</html>
