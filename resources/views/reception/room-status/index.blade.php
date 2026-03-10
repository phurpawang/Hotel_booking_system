<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Status - Reception Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'room-status'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Room Status Overview</h2>
                    <p class="text-gray-600 text-sm">Monitor and update room statuses</p>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border-2 border-gray-200 p-6 text-center">
                        <i class="fas fa-door-open text-4xl text-gray-500 mb-2"></i>
                        <div class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                        <div class="text-sm text-gray-600">Total Rooms</div>
                    </div>

                    <div class="bg-green-50 rounded-xl shadow-sm border-2 border-green-500 p-6 text-center">
                        <i class="fas fa-check-circle text-4xl text-green-600 mb-2"></i>
                        <div class="text-3xl font-bold text-green-700">{{ $stats['available'] }}</div>
                        <div class="text-sm text-green-700">Available</div>
                    </div>

                    <div class="bg-red-50 rounded-xl shadow-sm border-2 border-red-500 p-6 text-center">
                        <i class="fas fa-bed text-4xl text-red-600 mb-2"></i>
                        <div class="text-3xl font-bold text-red-700">{{ $stats['occupied'] }}</div>
                        <div class="text-sm text-red-700">Occupied</div>
                    </div>

                    <div class="bg-yellow-50 rounded-xl shadow-sm border-2 border-yellow-500 p-6 text-center">
                        <i class="fas fa-broom text-4xl text-yellow-600 mb-2"></i>
                        <div class="text-3xl font-bold text-yellow-700">{{ $stats['cleaning'] }}</div>
                        <div class="text-sm text-yellow-700">Cleaning</div>
                    </div>

                    <div class="bg-orange-50 rounded-xl shadow-sm border-2 border-orange-500 p-6 text-center">
                        <i class="fas fa-tools text-4xl text-orange-600 mb-2"></i>
                        <div class="text-3xl font-bold text-orange-700">{{ $stats['maintenance'] }}</div>
                        <div class="text-sm text-orange-700">Maintenance</div>
                    </div>
                </div>

                <!-- Status Legend -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <div class="flex flex-wrap gap-6 justify-center">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-sm text-gray-700">Available</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-red-500 mr-2"></div>
                            <span class="text-sm text-gray-700">Occupied</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-yellow-500 mr-2"></div>
                            <span class="text-sm text-gray-700">Cleaning</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full bg-orange-500 mr-2"></div>
                            <span class="text-sm text-gray-700">Maintenance</span>
                        </div>
                    </div>
                </div>

                <!-- Room Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($rooms as $room)
                    <div class="bg-white rounded-xl shadow-sm border-2 
                        @if(strtoupper($room->status) == 'AVAILABLE') border-green-500
                        @elseif(strtoupper($room->status) == 'OCCUPIED') border-red-500
                        @elseif(strtoupper($room->status) == 'CLEANING') border-yellow-500
                        @else border-orange-500
                        @endif
                        p-6 hover:shadow-lg transition">
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg
                                    @if(strtoupper($room->status) == 'AVAILABLE') bg-green-500
                                    @elseif(strtoupper($room->status) == 'OCCUPIED') bg-red-500
                                    @elseif(strtoupper($room->status) == 'CLEANING') bg-yellow-500
                                    @else bg-orange-500
                                    @endif">
                                    {{ $room->room_number }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900">Room {{ $room->room_number }}</h3>
                                    <p class="text-sm text-gray-600">{{ $room->room_type }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                @if(strtoupper($room->status) == 'AVAILABLE') bg-green-100 text-green-800
                                @elseif(strtoupper($room->status) == 'OCCUPIED') bg-red-100 text-red-800
                                @elseif(strtoupper($room->status) == 'CLEANING') bg-yellow-100 text-yellow-800
                                @else bg-orange-100 text-orange-800
                                @endif">
                                <i class="fas fa-circle text-xs mr-1"></i>
                                {{ ucfirst(strtolower($room->status)) }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-600 mb-4">
                            <div class="flex justify-between mb-1">
                                <span>Capacity:</span>
                                <span class="font-semibold">{{ $room->capacity }} guests</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Price:</span>
                                <span class="font-semibold">Nu. {{ number_format($room->price, 2) }}</span>
                            </div>
                        </div>

                        <!-- Current Guest (if occupied) -->
                        @if($room->bookings->isNotEmpty())
                            @php $booking = $room->bookings->first(); @endphp
                            <div class="bg-gray-50 rounded-lg p-3 mb-4 text-sm">
                                <p class="text-gray-700 font-semibold mb-1">Current Guest:</p>
                                <p class="text-gray-600">{{ $booking->guest_name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Check-out: {{ $booking->check_out_date?->format('M d, Y') ?? 'N/A' }}
                                </p>
                            </div>
                        @endif

                        <!-- Change Status Form -->
                        <form method="POST" action="{{ route('reception.room-status.update', $room->id) }}" class="mt-4">
                            @csrf
                            <div class="flex gap-2">
                                <select name="status" class="flex-1 text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="AVAILABLE" {{ strtoupper($room->status) == 'AVAILABLE' ? 'selected' : '' }}>Available</option>
                                    <option value="OCCUPIED" {{ strtoupper($room->status) == 'OCCUPIED' ? 'selected' : '' }}>Occupied</option>
                                    <option value="CLEANING" {{ strtoupper($room->status) == 'CLEANING' ? 'selected' : '' }}>Cleaning</option>
                                    <option value="MAINTENANCE" {{ strtoupper($room->status) == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-1.5 rounded-lg text-sm transition">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>

                @if($rooms->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <i class="fas fa-door-closed text-6xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 text-lg">No rooms found</p>
                </div>
                @endif
            </main>
        </div>
    </div>
</body>
</html>
