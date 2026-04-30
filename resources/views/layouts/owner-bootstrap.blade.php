<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Owner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Remove all borders and gaps */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            width: 100% !important;
            height: 100% !important;
        }
        
        .flex.h-screen {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            gap: 0 !important;
        }
        
        /* Sidebar styling - complete removal of any borders */
        aside {
            background: linear-gradient(to bottom, #1e3a8a 0%, #1e40af 100%) !important;
            color: white !important;
            border: none !important;
            border-top: none !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            box-shadow: none !important;
            outline: none !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 100vh !important;
        }
        
        /* Universal border removal for all sidebar elements */
        aside * {
            border: none !important;
            border-width: 0 !important;
            border-style: none !important;
            border-color: transparent !important;
            border-top: none !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            outline: none !important;
            text-decoration: none !important;
            box-shadow: none !important;
        }
        
        aside > div {
            border: none !important;
        }
        
        aside > div:first-child {
            padding: 1rem !important;
            border: none !important;
            margin: 0 !important;
        }
        
        aside nav {
            padding: 0.75rem !important;
            margin: 0 !important;
            border: none !important;
        }
        
        /* Explicitly remove all border variants */
        aside nav a {
            border: none !important;
            border-top: none !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            border-width: 0 !important;
            text-decoration: none !important;
            outline: none !important;
            box-shadow: none !important;
            display: flex !important;
        }
        
        aside nav a:hover,
        aside nav a:focus,
        aside nav a:active,
        aside nav a.active,
        aside nav a[class*="bg-blue"] {
            border: none !important;
            border-top: none !important;
            border-bottom: none !important;
            text-decoration: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
        
        /* Catch all border Tailwind classes */
        aside .border,
        aside .border-0,
        aside .border-t,
        aside .border-b,
        aside .border-l,
        aside .border-r,
        aside .border-x,
        aside .border-y,
        aside .border-t-2,
        aside .border-b-2,
        aside .border-l-2,
        aside .border-r-2,
        aside .border-t-4,
        aside .border-b-4,
        aside .border-l-4,
        aside .border-r-4,
        aside .border-t-8,
        aside .border-b-8,
        aside .border-l-8,
        aside .border-r-8 {
            border: none !important;
            border-width: 0 !important;
        }
        
        /* Header styling */
        header {
            border: none !important;
            border-top: none !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            box-shadow: none !important;
            margin: 0 !important;
        }
        
        /* Main content area */
        .flex-1 {
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        aside span,
        aside p,
        aside i {
            color: inherit !important;
            height: auto !important;
            border: none !important;
            text-decoration: none !important;
        }
        
        aside a {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s ease !important;
            border: none !important;
            text-decoration: none !important;
        }
        
        aside a:hover,
        aside a.bg-blue-800 {
            background-color: rgba(30, 58, 138, 0.8) !important;
            color: white !important;
            border: none !important;
            text-decoration: none !important;
        }
        
        aside .text-blue-200 {
            color: rgba(191, 219, 254, 1) !important;
        }
        
        /* Remove any divs with border classes */
        aside div[class*="border"] {
            border: none !important;
            border-width: 0 !important;
        }

        /* Additional styling for content areas */
        main {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        /* Card styling for better appearance */
        .table-card, .stat-card, .filter-card, .card {
            border-radius: 15px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
            border: none !important;
            transition: all 0.3s ease !important;
        }

        .table-card:hover, .stat-card:hover, .filter-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12) !important;
            transform: translateY(-2px) !important;
        }

        /* Table styling */
        table {
            border-collapse: collapse !important;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb !important;
            transition: background-color 0.2s ease !important;
        }

        tbody tr:hover {
            background-color: rgba(30, 58, 138, 0.05) !important;
        }

        thead {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%) !important;
            color: white !important;
        }

        /* Badge styling */
        .badge {
            border-radius: 20px !important;
            padding: 0.5rem 1rem !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        }

        /* Button styling */
        .btn {
            border-radius: 8px !important;
            padding: 0.5rem 1.2rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            border: none !important;
        }

        .btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15) !important;
        }

        /* Input and select styling */
        input[type="text"],
        input[type="email"],
        input[type="date"],
        select,
        textarea {
            background: white !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 8px !important;
            padding: 0.6rem 1rem !important;
            transition: all 0.3s ease !important;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        select:focus,
        textarea:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none !important;
        }

        /* Header styling */
        header {
            background: white !important;
            border-bottom: 2px solid #e5e7eb !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
        }

        /* Heading and text styling */
        h1, h2, h3, h4, h5, h6 {
            color: #1f2937 !important;
            font-weight: 700 !important;
        }

        .text-muted {
            color: #6b7280 !important;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-700 text-white flex-shrink-0 hidden md:flex flex-col overflow-y-auto">
            <div class="p-4">
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
                
                <div class="mt-2 pt-2">
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
            <header class="bg-white">
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
            <main class="bg-gray-50 min-h-screen overflow-auto">
                <div class="max-w-7xl mx-auto p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
