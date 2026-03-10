@extends('admin.layout')

@section('title', 'Reservation Details')

@section('content')
<div class="dashboard-header">
    <h1>Reservation Details #{{ $reservation->id }}</h1>
    <a href="{{ route('admin.reservations.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="dashboard-grid">
    <!-- Guest Information -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-user"></i> Guest Information</h3>
        <div class="card-body">
            <div class="info-item">
                <strong>Name:</strong>
                <span>{{ $reservation->guest_name }}</span>
            </div>
            <div class="info-item">
                <strong>Email:</strong>
                <span>{{ $reservation->guest_email }}</span>
            </div>
            <div class="info-item">
                <strong>Phone:</strong>
                <span>{{ $reservation->guest_phone }}</span>
            </div>
            <div class="info-item">
                <strong>Booking Date:</strong>
                <span>{{ \Carbon\Carbon::parse($reservation->created_at)->format('M d, Y h:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-calendar"></i> Booking Details</h3>
        <div class="card-body">
            <div class="info-item">
                <strong>Hotel:</strong>
                <span>{{ $reservation->hotel->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <strong>Room:</strong>
                <span>{{ $reservation->room->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <strong>Check-in:</strong>
                <span>{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</span>
            </div>
            <div class="info-item">
                <strong>Check-out:</strong>
                <span>{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</span>
            </div>
            <div class="info-item">
                <strong>Nights:</strong>
                <span>{{ \Carbon\Carbon::parse($reservation->check_in_date)->diffInDays(\Carbon\Carbon::parse($reservation->check_out_date)) }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-money-bill"></i> Payment Information</h3>
        <div class="card-body">
            <div class="info-item">
                <strong>Total Amount:</strong>
                <span style="font-size: 1.3rem; color: var(--primary-color);">Nu. {{ number_format($reservation->total_price, 2) }}</span>
            </div>
            <div class="info-item">
                <strong>Payment Status:</strong>
                @if($reservation->payment_status == 'PAID')
                    <span class="badge badge-success">Paid</span>
                @elseif($reservation->payment_status == 'PENDING')
                    <span class="badge badge-warning">Pending</span>
                @elseif($reservation->payment_status == 'REFUNDED')
                    <span class="badge badge-secondary">Refunded</span>
                @endif
            </div>
            <div class="info-item">
                <strong>Payment Method:</strong>
                <span>{{ ucfirst($reservation->payment_method ?? 'N/A') }}</span>
            </div>
            @if($reservation->payment_screenshot)
            <div class="info-item">
                <strong>Payment Screenshot:</strong>
                <a href="{{ asset('storage/' . $reservation->payment_screenshot) }}" target="_blank" class="btn-view">View Image</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Update Status -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-edit"></i> Update Reservation Status</h3>
        <div class="card-body">
            <form action="{{ route('admin.reservations.status', $reservation->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="status">Reservation Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="PENDING" {{ $reservation->status == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        <option value="CONFIRMED" {{ $reservation->status == 'CONFIRMED' ? 'selected' : '' }}>Confirmed</option>
                        <option value="CHECKED_IN" {{ $reservation->status == 'CHECKED_IN' ? 'selected' : '' }}>Checked In</option>
                        <option value="CHECKED_OUT" {{ $reservation->status == 'CHECKED_OUT' ? 'selected' : '' }}>Checked Out</option>
                        <option value="CANCELLED" {{ $reservation->status == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Update Status</button>
            </form>
        </div>
    </div>
</div>

@if($reservation->special_requests)
<div class="dashboard-card">
    <h3 class="card-title"><i class="fas fa-comment"></i> Special Requests</h3>
    <div class="card-body">
        <p>{{ $reservation->special_requests }}</p>
    </div>
</div>
@endif
@endsection
