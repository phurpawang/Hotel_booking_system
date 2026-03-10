<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guests - Reception Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'guests'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Guest Management</h2>
                    <p class="text-gray-600 text-sm">View and manage guest information</p>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Search and Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <form method="GET" class="flex-1 w-full md:w-auto">
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search ?? '' }}" 
                                    placeholder="Search guests by name, email, or phone..." 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </form>
                        <a href="{{ route('reception.guests.create') }}" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                            <i class="fas fa-plus mr-2"></i>Add Guest
                        </a>
                    </div>
                </div>

                <!-- Guests Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bookings</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Visit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($guests as $guest)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-semibold">
                                                {{ substr($guest->name, 0, 1) }}
                                            </div>
                                            <span class="ml-3 text-sm font-medium text-gray-900">{{ $guest->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $guest->phone ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $guest->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $guest->bookings_count }} bookings
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if($guest->bookings->first() && $guest->bookings->first()->actual_check_out)
                                            {{ $guest->bookings->first()->actual_check_out->format('M d, Y') }}
                                        @elseif($guest->bookings->first())
                                            {{ $guest->bookings->first()->check_out_date?->format('M d, Y') ?? 'N/A' }}
                                        @else
                                            Never
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('reception.guests.show', $guest->id) }}" 
                                            class="text-purple-600 hover:text-purple-900 mr-3">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('reception.guests.edit', $guest->id) }}" 
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-users text-4xl mb-2"></i>
                                        <p>No guests found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($guests->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $guests->links() }}
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
