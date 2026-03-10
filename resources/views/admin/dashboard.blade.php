@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-header">
    <h1>Dashboard</h1>
    <p class="welcome-message">Welcome, {{ $adminUsername }}!</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-hotel"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $totalHotels ?? 0 }}</h3>
            <p>Total Hotels</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $totalBookings ?? 0 }}</h3>
            <p>Total Bookings</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-credit-card"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $pendingPayouts->count() ?? 0 }}</h3>
            <p>Pending Payouts</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <h3>Nu. {{ number_format($totalPlatformRevenue ?? 0, 2) }}</h3>
            <p>Platform Revenue (All Time)</p>
        </div>
    </div>
</div>

<!-- Commission Overview Section -->
<div style="margin: 2rem 0; padding: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: white;">
    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-chart-line" style="font-size: 2rem;"></i>
            <div>
                <h2 style="margin: 0; color: white;">This Month's Platform Performance</h2>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 0.9rem;">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
            </div>
        </div>
        <a href="{{ route('admin.commissions.index') }}" style="background: rgba(255,255,255,0.2); padding: 0.75rem 1.5rem; border-radius: 8px; color: white; text-decoration: none; transition: all 0.3s;">
            <i class="fas fa-chart-bar"></i> View Full Report
        </a>
    </div>

    <div class="stats-grid" style="margin-top: 1.5rem;">
        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 10px;">
            <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; opacity: 0.9;">Total Bookings</p>
            <h3 style="margin: 0; font-size: 2rem; color: white;">{{ $platformStats['total_bookings'] ?? 0 }}</h3>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; opacity: 0.8;"><i class="fas fa-calendar-check"></i> This month</p>
        </div>

        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 10px;">
            <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; opacity: 0.9;">Total Guest Payments</p>
            <h3 style="margin: 0; font-size: 2rem; color: white;">Nu. {{ number_format($platformStats['total_guest_payments'] ?? 0, 2) }}</h3>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; opacity: 0.8;"><i class="fas fa-users"></i> From guests</p>
        </div>

        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 10px;">
            <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; opacity: 0.9;">Platform Commission (10%)</p>
            <h3 style="margin: 0; font-size: 2rem; color: white;">Nu. {{ number_format($platformStats['total_commission'] ?? 0, 2) }}</h3>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; opacity: 0.8;"><i class="fas fa-percentage"></i> Platform earning</p>
        </div>

        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 10px;">
            <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; opacity: 0.9;">Payouts to Hotels (Online)</p>
            <h3 style="margin: 0; font-size: 2rem; color: white;">Nu. {{ number_format($platformStats['total_hotel_payout'] ?? 0, 2) }}</h3>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; opacity: 0.8;"><i class="fas fa-arrow-right"></i> For online bookings</p>
        </div>
    </div>
    
    <div class="stats-grid" style="margin-top: 1rem;">
        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 10px;">
            <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; opacity: 0.9;">Commission Due from Hotels</p>
            <h3 style="margin: 0; font-size: 2rem; color: white;">Nu. {{ number_format($platformStats['hotel_owes_commission'] ?? 0, 2) }}</h3>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; opacity: 0.8;"><i class="fas fa-arrow-left"></i> Cash/Card/Bank payments</p>
        </div>
        
        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 10px;">
            <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; opacity: 0.9;">Online Payments</p>
            <h3 style="margin: 0; font-size: 2rem; color: white;">{{ $platformStats['pay_online_bookings'] ?? 0 }}</h3>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; opacity: 0.8;"><i class="fas fa-globe"></i> Via platform</p>
        </div>
        
        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 10px;">
            <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; opacity: 0.9;">Hotel Payments</p>
            <h3 style="margin: 0; font-size: 2rem; color: white;">{{ $platformStats['pay_at_hotel_bookings'] ?? 0 }}</h3>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; opacity: 0.8;"><i class="fas fa-building"></i> At hotel</p>
        </div>
    </div>
</div>

<!-- Pending Payouts Section -->
@if(isset($pendingPayouts) && $pendingPayouts->count() > 0)
<div class="dashboard-card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h2><i class="fas fa-money-bill-wave"></i> Pending Hotel Payouts</h2>
        <a href="{{ route('admin.commissions.index') }}" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Hotel Name</th>
                        <th>Month</th>
                        <th>Bookings</th>
                        <th>Guest Payments</th>
                        <th>Commission</th>
                        <th>Hotel Payout</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingPayouts as $payout)
                        <tr>
                            <td>{{ $payout->hotel->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::create($payout->year, $payout->month, 1)->format('F Y') }}</td>
                            <td>{{ $payout->total_bookings }}</td>
                            <td>Nu. {{ number_format($payout->total_guest_payments, 2) }}</td>
                            <td style="color: #e74c3c; font-weight: bold;">Nu. {{ number_format($payout->total_commission, 2) }}</td>
                            <td style="color: #27ae60; font-weight: bold;">Nu. {{ number_format($payout->hotel_payout_amount, 2) }}</td>
                            <td>
                                @if($payout->payout_status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($payout->payout_status == 'processing')
                                    <span class="badge badge-info">Processing</span>
                                @else
                                    <span class="badge badge-success">Paid</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.commissions.show', $payout->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($payout->payout_status == 'pending')
                                    <a href="{{ route('admin.commissions.payout-form', $payout->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-check-circle"></i> Process
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Recent Activities Section -->
<div class="dashboard-grid">
    <!-- Recent Reservations -->
    <div class="dashboard-card">
        <div class="card-header">
            <h2><i class="fas fa-calendar-alt"></i> Recent Reservations</h2>
            <a href="{{ route('admin.reservations.index') }}" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="card-body">
            @if(isset($recentBookings) && $recentBookings->count() > 0)
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Guest Name</th>
                                <th>Hotel</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->guest_name }}</td>
                                    <td>{{ $booking->hotel->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                                    <td>
                                        @if($booking->commission)
                                            @if($booking->commission->payment_method == 'pay_online')
                                                <span class="badge badge-info">Online</span>
                                            @else
                                                <span class="badge badge-secondary">At Hotel</span>
                                            @endif
                                        @else
                                            <span style="color: #95a5a6; font-size: 0.85rem;">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(strtoupper($booking->status) == 'CONFIRMED')
                                            <span class="badge badge-success">Confirmed</span>
                                        @elseif(strtoupper($booking->status) == 'PENDING')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif(strtoupper($booking->status) == 'CANCELLED')
                                            <span class="badge badge-danger">Cancelled</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </td>
                                    <td>Nu. {{ number_format($booking->total_price, 2) }}</td>
                                    <td>
                                        @if($booking->commission)
                                            <span style="color: #27ae60; font-weight: bold;">Nu. {{ number_format($booking->commission->commission_amount, 2) }}</span>
                                        @else
                                            <span style="color: #95a5a6; font-size: 0.85rem;">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="no-data">No reservations found</p>
            @endif
        </div>
    </div>
    
    <!-- Recent Hotel Registrations -->
    <div class="dashboard-card">
        <div class="card-header">
            <h2><i class="fas fa-hotel"></i> Recent Hotel Registrations</h2>
            <a href="#" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="card-body">
            @if($recentHotels->count() > 0)
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Hotel Name</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentHotels as $hotel)
                                <tr>
                                    <td>{{ $hotel->name }}</td>
                                    <td>{{ $hotel->owner->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($hotel->status == 'APPROVED')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($hotel->status == 'PENDING')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($hotel->status == 'REJECTED')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $hotel->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $hotel->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="no-data">No hotel registrations found</p>
            @endif
        </div>
    </div>
</div>
@endsection