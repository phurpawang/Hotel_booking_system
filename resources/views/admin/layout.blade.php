<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - BHBS Admin</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-hotel"></i> Hotel Admin</h2>
            </div>
            
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.hotels.index') }}" class="menu-item {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
                    <i class="fas fa-hotel"></i>
                    <span>Hotels</span>
                </a>
                
                <a href="{{ route('admin.reservations.index') }}" class="menu-item {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Reservations</span>
                </a>
                
                <a href="{{ route('admin.payments.index') }}" class="menu-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
                
                <a href="{{ route('admin.reports') }}" class="menu-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Reports</span>
                </a>
                
                <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                
                <form action="{{ route('admin.logout') }}" method="POST" style="margin-top: auto;">
                    @csrf
                    <button type="submit" class="menu-item logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <header class="top-navbar">
                <div class="navbar-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                </div>
                
                <div class="navbar-right">
                    <div class="notification-dropdown">
                        <button class="notification-icon" id="notificationBtn">
                            <i class="fas fa-bell"></i>
                            @php
                                $pendingBookings = \App\Models\Booking::where('status', 'PENDING')->count();
                                $pendingPayments = \App\Models\Booking::where('payment_status', 'PENDING')->count();
                                $notificationCount = $pendingBookings + $pendingPayments;
                            @endphp
                            @if($notificationCount > 0)
                                <span class="badge">{{ $notificationCount }}</span>
                            @endif
                        </button>
                        
                        <div class="notification-panel" id="notificationPanel">
                            <div class="notification-header">
                                <h4>Notifications</h4>
                                <span class="notification-count">{{ $notificationCount }} new</span>
                            </div>
                            
                            <div class="notification-list">
                                @php
                                    $recentBookings = \App\Models\Booking::where('status', 'PENDING')
                                        ->latest()
                                        ->take(5)
                                        ->get();
                                    $recentPayments = \App\Models\Booking::where('payment_status', 'PENDING')
                                        ->where('status', '!=', 'CANCELLED')
                                        ->latest()
                                        ->take(5)
                                        ->get();
                                @endphp
                                
                                @if($pendingBookings > 0)
                                    @foreach($recentBookings as $booking)
                                        <a href="{{ route('admin.reservations.show', $booking->id) }}" class="notification-item">
                                            <div class="notification-icon-wrapper pending">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p class="notification-title">New Booking Request</p>
                                                <p class="notification-text">{{ $booking->guest_name }} - {{ $booking->hotel->name ?? 'N/A' }}</p>
                                                <span class="notification-time">{{ $booking->created_at->diffForHumans() }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                                
                                @if($pendingPayments > 0)
                                    @foreach($recentPayments as $payment)
                                        <a href="{{ route('admin.payments.index') }}?payment_status=PENDING" class="notification-item">
                                            <div class="notification-icon-wrapper warning">
                                                <i class="fas fa-credit-card"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p class="notification-title">Pending Payment</p>
                                                <p class="notification-text">{{ $payment->guest_name }} - Nu. {{ number_format($payment->total_price, 2) }}</p>
                                                <span class="notification-time">{{ $payment->created_at->diffForHumans() }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                                
                                @if($notificationCount == 0)
                                    <div class="notification-empty">
                                        <i class="fas fa-bell-slash"></i>
                                        <p>No new notifications</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="notification-footer">
                                <a href="{{ route('admin.reservations.index') }}">View All Reservations</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="admin-dropdown">
                        <button class="admin-profile">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ session('admin_username', 'Admin') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <div class="dropdown-menu">
                            <a href="#"><i class="fas fa-user"></i> Profile</a>
                            <a href="#"><i class="fas fa-cog"></i> Settings</a>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="page-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Custom Confirmation Modal -->
    <div id="confirmModal" class="confirm-modal">
        <div class="confirm-modal-content">
            <div class="confirm-modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="confirm-modal-title">Confirm Action</h3>
            <p class="confirm-modal-message" id="confirmMessage"></p>
            <div class="confirm-modal-buttons">
                <button type="button" class="confirm-btn-cancel" id="confirmCancel">Cancel</button>
                <button type="button" class="confirm-btn-ok" id="confirmOk">Yes, Delete</button>
            </div>
        </div>
    </div>

    <script>
        // Custom Confirmation Modal
        let confirmCallback = null;
        const confirmModal = document.getElementById('confirmModal');
        const confirmMessage = document.getElementById('confirmMessage');
        const confirmOk = document.getElementById('confirmOk');
        const confirmCancel = document.getElementById('confirmCancel');

        window.showConfirm = function(message, callback) {
            confirmMessage.textContent = message;
            confirmModal.classList.add('show');
            confirmCallback = callback;
        };

        confirmOk.addEventListener('click', function() {
            confirmModal.classList.remove('show');
            if (confirmCallback) {
                confirmCallback(true);
                confirmCallback = null;
            }
        });

        confirmCancel.addEventListener('click', function() {
            confirmModal.classList.remove('show');
            if (confirmCallback) {
                confirmCallback(false);
                confirmCallback = null;
            }
        });

        // Close modal when clicking outside
        confirmModal.addEventListener('click', function(e) {
            if (e.target === confirmModal) {
                confirmModal.classList.remove('show');
                if (confirmCallback) {
                    confirmCallback(false);
                    confirmCallback = null;
                }
            }
        });

        // Handle all delete form submissions
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('button[data-confirm]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const message = this.getAttribute('data-confirm');
                    const form = this.closest('form');
                    
                    showConfirm(message, function(confirmed) {
                        if (confirmed && form) {
                            form.submit();
                        }
                    });
                });
            });
        });

        // Sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        });

        // Notification dropdown toggle
        document.getElementById('notificationBtn')?.addEventListener('click', function(e) {
            e.stopPropagation();
            const panel = document.getElementById('notificationPanel');
            panel.classList.toggle('show');
            
            // Close admin dropdown if open
            document.querySelector('.dropdown-menu')?.classList.remove('show');
        });

        // Admin dropdown toggle
        document.querySelector('.admin-profile')?.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelector('.dropdown-menu').classList.toggle('show');
            
            // Close notification panel if open
            document.getElementById('notificationPanel')?.classList.remove('show');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.admin-dropdown')) {
                document.querySelector('.dropdown-menu')?.classList.remove('show');
            }
            if (!event.target.closest('.notification-dropdown')) {
                document.getElementById('notificationPanel')?.classList.remove('show');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
