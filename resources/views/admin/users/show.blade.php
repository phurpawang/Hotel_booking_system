@extends('admin.layout')

@section('title', 'User Details')

@section('content')
<div class="dashboard-header">
    <h1>User Details</h1>
    <a href="{{ route('admin.users.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="dashboard-grid">
    <!-- User Information -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-user"></i> User Information</h3>
        <div class="card-body">
            <div class="info-item">
                <strong>Name:</strong>
                <span>{{ $user->name }}</span>
            </div>
            <div class="info-item">
                <strong>Email:</strong>
                <span>{{ $user->email }}</span>
            </div>
            <div class="info-item">
                <strong>Role:</strong>
                @if($user->role == 'guest')
                    <span class="badge badge-info">Guest</span>
                @elseif($user->role == 'hotel_owner')
                    <span class="badge badge-primary">Hotel Owner</span>
                @elseif($user->role == 'hotel_staff')
                    <span class="badge badge-secondary">Hotel Staff</span>
                @else
                    <span class="badge badge-light">{{ ucfirst($user->role) }}</span>
                @endif
            </div>
            <div class="info-item">
                <strong>Status:</strong>
                @if($user->status == 'active')
                    <span class="badge badge-success">Active</span>
                @else
                    <span class="badge badge-danger">Suspended</span>
                @endif
            </div>
            <div class="info-item">
                <strong>Phone:</strong>
                <span>{{ $user->phone ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <strong>Joined:</strong>
                <span>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y h:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-cog"></i> Account Actions</h3>
        <div class="card-body">
            @if($user->status == 'active')
                <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST" style="margin-bottom: 10px;">
                    @csrf
                    <button type="submit" class="btn-warning" style="width: 100%;">
                        <i class="fas fa-ban"></i> Suspend User
                    </button>
                </form>
            @else
                <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" style="margin-bottom: 10px;">
                    @csrf
                    <button type="submit" class="btn-success" style="width: 100%;">
                        <i class="fas fa-check-circle"></i> Activate User
                    </button>
                </form>
            @endif
            
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" data-confirm="Are you sure you want to delete this user? This action cannot be undone." style="width: 100%;">
                    <i class="fas fa-trash"></i> Delete User
                </button>
            </form>
        </div>
    </div>
</div>

<!-- User Activity -->
@if($user->role == 'guest')
<div class="dashboard-card">
    <h3 class="card-title"><i class="fas fa-history"></i> Recent Bookings</h3>
    <div class="card-body">
        @php
            $bookings = \App\Models\Booking::where('guest_email', $user->email)->latest()->take(10)->get();
        @endphp
        
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Hotel</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->hotel->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                                <td>Nu. {{ number_format($booking->total_price, 2) }}</td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="no-data">No bookings found</p>
        @endif
    </div>
</div>
@endif

@if($user->role == 'hotel_owner')
<div class="dashboard-card">
    <h3 class="card-title"><i class="fas fa-hotel"></i> Owned Hotels</h3>
    <div class="card-body">
        @php
            $hotels = \App\Models\Hotel::where('owner_id', $user->id)->get();
        @endphp
        
        @if($hotels->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Hotel Name</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Rooms</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hotels as $hotel)
                            <tr>
                                <td>{{ $hotel->name }}</td>
                                <td>Bhutan</td>
                                <td>
                                    @if($hotel->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($hotel->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $hotel->rooms->sum('quantity') }} rooms</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="no-data">No hotels registered</p>
        @endif
    </div>
</div>
@endif
@endsection
