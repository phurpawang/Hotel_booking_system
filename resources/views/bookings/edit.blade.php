@extends('layouts.app')

@section('title', 'Edit Booking - ' . $booking->booking_id)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }
    
    .dashboard-container {
        background: #F8FAFC;
        min-height: 100vh;
        padding: 40px 20px;
    }
    
    .booking-wrapper {
        max-width: 1100px;
        margin: 0 auto;
    }
    
    .booking-header {
        background: linear-gradient(135deg, #2563EB 0%, #7C3AED 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.15);
    }
    
    .booking-id-badge {
        background: #FCD34D;
        color: #78350F;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        display: inline-block;
        margin-bottom: 12px;
    }
    
    .info-pill {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        color: white;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.9rem;
        display: inline-block;
        margin-right: 8px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .total-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .total-label {
        color: #64748B;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    .total-amount {
        color: #10B981;
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    
    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #E2E8F0;
    }
    
    .section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2563EB 0%, #7C3AED 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        color: white;
        font-size: 1.2rem;
    }
    
    .section-title {
        color: #1E293B;
        font-weight: 700;
        font-size: 1.15rem;
        margin: 0;
    }
    
    .form-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    
    .input-group-icon {
        position: relative;
    }
    
    .input-group-icon .form-control,
    .input-group-icon .form-select {
        padding-left: 45px;
    }
    
    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        z-index: 10;
        pointer-events: none;
    }
    
    .form-control, .form-select {
        border: 2px solid #E2E8F0;
        border-radius: 10px;
        height: 44px;
        padding: 10px 15px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        color: #1E293B;
    }
    
    .form-control:hover, .form-select:hover {
        border-color: #CBD5E1;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    
    textarea.form-control {
        height: auto;
        min-height: 100px;
    }
    
    .total-display {
        background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        border: none;
        text-align: center;
        height: 54px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .btn-save {
        background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        border: none;
        padding: 14px 50px;
        font-weight: 700;
        font-size: 1rem;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
        transition: all 0.3s ease;
        color: white;
    }
    
    .btn-save:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669 0%, #10B981 100%);
    }
    
    .btn-cancel {
        background: #64748B;
        border: none;
        padding: 14px 40px;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        color: white;
    }
    
    .btn-cancel:hover {
        background: #475569;
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(100, 116, 139, 0.3);
    }
    
    .btn-back {
        background: white;
        border: 2px solid #E2E8F0;
        color: #64748B;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .btn-back:hover {
        border-color: #2563EB;
        color: #2563EB;
        background: #EFF6FF;
    }
    
    .alert {
        border-radius: 12px;
        border: none;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }
    
    .alert i {
        margin-right: 12px;
        font-size: 1.2rem;
    }
    
    .alert-success {
        background: #D1FAE5;
        color: #065F46;
    }
    
    .alert-danger {
        background: #FEE2E2;
        color: #991B1B;
    }
    
    .alert-warning {
        background: #FEF3C7;
        color: #92400E;
    }
    
    .required-star {
        color: #EF4444;
        margin-left: 2px;
    }
    
    @media (max-width: 768px) {
        .booking-header {
            padding: 20px;
        }
        
        .total-card {
            margin-top: 20px;
        }
        
        .btn-save, .btn-cancel {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .section-card {
            padding: 20px;
        }
    }
</style>

<div class="dashboard-container">
    <div class="booking-wrapper">
        <!-- Back Button -->
        <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.show', $booking->id) }}" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>Back to Booking
        </a>

        <!-- Booking Header -->
        <div class="booking-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="booking-id-badge">
                        <i class="fas fa-ticket-alt me-2"></i>{{ $booking->booking_id }}
                    </span>
                    <h2 class="text-white mb-3 mt-3">
                        <i class="fas fa-edit me-2"></i>Edit Booking
                    </h2>
                    <div>
                        <span class="info-pill">
                            <i class="fas fa-hotel me-2"></i>{{ $booking->room->hotel->name ?? 'Hotel' }}
                        </span>
                        <span class="info-pill">
                            <i class="fas fa-door-open me-2"></i>Room {{ $booking->room->room_number }}
                        </span>
                        <span class="info-pill">
                            <i class="fas fa-moon me-2"></i>{{ $booking->check_in_date->diffInDays($booking->check_out_date) }} night(s)
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 mt-3 mt-lg-0">
                    <div class="total-card text-center">
                        <div class="total-label">TOTAL AMOUNT</div>
                        <div class="total-amount">Nu. {{ number_format($booking->total_price, 2) }}</div>
                        <small class="text-muted d-block mt-2">Current booking total</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($booking->status != 'CONFIRMED')
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <span><strong>Notice:</strong> Only bookings with CONFIRMED status can be edited.</span>
            </div>
        @endif

        <!-- Edit Form -->
        <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.reservations.update', $booking->id) }}">
            @csrf
            @method('PUT')

            <!-- Guest Information Section -->
            <div class="section-card">
                <div class="card-body p-4">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 class="section-title">Guest Information</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Guest Name<span class="required-star">*</span></label>
                            <div class="input-group-icon">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" name="guest_name" 
                                       class="form-control @error('guest_name') is-invalid @enderror" 
                                       value="{{ old('guest_name', $booking->guest_name) }}" 
                                       placeholder="Enter guest name" required>
                                @error('guest_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number<span class="required-star">*</span></label>
                            <div class="input-group-icon">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="text" name="guest_phone" 
                                       class="form-control @error('guest_phone') is-invalid @enderror" 
                                       value="{{ old('guest_phone', $booking->guest_phone) }}" 
                                       placeholder="Enter phone number" required>
                                @error('guest_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email Address<span class="required-star">*</span></label>
                            <div class="input-group-icon">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" name="guest_email" 
                                       class="form-control @error('guest_email') is-invalid @enderror" 
                                       value="{{ old('guest_email', $booking->guest_email) }}" 
                                       placeholder="Enter email address" required>
                                @error('guest_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Booking Details Section -->
                <div class="section-card">
                    <div class="card-body p-4">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h5 class="section-title">Booking Details</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Room<span class="required-star">*</span></label>
                                <div class="input-group-icon">
                                    <i class="fas fa-door-open input-icon"></i>
                                    <select name="room_id" id="room_id" 
                                            class="form-select @error('room_id') is-invalid @enderror" required>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" 
                                                data-price="{{ $room->price_per_night }}"
                                                {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>
                                            Room {{ $room->room_number }} - {{ $room->room_type }} 
                                            (Nu. {{ number_format($room->price_per_night, 2) }}/night)
                                        </option>
                                    @endforeach
                                    </select>
                                    @error('room_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Number of Rooms<span class="required-star">*</span></label>
                                <div class="input-group-icon">
                                    <i class="fas fa-hashtag input-icon"></i>
                                    <input type="number" name="num_rooms" id="num_rooms" 
                                           class="form-control @error('num_rooms') is-invalid @enderror" 
                                           value="{{ old('num_rooms', $booking->num_rooms) }}" 
                                           min="1" placeholder="Enter number of rooms" required onchange="calculateTotal()">
                                    @error('num_rooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check-in Date<span class="required-star">*</span></label>
                                <div class="input-group-icon">
                                    <i class="fas fa-calendar-alt input-icon"></i>
                                    <input type="date" name="check_in_date" id="check_in_date" 
                                           class="form-control @error('check_in_date') is-invalid @enderror" 
                                           value="{{ old('check_in_date', $booking->check_in_date->format('Y-m-d')) }}" 
                                               required onchange="calculateTotal()">
                                    @error('check_in_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check-out Date<span class="required-star">*</span></label>
                                <div class="input-group-icon">
                                    <i class="fas fa-calendar-alt input-icon"></i>
                                    <input type="date" name="check_out_date" id="check_out_date" 
                                           class="form-control @error('check_out_date') is-invalid @enderror" 
                                           value="{{ old('check_out_date', $booking->check_out_date->format('Y-m-d')) }}" 
                                               required onchange="calculateTotal()">
                                    @error('check_out_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Number of Guests<span class="required-star">*</span></label>
                                <div class="input-group-icon">
                                    <i class="fas fa-users input-icon"></i>
                                    <input type="number" name="num_guests" 
                                           class="form-control @error('num_guests') is-invalid @enderror" 
                                           value="{{ old('num_guests', $booking->num_guests) }}" 
                                           min="1" placeholder="Enter number of guests" required>
                                    @error('num_guests')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estimated Total</label>
                                <input type="text" id="total_display" 
                                       class="form-control total-display" 
                                       value="Nu. {{ number_format($booking->total_price, 2) }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information Section -->
                <div class="section-card">
                    <div class="card-body p-4">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h5 class="section-title">Payment Information</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Payment Method<span class="required-star">*</span></label>
                                <div class="input-group-icon">
                                    <i class="fas fa-money-bill-wave input-icon"></i>
                                    <select name="payment_method" class="form-select" required>
                                        <option value="CASH" {{ old('payment_method', $booking->payment_method) == 'CASH' ? 'selected' : '' }}>
                                            Cash Payment
                                        </option>
                                        <option value="CARD" {{ old('payment_method', $booking->payment_method) == 'CARD' ? 'selected' : '' }}>
                                            Card Payment
                                        </option>
                                        <option value="BANK_TRANSFER" {{ old('payment_method', $booking->payment_method) == 'BANK_TRANSFER' ? 'selected' : '' }}>
                                            Bank Transfer
                                        </option>
                                        <option value="ONLINE" {{ old('payment_method', $booking->payment_method) == 'ONLINE' ? 'selected' : '' }}>
                                            Online Payment
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Status<span class="required-star">*</span></label>
                                <div class="input-group-icon">
                                    <i class="fas fa-check-circle input-icon"></i>
                                    <select name="payment_status" class="form-select" required>
                                        <option value="PENDING" {{ old('payment_status', $booking->payment_status) == 'PENDING' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="PAID" {{ old('payment_status', $booking->payment_status) == 'PAID' ? 'selected' : '' }}>
                                            Paid
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Special Requests Section -->
                <div class="section-card">
                    <div class="card-body p-4">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-comment-alt"></i>
                            </div>
                            <h5 class="section-title">Special Requests</h5>
                        </div>
                        <textarea name="special_requests" rows="4" 
                                  class="form-control" 
                                  placeholder="Enter any special requests or notes...">{{ old('special_requests', $booking->special_requests) }}</textarea>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>Optional - Any special requirements for this booking
                        </small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mb-5">
                    <button type="submit" class="btn btn-save me-3">
                        <i class="fas fa-save me-2"></i>SAVE CHANGES
                    </button>
                    <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.show', $booking->id) }}" 
                       class="btn btn-cancel">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function calculateTotal() {
    const roomSelect = document.getElementById('room_id');
    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
    const pricePerNight = parseFloat(selectedOption.dataset.price) || 0;
    
    const numRooms = parseInt(document.getElementById('num_rooms').value) || 1;
    const checkIn = new Date(document.getElementById('check_in_date').value);
    const checkOut = new Date(document.getElementById('check_out_date').value);
    
    if (checkIn && checkOut && checkOut > checkIn) {
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        const total = pricePerNight * nights * numRooms;
        document.getElementById('total_display').value = 'Nu. ' + total.toFixed(2) + ' (' + nights + ' night' + (nights > 1 ? 's' : '') + ')';
    } else if (checkOut <= checkIn) {
        document.getElementById('total_display').value = 'Invalid dates';
    }
}

// Attach event listeners
document.getElementById('room_id').addEventListener('change', calculateTotal);
document.getElementById('num_rooms').addEventListener('input', calculateTotal);
document.getElementById('check_in_date').addEventListener('change', calculateTotal);
document.getElementById('check_out_date').addEventListener('change', calculateTotal);

// Initial calculation
calculateTotal();
</script>
@endsection
