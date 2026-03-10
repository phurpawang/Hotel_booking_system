@extends('layouts.app')

@section('title', 'Create Booking - ' . $hotel->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-lg p-6">
            <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.index') }}" 
               class="inline-flex items-center bg-white text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg font-semibold mb-4 shadow-md transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reservations
            </a>
            <h1 class="text-4xl font-bold text-white mb-2 drop-shadow-lg">Create New Booking</h1>
            <div class="inline-block bg-white text-gray-900 font-semibold px-6 py-2 rounded-full text-lg shadow-lg">
                <i class="fas fa-hotel mr-2 text-green-600"></i>{{ $hotel->name }}
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <p class="text-green-800">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <p class="text-red-800">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </p>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.reservations.store') }}">
                @csrf

                <!-- Guest Information -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Guest Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guest Name *</label>
                            <input type="text" name="guest_name" value="{{ old('guest_name') }}" required
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('guest_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" required
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('guest_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="guest_email" value="{{ old('guest_email') }}" required
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('guest_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Booking Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Room Type *
                                <span class="text-xs text-gray-500">(System will auto-assign available room)</span>
                            </label>
                            <select name="room_type" id="room_type" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Room Type</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->room_type }}" 
                                            data-price="{{ $type->min_price }}" 
                                            {{ old('room_type') == $type->room_type ? 'selected' : '' }}>
                                        {{ $type->room_type }} - {{ $type->available_count }} available (from Nu. {{ number_format($type->min_price, 2) }}/night)
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle"></i> First available room will be automatically assigned
                            </p>
                            @error('room_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Number of Rooms *</label>
                            <input type="number" name="num_rooms" id="num_rooms" value="{{ old('num_rooms', 1) }}" min="1" required
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   onchange="calculateTotal()">
                            @error('num_rooms')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date *</label>
                            <input type="date" name="check_in_date" id="check_in_date" value="{{ old('check_in_date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" required
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   onchange="calculateTotal()">
                            @error('check_in_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date *</label>
                            <input type="date" name="check_out_date" id="check_out_date" value="{{ old('check_out_date') }}" required
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   onchange="calculateTotal()">
                            @error('check_out_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Number of Guests *</label>
                            <input type="number" name="num_guests" value="{{ old('num_guests', 1) }}" min="1" required
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('num_guests')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Total</label>
                            <input type="text" id="total_display" value="Nu. 0.00" readonly
                                   class="w-full border-gray-300 rounded-lg bg-gray-100 font-bold text-lg">
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Payment Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method *</label>
                            <select name="payment_method" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="CASH" {{ old('payment_method') == 'CASH' ? 'selected' : '' }}>Cash</option>
                                <option value="CARD" {{ old('payment_method') == 'CARD' ? 'selected' : '' }}>Card</option>
                                <option value="BANK_TRANSFER" {{ old('payment_method') == 'BANK_TRANSFER' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="ONLINE" {{ old('payment_method') == 'ONLINE' ? 'selected' : '' }}>Online Payment</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status *</label>
                            <select name="payment_status" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="PENDING" {{ old('payment_status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                <option value="PAID" {{ old('payment_status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Special Requests -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Requests (Optional)</label>
                    <textarea name="special_requests" rows="3" 
                              class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('special_requests') }}</textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-wrap gap-4 mt-8 mb-6" style="display: flex !important;">
                    <button type="submit" style="background-color: #16a34a !important; color: white !important; font-size: 18px !important; font-weight: bold !important; padding: 16px 48px !important; border-radius: 8px !important; box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important; border: none !important; cursor: pointer !important;">
                        <i class="fas fa-save" style="margin-right: 12px;"></i>SAVE BOOKING
                    </button>
                    <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.index') }}" 
                       style="background-color: #6b7280 !important; color: white !important; font-size: 18px !important; font-weight: 600 !important; padding: 16px 40px !important; border-radius: 8px !important; box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important; text-decoration: none !important; display: inline-block !important;">
                        <i class="fas fa-times" style="margin-right: 8px;"></i>Cancel
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
        document.getElementById('total_display').value = 'Nu. ' + total.toFixed(2) + ' (' + nights + ' nights)';
    } else {
        document.getElementById('total_display').value = 'Nu. 0.00';
    }
}

document.getElementById('room_type').addEventListener('change', calculateTotal);
</script>
@endsection
