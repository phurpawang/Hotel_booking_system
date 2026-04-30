@extends(
    strtoupper(Auth::user()->role) === 'MANAGER' ? 'manager.layouts.app' :
    (in_array(strtoupper(Auth::user()->role), ['RECEPTIONIST', 'RECEPTION']) ? 'reception.layouts.app' : 'layouts.owner-bootstrap')
)

@section('title', 'New Reservation')

@section('header')
    <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">New Reservation</h2>
    <p class="text-gray-600 text-sm mt-1">Create a new booking for your hotel</p>
@endsection

@section('styles')
<style>
    .form-control, .form-select {
        border: 2px solid #e0e0e0 !important;
        border-radius: 8px !important;
        padding: 0.75rem !important;
        font-size: 1rem !important;
        transition: all 0.3s ease !important;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15) !important;
        outline: none !important;
    }
    
    .form-label {
        font-weight: 600 !important;
        color: #333 !important;
        margin-bottom: 0.5rem !important;
    }
</style>
@endsection

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 2rem;">
    <div>
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);">
            <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.index') }}" 
               style="display: inline-flex; align-items: center; background: white; color: #667eea; padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 600; margin-bottom: 1rem; text-decoration: none; transition: all 0.3s ease;">
                <i class="bi bi-arrow-left me-2"></i>Back to Reservations
            </a>
            <h1 style="font-size: 2rem; font-weight: 700; color: white; margin: 0 0 1rem 0;"><i class="bi bi-plus-circle-fill me-2"></i>Create New Reservation</h1>
            <div style="display: inline-block; background: white; color: #333; font-weight: 600; padding: 0.8rem 1.5rem; border-radius: 20px; font-size: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <i class="bi bi-building me-2" style="color: #667eea;"></i>{{ $hotel->name }}
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px;">
                <p style="color: #155724; margin: 0;"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px;">
                <p style="color: #721c24; margin: 0;"><i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Form Card -->
        <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.reservations.store') }}">
                @csrf

                <!-- Guest Information Section -->
                <div style="border-bottom: 2px solid #f0f0f0; padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #667eea; margin-bottom: 1rem;">
                        <i class="bi bi-person-check-fill me-2"></i>Guest Information
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div>
                            <label class="form-label"><i class="bi bi-person me-1"></i>Guest Name *</label>
                            <input type="text" name="guest_name" value="{{ old('guest_name') }}" required
                                   class="form-control" placeholder="e.g., John Doe">
                            @error('guest_name')
                                <p style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label"><i class="bi bi-telephone me-1"></i>Phone Number *</label>
                            <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" required
                                   class="form-control" placeholder="e.g., +975 1234567">
                            @error('guest_phone')
                                <p style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="grid-column: 1 / -1;">
                            <label class="form-label"><i class="bi bi-envelope me-1"></i>Email *</label>
                            <input type="email" name="guest_email" value="{{ old('guest_email') }}" required
                                   class="form-control" placeholder="e.g., guest@example.com">
                            @error('guest_email')
                                <p style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Booking Details Section -->
                <div style="border-bottom: 2px solid #f0f0f0; padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #667eea; margin-bottom: 1rem;">
                        <i class="bi bi-calendar-event-fill me-2"></i>Booking Details
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div>
                            <label class="form-label"><i class="bi bi-door-closed me-1"></i>Room Type *</label>
                            <select name="room_type" id="room_type" required
                                    class="form-control">
                                <option value="">-- Select Room Type --</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->room_type }}" 
                                            data-price="{{ $type->min_price }}" 
                                            {{ old('room_type') == $type->room_type ? 'selected' : '' }}>
                                        {{ $type->room_type }} - {{ $type->available_count }} available (Nu. {{ number_format($type->min_price, 2) }}/night)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type')
                                <p style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label"><i class="bi bi-box me-1"></i>Number of Rooms *</label>
                            <input type="number" name="num_rooms" id="num_rooms" value="{{ old('num_rooms', 1) }}" min="1" required
                                   class="form-control" onchange="calculateTotal()">
                            @error('num_rooms')
                                <p style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label"><i class="bi bi-calendar me-1"></i>Check-in Date *</label>
                            <input type="date" name="check_in_date" id="check_in_date" value="{{ old('check_in_date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" required class="form-control" onchange="calculateTotal()">
                            @error('check_in_date')
                                <p style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label"><i class="bi bi-calendar me-1"></i>Check-out Date *</label>
                            <input type="date" name="check_out_date" id="check_out_date" value="{{ old('check_out_date') }}" required
                                   class="form-control" onchange="calculateTotal()">
                            @error('check_out_date')
                                <p style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label"><i class="bi bi-people me-1"></i>Number of Guests *</label>
                            <input type="number" name="num_guests" value="{{ old('num_guests', 1) }}" min="1" required
                                   class="form-control">
                            @error('num_guests')
                                <p style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label"><i class="bi bi-cash-coin me-1"></i>Estimated Total</label>
                            <input type="text" id="total_display" value="Nu. 0.00" readonly
                                   class="form-control" style="background: #f5f5f5; font-weight: 700; color: #667eea; font-size: 1.1rem;">
                        </div>
                    </div>
                </div>

                <!-- Payment Information Section -->
                <div style="border-bottom: 2px solid #f0f0f0; padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #667eea; margin-bottom: 1rem;">
                        <i class="bi bi-credit-card-fill me-2"></i>Payment Information
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div>
                            <label class="form-label"><i class="bi bi-wallet2 me-1"></i>Payment Method *</label>
                            <select name="payment_method" required class="form-control">
                                <option value="">-- Select Payment Method --</option>
                                <option value="CASH" {{ old('payment_method') == 'CASH' ? 'selected' : '' }}>💵 Cash</option>
                                <option value="CARD" {{ old('payment_method') == 'CARD' ? 'selected' : '' }}>💳 Card</option>
                                <option value="BANK_TRANSFER" {{ old('payment_method') == 'BANK_TRANSFER' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                                <option value="ONLINE" {{ old('payment_method') == 'ONLINE' ? 'selected' : '' }}>🌐 Online Payment</option>
                            </select>
                            @error('payment_method')
                                <p style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label"><i class="bi bi-check-circle me-1"></i>Payment Status *</label>
                            <select name="payment_status" required class="form-control">
                                <option value="">-- Select Payment Status --</option>
                                <option value="PENDING" {{ old('payment_status') == 'PENDING' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="PAID" {{ old('payment_status') == 'PAID' ? 'selected' : '' }}>✅ Paid</option>
                            </select>
                            @error('payment_status')
                                <p style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Special Requests Section -->
                <div style="margin-bottom: 2rem;">
                    <label class="form-label"><i class="bi bi-chat-left-text me-1"></i>Special Requests (Optional)</label>
                    <textarea name="special_requests" rows="4" 
                              class="form-control">{{ old('special_requests') }}</textarea>
                </div>

                <!-- Submit Buttons -->
                <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 2rem;">
                    <button type="submit" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; font-size: 1rem; font-weight: 700; padding: 0.9rem 2rem; border-radius: 8px; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-save"></i>SAVE BOOKING
                    </button>
                    <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.index') }}" 
                       style="background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); color: white; font-size: 1rem; font-weight: 700; padding: 0.9rem 2rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3); transition: all 0.3s ease;">
                        <i class="bi bi-x-circle"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateTotal() {
    const roomSelect = document.getElementById('room_type');
    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
    const pricePerNight = parseFloat(selectedOption.dataset.price) || 0;
    
    const numRooms = parseInt(document.getElementById('num_rooms').value) || 1;
    const checkIn = new Date(document.getElementById('check_in_date').value);
    const checkOut = new Date(document.getElementById('check_out_date').value);
    
    if (checkIn && checkOut && checkOut > checkIn) {
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        const total = pricePerNight * nights * numRooms;
        document.getElementById('total_display').value = 'Nu. ' + total.toFixed(2) + ' (' + nights + ' night' + (nights !== 1 ? 's' : '') + ')';
    } else {
        document.getElementById('total_display').value = 'Nu. 0.00';
    }
}

// Initialize calculation when page loads
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
    document.getElementById('room_type').addEventListener('change', calculateTotal);
    document.getElementById('num_rooms').addEventListener('change', calculateTotal);
    document.getElementById('check_in_date').addEventListener('change', calculateTotal);
    document.getElementById('check_out_date').addEventListener('change', calculateTotal);
});
</script>

@endsection
