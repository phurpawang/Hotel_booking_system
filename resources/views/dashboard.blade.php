<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BHBS - Hotel Dashboard</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: #fff;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        .brand-name {
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .user-info {
            color: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .user-role {
            display: inline-block;
            background-color: rgba(255,255,255,0.2);
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-top: 5px;
        }
        .main-content {
            padding: 30px;
        }
        .stats-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-card .card-body {
            padding: 25px;
        }
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
        }
        .bg-primary-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bg-success-gradient {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .bg-warning-gradient {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .bg-info-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .page-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 30px;
        }
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .badge {
            padding: 6px 12px;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="brand-name">
                    <img src="{{ asset('images/bhbs-logo.png') }}" alt="BHBS" style="height: 40px; width: 40px; border-radius: 50%; object-fit: cover;">
                </div>
                
                <div class="user-info">
                    <div><strong>{{ auth()->user()->name }}</strong></div>
                    <span class="user-role">{{ auth()->user()->role }}</span>
                </div>

                <nav class="nav flex-column mt-3">
                    <!-- Common Dashboard Link -->
                    <a class="nav-link active" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>

                    @if(auth()->user()->role == 'ADMIN')
                        <!-- Admin Menu -->
                        <a class="nav-link" href="{{ route('admin.hotels') }}">
                            <i class="bi bi-building-check"></i> Hotel Approvals
                        </a>
                        <a class="nav-link" href="{{ route('admin.hotels.all') }}">
                            <i class="bi bi-buildings"></i> All Hotels
                        </a>
                        <a class="nav-link" href="{{ route('admin.reports') }}">
                            <i class="bi bi-graph-up"></i> System Reports
                        </a>
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="bi bi-people"></i> Manage Users
                        </a>

                    @elseif(auth()->user()->role == 'OWNER')
                        <!-- Owner Menu -->
                        <a class="nav-link" href="{{ route('owner.hotel') }}">
                            <i class="bi bi-building"></i> My Hotel
                        </a>
                        <a class="nav-link" href="{{ route('owner.rooms.index') }}">
                            <i class="bi bi-door-open"></i> Manage Rooms
                        </a>
                        <a class="nav-link" href="{{ route('owner.reservations.index') }}">
                            <i class="bi bi-calendar-check"></i> Bookings
                        </a>
                        <a class="nav-link" href="{{ route('owner.reports') }}">
                            <i class="bi bi-cash-stack"></i> Financial Reports
                        </a>
                        <a class="nav-link" href="{{ route('owner.settings') }}">
                            <i class="bi bi-gear"></i> Hotel Settings
                        </a>

                    @elseif(auth()->user()->role == 'MANAGER')
                        <!-- Manager Menu -->
                        <a class="nav-link" href="{{ route('manager.reservations.index') }}">
                            <i class="bi bi-calendar-check"></i> Bookings
                        </a>
                        <a class="nav-link" href="{{ route('manager.rooms.index') }}">
                            <i class="bi bi-door-open"></i> Manage Rooms
                        </a>
                        <a class="nav-link" href="{{ route('manager.rates') }}">
                            <i class="bi bi-tag"></i> Room Pricing
                        </a>
                        <a class="nav-link" href="{{ route('manager.reports') }}">
                            <i class="bi bi-file-text"></i> Reports
                        </a>

                    @elseif(auth()->user()->role == 'RECEPTION')
                        <!-- Reception Menu -->
                        <a class="nav-link" href="{{ route('reception.reservations.index') }}">
                            <i class="bi bi-calendar-check"></i> Bookings
                        </a>
                        <a class="nav-link" href="{{ route('reception.checkin') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Check-In
                        </a>
                        <a class="nav-link" href="{{ route('reception.checkout') }}">
                            <i class="bi bi-box-arrow-right"></i> Check-Out
                        </a>
                        <a class="nav-link" href="{{ route('reception.rooms.index') }}">
                            <i class="bi bi-door-open"></i> Rooms
                        </a>
                    @endif

                    <!-- Profile & Logout -->
                    <hr style="border-color: rgba(255,255,255,0.2); margin: 20px 15px;">
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person-circle"></i> Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <h2 class="page-title">
                    {{ auth()->user()->role }} Dashboard
                </h2>

                <!-- ADMIN Dashboard -->
                @if(auth()->user()->role == 'ADMIN')
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Total Hotels</h6>
                                            <h3 class="mb-0">{{ $data['totalHotels'] ?? 0 }}</h3>
                                        </div>
                                        <div class="stats-icon bg-primary-gradient">
                                            <i class="bi bi-building"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Pending Approvals</h6>
                                            <h3 class="mb-0">{{ $data['pendingHotels'] ?? 0 }}</h3>
                                        </div>
                                        <div class="stats-icon bg-warning-gradient">
                                            <i class="bi bi-clock-history"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Approved Hotels</h6>
                                            <h3 class="mb-0">{{ $data['approvedHotels'] ?? 0 }}</h3>
                                        </div>
                                        <div class="stats-icon bg-success-gradient">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Total Bookings</h6>
                                            <h3 class="mb-0">{{ $data['totalBookings'] ?? 0 }}</h3>
                                        </div>
                                        <div class="stats-icon bg-info-gradient">
                                            <i class="bi bi-calendar-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Hotels Table -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Pending Hotel Approvals</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Hotel Name</th>
                                            <th>Owner</th>
                                            <th>Location</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data['pendingHotels_list'] ?? [] as $hotel)
                                        <tr>
                                            <td><strong>{{ $hotel->name }}</strong></td>
                                            <td>{{ $hotel->owner->name }}</td>
                                            <td>{{ $hotel->address }}</td>
                                            <td>{{ $hotel->email }}</td>
                                            <td><span class="badge bg-warning">{{ $hotel->status }}</span></td>
                                            <td>
                                                <form action="{{ route('admin.hotel.approve', $hotel->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bi bi-check"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.hotel.reject', $hotel->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-x"></i> Reject
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">No pending approvals</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <!-- OWNER Dashboard -->
                @elseif(auth()->user()->role == 'OWNER')
                    @if($data['hasHotel'] ?? false)
                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Total Rooms</h6>
                                                <h3 class="mb-0">{{ $data['totalRooms'] ?? 0 }}</h3>
                                            </div>
                                            <div class="stats-icon bg-primary-gradient">
                                                <i class="bi bi-door-open"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Available Rooms</h6>
                                                <h3 class="mb-0">{{ $data['availableRooms'] ?? 0 }}</h3>
                                            </div>
                                            <div class="stats-icon bg-success-gradient">
                                                <i class="bi bi-check-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Total Revenue</h6>
                                                <h3 class="mb-0">Nu. {{ number_format($data['totalRevenue'] ?? 0, 2) }}</h3>
                                            </div>
                                            <div class="stats-icon bg-warning-gradient">
                                                <i class="bi bi-cash-stack"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Monthly Revenue</h6>
                                                <h3 class="mb-0">Nu. {{ number_format($data['monthlyRevenue'] ?? 0, 2) }}</h3>
                                            </div>
                                            <div class="stats-icon bg-info-gradient">
                                                <i class="bi bi-graph-up"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> {{ $data['message'] ?? 'No hotel data available.' }}
                        </div>
                    @endif

                <!-- MANAGER Dashboard -->
                @elseif(auth()->user()->role == 'MANAGER')
                    @if($data['hasHotel'] ?? false)
                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Total Bookings</h6>
                                                <h3 class="mb-0">{{ $data['totalBookings'] ?? 0 }}</h3>
                                            </div>
                                            <div class="stats-icon bg-primary-gradient">
                                                <i class="bi bi-calendar-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Confirmed</h6>
                                                <h3 class="mb-0">{{ $data['confirmedBookings'] ?? 0 }}</h3>
                                            </div>
                                            <div class="stats-icon bg-info-gradient">
                                                <i class="bi bi-check2-square"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Checked In</h6>
                                                <h3 class="mb-0">{{ $data['checkedInBookings'] ?? 0 }}</h3>
                                            </div>
                                            <div class="stats-icon bg-success-gradient">
                                                <i class="bi bi-box-arrow-in-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Available Rooms</h6>
                                                <h3 class="mb-0">{{ $data['availableRooms'] ?? 0 }}</h3>
                                            </div>
                                            <div class="stats-icon bg-warning-gradient">
                                                <i class="bi bi-door-open"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> {{ $data['message'] ?? 'No hotel assigned.' }}
                        </div>
                    @endif

                <!-- RECEPTION Dashboard -->
                @elseif(auth()->user()->role == 'RECEPTION')
                    @if($data['hasHotel'] ?? false)
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Today's Arrivals</h6>
                                                <h3 class="mb-0">{{ $data['todayArrivalsCount'] ?? 0 }}</h3>
                                            </div>
                                            <div class="stats-icon bg-info-gradient">
                                                <i class="bi bi-arrow-down-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Today's Departures</h6>
                                                <h3 class="mb-0">{{ $data['todayDeparturesCount'] ?? 0 }}</h3>
                                            </div>
                                            <div class="stats-icon bg-warning-gradient">
                                                <i class="bi bi-arrow-up-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Current Guests</h6>
                                                <h3 class="mb-0">{{ $data['currentGuests'] ?? 0 }}</h3>
                                            </div>
                                            <div class="stats-icon bg-success-gradient">
                                                <i class="bi bi-people"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Today's Arrivals -->
                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Today's Arrivals</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Guest Name</th>
                                                <th>Room</th>
                                                <th>Phone</th>
                                                <th>Check-In Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($data['todayArrivals'] ?? [] as $booking)
                                            <tr>
                                                <td><strong>{{ $booking->guest_name }}</strong></td>
                                                <td>{{ $booking->room->room_number }}</td>
                                                <td>{{ $booking->guest_phone }}</td>
                                                <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
                                                <td>
                                                    <form action="{{ route('booking.status', $booking->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="CHECKED_IN">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-box-arrow-in-right"></i> Check In
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">No arrivals today</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Today's Departures -->
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Today's Departures</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Guest Name</th>
                                                <th>Room</th>
                                                <th>Phone</th>
                                                <th>Check-Out Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($data['todayDepartures'] ?? [] as $booking)
                                            <tr>
                                                <td><strong>{{ $booking->guest_name }}</strong></td>
                                                <td>{{ $booking->room->room_number }}</td>
                                                <td>{{ $booking->guest_phone }}</td>
                                                <td>{{ $booking->check_out_date->format('M d, Y') }}</td>
                                                <td>
                                                    <form action="{{ route('booking.status', $booking->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="CHECKED_OUT">
                                                        <button type="submit" class="btn btn-sm btn-secondary">
                                                            <i class="bi bi-box-arrow-right"></i> Check Out
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">No departures today</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> {{ $data['message'] ?? 'No hotel assigned.' }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
