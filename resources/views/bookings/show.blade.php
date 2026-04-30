@extends(
    strtoupper(Auth::user()->role) === 'MANAGER' ? 'manager.layouts.app' :
    (in_array(strtoupper(Auth::user()->role), ['RECEPTIONIST', 'RECEPTION']) ? 'reception.layouts.app' : 'layouts.owner-bootstrap')
)

@section('title', 'Reservation Details')

@section('header')
    <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Booking Details</h2>
    <p class="text-gray-600 text-sm mt-1">Booking #{{ $booking->booking_id }}</p>
@endsection

@section('styles')
<style>
</style>
@endsection

@section('content')
    <div class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 min-h-screen p-6">
        <!-- Header with Back Button -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.index') }}" 
               class="inline-flex items-center text-white hover:text-blue-300 transition font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reservations
            </a>
            <div class="flex items-center space-x-2">
                <span class="px-4 py-2 bg-blue-500 text-white rounded-full text-sm font-semibold">
                    Booking #{{ $booking->booking_id }}
                </span>
            </div>
        </div>

        <!-- Date Display -->
        <div class="text-white mb-6 text-sm">
            <p>{{ now()->format('l, F d, Y') }}</p>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Details (2 columns) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Guest Information Card -->
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl shadow-lg border-l-4 border-blue-500 overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-user-circle bg-white bg-opacity-20 rounded-full p-2 mr-3 w-10 h-10 flex items-center justify-center"></i>
                            Guest Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">Full Name</p>
                                <p class="text-lg font-bold text-gray-800">{{ $booking->guest_name }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">Phone</p>
                                <p class="text-lg font-bold text-gray-800">{{ $booking->guest_phone }}</p>
                            </div>
                            <div class="col-span-2 bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">Email</p>
                                <p class="text-lg font-bold text-gray-800">{{ $booking->guest_email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Information Card -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl shadow-lg border-l-4 border-emerald-500 overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-calendar-check bg-white bg-opacity-20 rounded-full p-2 mr-3 w-10 h-10 flex items-center justify-center"></i>
                            Booking Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-2">Room Number</p>
                                <p class="text-xl font-bold text-gray-800">{{ $booking->room->room_number ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-2">Room Type</p>
                                <p class="text-lg font-bold text-gray-800">{{ $booking->room->room_type ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-2">Nights</p>
                                <p class="text-xl font-bold text-gray-800">{{ $booking->nights }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-2">Check-in Date</p>
                                <p class="text-sm font-bold text-gray-800">{{ $booking->check_in_date->format('M d, Y') }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-2">Check-out Date</p>
                                <p class="text-sm font-bold text-gray-800">{{ $booking->check_out_date->format('M d, Y') }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-2">Guests</p>
                                <p class="text-xl font-bold text-gray-800">{{ $booking->num_guests }}</p>
                            </div>
                            @if($booking->actual_check_in)
                            <div class="bg-white rounded-lg p-4 shadow-sm md:col-span-2">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-2">Actual Check-in</p>
                                <p class="text-sm font-bold text-gray-800">{{ $booking->actual_check_in->format('M d, Y H:i') }}</p>
                            </div>
                            @endif
                            @if($booking->actual_check_out)
                            <div class="bg-white rounded-lg p-4 shadow-sm md:col-span-2">
                                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-2">Actual Check-out</p>
                                <p class="text-sm font-bold text-gray-800">{{ $booking->actual_check_out->format('M d, Y H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Special Requests -->
                @if($booking->special_requests)
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl shadow-lg border-l-4 border-amber-500 overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-lightbulb bg-white bg-opacity-20 rounded-full p-2 mr-3 w-10 h-10 flex items-center justify-center"></i>
                            Special Requests
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 leading-relaxed">{{ $booking->special_requests }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column - Summary & Actions -->
            <div class="space-y-6">
                
                <!-- Payment Summary Card -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-lg border-l-4 border-purple-500 overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-credit-card bg-white bg-opacity-20 rounded-full p-2 mr-3 w-10 h-10 flex items-center justify-center"></i>
                            Payment Summary
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center bg-white rounded-lg p-3">
                            <span class="text-gray-700 font-medium">Room Rate</span>
                            <span class="font-bold text-purple-600">Nu. {{ number_format($booking->room->price_per_night ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-white rounded-lg p-3">
                            <span class="text-gray-700 font-medium">Nights</span>
                            <span class="font-bold text-purple-600">{{ $booking->nights }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-white rounded-lg p-3">
                            <span class="text-gray-700 font-medium">Rooms</span>
                            <span class="font-bold text-purple-600">{{ $booking->num_rooms }}</span>
                        </div>
                        <div class="border-t-2 border-purple-200 pt-4">
                            <div class="flex justify-between items-center bg-gradient-to-r from-purple-100 to-pink-100 rounded-lg p-4">
                                <span class="text-gray-800 font-bold text-lg">Total Amount</span>
                                <span class="font-bold text-2xl text-purple-700">Nu. {{ number_format($booking->total_price, 2) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center bg-white rounded-lg p-3">
                            <span class="text-gray-700 font-medium">Payment Method</span>
                            <span class="font-bold text-gray-800">{{ $booking->payment_method }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-white rounded-lg p-3">
                            <span class="text-gray-700 font-medium">Payment Status</span>
                            @if($booking->payment_status == 'PAID')
                                <span class="px-3 py-1 rounded-full bg-green-500 text-white text-xs font-bold">✓ Paid</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-yellow-500 text-white text-xs font-bold">⏳ Pending</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-2xl shadow-lg border-l-4 border-red-500 overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-red-500 to-rose-500 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-tasks bg-white bg-opacity-20 rounded-full p-2 mr-3 w-10 h-10 flex items-center justify-center"></i>
                            Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($booking->status == 'CONFIRMED' && in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
                            <a href="{{ route(strtolower(Auth::user()->role) . '.reservations.edit', $booking->id) }}" 
                               class="block w-full bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white text-center px-4 py-3 rounded-lg transition font-semibold shadow-md">
                                <i class="fas fa-edit mr-2"></i>Edit Booking
                            </a>
                        @endif

                        @if($booking->status == 'CONFIRMED')
                            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.reservations.checkin', $booking->id) }}">
                                @csrf
                                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white px-4 py-3 rounded-lg transition font-semibold shadow-md">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Check In
                                </button>
                            </form>
                        @endif

                        @if($booking->status == 'CHECKED_IN')
                            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.reservations.checkout', $booking->id) }}">
                                @csrf
                                <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white px-4 py-3 rounded-lg transition font-semibold shadow-md">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Check Out
                                </button>
                            </form>
                        @endif

                        @if(in_array($booking->status, ['CONFIRMED', 'CHECKED_IN']))
                            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.reservations.cancel', $booking->id) }}">
                                @csrf
                                <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-4 py-3 rounded-lg transition font-semibold shadow-md"
                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    <i class="fas fa-times-circle mr-2"></i>Cancel Booking
                                </button>
                            </form>
                        @endif

                        @if(strtoupper(Auth::user()->role) == 'OWNER' && $booking->status == 'CANCELLED')
                            <form method="POST" action="{{ route('owner.reservations.destroy', $booking->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-3 rounded-lg transition font-semibold shadow-md"
                                        onclick="return confirm('Are you sure you want to permanently delete this booking?')">
                                    <i class="fas fa-trash mr-2"></i>Delete Booking
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Metadata Card -->
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl shadow-lg border-l-4 border-indigo-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-blue-500 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-info-circle bg-white bg-opacity-20 rounded-full p-2 mr-3 w-10 h-10 flex items-center justify-center"></i>
                            Details
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-1">Created</p>
                            <p class="text-gray-800 font-medium">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if($booking->creator)
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-1">Created By</p>
                            <p class="text-gray-800 font-medium">{{ $booking->creator->name }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

                        <!-- Metadata -->
                        <div class="bg-gray-50 rounded-lg p-4 text-sm border border-gray-200">
                    <div class="space-y-2">
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <span class="text-gray-800">{{ $booking->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        @if($booking->creator)
                        <div>
                            <span class="text-gray-500">Created By:</span>
                            <span class="text-gray-800">{{ $booking->creator->name }}</span>
                        </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <!-- Include any JavaScript if needed -->
@endsection
