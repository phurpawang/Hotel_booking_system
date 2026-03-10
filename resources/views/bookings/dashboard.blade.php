@extends('layouts.app')

@section('title', 'Reservation Dashboard - ' . $hotel->name)

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
    body {
        background: #f8f9fa;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .stat-card {
        border-radius: 15px;
        padding: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .stat-card.total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .stat-card.checkin {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .stat-card.checkout {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    
    .stat-card.pending {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }
    
    .stat-card.revenue {
        background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        color: white;
    }
    
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.9;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.95;
        font-weight: 500;
    }
    
    .table-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .badge-confirmed {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
    
    .badge-pending {
        background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
    
    .badge-checked-in {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
    
    .badge-checked-out {
        background: linear-gradient(135deg, #868f96 0%, #596164 100%);
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
    
    .badge-cancelled {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
    
    .badge-paid {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.85rem;
    }
    
    .badge-unpaid {
        background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.85rem;
    }
    
    .action-btn {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        border: none;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        transform: scale(1.05);
    }
    
    .btn-checkin {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-checkout {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    
    .filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 0.6rem 1rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .modal-content {
        border-radius: 15px;
        border: none;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }
    
    .table {
        border-collapse: separate;
        border-spacing: 0 0.5rem;
    }
    
    .table tbody tr {
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .table tbody td {
        vertical-align: middle;
        padding: 1rem;
        border: none;
    }
    
    .table tbody tr td:first-child {
        border-radius: 10px 0 0 10px;
    }
    
    .table tbody tr td:last-child {
        border-radius: 0 10px 10px 0;
    }
    
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-top: 2rem;
    }
    
    .trend-indicator {
        font-size: 0.8rem;
        opacity: 0.9;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2"><i class="bi bi-calendar-check me-2"></i>Reservation Dashboard</h1>
                <p class="mb-0 opacity-90">{{ $hotel->name }} - Real-time booking management</p>
            </div>
            @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER', 'RECEPTION']))
            <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#createBookingModal">
                <i class="bi bi-plus-lg me-2"></i>New Reservation
            </button>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4 col-lg-2">
            <div class="stat-card total">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Total Bookings</div>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="trend-indicator">
                            <i class="bi bi-graph-up"></i> All time
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-2">
            <div class="stat-card checkin">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Today Check-ins</div>
                        <div class="stat-value">{{ $stats['today_checkins'] }}</div>
                        <div class="trend-indicator">
                            <i class="bi bi-arrow-down-circle"></i> Arriving
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-door-open"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-2">
            <div class="stat-card checkout">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Today Check-outs</div>
                        <div class="stat-value">{{ $stats['today_checkouts'] }}</div>
                        <div class="trend-indicator">
                            <i class="bi bi-arrow-up-circle"></i> Departing
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-door-closed"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-3">
            <div class="stat-card pending">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Pending Bookings</div>
                        <div class="stat-value">{{ $stats['pending'] }}</div>
                        <div class="trend-indicator">
                            <i class="bi bi-hourglass-split"></i> Awaiting payment
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-3">
            <div class="stat-card revenue">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Monthly Revenue</div>
                        <div class="stat-value">Nu. {{ number_format($stats['monthly_revenue'], 2) }}</div>
                        <div class="trend-indicator">
                            <i class="bi bi-currency-dollar"></i> This month
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                <div class="text-center">
                    <i class="bi bi-check-circle stat-icon"></i>
                    <div class="stat-value">{{ $stats['confirmed'] }}</div>
                    <div class="stat-label">Confirmed</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="text-center">
                    <i class="bi bi-person-check stat-icon"></i>
                    <div class="stat-value">{{ $stats['checked_in'] }}</div>
                    <div class="stat-label">Checked In</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%); color: white;">
                <div class="text-center">
                    <i class="bi bi-box-arrow-right stat-icon"></i>
                    <div class="stat-value">{{ $stats['checked_out'] }}</div>
                    <div class="stat-label">Checked Out</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white;">
                <div class="text-center">
                    <i class="bi bi-x-circle stat-icon"></i>
                    <div class="stat-value">{{ $stats['cancelled'] }}</div>
                    <div class="stat-label">Cancelled</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Occupancy Rate -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="stat-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-2"><i class="bi bi-building me-2"></i>Current Occupancy Rate</h5>
                        <div class="progress" style="height: 30px; border-radius: 15px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $stats['occupancy'] }}%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);" 
                                 aria-valuenow="{{ $stats['occupancy'] }}" aria-valuemin="0" aria-valuemax="100">
                                <strong>{{ $stats['occupancy'] }}%</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-section">
        <form method="GET" action="{{ route(strtolower(Auth::user()->role) . '.reservations.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold"><i class="bi bi-search me-1"></i>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Guest name, phone, booking ID..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold"><i class="bi bi-flag me-1"></i>Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="CONFIRMED" {{ request('status') == 'CONFIRMED' ? 'selected' : '' }}>Confirmed</option>
                        <option value="CHECKED_IN" {{ request('status') == 'CHECKED_IN' ? 'selected' : '' }}>Checked In</option>
                        <option value="CHECKED_OUT" {{ request('status') == 'CHECKED_OUT' ? 'selected' : '' }}>Checked Out</option>
                        <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold"><i class="bi bi-credit-card me-1"></i>Payment</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All</option>
                        <option value="PAID" {{ request('payment_status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                        <option value="PENDING" {{ request('payment_status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold"><i class="bi bi-calendar-event me-1"></i>Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold"><i class="bi bi-calendar-event me-1"></i>End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="bi bi-list-ul me-2"></i>Recent Reservations</h4>
            <span class="badge bg-secondary">{{ $bookings->total() }} Total</span>
        </div>
        
        @if($bookings->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th class="fw-semibold">Booking ID</th>
                        <th class="fw-semibold">Guest Name</th>
                        <th class="fw-semibold">Room</th>
                        <th class="fw-semibold">Check-in</th>
                        <th class="fw-semibold">Check-out</th>
                        <th class="fw-semibold">Amount</th>
                        <th class="fw-semibold">Payment</th>
                        <th class="fw-semibold">Status</th>
                        <th class="fw-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td><strong class="text-primary">{{ $booking->booking_id }}</strong></td>
                        <td>
                            <div>
                                <strong>{{ $booking->guest_name }}</strong><br>
                                <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $booking->guest_phone }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                <i class="bi bi-door-closed me-1"></i>{{ $booking->room->room_number }}
                            </span>
                        </td>
                        <td><i class="bi bi-calendar-check text-success me-1"></i>{{ $booking->check_in_date->format('M d, Y') }}</td>
                        <td><i class="bi bi-calendar-x text-danger me-1"></i>{{ $booking->check_out_date->format('M d, Y') }}</td>
                        <td><strong class="text-success">Nu. {{ number_format($booking->total_price, 2) }}</strong></td>
                        <td>
                            @if($booking->payment_status == 'PAID')
                                <span class="badge badge-paid"><i class="bi bi-check-circle me-1"></i>Paid</span>
                            @else
                                <span class="badge badge-unpaid"><i class="bi bi-clock me-1"></i>Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($booking->status == 'CONFIRMED')
                                <span class="badge badge-confirmed">Confirmed</span>
                            @elseif($booking->status == 'CHECKED_IN')
                                <span class="badge badge-checked-in">Checked In</span>
                            @elseif($booking->status == 'CHECKED_OUT')
                                <span class="badge badge-checked-out">Checked Out</span>
                            @elseif($booking->status == 'CANCELLED')
                                <span class="badge badge-cancelled">Cancelled</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                @if($booking->status == 'CONFIRMED')
                                    <form action="{{ route(strtolower(Auth::user()->role) . '.reservations.checkin', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn btn-checkin" title="Check In">
                                            <i class="bi bi-box-arrow-in-down"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($booking->status == 'CHECKED_IN')
                                    <form action="{{ route(strtolower(Auth::user()->role) . '.reservations.checkout', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn btn-checkout" title="Check Out">
                                            <i class="bi bi-box-arrow-right"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.show', $booking->id) }}" 
                                   class="action-btn btn-info" title="View Details" style="background: #17a2b8; color: white;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']) && $booking->status == 'CONFIRMED')
                                    <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.edit', $booking->id) }}" 
                                       class="action-btn btn-warning" title="Edit" style="background: #ffc107; color: white;">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                                
                                @if(strtoupper(Auth::user()->role) == 'OWNER' && in_array($booking->status, ['CONFIRMED', 'CHECKED_IN']))
                                    <form action="{{ route(strtolower(Auth::user()->role) . '.reservations.destroy', $booking->id) }}" 
                                          method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-danger" title="Delete" style="background: #dc3545; color: white;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} entries
            </div>
            <div>
                {{ $bookings->links() }}
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="text-muted mt-3">No bookings found</h5>
            <p class="text-muted">Try adjusting your filters or create a new booking</p>
        </div>
        @endif
    </div>

</div>

<!-- Create Booking Modal -->
<div class="modal fade" id="createBookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Create New Reservation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route(strtolower(Auth::user()->role) . '.reservations.store') }}" method="POST" id="createBookingForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Guest Name <span class="text-danger">*</span></label>
                            <input type="text" name="guest_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Guest Phone <span class="text-danger">*</span></label>
                            <input type="text" name="guest_phone" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Guest Email <span class="text-danger">*</span></label>
                            <input type="email" name="guest_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Room <span class="text-danger">*</span></label>
                            <select name="room_id" class="form-select" id="roomSelect" required>
                                <option value="">Select Room</option>
                                @php
                                    $rooms = \App\Models\Room::where('hotel_id', Auth::user()->hotel_id)
                                                             ->where('is_available', true)
                                                             ->get();
                                @endphp
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" data-price="{{ $room->price_per_night }}">
                                        Room {{ $room->room_number }} - {{ $room->room_type }} (Nu. {{ number_format($room->price_per_night, 2) }}/night)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Number of Rooms <span class="text-danger">*</span></label>
                            <input type="number" name="num_rooms" class="form-control" id="numRooms" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Check-in Date <span class="text-danger">*</span></label>
                            <input type="date" name="check_in_date" class="form-control" id="checkInDate" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Check-out Date <span class="text-danger">*</span></label>
                            <input type="date" name="check_out_date" class="form-control" id="checkOutDate" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Number of Guests <span class="text-danger">*</span></label>
                            <input type="number" name="num_guests" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Payment Status <span class="text-danger">*</span></label>
                            <select name="payment_status" class="form-select" required>
                                <option value="PENDING">Pending</option>
                                <option value="PAID">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="">Select Method</option>
                                <option value="CASH">Cash</option>
                                <option value="CARD">Card</option>
                                <option value="ONLINE">Online Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Special Requests</label>
                            <textarea name="special_requests" class="form-control" rows="3" placeholder="Any special requirements..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <strong><i class="bi bi-calculator me-2"></i>Total Estimate:</strong>
                                <span id="totalEstimate">Nu. 0.00</span>
                                <small class="d-block mt-1">* Based on room rate and nights</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Price Calculator
    function calculateTotal() {
        const roomSelect = document.getElementById('roomSelect');
        const checkIn = document.getElementById('checkInDate');
        const checkOut = document.getElementById('checkOutDate');
        const numRooms = document.getElementById('numRooms');
        const totalEstimate = document.getElementById('totalEstimate');
        
        if (roomSelect.value && checkIn.value && checkOut.value) {
            const pricePerNight = parseFloat(roomSelect.options[roomSelect.selectedIndex].dataset.price || 0);
            const checkInDate = new Date(checkIn.value);
            const checkOutDate = new Date(checkOut.value);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            const rooms = parseInt(numRooms.value || 1);
            
            if (nights > 0) {
                const total = pricePerNight * nights * rooms;
                totalEstimate.textContent = 'Nu. ' + total.toFixed(2) + ' (' + nights + ' night' + (nights > 1 ? 's' : '') + ')';
            }
        }
    }
    
    document.getElementById('roomSelect').addEventListener('change', calculateTotal);
    document.getElementById('checkInDate').addEventListener('change', function() {
        document.getElementById('checkOutDate').min = this.value;
        calculateTotal();
    });
    document.getElementById('checkOutDate').addEventListener('change', calculateTotal);
    document.getElementById('numRooms').addEventListener('input', calculateTotal);
    
    // AJAX Filtering (debounced)
    let filterTimeout;
    document.querySelectorAll('#filterForm input, #filterForm select').forEach(element => {
        element.addEventListener('change', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 300);
        });
    });
    
    // Success/Error Messages Auto-hide
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endsection
