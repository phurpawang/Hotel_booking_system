<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Arrivals - {{ Auth::user()->hotel->name ?? 'BHBS' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-purple-900 text-white flex-shrink-0">
            <div class="p-6 border-b border-purple-800">
                <h1 class="text-2xl font-bold">BHBS</h1>
                <p class="text-sm text-purple-200 mt-1">{{ Auth::user()->hotel->name ?? 'Hotel Name' }}</p>
                <span class="text-xs bg-purple-700 px-2 py-1 rounded mt-2 inline-block">Receptionist</span>
            </div>
            
            <nav class="p-4">
                <a href="{{ route('reception.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-purple-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('reception.reservations.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-800 rounded-lg mb-2 transition">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span>Bookings</span>
                </a>
                <a href="{{ route('reception.checkin') }}" class="flex items-center px-4 py-3 bg-purple-800 rounded-lg mb-2">
                    <i class="fas fa-sign-in-alt mr-3"></i>
                    <span>Check-in</span>
                </a>
                <a href="{{ route('reception.checkout') }}" class="flex items-center px-4 py-3 hover:bg-purple-800 rounded-lg mb-2 transition">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Check-out</span>
                </a>
                
                <div class="border-t border-purple-800 mt-4 pt-4">
                    <form method="POST" action="{{ route('hotel.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center px-4 py-3 hover:bg-red-600 rounded-lg w-full transition">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Today's Check-ins</h2>
                        <p class="text-gray-600 text-sm">{{ \Carbon\Carbon::now()->format('l, F d, Y') }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                        <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
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

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
                @endif

                <!-- Arrivals List -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Expected Arrivals ({{ $arrivals->count() }})</h3>
                    </div>
                    
                    @if($arrivals->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($arrivals as $arrival)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $arrival->booking_id ?? $arrival->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $arrival->guest_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $arrival->room->room_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($arrival->check_in_date)->format('h:i A') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $arrival->num_guests ?? 0 }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if(strtoupper($arrival->status) == 'CONFIRMED')
                                            <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded">Pending</span>
                                        @elseif(strtoupper($arrival->status) == 'CHECKED_IN')
                                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">Checked In</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded">{{ $arrival->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if(strtoupper($arrival->status) == 'CONFIRMED')
                                        <form method="POST" action="{{ route('reception.checkin.process', $arrival->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-xs font-semibold transition">
                                                <i class="fas fa-check mr-1"></i> Check In
                                            </button>
                                        </form>
                                        @else
                                            <span class="text-gray-400 text-xs">Already checked in</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-12 text-center">
                        <i class="fas fa-calendar-check text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">No arrivals expected today.</p>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
