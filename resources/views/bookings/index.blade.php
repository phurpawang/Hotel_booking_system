@extends(
    strtoupper(Auth::user()->role) === 'MANAGER' ? 'manager.layouts.app' :
    (in_array(strtoupper(Auth::user()->role), ['RECEPTIONIST', 'RECEPTION']) ? 'reception.layouts.app' : 'layouts.owner-bootstrap')
)

@section('title', 'Reservations')

@section('header')
    <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Reservations</h2>
    <p class="text-gray-600 text-sm mt-1">{{ $hotel->name }} - Real-time booking management</p>
@endsection

@section('styles')
<style>
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
    
    .dashboard-header > div {
        position: relative !important;
        z-index: 1 !important;
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
        pointer-events: none !important;
        z-index: 0 !important;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .stat-card {
        border-radius: 20px;
        padding: 2rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    
    .stat-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 35px rgba(0,0,0,0.20);
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
        font-size: 3rem;
        opacity: 0.95;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .stat-label {
        font-size: 1rem;
        opacity: 0.95;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .table-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    /* Enhanced Table Styling */
    .table {
        border-collapse: separate !important;
        border-spacing: 0 8px !important;
        width: 100%;
    }

    .table thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table thead tr {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
    }

    .table thead th {
        padding: 1.2rem !important;
        color: white !important;
        font-weight: 700 !important;
        text-align: center !important;
        border: none !important;
        letter-spacing: 0.5px !important;
        font-size: 0.95rem !important;
    }

    .table thead th:first-child {
        border-radius: 12px 0 0 12px !important;
        text-align: left !important;
    }

    .table thead th:last-child {
        border-radius: 0 12px 12px 0 !important;
        text-align: center !important;
    }

    /* Table Body */
    .table tbody tr {
        background: white !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
        border-left: 4px solid #667eea !important;
        transition: all 0.3s ease !important;
        border: none !important;
    }

    .table tbody tr:hover {
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.2) !important;
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%) !important;
        transform: translateY(-2px) !important;
    }

    .table tbody td {
        padding: 1rem 1.2rem !important;
        vertical-align: middle !important;
        border: none !important;
        color: #333 !important;
        font-size: 0.95rem !important;
    }

    .table tbody td:first-child {
        border-radius: 12px 0 0 12px !important;
        text-align: left !important;
    }

    .table tbody td:last-child {
        border-radius: 0 12px 12px 0 !important;
        text-align: center !important;
    }

    /* Center align specific columns */
    .table tbody td:nth-child(3),
    .table tbody td:nth-child(4),
    .table tbody td:nth-child(5),
    .table tbody td:nth-child(6),
    .table tbody td:nth-child(7),
    .table tbody td:nth-child(8),
    .table tbody td:nth-child(9),
    .table tbody td:nth-child(10) {
        text-align: center !important;
    }

    /* Booking ID Column */
    .table tbody td:first-child {
        font-weight: 700;
        font-size: 1.05rem;
        color: #667eea;
    }
    
    .badge-confirmed {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(17, 153, 142, 0.3);
        color: white !important;
        display: inline-block;
    }
    
    .badge-pending {
        background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-weight: 600;
        color: #333 !important;
        box-shadow: 0 3px 10px rgba(255, 167, 81, 0.3);
        display: inline-block;
    }
    
    .badge-checked-in {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        color: white !important;
        display: inline-block;
    }
    
    .badge-checked-out {
        background: linear-gradient(135deg, #868f96 0%, #596164 100%);
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(134, 143, 150, 0.3);
        color: white !important;
        display: inline-block;
    }
    
    .badge-cancelled {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(235, 51, 73, 0.3);
    }
    
    .badge-paid {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(17, 153, 142, 0.3);
    }
    
    .badge-unpaid {
        background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #333;
        box-shadow: 0 2px 8px rgba(255, 167, 81, 0.3);
    }
    
    .action-btn {
        padding: 0.7rem 1.2rem !important;
        border-radius: 8px !important;
        border: none !important;
        font-size: 0.9rem !important;
        transition: all 0.3s ease !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        text-decoration: none !important;
        min-width: 100px !important;
    }
    
    .action-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.25) !important;
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
        padding: 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-left: 5px solid #667eea;
    }
    
    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        padding: 0.7rem 1.2rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 0.7rem 2rem;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }
    
    .btn-light {
        background: white;
        border: 2px solid white;
        color: #667eea;
        font-weight: 700;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .btn-light:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        color: #764ba2;
    }
    
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 1.5rem 2rem;
    }
    
    .table {
        border-collapse: separate;
        border-spacing: 0 0.8rem;
    }
    
    .table thead tr {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }
    
    .table thead th {
        padding: 1.2rem;
        font-weight: 600;
        border: none;
        letter-spacing: 0.5px;
    }
    
    .table thead tr th:first-child {
        border-radius: 12px 0 0 12px;
    }
    
    .table thead tr th:last-child {
        border-radius: 0 12px 12px 0;
    }
    
    .table tbody tr {
        background: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        transform: translateY(-3px) scale(1.01);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
    }
    
    .table tbody td {
        vertical-align: middle;
        padding: 1.2rem;
        border: none;
    }
    
    .table tbody tr td:first-child {
        border-radius: 12px 0 0 12px;
        border-left: 4px solid #667eea;
    }
    
    .table tbody tr td:last-child {
        border-radius: 0 12px 12px 0;
    }
    
    .trend-indicator {
        font-size: 0.85rem;
        opacity: 0.95;
        font-weight: 500;
    }
    
    .alert {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')

<!-- Dashboard Header -->
<div class="dashboard-header mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="margin: 0; font-size: 2rem; font-weight: 700; color: white;"><i class="bi bi-calendar-check me-2"></i>Reservation Dashboard</h2>
            <p style="margin: 0.5rem 0 0; font-size: 0.95rem; opacity: 0.9; color: white;">{{ $hotel->name }} - Real-time booking management</p>
        </div>
        <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.create') }}" 
           style="background: white !important; color: #667eea !important; font-weight: 700 !important; border-radius: 12px !important; padding: 0.7rem 1.5rem !important; box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important; transition: all 0.3s ease !important; white-space: nowrap !important; text-decoration: none !important; display: inline-flex !important; align-items: center !important; gap: 0.5rem !important; cursor: pointer !important;">
            <i class="bi bi-plus-circle-fill"></i>Add Reservation
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <h5 style="color: #667eea; font-weight: 700; margin-bottom: 1.5rem;">
        <i class="bi bi-funnel-fill me-2"></i>Filter Reservations
    </h5>
    <form method="GET" action="{{ route(strtolower(Auth::user()->role) . '.reservations.index') }}" id="filterForm">
        <div style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;">
            <div style="flex: 1 1 150px; min-width: 150px;">
                <label class="form-label fw-semibold" style="color: #667eea; display: block; margin-bottom: 0.5rem;">
                    <i class="bi bi-search me-1"></i>Search
                </label>
                <input type="text" name="search" class="form-control" placeholder="Guest name, phone, booking ID..." value="{{ request('search') }}" style="width: 100%;">
            </div>
            <div style="flex: 1 1 120px; min-width: 120px;">
                <label class="form-label fw-semibold" style="color: #667eea; display: block; margin-bottom: 0.5rem;">
                    <i class="bi bi-flag me-1"></i>Status
                </label>
                <select name="status" class="form-select" style="width: 100%;">
                    <option value="">All Status</option>
                    <option value="CONFIRMED" {{ request('status') == 'CONFIRMED' ? 'selected' : '' }}>Confirmed</option>
                    <option value="CHECKED_IN" {{ request('status') == 'CHECKED_IN' ? 'selected' : '' }}>Checked In</option>
                    <option value="CHECKED_OUT" {{ request('status') == 'CHECKED_OUT' ? 'selected' : '' }}>Checked Out</option>
                    <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div style="flex: 1 1 100px; min-width: 100px;">
                <label class="form-label fw-semibold" style="color: #667eea; display: block; margin-bottom: 0.5rem;">
                    <i class="bi bi-credit-card me-1"></i>Payment
                </label>
                <select name="payment_status" class="form-select" style="width: 100%;">
                    <option value="">All</option>
                    <option value="PAID" {{ request('payment_status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                    <option value="PENDING" {{ request('payment_status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div style="flex: 1 1 120px; min-width: 120px;">
                <label class="form-label fw-semibold" style="color: #667eea; display: block; margin-bottom: 0.5rem;">
                    <i class="bi bi-calendar-event me-1"></i>Start Date
                </label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" style="width: 100%;">
            </div>
            <div style="flex: 1 1 120px; min-width: 120px;">
                <label class="form-label fw-semibold" style="color: #667eea; display: block; margin-bottom: 0.5rem;">
                    <i class="bi bi-calendar-event me-1"></i>End Date
                </label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" style="width: 100%;">
            </div>
            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 10px; padding: 0.7rem 1.5rem; font-weight: 600; height: 40px; display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
                <i class="bi bi-search"></i>Search
            </button>
        </div>
    </form>
</div>

<!-- Bookings Table -->
<div class="table-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h4 style="margin: 0; color: #667eea; font-weight: 700;">
            <i class="bi bi-list-ul me-2"></i>Recent Reservations
        </h4>
        <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 0.6rem 1.2rem; border-radius: 20px; font-size: 1rem; white-space: nowrap;">
            {{ $bookings->total() }} Total
        </span>
    </div>
    
    @if($bookings->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="fw-semibold">Booking ID</th>
                    <th class="fw-semibold">Guest Name</th>
                    <th class="fw-semibold">Room</th>
                    <th class="fw-semibold">Check-in</th>
                    <th class="fw-semibold">Check-out</th>
                    <th class="fw-semibold">Amount</th>
                    <th class="fw-semibold">Payment Status</th>
                    <th class="fw-semibold">Payment Method</th>
                    <th class="fw-semibold">Status</th>
                    <th class="fw-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td><strong>{{ $booking->booking_id }}</strong></td>
                    <td>
                        <div>
                            <strong style="color: #333; display: block;">{{ $booking->guest_name }}</strong>
                            <small class="text-muted"><i class="bi bi-telephone-fill me-1" style="color: #667eea;"></i>{{ $booking->guest_phone }}</small>
                        </div>
                    </td>
                    <td>
                        <span class="badge" style="background: linear-gradient(135deg, #17a2b8 0%, #00d4ff 100%); padding: 0.6rem 1rem; border-radius: 15px; font-weight: 600; color: white; display: inline-block;">
                            <i class="bi bi-door-closed-fill me-1"></i>{{ $booking->room->room_number }}
                        </span>
                    </td>
                    <td>
                        <i class="bi bi-calendar-check-fill text-success me-1"></i>
                        <strong style="font-weight: 600;">{{ $booking->check_in_date->format('M d, Y') }}</strong>
                    </td>
                    <td>
                        <i class="bi bi-calendar-x-fill text-danger me-1"></i>
                        <strong style="font-weight: 600;">{{ $booking->check_out_date->format('M d, Y') }}</strong>
                    </td>
                    <td>
                        <strong style="color: #11998e; font-size: 1.05rem; font-weight: 700;">Nu. {{ number_format($booking->total_price, 2) }}</strong>
                    </td>
                    <td>
                        @if($booking->payment_status == 'PAID')
                            <span class="badge badge-paid" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 0.6rem 1rem; border-radius: 20px; font-weight: 600; display: inline-block; box-shadow: 0 2px 8px rgba(17, 153, 142, 0.3);"><i class="bi bi-check-circle me-1"></i>Paid</span>
                        @else
                            <span class="badge badge-unpaid" style="background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); color: #333; padding: 0.6rem 1rem; border-radius: 20px; font-weight: 600; display: inline-block; box-shadow: 0 2px 8px rgba(255, 167, 81, 0.3);"><i class="bi bi-clock me-1"></i>Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($booking->payment_method)
                            @if($booking->payment_method == 'CASH')
                                <span class="badge" style="background: #28a745; color: white; padding: 0.6rem 0.9rem; border-radius: 15px; font-weight: 600; display: inline-block; box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);"><i class="bi bi-cash me-1"></i>Cash</span>
                            @elseif($booking->payment_method == 'CARD')
                                <span class="badge" style="background: linear-gradient(135deg, #17a2b8 0%, #00d4ff 100%); color: white; padding: 0.6rem 0.9rem; border-radius: 15px; font-weight: 600; display: inline-block; box-shadow: 0 2px 6px rgba(23, 162, 184, 0.3);"><i class="bi bi-credit-card me-1"></i>Card</span>
                            @elseif($booking->payment_method == 'BANK_TRANSFER')
                                <span class="badge" style="background: #6c757d; color: white; padding: 0.6rem 0.9rem; border-radius: 15px; font-weight: 600; display: inline-block; box-shadow: 0 2px 6px rgba(108, 117, 125, 0.3);"><i class="bi bi-bank me-1"></i>Bank</span>
                            @elseif($booking->payment_method == 'ONLINE')
                                <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.6rem 0.9rem; border-radius: 15px; font-weight: 600; display: inline-block; box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);"><i class="bi bi-globe me-1"></i>Online</span>
                            @endif
                        @else
                            <span class="text-muted"><i class="bi bi-dash-circle"></i> Not Set</span>
                        @endif
                    </td>
                    <td>
                        @if($booking->status == 'CONFIRMED')
                            <span class="badge badge-confirmed" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;"><i class="bi bi-check-circle me-1"></i>Confirmed</span>
                        @elseif($booking->status == 'CHECKED_IN')
                            <span class="badge badge-checked-in" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;"><i class="bi bi-box-arrow-in me-1"></i>Checked In</span>
                        @elseif($booking->status == 'CHECKED_OUT')
                            <span class="badge badge-checked-out" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%); color: white;"><i class="bi bi-box-arrow-out me-1"></i>Checked Out</span>
                        @elseif($booking->status == 'CANCELLED')
                            <span class="badge badge-cancelled" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white;"><i class="bi bi-x-circle me-1"></i>Cancelled</span>
                        @endif
                    </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center; align-items: center; flex-wrap: wrap;">
                                @if($booking->status == 'CONFIRMED')
                                    <form action="{{ route(strtolower(Auth::user()->role) . '.reservations.checkin', $booking->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn btn-checkin" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);">
                                            <i class="bi bi-box-arrow-in-down"></i>Check In
                                        </button>
                                    </form>
                                @endif
                                
                                @if($booking->status == 'CHECKED_IN')
                                    <form action="{{ route(strtolower(Auth::user()->role) . '.reservations.checkout', $booking->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn btn-checkout" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; box-shadow: 0 2px 6px rgba(79, 172, 254, 0.3);">
                                            <i class="bi bi-box-arrow-right"></i>Check Out
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.show', $booking->id) }}" 
                                   class="action-btn" style="background: #17a2b8; color: white; box-shadow: 0 2px 6px rgba(23, 162, 184, 0.3);" title="View Details">
                                    <i class="bi bi-eye"></i>View
                                </a>
                                
                                @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']) && $booking->status == 'CONFIRMED')
                                    <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.edit', $booking->id) }}" 
                                       class="action-btn" style="background: #ffc107; color: white; box-shadow: 0 2px 6px rgba(255, 193, 7, 0.3);" title="Edit">
                                        <i class="bi bi-pencil"></i>Edit
                                    </a>
                                @endif
                                
                                @if($booking->payment_status == 'PENDING')
                                    <button type="button" class="action-btn togglePaymentForm" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);" title="Mark as Paid" data-booking-id="{{ $booking->id }}">
                                        <i class="bi bi-check-lg"></i>Mark Paid
                                    </button>
                                @endif
                                
                                @if(strtoupper(Auth::user()->role) == 'OWNER' && in_array($booking->status, ['CONFIRMED', 'CHECKED_IN']))
                                    <form action="{{ route(strtolower(Auth::user()->role) . '.reservations.destroy', $booking->id) }}" 
                                          method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn" style="background: #dc3545; color: white; box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);" title="Delete">
                                            <i class="bi bi-trash"></i>Delete
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
            <div class="text-muted" style="font-size: 0.9rem;">
                Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} entries
            </div>
            <div>
                {{ $bookings->links() }}
            </div>
        </div>
        @else
        <div style="text-align: center; padding: 2rem;">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="text-muted mt-3">No bookings found</h5>
            <p class="text-muted">Try adjusting your filters or create a new booking</p>
        </div>
        @endif
    </div>

    <!-- Hidden Payment Method Cards (Appear inline when button clicked) -->
    <div id="paymentFormsContainer" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1050; display: none; width: 95%; max-width: 480px; animation: slideIn 0.3s ease-out;">
        @foreach($bookings as $booking)
            @if($booking->payment_status == 'PENDING')
            <div class="paymentFormCard" id="paymentForm{{ $booking->id }}" style="background: white; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.35); padding: 0; overflow: hidden; display: none; animation: popIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
                
                <!-- Header with Gradient -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 70%, #6366f1 100%); padding: 1.8rem 2rem; color: white; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -50%; right: -50%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
                    <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 1;">
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.25); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">💳</div>
                            <h4 style="margin: 0; font-weight: 800; font-size: 1.3rem; letter-spacing: 0.3px;">Mark as Paid</h4>
                        </div>
                        <button type="button" class="closePaymentForm" data-booking-id="{{ $booking->id }}" style="background: rgba(255,255,255,0.2); border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 6px 12px; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: all 0.2s;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div style="padding: 2rem; background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);">
                    
                    <!-- Booking Info Box -->
                    <div style="background: white; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.8rem; border-left: 5px solid #667eea; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.12);">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.5rem;">
                            <div>
                                <p style="margin: 0; color: #999; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px;">Booking ID</p>
                                <p style="margin: 0.4rem 0 0 0; color: #667eea; font-weight: 800; font-size: 1.15rem;">{{ $booking->booking_id }}</p>
                            </div>
                            <div style="text-align: right;">
                                <p style="margin: 0; color: #999; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px;">Guest</p>
                                <p style="margin: 0.4rem 0 0 0; color: #333; font-weight: 700; font-size: 0.95rem;">{{ $booking->guest_name }}</p>
                            </div>
                        </div>
                        <div style="border-top: 1px solid #eee; padding-top: 1rem; margin-top: 1rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <p style="margin: 0; color: #999; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px;">Room</p>
                                    <p style="margin: 0.4rem 0 0 0; color: #333; font-weight: 700; font-size: 1rem;">Room {{ $booking->room->room_number }}</p>
                                </div>
                                <div style="text-align: right; padding-top: 0.4rem;">
                                    <p style="margin: 0; color: #11998e; font-size: 1.4rem; font-weight: 800;">Nu. {{ number_format($booking->total_price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Selection -->
                    <form action="{{ route(strtolower(Auth::user()->role) . '.reservations.mark-paid', $booking->id) }}" method="POST">
                        @csrf

                        <div style="margin-bottom: 1.5rem;">
                            <label style="color: #667eea; font-weight: 800; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.6rem; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.3px;">
                                <span style="width: 24px; height: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.9rem; font-weight: 700;">1</span>
                                Payment Method <span style="color: #ff4757; font-weight: 900;">*</span>
                            </label>
                            <select class="form-select" name="payment_method" required style="border-radius: 12px; border: 2px solid #e8eaf6; padding: 0.95rem 1rem; font-size: 0.95rem; background-color: white; cursor: pointer; color: #333; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                <option value="" style="color: #999;">-- Choose Payment Method --</option>
                                <option value="CASH">💰 Cash Payment</option>
                                <option value="CARD">💳 Credit/Debit Card</option>
                                <option value="BANK_TRANSFER">🏦 Bank Transfer</option>
                                <option value="ONLINE">🌐 Online Payment</option>
                            </select>
                        </div>

                        <!-- Payment Notes Section -->
                        <div style="margin-bottom: 1.8rem;">
                            <label style="color: #667eea; font-weight: 800; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.6rem; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.3px;">
                                <span style="width: 24px; height: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.9rem; font-weight: 700;">2</span>
                                Reference (Optional)
                            </label>
                            <textarea class="form-control" name="payment_notes" rows="2" placeholder="Transaction ID, reference number, or notes..." style="border-radius: 12px; border: 2px solid #e8eaf6; padding: 0.9rem 1rem; font-size: 0.9rem; resize: none; font-family: inherit; color: #333; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05);"></textarea>
                            <small style="color: #bbb; margin-top: 0.5rem; display: block; font-size: 0.8rem;">Max 500 characters</small>
                        </div>

                        <!-- Buttons -->
                        <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 1rem;">
                            <button type="button" class="closePaymentForm" data-booking-id="{{ $booking->id }}" style="background: #f0f2f7; color: #667eea; border: 2px solid #e8eaf6; border-radius: 12px; padding: 0.95rem 1.5rem; font-weight: 700; cursor: pointer; font-size: 0.95rem; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                <i class="bi bi-x-circle" style="font-size: 1.1rem;"></i>Cancel
                            </button>
                            <button type="submit" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border: none; border-radius: 12px; padding: 0.95rem 1.5rem; font-weight: 700; cursor: pointer; font-size: 0.95rem; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 6px 16px rgba(17, 153, 142, 0.35); font-size: 0.95rem;">
                                <i class="bi bi-check-lg" style="font-size: 1.1rem;"></i>Confirm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        @endforeach
    </div>

    <!-- Backdrop Overlay -->
    <div id="paymentFormBackdrop" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.55); z-index: 1040; display: none; backdrop-filter: blur(4px); animation: fadeIn 0.3s ease-out;"></div>

    <!-- Animations -->
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -55%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }
        
        @keyframes popIn {
            0% {
                opacity: 0;
                transform: scale(0.85);
            }
            50% {
                opacity: 1;
                transform: scale(1.02);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .closePaymentForm:hover {
            background: rgba(255,255,255,0.3) !important;
        }

        #paymentFormsContainer select:focus,
        #paymentFormsContainer textarea:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12), 0 2px 8px rgba(0,0,0,0.05) !important;
            outline: none;
        }
    </style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle Payment Form Visibility
    document.querySelectorAll('.togglePaymentForm').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-booking-id');
            const formCard = document.getElementById('paymentForm' + bookingId);
            const container = document.getElementById('paymentFormsContainer');
            const backdrop = document.getElementById('paymentFormBackdrop');
            
            // Hide all other forms
            document.querySelectorAll('.paymentFormCard').forEach(card => {
                card.style.display = 'none';
            });
            
            // Show selected form
            formCard.style.display = 'block';
            container.style.display = 'block';
            backdrop.style.display = 'block';
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        });
    });

    // Close Payment Form
    document.querySelectorAll('.closePaymentForm').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const container = document.getElementById('paymentFormsContainer');
            const backdrop = document.getElementById('paymentFormBackdrop');
            const formCard = this.closest('.paymentFormCard');
            
            // Hide form and backdrop
            formCard.style.display = 'none';
            if (document.querySelectorAll('.paymentFormCard[style*="display: block"]').length === 0) {
                container.style.display = 'none';
                backdrop.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    });

    // Close on backdrop click
    document.getElementById('paymentFormBackdrop').addEventListener('click', function() {
        const container = document.getElementById('paymentFormsContainer');
        const backdrop = document.getElementById('paymentFormBackdrop');
        
        document.querySelectorAll('.paymentFormCard').forEach(card => {
            card.style.display = 'none';
        });
        
        container.style.display = 'none';
        backdrop.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

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
