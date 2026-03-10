@extends('admin.layout')

@section('title', 'Hotel Details')

@section('content')
<div class="dashboard-header">
    <h1>Hotel Details</h1>
    <div class="header-actions">
        <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn-primary">
            <i class="fas fa-edit"></i> Edit Hotel
        </a>
        <a href="{{ route('admin.hotels.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="details-grid">
    <!-- Hotel Information -->
    <div class="dashboard-card">
        <div class="card-header">
            <h2><i class="fas fa-hotel"></i> Hotel Information</h2>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="detail-label">Hotel ID:</span>
                <span class="detail-value">#{{ $hotel->id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Hotel Name:</span>
                <span class="detail-value">{{ $hotel->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Property Type:</span>
                <span class="detail-value">{{ $hotel->property_type }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Star Rating:</span>
                <span class="detail-value">
                    @if($hotel->star_rating)
                        @for($i = 0; $i < $hotel->star_rating; $i++)
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                        @endfor
                    @else
                        Not rated
                    @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    @if($hotel->status == 'approved')
                        <span class="badge badge-success">Approved</span>
                    @elseif($hotel->status == 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($hotel->status == 'rejected')
                        <span class="badge badge-danger">Rejected</span>
                    @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Registered:</span>
                <span class="detail-value">{{ $hotel->created_at->format('M d, Y g:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="dashboard-card">
        <div class="card-header">
            <h2><i class="fas fa-address-book"></i> Contact Information</h2>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="detail-label">Owner:</span>
                <span class="detail-value">{{ $hotel->owner->name ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span class="detail-value">{{ $hotel->phone }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $hotel->email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Location:</span>
                <span class="detail-value">Bhutan</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Address:</span>
                <span class="detail-value">{{ $hotel->address }}</span>
            </div>
        </div>
    </div>
</div>

@if($hotel->description)
<div class="dashboard-card">
    <div class="card-header">
        <h2><i class="fas fa-info-circle"></i> Description</h2>
    </div>
    <div class="card-body">
        <p>{{ $hotel->description }}</p>
    </div>
</div>
@endif

<!-- Rooms -->
<div class="dashboard-card">
    <div class="card-header">
        <h2><i class="fas fa-bed"></i> Rooms ({{ $hotel->rooms->sum('quantity') }})</h2>
    </div>
    <div class="card-body">
        @if($hotel->rooms->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Price/Night</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hotel->rooms as $room)
                            <tr>
                                <td>{{ $room->room_number }}</td>
                                <td>{{ $room->room_type }}</td>
                                <td>{{ $room->capacity }} guests</td>
                                <td>Nu. {{ number_format($room->price_per_night, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="no-data">No rooms added yet</p>
        @endif
    </div>
</div>

<!-- Recent Bookings -->
<div class="dashboard-card">
    <div class="card-header">
        <h2><i class="fas fa-calendar-check"></i> Recent Bookings</h2>
    </div>
    <div class="card-body">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->guest_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                                <td>
                                    @if(strtoupper($booking->status) == 'CONFIRMED')
                                        <span class="badge badge-success">Confirmed</span>
                                    @elseif(strtoupper($booking->status) == 'PENDING')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $booking->status }}</span>
                                    @endif
                                </td>
                                <td>Nu. {{ number_format($booking->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="no-data">No bookings yet</p>
        @endif
    </div>
</div>

@push('styles')
<style>
.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #666;
}

.detail-value {
    color: #333;
}

@media (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
@endsection
