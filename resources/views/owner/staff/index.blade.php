<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Owner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-700 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6 border-b border-blue-800">
                <a href="{{ route('owner.dashboard') }}" class="flex items-center space-x-3">
                    <i class="fas fa-building text-3xl"></i>
                    <span class="text-xl font-bold">BHBS</span>
                </a>
            </div>
            
            <nav class="p-4 overflow-y-auto max-h-[calc(100vh-140px)]">
                <a href="{{ route('owner.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('owner.reservations.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="{{ route('owner.rooms.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-bed mr-3"></i>
                    <span>Rooms</span>
                </a>
                <a href="{{ route('owner.rates') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-dollar-sign mr-3"></i>
                    <span>Rates & Availability</span>
                </a>
                <a href="{{ route('owner.guests.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-users-cog mr-3"></i>
                    <span>Guests</span>
                </a>
                <a href="{{ route('owner.payments.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-credit-card mr-3"></i>
                    <span>Payments</span>
                </a>
                <a href="{{ route('owner.reviews.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-star mr-3"></i>
                    <span>Reviews</span>
                </a>
                <a href="{{ route('owner.reports') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
                <a href="{{ route('owner.staff.index') }}" class="flex items-center px-4 py-3 bg-blue-800 rounded-lg mb-2">
                    <i class="fas fa-user-tie mr-3"></i>
                    <span>Staff Management</span>
                </a>
                <a href="{{ route('owner.amenities.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-concierge-bell mr-3"></i>
                    <span>Amenities</span>
                </a>
                <a href="{{ route('owner.promotions.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-tags mr-3"></i>
                    <span>Promotions</span>
                </a>
                <a href="{{ route('owner.property.edit') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Property Settings</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-user-circle mr-3"></i>
                    <span>Profile</span>
                </a>
                <a href="{{ route('owner.notifications.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-bell mr-3"></i>
                    <span>Notifications</span>
                </a>
                
                <div class="border-t border-blue-800 mt-4 pt-4">
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
                        <h2 class="text-2xl font-bold text-gray-800">Staff Management</h2>
                        <p class="text-gray-600 text-sm">Manage your hotel staff members</p>
                    </div>
                    <a href="{{ route('owner.staff.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Staff
                    </a>
                </div>
            </header>

            <!-- Staff List -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($staff as $member)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $member->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->role == 'MANAGER')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Manager
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Reception
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $member->mobile ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('owner.staff.edit', $member->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('owner.staff.destroy', $member->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-users text-4xl mb-2"></i>
                                        <p>No staff members found</p>
                                        <a href="{{ route('owner.staff.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                            Add your first staff member
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($staff->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $staff->links() }}
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
