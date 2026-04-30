@extends('admin.layout')

@section('title', 'Hotel Details')

@section('content')
<div class="hotel-details-container">
    <div class="dashboard-header hotel-header-gradient">
        <div class="header-content">
            <h1><i class="fas fa-building"></i> Hotel Details</h1>
            <p class="hotel-name-subtitle">{{ $hotel->name }}</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn-primary btn-edit">
                <i class="fas fa-edit"></i> Edit Hotel
            </a>
            <a href="{{ route('admin.hotels.index') }}" class="btn-secondary btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

<div class="details-grid">
    <!-- Hotel Information -->
    <div class="dashboard-card card-hotel-info">
        <div class="card-header header-gradient-blue">
            <h2><i class="fas fa-info-circle"></i> Hotel Information</h2>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-id-card"></i> Hotel ID:</span>
                <span class="detail-value detail-value-id">#{{ $hotel->id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-hotel"></i> Hotel Name:</span>
                <span class="detail-value detail-value-name">{{ $hotel->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-building"></i> Property Type:</span>
                <span class="detail-value detail-value-type">{{ $hotel->property_type }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-star"></i> Star Rating:</span>
                <span class="detail-value">
                    @if($hotel->star_rating)
                        <span class="star-rating">
                            @for($i = 0; $i < $hotel->star_rating; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </span>
                    @else
                        <span class="badge badge-secondary">Not rated</span>
                    @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-circle"></i> Status:</span>
                <span class="detail-value">
                    @if($hotel->status == 'approved')
                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Approved</span>
                    @elseif($hotel->status == 'pending')
                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                    @elseif($hotel->status == 'rejected')
                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Rejected</span>
                    @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-calendar"></i> Registered:</span>
                <span class="detail-value detail-value-date">{{ $hotel->created_at->format('M d, Y g:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="dashboard-card card-contact-info">
        <div class="card-header header-gradient-green">
            <h2><i class="fas fa-phone"></i> Contact Information</h2>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-user"></i> Owner:</span>
                <span class="detail-value detail-value-owner">{{ $hotel->owner->name ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-mobile-alt"></i> Phone:</span>
                <span class="detail-value detail-value-phone">
                    <a href="tel:{{ $hotel->phone }}" class="phone-link">{{ $hotel->phone }}</a>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-envelope"></i> Email:</span>
                <span class="detail-value detail-value-email">
                    <a href="mailto:{{ $hotel->email }}" class="email-link">{{ $hotel->email }}</a>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Location:</span>
                <span class="detail-value detail-value-location">Bhutan</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-map-pin"></i> Address:</span>
                <span class="detail-value detail-value-address">{{ $hotel->address }}</span>
            </div>
        </div>
    </div>
</div>

@if($hotel->description)
<div class="dashboard-card card-description">
    <div class="card-header header-gradient-orange">
        <h2><i class="fas fa-align-left"></i> Description</h2>
    </div>
    <div class="card-body">
        <div class="description-content">
            {{ $hotel->description }}
        </div>
    </div>
</div>
@endif

<!-- Rooms -->
<div class="dashboard-card card-rooms">
    <div class="card-header header-gradient-purple">
        <h2><i class="fas fa-door-open"></i> Rooms ({{ $hotel->rooms->sum('quantity') }})</h2>
    </div>
    <div class="card-body">
        @if($hotel->rooms->count() > 0)
            <div class="table-responsive">
                <table class="data-table rooms-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-key"></i> Room Number</th>
                            <th><i class="fas fa-home"></i> Type</th>
                            <th><i class="fas fa-users"></i> Capacity</th>
                            <th><i class="fas fa-money-bill-alt"></i> Price/Night</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hotel->rooms as $room)
                            <tr class="room-row">
                                <td class="room-number">{{ $room->room_number }}</td>
                                <td class="room-type">{{ $room->room_type }}</td>
                                <td class="room-capacity">{{ $room->capacity }} guests</td>
                                <td class="room-price">Nu. {{ number_format($room->price_per_night, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p class="no-data">No rooms added yet</p>
            </div>
        @endif
    </div>
</div>

<!-- Recent Bookings -->
<div class="dashboard-card card-bookings">
    <div class="card-header header-gradient-red">
        <h2><i class="fas fa-calendar-check"></i> Recent Bookings</h2>
    </div>
    <div class="card-body">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="data-table bookings-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> Booking ID</th>
                            <th><i class="fas fa-user"></i> Guest</th>
                            <th><i class="fas fa-sign-in-alt"></i> Check-in</th>
                            <th><i class="fas fa-sign-out-alt"></i> Check-out</th>
                            <th><i class="fas fa-check"></i> Status</th>
                            <th><i class="fas fa-credit-card"></i> Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr class="booking-row">
                                <td class="booking-id">#{{ $booking->id }}</td>
                                <td class="booking-guest">{{ $booking->guest_name }}</td>
                                <td class="booking-date">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                <td class="booking-date">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                                <td>
                                    @if(strtoupper($booking->status) == 'CONFIRMED')
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Confirmed</span>
                                    @elseif(strtoupper($booking->status) == 'PENDING')
                                        <span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Pending</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $booking->status }}</span>
                                    @endif
                                </td>
                                <td class="booking-amount">Nu. {{ number_format($booking->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p class="no-data">No bookings yet</p>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
/* ===== CONTAINER & LAYOUT ===== */
.hotel-details-container {
    padding-top: 20px;
}

.hotel-header-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px 40px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.4);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content h1 {
    font-size: 2.2em;
    margin: 0;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.hotel-name-subtitle {
    font-size: 1.1em;
    margin: 8px 0 0 0;
    opacity: 0.95;
    font-weight: 300;
}

.header-actions {
    display: flex;
    gap: 12px;
}

/* ===== DETAILS GRID ===== */
.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 30px;
}

/* ===== CARD STYLING ===== */
.dashboard-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.dashboard-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

/* ===== CARD HEADERS WITH GRADIENTS ===== */
.card-header {
    padding: 20px 25px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-header h2 {
    margin: 0;
    font-size: 1.3em;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-gradient-blue {
    background: linear-gradient(135deg, #0066ff 0%, #0099ff 100%);
}

.header-gradient-green {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.header-gradient-orange {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.header-gradient-purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.header-gradient-red {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
}

/* ===== CARD BODY ===== */
.card-body {
    padding: 25px;
    line-height: 1.8;
}

/* ===== DETAIL ROWS ===== */
.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    border-bottom: 1px solid #f0f0f0;
    gap: 20px;
}

.detail-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.detail-label {
    font-weight: 700;
    color: #555;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.95em;
    min-width: 150px;
}

.detail-label i {
    width: 20px;
    text-align: center;
}

.detail-value {
    color: #333;
    font-weight: 500;
    flex: 1;
    text-align: right;
}

/* ===== SPECIFIC VALUE STYLES ===== */
.detail-value-id {
    color: #0066ff;
    font-weight: 700;
    font-size: 1.1em;
}

.detail-value-name {
    color: #667eea;
    font-weight: 700;
    font-size: 1.05em;
}

.detail-value-type {
    color: #764ba2;
    font-weight: 600;
}

.detail-value-date {
    color: #666;
}

.detail-value-owner {
    color: #11998e;
    font-weight: 600;
}

.detail-value-phone,
.detail-value-email {
    color: #666;
}

.phone-link,
.email-link {
    color: #0066ff;
    text-decoration: none;
    border-bottom: 2px dotted #0066ff;
    transition: all 0.2s ease;
}

.phone-link:hover,
.email-link:hover {
    color: #0052cc;
    border-bottom-color: #0052cc;
}

.detail-value-location {
    color: #f5576c;
    font-weight: 600;
}

.detail-value-address {
    color: #666;
    line-height: 1.6;
}

/* ===== STAR RATING ===== */
.star-rating {
    display: flex;
    gap: 4px;
    justify-content: flex-end;
}

.star-rating i {
    color: #ffc107;
    font-size: 1.15em;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* ===== BADGES ===== */
.badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.badge-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.badge-warning {
    background: linear-gradient(135deg, #ffa500 0%, #ffb84d 100%);
    color: white;
}

.badge-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    color: white;
}

.badge-secondary {
    background: #e0e0e0;
    color: #666;
}

/* ===== DESCRIPTION SECTION ===== */
.description-content {
    color: #444;
    line-height: 1.8;
    font-size: 1em;
    padding: 10px;
    background: #f9f9f9;
    border-left: 4px solid #f5576c;
    border-radius: 4px;
}

/* ===== TABLES ===== */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95em;
}

.data-table thead {
    background: #f8f9fa;
}

.data-table thead th {
    padding: 16px;
    text-align: left;
    font-weight: 700;
    color: #333;
    border-bottom: 2px solid #e0e0e0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.data-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #f0f0f0;
    color: #555;
}

.rooms-table tbody tr:hover,
.bookings-table tbody tr:hover {
    background-color: #f5f5f5;
}

.room-row:hover {
    background: rgba(102, 126, 234, 0.05);
}

.booking-row:hover {
    background: rgba(255, 107, 107, 0.05);
}

.room-number,
.booking-id {
    font-weight: 700;
    color: #667eea;
}

.room-type {
    color: #666;
}

.room-capacity {
    color: #11998e;
    font-weight: 500;
}

.room-price {
    color: #38ef7d;
    font-weight: 700;
}

.booking-guest {
    color: #666;
    font-weight: 500;
}

.booking-date {
    color: #888;
}

.booking-amount {
    color: #ff6b6b;
    font-weight: 700;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-state i {
    font-size: 3em;
    color: #ddd;
    display: block;
    margin-bottom: 15px;
}

.no-data {
    color: #999;
    font-size: 1.05em;
    margin: 0;
}

/* ===== BUTTONS ===== */
.btn-primary,
.btn-secondary {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95em;
}

.btn-primary {
    background: #38ef7d;
    color: white;
    box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3);
}

.btn-primary:hover {
    background: #2ed470;
    box-shadow: 0 6px 20px rgba(56, 239, 125, 0.4);
    transform: translateY(-2px);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid white;
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .hotel-header-gradient {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .detail-value {
        text-align: left;
    }
}

@media (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .hotel-header-gradient {
        padding: 20px;
        border-radius: 8px;
    }
    
    .header-content h1 {
        font-size: 1.8em;
    }
    
    .card-header h2 {
        font-size: 1.1em;
    }
    
    .data-table {
        font-size: 0.9em;
    }
    
    .data-table thead th,
    .data-table tbody td {
        padding: 10px 8px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .hotel-header-gradient {
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .header-content h1 {
        font-size: 1.4em;
    }
    
    .hotel-name-subtitle {
        font-size: 0.95em;
    }
    
    .detail-label {
        min-width: auto;
        font-size: 0.85em;
    }
    
    .data-table thead th {
        padding: 8px 6px;
        font-size: 0.8em;
    }
    
    .data-table tbody td {
        padding: 8px 6px;
        font-size: 0.85em;
    }
}
</style>
@endpush
@endsection
