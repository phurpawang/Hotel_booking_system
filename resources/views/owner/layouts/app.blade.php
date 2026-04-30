<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Owner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-700 text-white flex-shrink-0 hidden md:flex flex-col overflow-y-auto">
            <div class="p-4 border-b border-blue-800">
                <a href="{{ route('owner.dashboard') }}" class="flex flex-col items-center justify-center">
                    <img src="{{ asset('images/bhbs-logo.png') }}" alt="BHBS" style="height: 55px; width: 55px; border-radius: 50%; object-fit: cover; margin-bottom: 0.75rem;">
                    <p class="text-center text-sm font-semibold">{{ auth()->user()->hotel->name ?? 'Your Hotel' }}</p>
                    <span class="text-xs text-blue-200 mt-1">Owner</span>
                </a>
            </div>
            
            <nav class="p-3">
                <a href="{{ route('owner.dashboard') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.dashboard') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('owner.reservations.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.reservations.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="{{ route('owner.rooms.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.rooms.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-bed mr-3"></i>
                    <span>Rooms</span>
                </a>
                <a href="{{ route('owner.rates') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.rates') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-dollar-sign mr-3"></i>
                    <span>Rates & Availability</span>
                </a>
                <a href="{{ route('owner.guests.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.guests.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-users-cog mr-3"></i>
                    <span>Guests</span>
                </a>
                <a href="{{ route('owner.payments.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.payments.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-credit-card mr-3"></i>
                    <span>Payments</span>
                </a>
                <a href="{{ route('owner.reviews.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.reviews.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-star mr-3"></i>
                    <span>Reviews</span>
                </a>
                <a href="{{ route('owner.inquiries.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.inquiries.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-comments mr-3"></i>
                    <span>Guest Questions</span>
                </a>
                <a href="{{ route('owner.reports') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.reports') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
                <a href="{{ route('owner.staff.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.staff.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-user-tie mr-3"></i>
                    <span>Staff Management</span>
                </a>
                <a href="{{ route('owner.amenities.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.amenities.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-concierge-bell mr-3"></i>
                    <span>Amenities</span>
                </a>
                <a href="{{ route('owner.promotions.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.promotions.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-tags mr-3"></i>
                    <span>Promotions</span>
                </a>
                <a href="{{ route('owner.property.edit') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.property.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Property Settings</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('profile.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-user-circle mr-3"></i>
                    <span>Profile</span>
                </a>
                <a href="{{ route('owner.notifications.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.notifications.*') ? 'bg-blue-800' : 'hover:bg-blue-800' }} rounded-lg mb-1 transition relative group">
                    <i class="fas fa-bell mr-3"></i>
                    <span>Notifications</span>
                    @php
                        $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                            ->where('target_role', 'owner')
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    @if($unreadCount > 0)
                    <span class="absolute top-1 right-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center group-hover:bg-red-600 transition">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                    @endif
                </a>
                
                <div class="border-t border-blue-800 mt-2 pt-2">
                    <a href="{{ route('owner.deregistration.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('owner.deregistration.*') ? 'bg-blue-800' : 'hover:bg-red-600' }} rounded-lg mb-1 transition text-red-300 hover:text-white">
                        <i class="fas fa-building-circle-exclamation mr-3"></i>
                        <span>Deregistration</span>
                    </a>
                    <form method="POST" action="{{ route('hotel.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center px-3 py-2 hover:bg-red-600 rounded-lg w-full transition">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        @yield('header')
                    </div>
                    <div class="flex items-center space-x-6">
                        <!-- Notifications Bell Icon -->
                        <div class="relative group">
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                                    ->where('target_role', 'owner')
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            <a href="{{ route('owner.notifications.index') }}" class="relative text-gray-600 hover:text-gray-900 transition">
                                <i class="fas fa-bell text-xl"></i>
                                @if($unreadCount > 0)
                                <span class="absolute top-0 right-0 inline-block w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                                @endif
                            </a>
                        </div>
                        
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->format('l, F d, Y') }}</span>
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
