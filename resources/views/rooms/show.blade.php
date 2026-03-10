@extends('layouts.dashboard-bootstrap')

@section('title', 'Room Details - ' . $room->room_number)

@section('content')
<div class="container-fluid py-4">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2"><i class="bi bi-door-closed-fill me-2"></i>Room {{ $room->room_number }}</h2>
                <p class="text-muted mb-0">{{ $room->room_type }} Room - {{ $room->hotel->name ?? 'Hotel' }}</p>
            </div>
            <div class="d-flex gap-2">
                @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
                <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.edit', $room->id) }}" class="btn btn-warning btn-lg">
                    <i class="bi bi-pencil-square me-2"></i>Edit Room
                </a>
                @endif
                <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.index') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Back to Rooms
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Room Information -->
        <div class="col-lg-8">
            <!-- Basic Info Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i>Room Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Room Number</label>
                                <h4 class="mb-0 text-primary">{{ $room->room_number }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Room Type</label>
                                <h4 class="mb-0">{{ $room->room_type }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Quantity</label>
                                <p class="mb-0 fs-5"><i class="bi bi-stack text-info me-2"></i>{{ $room->quantity ?? 1 }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Capacity</label>
                                <p class="mb-0 fs-5"><i class="bi bi-people-fill text-success me-2"></i>{{ $room->capacity }} guests</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Price per Night</label>
                                <p class="mb-0 fs-5 fw-bold text-success"><i class="bi bi-currency-rupee me-1"></i>{{ number_format($room->price_per_night, 2) }}</p>
                            </div>
                        </div>
                        @if($room->description)
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Description</label>
                                <p class="mb-0">{{ $room->description }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Amenities Card -->
            @if($room->amenities && count($room->amenities) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                    <h5 class="mb-0"><i class="bi bi-stars me-2"></i>Amenities</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @foreach($room->amenities as $amenity)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>{{ $amenity }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Photos Card -->
            @if($room->photos && count($room->photos) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <h5 class="mb-0"><i class="bi bi-images me-2"></i>Room Photos</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @foreach($room->photos as $photo)
                        <div class="col-md-6">
                            <img src="{{ asset('storage/' . $photo) }}" 
                                 alt="Room Photo" 
                                 class="img-fluid rounded shadow-sm">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Cancellation Policy -->
            @if($room->cancellation_policy)
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); color: white;">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Cancellation Policy</h5>
                </div>
                <div class="card-body p-4">
                    <p class="mb-0">{{ $room->cancellation_policy }}</p>
                </div>
            </div>
            @endif

            <!-- Bookings -->
            @if($room->bookings && count($room->bookings) > 0)
            <div class="card shadow-sm">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0"><i class="bi bi-calendar-check-fill me-2"></i>Current & Upcoming Bookings</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Guest</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($room->bookings as $booking)
                                <tr>
                                    <td>
                                        <strong>{{ $booking->guest_name }}</strong><br>
                                        <small class="text-muted">{{ $booking->guest_email }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                                    <td>
                                        @if($booking->status == 'CONFIRMED')
                                            <span class="badge bg-primary">Confirmed</span>
                                        @elseif($booking->status == 'CHECKED_IN')
                                            <span class="badge bg-success">Checked In</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.show', $booking->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye-fill"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 mb-0">No current or upcoming bookings for this room</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-toggle-on me-2"></i>Room Status</h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        @if($room->status == 'AVAILABLE')
                            <div class="badge bg-success fs-5 py-2 px-4 mb-2">
                                <i class="bi bi-check-circle-fill me-2"></i>AVAILABLE
                            </div>
                        @elseif($room->status == 'OCCUPIED')
                            <div class="badge bg-danger fs-5 py-2 px-4 mb-2">
                                <i class="bi bi-person-fill-check me-2"></i>OCCUPIED
                            </div>
                        @elseif($room->status == 'MAINTENANCE')
                            <div class="badge bg-warning fs-5 py-2 px-4 mb-2">
                                <i class="bi bi-tools me-2"></i>MAINTENANCE
                            </div>
                        @else
                            <div class="badge bg-secondary fs-5 py-2 px-4 mb-2">{{ $room->status }}</div>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Availability for Booking</label>
                        <p class="mb-0 fs-5">
                            @if($room->is_available)
                                <i class="bi bi-unlock-fill text-success me-2"></i>Open
                            @else
                                <i class="bi bi-lock-fill text-danger me-2"></i>Closed
                            @endif
                        </p>
                    </div>

                    @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
                    <hr>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="bi bi-gear-fill me-2"></i>Change Status
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#availabilityModal">
                            <i class="bi bi-calendar-check me-2"></i>Toggle Availability
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER', 'RECEPTION']))
            <div class="card shadow-sm">
                <div class="card-header py-3 bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-lightning-charge-fill me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.create') }}?room_id={{ $room->id }}" 
                           class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Create Booking
                        </a>
                        <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.availability', $room->id) }}" 
                           class="btn btn-info">
                            <i class="bi bi-calendar3 me-2"></i>View Calendar
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Change Modal -->
@if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-gear-fill me-2"></i>Change Room Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.rooms.status', $room->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select New Status</label>
                        <select name="status" class="form-select" required>
                            <option value="AVAILABLE" {{ $room->status == 'AVAILABLE' ? 'selected' : '' }}>Available</option>
                            <option value="OCCUPIED" {{ $room->status == 'OCCUPIED' ? 'selected' : '' }}>Occupied</option>
                            <option value="MAINTENANCE" {{ $room->status == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Availability Toggle Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-calendar-check me-2"></i>Toggle Booking Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.rooms.toggle', $room->id) }}">
                @csrf
                <div class="modal-body">
                    <p>Current availability status: 
                        <strong>
                            @if($room->is_available)
                                <span class="text-success">Open for Booking</span>
                            @else
                                <span class="text-danger">Closed for Booking</span>
                            @endif
                        </strong>
                    </p>
                    <p>Are you sure you want to toggle the booking availability for this room?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        @if($room->is_available)
                            Close for Booking
                        @else
                            Open for Booking
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
