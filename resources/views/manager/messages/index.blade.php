<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - {{ $hotel->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-green-900 text-white flex-shrink-0">
            <div class="p-6 border-b border-green-800">
                <a href="{{ route('manager.dashboard') }}" class="block">
                    <h1 class="text-2xl font-bold hover:text-green-300 transition cursor-pointer">
                        <i class="fas fa-building mr-2"></i>BHBS
                    </h1>
                </a>
                <p class="text-sm text-green-200 mt-1">{{ $hotel->name }}</p>
                <span class="text-xs bg-green-700 px-2 py-1 rounded mt-2 inline-block">Manager</span>
            </div>
            
            <nav class="p-4">
                <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('manager.reservations.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="{{ route('manager.rooms.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-bed mr-3"></i>
                    <span>Rooms</span>
                </a>
                <a href="{{ route('manager.rates') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-dollar-sign mr-3"></i>
                    <span>Rates</span>
                </a>
                <a href="{{ route('manager.reports.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
                <a href="{{ route('manager.property.edit') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Property Settings</span>
                </a>
                <a href="{{ route('manager.messages.index') }}" class="flex items-center px-4 py-3 bg-green-800 rounded-lg mb-2">
                    <i class="fas fa-envelope mr-3"></i>
                    <span>Messages</span>
                </a>
                <a href="{{ route('manager.deregistration.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Deregistration</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-user mr-3"></i>
                    <span>Profile</span>
                </a>
                <form method="POST" action="{{ route('hotel.logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-3 hover:bg-red-800 rounded-lg mb-2 transition w-full text-left">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Messages</h2>
                    <p class="text-gray-600">View and manage guest messages</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <!-- Messages Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-4 rounded-lg">
                                <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Total Messages</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $messages->total() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="bg-orange-100 p-4 rounded-lg">
                                <i class="fas fa-envelope-open text-orange-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600 text-sm">Unread Messages</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $messages->where('status', 'UNREAD')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages List -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @forelse($messages as $message)
                    <div class="border-b border-gray-200 p-6 {{ $message->status == 'UNREAD' ? 'bg-blue-50' : '' }} hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-user-circle text-gray-400 text-lg mr-2"></i>
                                    <h3 class="font-semibold text-gray-800">{{ $message->guest_name }}</h3>
                                    @if($message->status == 'UNREAD')
                                    <span class="ml-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">New</span>
                                    @endif
                                </div>
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                    <span>{{ $message->guest_email }}</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock text-gray-400 mr-2"></i>
                                    <span>{{ $message->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <p class="text-gray-700 leading-relaxed">{{ $message->message }}</p>
                            </div>
                            <div class="ml-4 flex flex-col gap-2">
                                @if($message->status == 'UNREAD')
                                <form method="POST" action="{{ route('manager.messages.markAsRead', $message->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition">
                                        <i class="fas fa-check mr-1"></i>Mark as Read
                                    </button>
                                </form>
                                @else
                                <span class="bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm text-center">
                                    <i class="fas fa-check-double mr-1"></i>Read
                                </span>
                                @endif
                                <form method="POST" action="{{ route('manager.messages.destroy', $message->id) }}" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition w-full">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No messages yet</p>
                        <p class="text-gray-400 text-sm mt-2">Guest messages will appear here</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($messages->hasPages())
                <div class="mt-6">
                    {{ $messages->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
