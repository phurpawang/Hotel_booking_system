<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Remove all borders and gaps from sidebar */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
        }
        
        .flex.h-screen {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            gap: 0 !important;
        }
        
        /* Sidebar styling - complete removal of any borders */
        aside {
            background: linear-gradient(to bottom, #065f46 0%, #047857 100%) !important;
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
        aside nav a[class*="bg-green"] {
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
        
        header {
            border: none !important;
            border-top: none !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            box-shadow: none !important;
            margin: 0 !important;
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
            background-color: rgba(16, 185, 129, 0.05) !important;
        }

        thead {
            background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
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
            border-color: #059669 !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
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

        /* Dashboard header styling */
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .dashboard-header h1, .dashboard-header h2 {
            color: white !important;
            margin: 0 !important;
        }

        .dashboard-header p {
            color: rgba(255, 255, 255, 0.9) !important;
            margin: 0.5rem 0 0 !important;
        }

        /* Filter section styling */
        .filter-section {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #667eea;
        }

        .filter-section h5 {
            color: #667eea !important;
            font-weight: 700 !important;
            margin-bottom: 1.5rem !important;
        }

        .filter-section .form-control,
        .filter-section .form-select {
            border-radius: 10px !important;
            border: 2px solid #e5e7eb !important;
            padding: 0.7rem 1.2rem !important;
            transition: all 0.3s ease !important;
        }

        .filter-section .form-control:focus,
        .filter-section .form-select:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15) !important;
            transform: translateY(-2px) !important;
        }

        .filter-section .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 0.7rem 1.5rem !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4) !important;
            transition: all 0.3s ease !important;
        }

        .filter-section .btn-primary:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5) !important;
        }

        /* Table card styling */
        .table-card {
            background: white !important;
            border-radius: 20px !important;
            padding: 2rem !important;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .table-card h4 {
            color: #667eea !important;
            font-weight: 700 !important;
        }

        /* Table styling */
        .table {
            border-collapse: separate !important;
            border-spacing: 0 0.8rem !important;
        }

        .table thead tr {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3) !important;
        }

        .table thead th {
            padding: 1.2rem !important;
            font-weight: 600 !important;
            border: none !important;
            letter-spacing: 0.5px !important;
        }

        .table thead tr th:first-child {
            border-radius: 12px 0 0 12px !important;
        }

        .table thead tr th:last-child {
            border-radius: 0 12px 12px 0 !important;
        }

        .table tbody tr {
            background: white !important;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08) !important;
            transition: all 0.3s ease !important;
            border: none !important;
        }

        .table tbody tr:hover {
            transform: translateY(-3px) scale(1.01) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%) !important;
        }

        .table tbody td {
            vertical-align: middle !important;
            padding: 1.2rem !important;
            border: none !important;
        }

        .table tbody tr td:first-child {
            border-radius: 12px 0 0 12px !important;
            border-left: 4px solid #667eea !important;
        }

        .table tbody tr td:last-child {
            border-radius: 0 12px 12px 0 !important;
        }

        /* Badge styling */
        .badge-confirmed {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            box-shadow: 0 3px 10px rgba(17, 153, 142, 0.3) !important;
            color: white !important;
        }

        .badge-pending {
            background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%) !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            color: #333 !important;
            box-shadow: 0 3px 10px rgba(255, 167, 81, 0.3) !important;
        }

        .badge-checked-in {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3) !important;
            color: white !important;
        }

        .badge-checked-out {
            background: linear-gradient(135deg, #868f96 0%, #596164 100%) !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            box-shadow: 0 3px 10px rgba(134, 143, 150, 0.3) !important;
            color: white !important;
        }

        .badge-cancelled {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%) !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            box-shadow: 0 3px 10px rgba(235, 51, 73, 0.3) !important;
            color: white !important;
        }

        .badge-paid {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 20px !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(17, 153, 142, 0.3) !important;
            color: white !important;
        }

        .badge-unpaid {
            background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 20px !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            color: #333 !important;
            box-shadow: 0 2px 8px rgba(255, 167, 81, 0.3) !important;
        }

        /* Action button styling */
        .action-btn {
            padding: 0.5rem 1rem !important;
            border-radius: 10px !important;
            border: none !important;
            font-size: 0.9rem !important;
            transition: all 0.3s ease !important;
            font-weight: 500 !important;
        }

        .action-btn:hover {
            transform: scale(1.08) !important;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2) !important;
        }

        .btn-checkin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
        }

        .btn-checkout {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
            color: white !important;
        }

        /* Modal styling */
        .modal-content {
            border-radius: 20px !important;
            border: none !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border-radius: 20px 20px 0 0 !important;
            padding: 1.5rem 2rem !important;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-green-900 to-green-700 text-white flex-shrink-0 hidden md:flex flex-col overflow-y-auto">
            <div class="p-4">
                <a href="{{ route('manager.dashboard') }}" class="flex flex-col items-center justify-center">
                    <img src="{{ asset('images/bhbs-logo.png') }}" alt="BHBS" style="height: 55px; width: 55px; border-radius: 50%; object-fit: cover; margin-bottom: 0.75rem;">
                    <p class="text-center text-sm font-semibold">{{ auth()->user()->hotel->name ?? 'Your Hotel' }}</p>
                    <span class="text-xs text-green-200 mt-1">Manager</span>
                </a>
            </div>
            
            <nav class="p-3">
                <a href="{{ route('manager.dashboard') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('manager.dashboard') ? 'bg-green-800' : 'hover:bg-green-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('manager.reservations.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('manager.reservations.*') ? 'bg-green-800' : 'hover:bg-green-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="{{ route('manager.rooms.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('manager.rooms.*') ? 'bg-green-800' : 'hover:bg-green-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-bed mr-3"></i>
                    <span>Rooms</span>
                </a>
                <a href="{{ route('manager.rates') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('manager.rates') ? 'bg-green-800' : 'hover:bg-green-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-dollar-sign mr-3"></i>
                    <span>Rates</span>
                </a>
                <a href="{{ route('manager.reports') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('manager.reports') ? 'bg-green-800' : 'hover:bg-green-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
                <a href="{{ route('manager.messages.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('manager.messages.*') ? 'bg-green-800' : 'hover:bg-green-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-envelope mr-3"></i>
                    <span>Messages</span>
                </a>
                <a href="{{ route('manager.reviews.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('manager.reviews.*') ? 'bg-green-800' : 'hover:bg-green-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-star mr-3"></i>
                    <span>Reviews</span>
                </a>
                <a href="{{ route('manager.property.edit') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('manager.property.*') ? 'bg-green-800' : 'hover:bg-green-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Property Settings</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('profile.*') ? 'bg-green-800' : 'hover:bg-green-800' }} rounded-lg mb-1 transition">
                    <i class="fas fa-user-circle mr-3"></i>
                    <span>Profile</span>
                </a>
                <a href="{{ route('manager.notifications.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('manager.notifications.*') ? 'bg-green-800' : 'hover:bg-green-800' }} rounded-lg mb-1 transition relative group">
                    <i class="fas fa-bell mr-3"></i>
                    <span>Notifications</span>
                    @php
                        $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                            ->where('target_role', 'manager')
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
                    <a href="{{ route('manager.deregistration.index') }}" class="flex items-center px-3 py-2 {{ request()->routeIs('manager.deregistration.*') ? 'bg-green-800' : 'hover:bg-red-600' }} rounded-lg mb-1 transition text-red-300 hover:text-white">
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
                                    ->where('target_role', 'manager')
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            <a href="{{ route('manager.notifications.index') }}" class="relative text-gray-600 hover:text-gray-900 transition">
                                <i class="fas fa-bell text-xl"></i>
                                @if($unreadCount > 0)
                                <span class="absolute top-0 right-0 inline-block w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                                @endif
                            </a>
                        </div>
                        
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->format('l, F d, Y') }}</span>
                        <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="bg-gray-50 min-h-screen overflow-auto">
                <div class="max-w-7xl mx-auto p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
