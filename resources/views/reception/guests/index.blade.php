<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guests - Reception Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .guest-avatar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .guest-avatar.avatar-red {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .guest-avatar.avatar-green {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .guest-avatar.avatar-orange {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        .guest-avatar.avatar-pink {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        }
        .table-row-hover {
            transition: all 0.3s ease;
        }
        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: translateX(4px);
        }
        .badge-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .badge-booking {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'guests'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="header-gradient shadow-lg">
                <div class="px-8 py-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-4xl font-bold text-white">👥 Guest Management</h2>
                            <p class="text-purple-100 text-sm mt-2">View and manage guest information</p>
                        </div>
                        <div class="text-right">
                            <p class="text-6xl font-bold text-white opacity-20">{{ $guests->total() ?? 0 }}</p>
                            <p class="text-purple-100 text-sm">Total Guests</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-gradient-to-r from-green-400 to-green-600 border-0 text-white px-6 py-4 rounded-xl mb-6 shadow-lg flex items-center">
                    <i class="fas fa-check-circle mr-3 text-lg"></i>
                    {{ session('success') }}
                </div>
                @endif

                <!-- Search and Actions -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                        <form method="GET" class="flex-1 w-full md:w-auto">
                            <div class="relative group">
                                <i class="fas fa-search absolute left-4 top-4 text-gray-400 group-focus-within:text-purple-600 transition"></i>
                                <input type="text" name="search" value="{{ $search ?? '' }}" 
                                    placeholder="Search guests by name, email, or phone..." 
                                    class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-gray-700 placeholder-gray-400">
                            </div>
                        </form>
                        <a href="{{ route('reception.guests.create') }}" 
                            class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-8 py-3 rounded-xl transition shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold flex items-center whitespace-nowrap">
                            <i class="fas fa-plus mr-2"></i>Add Guest
                        </a>
                    </div>
                </div>

                <!-- Guests Table -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="header-gradient text-white">
                                    <th class="px-6 py-4 text-left text-sm font-semibold">GUEST NAME</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">PHONE</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">EMAIL</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">TOTAL BOOKINGS</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">LAST VISIT</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($guests as $guest)
                                <tr class="table-row-hover">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $colors = ['', 'avatar-red', 'avatar-green', 'avatar-orange', 'avatar-pink'];
                                                $colorIndex = (ord(substr($guest->name, 0, 1)) % 5);
                                            @endphp
                                            <div class="w-12 h-12 rounded-lg guest-avatar {{ $colors[$colorIndex] }} flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                {{ substr($guest->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $guest->name }}</p>
                                                <p class="text-xs text-gray-500">Guest ID: #{{ $guest->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">
                                            @if($guest->phone)
                                                <i class="fas fa-phone text-purple-600 mr-2"></i>{{ $guest->phone }}
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">
                                            <i class="fas fa-envelope text-blue-600 mr-2"></i>{{ $guest->email }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        @php
                                            $bookingCount = $guest->bookings_count ?? 0;
                                            $badgeColor = $bookingCount >= 5 ? 'from-green-400 to-green-600' : ($bookingCount >= 3 ? 'from-blue-400 to-blue-600' : 'from-yellow-400 to-yellow-600');
                                        @endphp
                                        <span class="px-4 py-2 text-sm font-bold text-white rounded-full bg-gradient-to-r {{ $badgeColor }} shadow-md inline-flex items-center gap-1">
                                            <i class="fas fa-bookmark"></i> {{ $bookingCount }} booking{{ $bookingCount != 1 ? 's' : '' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm">
                                            @if($guest->bookings->first() && $guest->bookings->first()->actual_check_out)
                                                <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                                                <span class="font-semibold text-gray-900">{{ $guest->bookings->first()->actual_check_out->format('M d, Y') }}</span>
                                            @elseif($guest->bookings->first())
                                                <i class="fas fa-calendar text-blue-600 mr-2"></i>
                                                <span class="font-semibold text-gray-900">{{ $guest->bookings->first()->check_out_date?->format('M d, Y') ?? 'N/A' }}</span>
                                            @else
                                                <span class="text-gray-400 italic">Never</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex gap-3">
                                            <a href="{{ route('reception.guests.show', $guest->id) }}" 
                                                class="inline-flex items-center gap-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white px-3 py-2 rounded-lg hover:from-purple-600 hover:to-purple-700 transition shadow-md hover:shadow-lg transform hover:scale-105 text-xs font-semibold">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('reception.guests.edit', $guest->id) }}" 
                                                class="inline-flex items-center gap-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-md hover:shadow-lg transform hover:scale-105 text-xs font-semibold">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center mb-4">
                                                <i class="fas fa-users text-4xl text-gray-400"></i>
                                            </div>
                                            <p class="text-gray-500 font-semibold text-lg">No guests found</p>
                                            <p class="text-gray-400 text-sm mt-1">Start by adding your first guest</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($guests->hasPages())
                    <div class="px-6 py-6 border-t border-gray-100 bg-gray-50">
                        <div class="flex justify-center">
                            {{ $guests->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
